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

use Grandeljay\Fedex\{Constants, Installer, Quote};
use Grandeljay\Fedex\Field\{Shipping, Surcharges};
use RobinTheHood\ModifiedStdModule\Classes\StdModule;

class grandeljayfedex extends StdModule
{
    private Installer $installer;

    public const VERSION     = '0.1.0';
    public array $properties = array();

    public static function shipping(string $value, string $option): string
    {
        $decoded       = json_decode(base64_decode($value), true);
        $international = $decoded['international'];

        $html = Shipping::getInternational($international);

        return $html;
    }

    public static function surcharges(string $value, string $option): string
    {
        $decoded    = json_decode(base64_decode($value), true);
        $surcharges = $decoded;

        $html = Surcharges::getSurcharges($surcharges);

        return $html;
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

        $this->addKey('SHIPPING');
        $this->addKey('SURCHARGES');

        $this->installer               = new Installer();
        $this->properties['form_edit'] = xtc_draw_form('modules', 'grandeljayfedex.php');
        $this->quotes                  = $this->quote();
    }

    public function install()
    {
        parent::install();

        $this->addConfiguration('ALLOWED', '', 6, 1);
        $this->addConfiguration('SHIPPING', $this->installer->getShipping(), 6, 1, \grandeljayfedex::class . '::shipping(');
        $this->addConfiguration('SURCHARGES', $this->installer->getSurcharges(), 6, 1, \grandeljayfedex::class . '::surcharges(');

        $this->installer->installAdminAccess();
    }

    public function remove()
    {
        parent::remove();

        $this->removeConfiguration('ALLOWED');
        $this->removeConfiguration('SHIPPING');
        $this->removeConfiguration('SURCHARGES');

        $this->installer->uninstallAdminAccess();
    }

    public function quote(): array
    {
        $quote = new Quote();

        return $quote->getQuote();
    }
}
