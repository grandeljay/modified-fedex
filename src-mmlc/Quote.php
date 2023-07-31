<?php

namespace Grandeljay\Fedex;

class Quote
{
    private function getEmpty(): array
    {
        $emptyQuote = array(
            'methods' => array(),
        );

        return $emptyQuote;
    }

    private function getShippingCosts(string $method, string $zone_name): float
    {
        global $shipping_weight;

        $shipping = constant(Constants::MODULE_SHIPPING_NAME . '_SHIPPING');
        $shipping = json_decode(base64_decode($shipping), true);

        $costs_list = $shipping['international'][$method][$zone_name] ?? null;

        if (null === $costs_list) {
            return 0;
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

                break;
            }
        }

        return $costs;
    }

    private function getSurcharges(float $method_costs): float
    {
        $surcharges = 0;

        $option  = constant(Constants::MODULE_SHIPPING_NAME . '_SURCHARGES');
        $decoded = json_decode(base64_decode($option), true);

        foreach ($decoded as $surcharge) {
            $amount = match ($surcharge['type']) {
                'fixed'   => $surcharge['costs'],
                'percent' => $method_costs * $surcharge['costs'],
            };

            $surcharges += $amount;
        }

        return $surcharges;
    }

    public function getQuote(): array
    {
        global $order, $shipping_weight;

        $country_code = $order->delivery['country']['iso_code_2'] ?? null;

        if (null === $country_code) {
            return $this->getEmpty();
        }

        $country_zone      = Zone::fromCountry($country_code);
        $country_zone_name = 'zone_' . strtolower($country_zone->name);

        if (null === $country_zone) {
            return $this->getEmpty();
        }

        $methods = array();

        $method_economy = array(
            'id'    => 'economy',
            'title' => sprintf(
                'FedEx Economy (%s kg)<!-- BREAK -->Zone %s',
                round($shipping_weight, 2),
                $country_zone->name
            ),
            'cost'  => $this->getShippingCosts('economy', $country_zone_name),
            'type'  => 'standard',
        );
        if ($method_economy['cost'] > 0) {
            $methods[] = $method_economy;
        }

        $method_priority = array(
            'id'    => 'priority',
            'title' => sprintf(
                'FedEx Priority (%s kg)<!-- BREAK -->Zone %s',
                round($shipping_weight, 2),
                $country_zone->name
            ),
            'cost'  => $this->getShippingCosts('priority', $country_zone_name),
            'type'  => 'express',
        );
        if ($method_priority['cost'] > 0) {
            $methods[] = $method_priority;
        }

        /** Surcharges */
        foreach ($methods as &$method) {
            $method['cost'] += $this->getSurcharges($method['cost']);
        }

        /** Quote */
        $quote = array(
            'id'      => self::class,
            'module'  => sprintf(
                constant(Constants::MODULE_SHIPPING_NAME . '_TEXT_TITLE_WEIGHT'),
                round($shipping_weight, 2)
            ),
            'methods' => $methods,
        );

        return $quote;
    }
}
