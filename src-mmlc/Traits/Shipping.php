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
                'National Shipping (price per kg)',
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
         * Envelope
         */
        if ($this->weight < 0.5) {
            $weight_costs = 8.66;

            $shipping_national_priority_express['cost']           = $weight_costs;
            $shipping_national_priority_express['calculations'][] = [
                'item'  => sprintf(
                    'National Shipping (Envelope)',
                ),
                'costs' => $weight_costs,
            ];

            return $shipping_national_priority_express;
        }

        /**
         * Pak
         */
        $prices_pak        = [
            [
                'weight-max'   => 0.5,
                'weight-costs' => 9.04,
            ],
            [
                'weight-max'   => 1.0,
                'weight-costs' => 9.32,
            ],
            [
                'weight-max'   => 1.5,
                'weight-costs' => 9.32,
            ],
            [
                'weight-max'   => 2.0,
                'weight-costs' => 9.32,
            ],
            [
                'weight-max'   => 2.5,
                'weight-costs' => 9.32,
            ],
        ];
        $prices_other      = [
            [
                'weight-max'   => 5,
                'weight-costs' => 9.55,
            ],
            [
                'weight-max'   => 10,
                'weight-costs' => 9.55,
            ],
            [
                'weight-max'   => 15,
                'weight-costs' => 15.20,
            ],
            [
                'weight-max'   => 20,
                'weight-costs' => 15.20,
            ],
            [
                'weight-max'   => 25,
                'weight-costs' => 21.95,
            ],
            [
                'weight-max'   => 30,
                'weight-costs' => 21.95,
            ],
            [
                'weight-max'   => 35,
                'weight-costs' => 31.45,
            ],
            [
                'weight-max'   => 40,
                'weight-costs' => 31.45,
            ],
            [
                'weight-max'   => 45,
                'weight-costs' => 36.45,
            ],
            [
                'weight-max'   => 50,
                'weight-costs' => 36.45,
            ],
            [
                'weight-max'   => 60,
                'weight-costs' => 43.24,
            ],
            [
                'weight-max'   => 70,
                'weight-costs' => 49.34,
            ],
            [
                'weight-max'   => 80,
                'weight-costs' => 55.40,
            ],
            [
                'weight-max'   => 90,
                'weight-costs' => 61.54,
            ],
            [
                'weight-max'   => 100,
                'weight-costs' => 67.59,
            ],
        ];
        $prices            = \array_merge($prices_pak, $prices_other);
        $prices_weight_max = \array_key_last($prices);

        if ($this->weight <= $prices_weight_max) {
            foreach ($prices as $weight => $entry) {
                if ($this->weight <= $weight) {
                    $shipping_national_priority_express['cost']           = $entry['costs'];
                    $shipping_national_priority_express['calculations'][] = [
                        'item'  => sprintf(
                            'National Shipping (Pak)',
                        ),
                        'costs' => $entry['costs'],
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
                'National Shipping (price per kg)',
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
         * Envelope
         */
        if ($this->weight < 0.5) {
            $weight_costs = 5.35;

            $shipping_national_priority['cost']           = $weight_costs;
            $shipping_national_priority['calculations'][] = [
                'item'  => sprintf(
                    'National Shipping (Envelope)',
                ),
                'costs' => $weight_costs,
            ];

            return $shipping_national_priority;
        }

        /**
         * Pak
         */
        $prices_pak        = [
            [
                'weight-max'   => 0.5,
                'weight-costs' => 5.35,
            ],
            [
                'weight-max'   => 1.0,
                'weight-costs' => 5.38,
            ],
            [
                'weight-max'   => 1.5,
                'weight-costs' => 5.77,
            ],
            [
                'weight-max'   => 2.0,
                'weight-costs' => 5.77,
            ],
            [
                'weight-max'   => 2.5,
                'weight-costs' => 5.77,
            ],
        ];
        $prices_other      = [
            [
                'weight-max'   => 5,
                'weight-costs' => 6.40,
            ],
            [
                'weight-max'   => 10,
                'weight-costs' => 6.40,
            ],
            [
                'weight-max'   => 15,
                'weight-costs' => 7.75,
            ],
            [
                'weight-max'   => 20,
                'weight-costs' => 7.75,
            ],
            [
                'weight-max'   => 25,
                'weight-costs' => 10.15,
            ],
            [
                'weight-max'   => 30,
                'weight-costs' => 10.15,
            ],
            [
                'weight-max'   => 35,
                'weight-costs' => 27.05,
            ],
            [
                'weight-max'   => 40,
                'weight-costs' => 27.05,
            ],
            [
                'weight-max'   => 45,
                'weight-costs' => 31.29,
            ],
            [
                'weight-max'   => 50,
                'weight-costs' => 31.29,
            ],
            [
                'weight-max'   => 60,
                'weight-costs' => 38.04,
            ],
            [
                'weight-max'   => 70,
                'weight-costs' => 44.39,
            ],
            [
                'weight-max'   => 80,
                'weight-costs' => 49.93,
            ],
            [
                'weight-max'   => 90,
                'weight-costs' => 55.33,
            ],
            [
                'weight-max'   => 100,
                'weight-costs' => 60.84,
            ],
        ];
        $prices            = \array_merge($prices_pak, $prices_other);
        $prices_weight_max = \array_key_last($prices);

        if ($this->weight <= $prices_weight_max) {
            foreach ($prices as $weight => $entry) {
                if ($this->weight <= $weight) {
                    $shipping_national_priority['cost']           = $entry['costs'];
                    $shipping_national_priority['calculations'][] = [
                        'item'  => sprintf(
                            'National Shipping (Pak)',
                        ),
                        'costs' => $entry['costs'],
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
                'National Shipping (price per kg)',
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

        if ($this->weight >= 68 && $this->weight <= 100) {
            $price_per_kg = 1.48;
        } elseif ($this->weight > 100 && $this->weight <= 200) {
            $price_per_kg = 1.48;
        } elseif ($this->weight > 200) {
            $price_per_kg = 1.48;
        } else {
            return [];
        }

        $price = $this->weight * $price_per_kg;

        $shipping_national_priority_express_freight['cost']           = $price;
        $shipping_national_priority_express_freight['calculations'][] = [
            'item'  => sprintf(
                'National Shipping (price per kg)',
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

        if ($this->weight >= 68 && $this->weight <= 100) {
            $price_per_kg = 0.99;
        } elseif ($this->weight > 100 && $this->weight <= 200) {
            $price_per_kg = 0.99;
        } elseif ($this->weight > 200) {
            $price_per_kg = 0.99;
        } else {
            return [];
        }

        $price = $this->weight * $price_per_kg;

        $shipping_national_priority_freight['cost']           = $price;
        $shipping_national_priority_freight['calculations'][] = [
            'item'  => sprintf(
                'National Shipping (price per kg)',
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
