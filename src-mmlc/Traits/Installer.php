<?php

namespace Grandeljay\Fedex\Traits;

use Grandeljay\Fedex\Classes\Defaults;
use Grandeljay\Fedex\Constants;
use Grandeljay\Fedex\Zone;

trait Installer
{
    use SetFunctions;

    private function addConfigurationWeight(): void
    {
        $this->addConfiguration('WEIGHT', '', 6, 1, self::class . '::weight(');
        $this->addConfiguration('WEIGHT_IDEAL', round(SHIPPING_MAX_WEIGHT * 0.75), 6, 1);
        $this->addConfiguration('WEIGHT_MAXIMUM', SHIPPING_MAX_WEIGHT, 6, 1);
    }

    private function addConfigurationShipping(): void
    {
        $this->addConfiguration('SHIPPING', '', 6, 1, self::class . '::shipping(');

        $this->addConfigurationShippingNational();
        $this->addConfigurationShippingInternational();
    }

    private function addConfigurationShippingNational(): void
    {
        $configuration_key = 'ENVELOPE_0_5_KG';

        $this->addConfiguration($configuration_key, 18.14, 6, 1);
    }

    private function addConfigurationShippingInternational(): void
    {
        foreach (Zone::cases() as $zone) {
            $configuration_key    = sprintf('SHIPPING_INTERNATIONAL_ECONOMY_ZONE%s', $zone->name);
            $configuration_method = sprintf('getShippingInternationalEconomyZone%s', $zone->name);
            $configuration_value  = Defaults::$configuration_method();

            $this->addConfiguration($configuration_key, $configuration_value, 6, 1);
        }

        foreach (Zone::cases() as $zone) {
            $configuration_key    = sprintf('SHIPPING_INTERNATIONAL_PRIORITY_ZONE%s', $zone->name);
            $configuration_method = sprintf('getShippingInternationalPriorityZone%s', $zone->name);
            $configuration_value  = Defaults::$configuration_method();

            $this->addConfiguration($configuration_key, $configuration_value, 6, 1);
        }
    }

    private function addConfigurationSurcharges(): void
    {
        $this->addConfiguration('SURCHARGES', Defaults::getSurcharges(), 6, 1, self::class . '::surcharges(');
        $this->addConfiguration('PICK_PACK', Defaults::getPickPack(), 6, 1);
    }

    private function addConfigurationBulkPriceChangePreview(): void
    {
        $this->addConfiguration('BULK_PRICE_CHANGE_PREVIEW', '', 6, 1, self::class . '::bulkPriceChangePreview(');
    }
}