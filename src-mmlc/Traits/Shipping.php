<?php

namespace Grandeljay\Fedex\Traits;

use Grandeljay\Fedex\Constants;

trait Shipping
{
    public function getNational(): array
    {
        $national = \array_filter(
            array(
                $this->getNationalFirst(),
                $this->getNationalPriorityExpress(),
                $this->getNationalPriority(),
                $this->getNationalPriorityExpressFreight(),
                $this->getNationalPriorityFreight(),
            ),
            function (array $method) {
                return !empty($method);
            }
        );

        return $national;
    }

    private function getNationalFirst(): array
    {
        $shipping_national_first = array(
            'id'               => 'nationalfirst',
            'title'            => 'First',
            'description'      => 'Zustellung am Vormittag.',
            'cost'             => 0,
            'calculations'     => array(),
            'type'             => 'express',
            'weight_formatted' => $this->weight_formatted,
        );

        /**
         * Envelope
         */
        if ($this->weight < 0.5) {
            $weight_constant = Constants::MODULE_SHIPPING_NAME . '_ENVELOPE_0_5_KG';
            $weight_costs    = \defined($weight_constant) ? \constant($weight_constant) : 0;

            $shipping_national_first['cost']           = $weight_costs;
            $shipping_national_first['calculations'][] = array(
                'item'  => sprintf(
                    'National Shipping (Envelope)',
                ),
                'costs' => $weight_costs,
            );

            return $shipping_national_first;
        }

        /**
         * Pak
         */
        $prices_pak        = array(
            array(
                'weight-max'   => 0.5,
                'weight-costs' => 18.94,
            ),
            array(
                'weight-max'   => 1.0,
                'weight-costs' => 19.54,
            ),
            array(
                'weight-max'   => 1.5,
                'weight-costs' => 19.54,
            ),
            array(
                'weight-max'   => 2.0,
                'weight-costs' => 19.54,
            ),
            array(
                'weight-max'   => 2.5,
                'weight-costs' => 19.54,
            ),
        );
        $prices_other      = array(
            array(
                'weight-max'   => 5,
                'weight-costs' => 22.45,
            ),
            array(
                'weight-max'   => 10,
                'weight-costs' => 22.45,
            ),
            array(
                'weight-max'   => 15,
                'weight-costs' => 31.95,
            ),
            array(
                'weight-max'   => 20,
                'weight-costs' => 31.95,
            ),
            array(
                'weight-max'   => 25,
                'weight-costs' => 39.20,
            ),
            array(
                'weight-max'   => 30,
                'weight-costs' => 39.20,
            ),
            array(
                'weight-max'   => 35,
                'weight-costs' => 50.20,
            ),
            array(
                'weight-max'   => 40,
                'weight-costs' => 50.20,
            ),
            array(
                'weight-max'   => 45,
                'weight-costs' => 56.31,
            ),
            array(
                'weight-max'   => 50,
                'weight-costs' => 56.31,
            ),
            array(
                'weight-max'   => 60,
                'weight-costs' => 64.59,
            ),
            array(
                'weight-max'   => 70,
                'weight-costs' => 71.90,
            ),
            array(
                'weight-max'   => 80,
                'weight-costs' => 79.09,
            ),
            array(
                'weight-max'   => 90,
                'weight-costs' => 86.44,
            ),
            array(
                'weight-max'   => 100,
                'weight-costs' => 93.65,
            ),
        );
        $prices            = \array_merge($prices_pak, $prices_other);
        $prices_weight_max = \array_key_last($prices);

        if ($this->weight <= $prices_weight_max) {
            foreach ($prices as $weight => $entry) {
                if ($this->weight <= $weight) {
                    $shipping_national_first['cost']           = $entry['costs'];
                    $shipping_national_first['calculations'][] = array(
                        'item'  => sprintf(
                            'National Shipping (Pak)',
                        ),
                        'costs' => $entry['costs'],
                    );
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
        $shipping_national_first['calculations'][] = array(
            'item'  => sprintf(
                'National Shipping (price per kg)',
            ),
            'costs' => $price,
        );

        return $shipping_national_first;
    }

    private function getNationalPriorityExpress(): array
    {
        $shipping_national_priority_express = array(
            'id'               => 'nationalpriorityexpress',
            'title'            => 'Priority Express',
            'description'      => 'Zustellung bis Mittag.',
            'cost'             => 0,
            'calculations'     => array(),
            'type'             => 'express',
            'weight_formatted' => $this->weight_formatted,
        );

        /**
         * Envelope
         */
        if ($this->weight < 0.5) {
            $weight_costs = 8.66;

            $shipping_national_priority_express['cost']           = $weight_costs;
            $shipping_national_priority_express['calculations'][] = array(
                'item'  => sprintf(
                    'National Shipping (Envelope)',
                ),
                'costs' => $weight_costs,
            );

            return $shipping_national_priority_express;
        }

        /**
         * Pak
         */
        $prices_pak        = array(
            array(
                'weight-max'   => 0.5,
                'weight-costs' => 9.04,
            ),
            array(
                'weight-max'   => 1.0,
                'weight-costs' => 9.32,
            ),
            array(
                'weight-max'   => 1.5,
                'weight-costs' => 9.32,
            ),
            array(
                'weight-max'   => 2.0,
                'weight-costs' => 9.32,
            ),
            array(
                'weight-max'   => 2.5,
                'weight-costs' => 9.32,
            ),
        );
        $prices_other      = array(
            array(
                'weight-max'   => 5,
                'weight-costs' => 9.55,
            ),
            array(
                'weight-max'   => 10,
                'weight-costs' => 9.55,
            ),
            array(
                'weight-max'   => 15,
                'weight-costs' => 15.20,
            ),
            array(
                'weight-max'   => 20,
                'weight-costs' => 15.20,
            ),
            array(
                'weight-max'   => 25,
                'weight-costs' => 21.95,
            ),
            array(
                'weight-max'   => 30,
                'weight-costs' => 21.95,
            ),
            array(
                'weight-max'   => 35,
                'weight-costs' => 31.45,
            ),
            array(
                'weight-max'   => 40,
                'weight-costs' => 31.45,
            ),
            array(
                'weight-max'   => 45,
                'weight-costs' => 36.45,
            ),
            array(
                'weight-max'   => 50,
                'weight-costs' => 36.45,
            ),
            array(
                'weight-max'   => 60,
                'weight-costs' => 43.24,
            ),
            array(
                'weight-max'   => 70,
                'weight-costs' => 49.34,
            ),
            array(
                'weight-max'   => 80,
                'weight-costs' => 55.40,
            ),
            array(
                'weight-max'   => 90,
                'weight-costs' => 61.54,
            ),
            array(
                'weight-max'   => 100,
                'weight-costs' => 67.59,
            ),
        );
        $prices            = \array_merge($prices_pak, $prices_other);
        $prices_weight_max = \array_key_last($prices);

        if ($this->weight <= $prices_weight_max) {
            foreach ($prices as $weight => $entry) {
                if ($this->weight <= $weight) {
                    $shipping_national_priority_express['cost']           = $entry['costs'];
                    $shipping_national_priority_express['calculations'][] = array(
                        'item'  => sprintf(
                            'National Shipping (Pak)',
                        ),
                        'costs' => $entry['costs'],
                    );
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
        $shipping_national_priority_express['calculations'][] = array(
            'item'  => sprintf(
                'National Shipping (price per kg)',
            ),
            'costs' => $price,
        );

        return $shipping_national_priority_express;
    }

    private function getNationalPriority(): array
    {
        $shipping_national_priority = array(
            'id'               => 'nationalpriority',
            'title'            => 'Priority',
            'description'      => 'Zustellung bis zum Ende des Geschäftstages.',
            'cost'             => 0,
            'calculations'     => array(),
            'type'             => 'express',
            'weight_formatted' => $this->weight_formatted,
        );

        /**
         * Envelope
         */
        if ($this->weight < 0.5) {
            $weight_costs = 5.35;

            $shipping_national_priority['cost']           = $weight_costs;
            $shipping_national_priority['calculations'][] = array(
                'item'  => sprintf(
                    'National Shipping (Envelope)',
                ),
                'costs' => $weight_costs,
            );

            return $shipping_national_priority;
        }

        /**
         * Pak
         */
        $prices_pak        = array(
            array(
                'weight-max'   => 0.5,
                'weight-costs' => 5.35,
            ),
            array(
                'weight-max'   => 1.0,
                'weight-costs' => 5.38,
            ),
            array(
                'weight-max'   => 1.5,
                'weight-costs' => 5.77,
            ),
            array(
                'weight-max'   => 2.0,
                'weight-costs' => 5.77,
            ),
            array(
                'weight-max'   => 2.5,
                'weight-costs' => 5.77,
            ),
        );
        $prices_other      = array(
            array(
                'weight-max'   => 5,
                'weight-costs' => 6.40,
            ),
            array(
                'weight-max'   => 10,
                'weight-costs' => 6.40,
            ),
            array(
                'weight-max'   => 15,
                'weight-costs' => 7.75,
            ),
            array(
                'weight-max'   => 20,
                'weight-costs' => 7.75,
            ),
            array(
                'weight-max'   => 25,
                'weight-costs' => 10.15,
            ),
            array(
                'weight-max'   => 30,
                'weight-costs' => 10.15,
            ),
            array(
                'weight-max'   => 35,
                'weight-costs' => 27.05,
            ),
            array(
                'weight-max'   => 40,
                'weight-costs' => 27.05,
            ),
            array(
                'weight-max'   => 45,
                'weight-costs' => 31.29,
            ),
            array(
                'weight-max'   => 50,
                'weight-costs' => 31.29,
            ),
            array(
                'weight-max'   => 60,
                'weight-costs' => 38.04,
            ),
            array(
                'weight-max'   => 70,
                'weight-costs' => 44.39,
            ),
            array(
                'weight-max'   => 80,
                'weight-costs' => 49.93,
            ),
            array(
                'weight-max'   => 90,
                'weight-costs' => 55.33,
            ),
            array(
                'weight-max'   => 100,
                'weight-costs' => 60.84,
            ),
        );
        $prices            = \array_merge($prices_pak, $prices_other);
        $prices_weight_max = \array_key_last($prices);

        if ($this->weight <= $prices_weight_max) {
            foreach ($prices as $weight => $entry) {
                if ($this->weight <= $weight) {
                    $shipping_national_priority['cost']           = $entry['costs'];
                    $shipping_national_priority['calculations'][] = array(
                        'item'  => sprintf(
                            'National Shipping (Pak)',
                        ),
                        'costs' => $entry['costs'],
                    );
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
        $shipping_national_priority['calculations'][] = array(
            'item'  => sprintf(
                'National Shipping (price per kg)',
            ),
            'costs' => $price,
        );

        return $shipping_national_priority;
    }

    private function getNationalPriorityExpressFreight(): array
    {
        $shipping_national_priority_express_freight = array(
            'id'               => 'nationalpriorityexpressfreight',
            'title'            => 'Priority Express Freight',
            'description'      => 'Zustellung bis Mittag',
            'cost'             => 0,
            'calculations'     => array(),
            'type'             => 'freight',
            'weight_formatted' => $this->weight_formatted,
        );

        if ($this->weight >= 68 && $this->weight <= 100) {
            $price_per_kg = 1.48;
        } elseif ($this->weight > 100 && $this->weight <= 200) {
            $price_per_kg = 1.48;
        } elseif ($this->weight > 200) {
            $price_per_kg = 1.48;
        } else {
            return array();
        }

        $price = $this->weight * $price_per_kg;

        $shipping_national_priority_express_freight['cost']           = $price;
        $shipping_national_priority_express_freight['calculations'][] = array(
            'item'  => sprintf(
                'National Shipping (price per kg)',
            ),
            'costs' => $price,
        );

        return $shipping_national_priority_express_freight;
    }

    private function getNationalPriorityFreight(): array
    {
        $shipping_national_priority_freight = array(
            'id'               => 'nationalpriorityfreight',
            'title'            => 'Priority Freight',
            'description'      => 'Zustellung bis zum Ende des Geschäftstages.',
            'cost'             => 0,
            'calculations'     => array(),
            'type'             => 'freight',
            'weight_formatted' => $this->weight_formatted,
        );

        if ($this->weight >= 68 && $this->weight <= 100) {
            $price_per_kg = 0.99;
        } elseif ($this->weight > 100 && $this->weight <= 200) {
            $price_per_kg = 0.99;
        } elseif ($this->weight > 200) {
            $price_per_kg = 0.99;
        } else {
            return array();
        }

        $price = $this->weight * $price_per_kg;

        $shipping_national_priority_freight['cost']           = $price;
        $shipping_national_priority_freight['calculations'][] = array(
            'item'  => sprintf(
                'National Shipping (price per kg)',
            ),
            'costs' => $price,
        );

        return $shipping_national_priority_freight;
    }

    public function getInternational(): array
    {
        $international = \array_filter(
            array(
                $this->getInternationalEconomy(),
                $this->getInternationalPriority(),
            ),
            function (array $method) {
                return !empty($method);
            }
        );

        return $international;
    }

    private function getInternationalEconomy(): array
    {
        $shipping_international_economy = array(
            'id'               => 'internationaleconomy',
            'title'            => 'Economy',
            'description'      => 'Economy Versand.',
            'cost'             => 0,
            'calculations'     => array(),
            'type'             => 'standard',
            'weight_formatted' => $this->weight_formatted,
        );

        $this->setShippingCosts($shipping_international_economy);

        if ($shipping_international_economy['cost'] <= 0) {
            return array();
        }

        return $shipping_international_economy;
    }

    private function getInternationalPriority(): array
    {
        $shipping_international_priority = array(
            'id'               => 'internationalpriority',
            'title'            => 'Priority',
            'description'      => 'Priority Versand.',
            'cost'             => 0,
            'calculations'     => array(),
            'type'             => 'express',
            'weight_formatted' => $this->weight_formatted,
        );

        $this->setShippingCosts($shipping_international_priority);

        if ($shipping_international_priority['cost'] <= 0) {
            return array();
        }

        return $shipping_international_priority;
    }
}
