<?php

namespace Grandeljay\Fedex;

class Quote
{
    private array $calculations = array();

    private function getShippingCosts(string $method, Zone $zone): float
    {
        global $shipping_weight;

        switch ($method) {
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

        $costs = 0;

        foreach ($costs_list as $cost) {
            if ($shipping_weight <= $cost['weight-max']) {
                $costs = $cost['weight-costs'];

                $this->calculations[$method][] = array(
                    'item'  => sprintf(
                        'Shipping weight of %s kg',
                        $shipping_weight
                    ),
                    'costs' => $costs,
                );

                break;
            }
        }

        return $costs;
    }

    private function getSurcharges(array $method): float
    {
        $surcharges = 0;

        $configuration_value = constant(Constants::MODULE_SHIPPING_NAME . '_SURCHARGES');
        $decoded             = json_decode($configuration_value, true);

        foreach ($decoded as $surcharge) {
            if (!empty($surcharge['date-from']) && !empty($surcharge['date-to'])) {
                $date_now  = time();
                $date_from = strtotime($surcharge['date-from']);
                $date_to   = strtotime($surcharge['date-to']);

                /** Skip iteration if date critera doesn't match */
                if ($date_now < $date_from || $date_now > $date_to) {
                    $this->calculations[$method['id']][] = array(
                        'item'  => sprintf(
                            'Surcharge %s has date set: %s - %s. Skipping surcharge...',
                            $surcharge['name'],
                            $surcharge['date-from'],
                            $surcharge['date-to']
                        ),
                        'costs' => 0,
                    );

                    continue;
                } else {
                    $this->calculations[$method['id']][] = array(
                        'item'  => sprintf(
                            'Surcharge %s has date set: %s - %s. Applying surcharge:',
                            $surcharge['name'],
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

            $this->calculations[$method['id']][] = array(
                'item'  => sprintf(
                    'Surcharge %s is %s: %s.',
                    $surcharge['name'],
                    $surcharge['type'],
                    $surcharge['costs']
                ),
                'costs' => $amount,
            );

            $surcharges += $amount;
        }

        /**
         * Pick & Pack
         */
        global $shipping_weight;

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
            if ($shipping_weight <= $cost['weight-max']) {
                $pick_pack_costs = $cost['weight-costs'];

                $this->calculations[$method['id']][] = array(
                    'item'  => sprintf(
                        'Pick & Pack for %s kg.',
                        $shipping_weight
                    ),
                    'costs' => $pick_pack_costs,
                );

                break;
            }
        }

        return $surcharges;
    }

    public function getQuote(): ?array
    {
        global $order, $shipping_weight;

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
            'id'    => 'economy',
            'title' => sprintf(
                'Fedex Economy (%s kg)<!-- BREAK -->Zone %s',
                round($shipping_weight, 2),
                $country_zone->name
            ),
            'cost'  => $this->getShippingCosts('economy', $country_zone),
            'type'  => 'standard',
        );
        if ($method_economy['cost'] > 0) {
            $methods[] = $method_economy;
        }

        $method_priority = array(
            'id'    => 'priority',
            'title' => sprintf(
                'Fedex Priority (%s kg)<!-- BREAK -->Zone %s',
                round($shipping_weight, 2),
                $country_zone->name
            ),
            'cost'  => $this->getShippingCosts('priority', $country_zone),
            'type'  => 'express',
        );
        if ($method_priority['cost'] > 0) {
            $methods[] = $method_priority;
        }

        /** Surcharges */
        foreach ($methods as &$method) {
            $method['cost'] += $this->getSurcharges($method);
        }

        /** Round up */
        foreach ($methods as &$method) {
            $costs_without_decimals = floor($method['cost']);
            $costs_decimals         = $method['cost'] - $costs_without_decimals;

            if (0.9 !== $costs_decimals) {
                $costs_rounded_up = ceil($method['cost']) + 0.9;

                $this->calculations[$method['id']][] = array(
                    'item'  => sprintf(
                        'Rounding up',
                    ),
                    'costs' => $costs_rounded_up - $method['cost'],
                );

                $method['cost'] = $costs_rounded_up;
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

                <h3 style="margin-bottom: 0.2rem;">Debug mode</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Costs</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($this->calculations[$method['id']] as $calculation) { ?>
                            <?php $total += $calculation['costs']; ?>

                            <tr>
                                <td><?= $calculation['item'] ?></td>
                                <td><?= $calculation['costs'] ?></td>
                                <td><?= $total ?></td>
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

    public function exceedsMaximumWeight(): bool
    {
        global $order;

        if (null === $order) {
            return false;
        }

        $configuration_key_weight_max   = Constants::MODULE_SHIPPING_NAME . '_WEIGHT_MAXIMUM';
        $configuration_value_weight_max = constant($configuration_key_weight_max);

        foreach ($order->products as $product) {
            if ($product['weight'] >= $configuration_value_weight_max) {
                return true;
            }
        }

        return false;
    }
}
