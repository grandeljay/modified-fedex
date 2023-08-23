<?php

/**
 * FedEx
 *
 * @author  Jay Trees <fedex@grandels.email>
 * @link    https://github.com/grandeljay/modified-fedex
 * @package GrandeljayFedex
 *
 * @phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
 * @phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
 */

use Grandeljay\Fedex\{Constants, Installer, Quote, Zone};
use Grandeljay\Fedex\Field\{Shipping, Surcharges, Weight};
use RobinTheHood\ModifiedStdModule\Classes\StdModule;

class grandeljayfedex extends StdModule
{
    private Installer $installer;

    public const VERSION     = '0.4.2';
    public array $properties = array();

    public static function weight(): string
    {
        $html = Weight::getWeight();

        return $html;
    }

    public static function shipping(): string
    {
        $html = Shipping::getInternational();

        return $html;
    }

    public static function surcharges(string $value, string $option): string
    {
        $html = Surcharges::getSurchargesGroup($value, $option);

        return $html;
    }

    public static function userMayAccessAPI(): bool
    {
        if (!isset($_SESSION['customer_id'])) {
            return false;
        }

        $access_query = xtc_db_query(
            sprintf(
                'SELECT *
                   FROM `%s`
                  WHERE `customers_id` = %s',
                TABLE_ADMIN_ACCESS,
                $_SESSION['customer_id']
            )
        );
        $access       = xtc_db_fetch_array($access_query);

        if (isset($access[self::class]) && '1' === $access[self::class]) {
            return true;
        }

        return false;
    }

    /**
     * Used by modified to determine the cheapest shipping method. Should
     * contain the return value of the `quote` method.
     *
     * @var array
     */
    public array $quotes = array();

    public function __construct()
    {
        parent::__construct(Constants::MODULE_SHIPPING_NAME);

        $this->addKey('SORT_ORDER');

        $this->addKey('WEIGHT');
        $this->addKey('SHIPPING');
        $this->addKey('SURCHARGES');

        $this->installer = new Installer();
    }

    public function install()
    {
        parent::install();

        $this->addConfiguration('ALLOWED', '', 6, 1);
        $this->addConfiguration('SORT_ORDER', 6, 6, 1);

        $this->addConfigurationWeight();
        $this->addConfigurationShipping();
        $this->addConfigurationSurcharges();

        $this->installer->installAdminAccess();
    }

    private function addConfigurationWeight(): void
    {
        $this->addConfiguration('WEIGHT', '', 6, 1, self::class . '::weight(');
        $this->addConfiguration('WEIGHT_IDEAL', round(SHIPPING_MAX_WEIGHT * 0.75), 6, 1);
        $this->addConfiguration('WEIGHT_MAXIMUM', SHIPPING_MAX_WEIGHT, 6, 1);
    }

    private function addConfigurationShipping(): void
    {
        $this->addConfiguration('SHIPPING', '', 6, 1, self::class . '::shipping(');

        foreach (Zone::cases() as $zone) {
            $configuration_key    = sprintf('SHIPPING_INTERNATIONAL_ECONOMY_ZONE%s', $zone->name);
            $configuration_method = sprintf('getShippingInternationalEconomyZone%s', $zone->name);
            $configuration_value  = $this->installer->$configuration_method();

            $this->addConfiguration($configuration_key, $configuration_value, 6, 1);
        }

        foreach (Zone::cases() as $zone) {
            $configuration_key    = sprintf('SHIPPING_INTERNATIONAL_PRIORITY_ZONE%s', $zone->name);
            $configuration_method = sprintf('getShippingInternationalPriorityZone%s', $zone->name);
            $configuration_value  = $this->installer->$configuration_method();

            $this->addConfiguration($configuration_key, $configuration_value, 6, 1);
        }
    }

    private function addConfigurationSurcharges(): void
    {
        $this->addConfiguration('SURCHARGES', $this->installer->getSurcharges(), 6, 1, self::class . '::surcharges(');
        $this->addConfiguration('PICK_PACK', $this->installer->getPickPack(), 6, 1);
    }

    public function remove()
    {
        parent::remove();

        $this->removeConfiguration('ALLOWED');
        $this->removeConfiguration('SORT_ORDER');

        $this->removeConfigurationWeight();
        $this->removeConfigurationShipping();
        $this->removeConfigurationSurcharges();

        $this->installer->uninstallAdminAccess();
    }

    private function removeConfigurationWeight(): void
    {
        $this->removeConfiguration('WEIGHT');
        $this->removeConfiguration('WEIGHT_IDEAL');
        $this->removeConfiguration('WEIGHT_MAXIMUM');
    }

    private function removeConfigurationShipping(): void
    {
        $this->removeConfiguration('SHIPPING');

        foreach (Zone::cases() as $zone) {
            $configuration_key = sprintf('SHIPPING_INTERNATIONAL_ECONOMY_ZONE%s', $zone->name);

            $this->removeConfiguration($configuration_key);
        }

        foreach (Zone::cases() as $zone) {
            $configuration_key = sprintf('SHIPPING_INTERNATIONAL_PRIORITY_ZONE%s', $zone->name);
            $this->removeConfiguration($configuration_key);
        }
    }

    private function removeConfigurationSurcharges(): void
    {
        $this->removeConfiguration('SURCHARGES');
        $this->removeConfiguration('PICK_PACK');
    }

    /**
     * Used by modified to show shipping costs. Will be ignored if the value is
     * not an array.
     *
     * @var ?array
     */
    public function quote(): ?array
    {
        $quote  = new Quote();
        $quotes = $quote->getQuote();

        if (is_array($quotes) && !$quote->exceedsMaximumWeight()) {
            $this->quotes = $quotes;
        } else {
            return null;
        }

        return $quotes;
    }
}
