<?php

namespace Grandeljay\Fedex;

use Grandeljay\Fedex\Traits\Shipping;
use Grandeljay\ShippingModuleHelper\OrderPacker;

class Quote
{
    use Shipping;

    private array $boxes;
    private float $weight;
    private string $weight_formatted;

    private function setShippingCosts(array &$method, Zone $zone): void
    {
        switch ($method['id']) {
            case 'economy':
                $configuration_key   = sprintf(Constants::MODULE_SHIPPING_NAME . '_SHIPPING_INTERNATIONAL_ECONOMY_ZONE%s', $zone->name);
                $configuration_value = constant($configuration_key);
                $costs_list          = json_decode($configuration_value, true);
                break;

            case 'priority':
                $configuration_key   = sprintf(Constants::MODULE_SHIPPING_NAME . '_SHIPPING_INTERNATIONAL_PRIORITY_ZONE%s', $zone->name);
                $configuration_value = constant($configuration_key);
                $costs_list          = json_decode($configuration_value, true);
                break;

            default:
                $costs_list = array();
                break;
        }

        usort(
            $costs_list,
            function ($costs_a, $costs_b) {
                return $costs_a['weight-costs'] <=> $costs_b['weight-costs'];
            }
        );

        foreach ($costs_list as $cost) {
            if ($this->weight <= $cost['weight-max']) {
                $method['cost']          += $cost['weight-costs'];
                $method['calculations'][] = array(
                    'item'  => sprintf(
                        'Shipping weight is <code>%01.2f</code> kg (tarif is <code>%01.2f</code> kg).',
                        $this->weight,
                        $cost['weight-max']
                    ),
                    'costs' => $cost['weight-costs'],
                );

                break;
            }
        }

        if (0 === $method['cost'] && count($costs_list) >= 1) {
            $cots_list_last = end($costs_list);
            $costs          = $cots_list_last['weight-costs'];

            $method['cost']          += $costs;
            $method['calculations'][] = array(
                'item'  => sprintf(
                    'No tarif defined for <code>%01.2f</code> kg. Falling back to highest defined tarif (<code>%01.2f</code> kg) for this zone.',
                    $this->weight,
                    $cots_list_last['weight-max']
                ),
                'costs' => $costs,
            );
        }
    }

    private function setSurcharges(array &$method): void
    {
        global $order;

        $configuration_value = constant(Constants::MODULE_SHIPPING_NAME . '_SURCHARGES');
        $decoded             = json_decode($configuration_value, true);

        foreach ($decoded as $surcharge) {
            if (!empty($surcharge['date-from']) && !empty($surcharge['date-to'])) {
                $date_now  = time();
                $date_from = strtotime($surcharge['date-from']);
                $date_to   = strtotime($surcharge['date-to']);

                /** Skip iteration if date critera doesn't match */
                if ($date_now < $date_from || $date_now > $date_to) {
                    $method['calculations'][] = array(
                        'item'  => sprintf(
                            'Surcharge %s has date set: %s - %s. Skipping surcharge...',
                            '<i>' . $surcharge['name'] . '</i>',
                            $surcharge['date-from'],
                            $surcharge['date-to']
                        ),
                        'costs' => 0,
                    );

                    continue;
                } else {
                    $method['calculations'][] = array(
                        'item'  => sprintf(
                            'Surcharge %s has date set: %s - %s. Applying surcharge:',
                            '<i>' . $surcharge['name'] . '</i>',
                            $surcharge['date-from'],
                            $surcharge['date-to']
                        ),
                        'costs' => 0,
                    );
                }
            }

            $amount = match ($surcharge['type']) {
                'fixed'   => $surcharge['costs'],
                'percent' => $method['cost'] * ($surcharge['costs'] / 100),
            };
            $symbol = match ($surcharge['type']) {
                'fixed'   => '€',
                'percent' => '%',
            };

            if (!empty($surcharge['weight'])) {
                foreach ($order->products as $product_data) {
                    if ($product_data['weight'] >= $surcharge['weight']) {
                        /** Apply the surcharge */
                        $method['cost']          += $amount;
                        $method['calculations'][] = array(
                            'item'  => sprintf(
                                'Surcharge %s (<code>%01.2f</code> kg) for %s.',
                                '<i>' . $surcharge['name'] . '</i>',
                                $surcharge['weight'],
                                $product_data['model']
                            ),
                            'costs' => $amount,
                        );
                    }
                }
            } else {
                $method['cost']          += $amount;
                $method['calculations'][] = array(
                    'item'  => sprintf(
                        'Surcharge %s is <code>%01.2f</code> %s.',
                        '<i>' . $surcharge['name'] . '</i>',
                        $surcharge['costs'],
                        $symbol
                    ),
                    'costs' => $amount,
                );
            }
        }

        /**
         * Pick & Pack
         */
        $pick_pack_key   = Constants::MODULE_SHIPPING_NAME . '_PICK_PACK';
        $pick_pack_value = constant($pick_pack_key);
        $pick_pack       = json_decode($pick_pack_value, true);

        usort(
            $pick_pack,
            function ($costs_a, $costs_b) {
                return $costs_a['weight-costs'] <=> $costs_b['weight-costs'];
            }
        );

        $pick_pack_costs = 0;

        foreach ($pick_pack as $cost) {
            if ($this->weight <= $cost['weight-max']) {
                $pick_pack_costs = $cost['weight-costs'];

                $method['cost']          += $pick_pack_costs;
                $method['calculations'][] = array(
                    'item'  => sprintf(
                        'Pick & Pack for <code>%01.2f</code> kg (tarif is <code>%01.2f</code> kg).',
                        $this->weight,
                        $cost['weight-max']
                    ),
                    'costs' => $pick_pack_costs,
                );

                break;
            }
        }
    }

    private function getShippingMethodsInternational(): array
    {
        $method_economy = array(
            'id'           => 'economy',
            'title'        => sprintf(
                'Fedex Economy (%s)<!-- BREAK -->Zone %s',
                $weight_formatted,
                $country_zone->name
            ),
            'cost'         => 0,
            'calculations' => array(),
            'type'         => 'standard',
        );

        $this->setShippingCosts($method_economy, $country_zone);

        if ($method_economy['cost'] > 0) {
            $methods[] = $method_economy;
        }

        $method_priority = array(
            'id'           => 'priority',
            'title'        => sprintf(
                'Fedex Priority (%s)<!-- BREAK -->Zone %s',
                $weight_formatted,
                $country_zone->name
            ),
            'cost'         => 0,
            'calculations' => array(),
            'type'         => 'express',
        );

        $this->setShippingCosts($method_priority, $country_zone);

        if ($method_priority['cost'] > 0) {
            $methods[] = $method_priority;
        }
    }

    private function getShippingMethods(): array
    {
        global $order;

        $country_code = $order->delivery['country']['iso_code_2'] ?? null;
        $country_id   = $order->delivery['country']['id']         ?? null;

        if (null === $country_code || null === $country_id) {
            return array();
        }

        $methods = array();

        /** National */
        $is_national = $country_id === \STORE_COUNTRY;

        if ($is_national) {
            $methods += $this->getNational();
        }

        /** International */
        $is_international = $country_id !== \STORE_COUNTRY;

        if ($is_international) {
            $methods = \array_merge($methods, $this->getShippingMethodsInternational());
        }

        return $methods;
    }

    public function getQuote(): ?array
    {
        /** Weight */
        $shipping_weight_ideal   = \constant(Constants::MODULE_SHIPPING_NAME . '_WEIGHT_IDEAL');
        $shipping_weight_maximum = \constant(Constants::MODULE_SHIPPING_NAME . '_WEIGHT_MAXIMUM');

        $order_packer = new OrderPacker();
        $order_packer->setIdealWeight($shipping_weight_ideal);
        $order_packer->setMaximumWeight($shipping_weight_maximum);
        $order_packer->packOrder();

        $this->boxes            = $order_packer->getBoxes();
        $this->weight           = $order_packer->getWeight();
        $this->weight_formatted = $order_packer->getWeightFormatted();

        if ($shipping_weight_maximum > 0) {
            foreach ($this->boxes as $box) {
                $box_weight = $box->getWeight();

                if ($box_weight > $shipping_weight_maximum) {
                    return null;
                }
            }
        }

        /** Methods */
        $methods = $this->getShippingMethods();

        /** Surcharges */
        foreach ($methods as &$method) {
            $this->setSurcharges($method);
        }

        if (\class_exists('Grandeljay\ShippingConditions\Surcharges')) {
            $surcharges = new \Grandeljay\ShippingConditions\Surcharges(
                \grandeljayfedex::class,
                $methods
            );
            $surcharges->setSurcharges();

            $methods = $surcharges->getMethods();
        }

        /** Round up */
        foreach ($methods as &$method) {
            $costs_without_decimals = floor($method['cost']);
            $costs_decimals         = $method['cost'] - $costs_without_decimals;

            if (0.9 !== $costs_decimals) {
                if ($costs_decimals > 0.9) {
                    $costs = 1.9 - $costs_decimals;
                } else {
                    $costs = 0.9 - $costs_decimals;
                }

                $method['cost']          += $costs;
                $method['calculations'][] = array(
                    'item'  => sprintf(
                        'Rounding up',
                    ),
                    'costs' => $costs,
                );
            }
        }

        /** Quote */
        if (empty($methods)) {
            return null;
        }

        $quote = array(
            'id'      => \grandeljayfedex::class,
            'module'  => 'FedEx',
            'methods' => $methods,
        );

        return $quote;
    }
}
