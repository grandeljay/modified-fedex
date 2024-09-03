<?php

namespace Grandeljay\Fedex\Traits;

use Grandeljay\Fedex\Constants;

trait Shipping
{
    public function getNational(): array
    {
        $national = \array_filter(
            [
                $this->getNationalFirst(),
                $this->getNationalPriorityExpress(),
                $this->getNationalPriority(),
                $this->getNationalPriorityExpressFreight(),
                $this->getNationalPriorityFreight(),
            ],
            function (array $method) {
                return !empty($method);
            }
        );

        return $national;
    }

    private function getNationalFirst(): array
    {
        $shipping_national_first = [
            'id'               => 'nationalfirst',
            'title'            => 'First',
            'description'      => 'Zustellung am Vormittag.',
            'cost'             => 0,
            'calculations'     => [],
            'type'             => 'express',
            'weight_formatted' => $this->weight_formatted,
        ];

        /**
         * Tariffs
         */
        $configuration_key   = \sprintf(
            '%s_SHIPPING_NATIONAL_FIRST',
            Constants::MODULE_SHIPPING_NAME
        );
        $configuration_value = \constant($configuration_key);

        $tariffs            = \json_decode($configuration_value, true);
        $tariffs_weight_max = 100;

        if ($this->weight <= $tariffs_weight_max) {
            foreach ($tariffs as $tariff) {
                if ($this->weight <= $tariff['weight-max']) {
                    $shipping_national_first['cost']           = $tariff['weight-costs'];
                    $shipping_national_first['calculations'][] = [
                        'item'  => sprintf(
                            'National Shipping: <i>%s</i> (<code>%s</code> kg)',
                            $shipping_national_first['title'],
                            \round($tariff['weight-max'], 2)
                        ),
                        'costs' => $tariff['weight-costs'],
                    ];
                    break;
                }
            }

            return $shipping_national_first;
        }

        /**
         * Price per kg
         */
        $price_per_kg = 0.97;
        $price        = $this->weight * $price_per_kg;

        $shipping_national_first['cost']           = $price;
        $shipping_national_first['calculations'][] = [
            'item'  => sprintf(
                'National Shipping (<code>%s+</code> kg, <code>%s</code> € per kg)',
                $tariffs_weight_max,
                $price_per_kg
            ),
            'costs' => $price,
        ];

        return $shipping_national_first;
    }

    private function getNationalPriorityExpress(): array
    {
        $shipping_national_priority_express = [
            'id'               => 'nationalpriorityexpress',
            'title'            => 'Priority Express',
            'description'      => 'Zustellung bis Mittag.',
            'cost'             => 0,
            'calculations'     => [],
            'type'             => 'express',
            'weight_formatted' => $this->weight_formatted,
        ];

        /**
         * Tariffs
         */
        $configuration_key   = \sprintf(
            '%s_SHIPPING_NATIONAL_PRIORITY_EXPRESS',
            Constants::MODULE_SHIPPING_NAME
        );
        $configuration_value = \constant($configuration_key);

        $tariffs            = \json_decode($configuration_value, true);
        $tariffs_weight_max = 100;

        if ($this->weight <= $tariffs_weight_max) {
            foreach ($tariffs as $tariff) {
                if ($this->weight <= $tariff['weight-max']) {
                    $shipping_national_priority_express['cost']           = $tariff['weight-costs'];
                    $shipping_national_priority_express['calculations'][] = [
                        'item'  => sprintf(
                            'National Shipping: <i>%s</i> (<code>%s</code> kg)',
                            $shipping_national_priority_express['title'],
                            \round($tariff['weight-max'], 2)
                        ),
                        'costs' => $tariff['weight-costs'],
                    ];
                    break;
                }
            }

            return $shipping_national_priority_express;
        }

        /**
         * Price per kg
         */
        $price_per_kg = 0.72;
        $price        = $this->weight * $price_per_kg;

        $shipping_national_priority_express['cost']           = $price;
        $shipping_national_priority_express['calculations'][] = [
            'item'  => sprintf(
                'National Shipping (<code>%s+</code> kg, <code>%s</code> € per kg)',
                $tariffs_weight_max,
                $price_per_kg
            ),
            'costs' => $price,
        ];

        return $shipping_national_priority_express;
    }

    private function getNationalPriority(): array
    {
        $shipping_national_priority = [
            'id'               => 'nationalpriority',
            'title'            => 'Priority',
            'description'      => 'Zustellung bis zum Ende des Geschäftstages.',
            'cost'             => 0,
            'calculations'     => [],
            'type'             => 'express',
            'weight_formatted' => $this->weight_formatted,
        ];

        /**
         * Tariffs
         */
        $configuration_key   = \sprintf(
            '%s_SHIPPING_NATIONAL_PRIORITY',
            Constants::MODULE_SHIPPING_NAME
        );
        $configuration_value = \constant($configuration_key);

        $tariffs            = \json_decode($configuration_value, true);
        $tariffs_weight_max = 100;

        if ($this->weight <= $tariffs_weight_max) {
            foreach ($tariffs as $tariff) {
                if ($this->weight <= $tariff['weight-max']) {
                    $shipping_national_priority['cost']           = $tariff['weight-costs'];
                    $shipping_national_priority['calculations'][] = [
                        'item'  => sprintf(
                            'National Shipping: <i>%s</i> (<code>%s</code> kg)',
                            $shipping_national_priority['title'],
                            \round($tariff['weight-max'], 2)
                        ),
                        'costs' => $tariff['weight-costs'],
                    ];
                    break;
                }
            }

            return $shipping_national_priority;
        }

        /**
         * Price per kg
         */
        $price_per_kg = 0.65;
        $price        = $this->weight * $price_per_kg;

        $shipping_national_priority['cost']           = $price;
        $shipping_national_priority['calculations'][] = [
            'item'  => sprintf(
                'National Shipping (<code>%s+</code> kg, <code>%s</code> € per kg)',
                $tariffs_weight_max,
                $price_per_kg
            ),
            'costs' => $price,
        ];

        return $shipping_national_priority;
    }

    private function getNationalPriorityExpressFreight(): array
    {
        $shipping_national_priority_express_freight = [
            'id'               => 'nationalpriorityexpressfreight',
            'title'            => 'Priority Express Freight',
            'description'      => 'Zustellung bis Mittag',
            'cost'             => 0,
            'calculations'     => [],
            'type'             => 'freight',
            'weight_formatted' => $this->weight_formatted,
        ];

        /**
         * Tariffs
         */
        $configuration_key   = \sprintf(
            '%s_SHIPPING_NATIONAL_PRIORITY_EXPRESS_FREIGHT',
            Constants::MODULE_SHIPPING_NAME
        );
        $configuration_value = \constant($configuration_key);

        $tariffs = \json_decode($configuration_value, true);

        \usort(
            $tariffs,
            function (array $tariff_a, array $tariff_b) {
                return $tariff_a['weight-min'] <=> $tariff_b['weight-min'];
            }
        );

        $tariffs_first      = \reset($tariffs);
        $tariffs_last       = \end($tariffs);
        $tariffs_weight_min = $tariffs_first['weight-min'] ?? 0;
        $tariffs_weight_max = $tariffs_last['weight-max']  ?? 0;

        if ($this->weight < $tariffs_weight_min || ($tariffs_weight_max > 0 && $this->weight > $tariffs_weight_max)) {
            return [];
        }

        $tariff_weight      = 0;
        $tariff_cost_per_kg = 0;

        foreach ($tariffs as $tariff) {
            $weight_min   = $tariff['weight-min']   ?? 0;
            $weight_max   = $tariff['weight-max']   ?? 0;
            $weight_costs = $tariff['weight-costs'] ?? 0;

            if (0 === $weight_max) {
                if ($this->weight >= $weight_min) {
                    $tariff_weight      = $weight_min . '+';
                    $tariff_cost_per_kg = $weight_costs;

                    break;
                }
            } else {
                if ($this->weight >= $weight_min && $this->weight <= $weight_max) {
                    $tariff_weight      = $weight_max;
                    $tariff_cost_per_kg = $weight_costs;

                    break;
                }
            }
        }

        if (0 === $tariff_cost_per_kg) {
            return [];
        }

        $price = $this->weight * $tariff_cost_per_kg;

        $shipping_national_priority_express_freight['cost']           = $price;
        $shipping_national_priority_express_freight['calculations'][] = [
            'item'  => sprintf(
                'National Shipping (<code>%s</code> kg, <code>%s</code> € per kg)',
                $tariff_weight,
                $tariff_cost_per_kg
            ),
            'costs' => $price,
        ];

        return $shipping_national_priority_express_freight;
    }

    private function getNationalPriorityFreight(): array
    {
        $shipping_national_priority_freight = [
            'id'               => 'nationalpriorityfreight',
            'title'            => 'Priority Freight',
            'description'      => 'Zustellung bis zum Ende des Geschäftstages.',
            'cost'             => 0,
            'calculations'     => [],
            'type'             => 'freight',
            'weight_formatted' => $this->weight_formatted,
        ];

        /**
         * Tariffs
         */
        $configuration_key   = \sprintf(
            '%s_SHIPPING_NATIONAL_PRIORITY_FREIGHT',
            Constants::MODULE_SHIPPING_NAME
        );
        $configuration_value = \constant($configuration_key);

        $tariffs = \json_decode($configuration_value, true);

        \usort(
            $tariffs,
            function (array $tariff_a, array $tariff_b) {
                return $tariff_a['weight-min'] <=> $tariff_b['weight-min'];
            }
        );

        $tariffs_first      = \reset($tariffs);
        $tariffs_last       = \end($tariffs);
        $tariffs_weight_min = $tariffs_first['weight-min'] ?? 0;
        $tariffs_weight_max = $tariffs_last['weight-max']  ?? 0;

        if ($this->weight < $tariffs_weight_min || ($tariffs_weight_max > 0 && $this->weight > $tariffs_weight_max)) {
            return [];
        }

        $tariff_weight      = 0;
        $tariff_cost_per_kg = 0;

        foreach ($tariffs as $tariff) {
            $weight_min   = $tariff['weight-min']   ?? 0;
            $weight_max   = $tariff['weight-max']   ?? 0;
            $weight_costs = $tariff['weight-costs'] ?? 0;

            if (0 === $weight_max) {
                if ($this->weight >= $weight_min) {
                    $tariff_weight      = $weight_min . '+';
                    $tariff_cost_per_kg = $weight_costs;

                    break;
                }
            } else {
                if ($this->weight >= $weight_min && $this->weight <= $weight_max) {
                    $tariff_weight      = $weight_max;
                    $tariff_cost_per_kg = $weight_costs;

                    break;
                }
            }
        }

        if (0 === $tariff_cost_per_kg) {
            return [];
        }

        $price = $this->weight * $tariff_cost_per_kg;

        $shipping_national_priority_freight['cost']           = $price;
        $shipping_national_priority_freight['calculations'][] = [
            'item'  => sprintf(
                'National Shipping (<code>%s</code> kg, <code>%s</code> € per kg)',
                $tariff_weight,
                $tariff_cost_per_kg
            ),
            'costs' => $price,
        ];

        return $shipping_national_priority_freight;
    }

    public function getInternational(): array
    {
        $international = \array_filter(
            [
                $this->getInternationalEconomy(),
                $this->getInternationalPriority(),
            ],
            function (array $method) {
                return !empty($method);
            }
        );

        return $international;
    }

    private function getInternationalEconomy(): array
    {
        $shipping_international_economy = [
            'id'               => 'internationaleconomy',
            'title'            => 'Economy',
            'description'      => 'Economy Versand.',
            'cost'             => 0,
            'calculations'     => [],
            'type'             => 'standard',
            'weight_formatted' => $this->weight_formatted,
        ];

        $this->setShippingCosts($shipping_international_economy);

        if ($shipping_international_economy['cost'] <= 0) {
            return [];
        }

        return $shipping_international_economy;
    }

    private function getInternationalPriority(): array
    {
        $shipping_international_priority = [
            'id'               => 'internationalpriority',
            'title'            => 'Priority',
            'description'      => 'Priority Versand.',
            'cost'             => 0,
            'calculations'     => [],
            'type'             => 'express',
            'weight_formatted' => $this->weight_formatted,
        ];

        $this->setShippingCosts($shipping_international_priority);

        if ($shipping_international_priority['cost'] <= 0) {
            return [];
        }

        return $shipping_international_priority;
    }
}
