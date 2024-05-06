<?php

namespace Grandeljay\Fedex;

class Quote
{
    private function setShippingCosts(array &$method, Zone $zone): void
    {
        global $total_weight;

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
            if ($total_weight <= $cost['weight-max']) {
                $method['cost']          += $cost['weight-costs'];
                $method['calculations'][] = array(
                    'item'  => sprintf(
                        'Shipping weight is <code>%01.2f</code> kg (tarif is <code>%01.2f</code> kg).',
                        $total_weight,
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

            $method['cost']          += $cost;
            $method['calculations'][] = array(
                'item'  => sprintf(
                    'No tarif defined for <code>%01.2f</code> kg. Falling back to highest defined tarif (<code>%01.2f</code> kg) for this zone.',
                    $total_weight,
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
                                'Surcharge %s (<code>%01.2f</code> kg) is <code>%01.2f</code> %s for %s.',
                                '<i>' . $surcharge['name'] . '</i>',
                                $surcharge['weight'],
                                $surcharge['costs'],
                                $symbol,
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
        global $total_weight;

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
            if ($total_weight <= $cost['weight-max']) {
                $pick_pack_costs = $cost['weight-costs'];

                $method['cost']          += $pick_pack_costs;
                $method['calculations'][] = array(
                    'item'  => sprintf(
                        'Pick & Pack for <code>%01.2f</code> kg (tarif is <code>%01.2f</code> kg).',
                        $total_weight,
                        $cost['weight-max']
                    ),
                    'costs' => $pick_pack_costs,
                );

                break;
            }
        }
    }

    private function getShippingWeight(): float
    {
        global $order;

        $shipping_weight = 0;

        foreach ($order->products as $product) {
            $length = $product['length'] ?? 0;
            $width  = $product['width']  ?? 0;
            $height = $product['height'] ?? 0;
            $weight = ($product['weight'] ?? 0) * $product['quantity'];

            if ($length > 0 && $width > 0 && $height > 0) {
                $volumetric_weight = (($length * $width * $height) / 5000) * $product['quantity'];

                if ($volumetric_weight > $weight) {
                    $weight = $volumetric_weight;
                }
            }

            $shipping_weight += $weight;
        }

        return $shipping_weight;
    }

    public function getQuote(): ?array
    {
        global $order;

        $shipping_weight = $this->getShippingWeight();

        $country_code = $order->delivery['country']['iso_code_2'] ?? null;

        if (null === $country_code) {
            return null;
        }

        $country_zone = Zone::fromCountry($country_code);

        if (null === $country_zone) {
            return null;
        }

        $methods = array();

        $method_economy = array(
            'id'           => 'economy',
            'title'        => sprintf(
                'Fedex Economy (%s kg)<!-- BREAK -->Zone %s',
                round($shipping_weight, 2),
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
                'Fedex Priority (%s kg)<!-- BREAK -->Zone %s',
                round($shipping_weight, 2),
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
                $costs_rounded_up = $costs_without_decimals + 0.9;

                $method['cost']          += $costs_rounded_up;
                $method['calculations'][] = array(
                    'item'  => sprintf(
                        'Rounding up',
                    ),
                    'costs' => $costs_rounded_up,
                );
            }
        }

        /** Debug information */
        $user_is_admin = isset($_SESSION['customers_status']['customers_status_id']) && 0 === (int) $_SESSION['customers_status']['customers_status_id'];

        if ($user_is_admin) {
            foreach ($methods as &$method) {
                $total = 0;

                ob_start();
                ?>
                <br><br>

                <h3>Debug mode</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Costs</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($method['calculations'] as $calculation) { ?>
                            <?php $total += $calculation['costs']; ?>

                            <tr>
                                <td><?= $calculation['item'] ?></td>
                                <td><?= \sprintf('%01.2f', $calculation['costs']) ?></td>
                                <td><?= \sprintf('%01.2f', $total) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php
                $method['title'] .= ob_get_clean();
            }
        }

        /** Quote */
        $quote = array(
            'id'      => \grandeljayfedex::class,
            'module'  => sprintf(
                constant(Constants::MODULE_SHIPPING_NAME . '_TEXT_TITLE_WEIGHT'),
                round($shipping_weight, 2)
            ),
            'methods' => $methods,
        );

        return $quote;
    }

    /**
     * The total shipping weight should not exceed `WEIGHT_MAXIMUM`. Individual
     * product weight should not exceed 45 kg.
     *
     * TODO: Add a setting to configure the 45 kg.
     *
     * @return bool
     */
    public function exceedsMaximumWeight(): bool
    {
        global $order, $total_weight;

        if (null === $order) {
            return true;
        }

        $configuration_key_weight_max   = Constants::MODULE_SHIPPING_NAME . '_WEIGHT_MAXIMUM';
        $configuration_value_weight_max = constant($configuration_key_weight_max);

        if ($total_weight > $configuration_value_weight_max) {
            return true;
        }

        foreach ($order->products as $product) {
            if ($product['weight'] >= 45) {
                return true;
            }
        }

        return false;
    }
}
