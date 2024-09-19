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

use Grandeljay\Fedex\{Constants, Quote, Zone};
use Grandeljay\Fedex\Traits\Installer;
use RobinTheHood\ModifiedStdModule\Classes\StdModule;

class grandeljayfedex extends StdModule
{
    use Installer;

    public const VERSION = '0.12.1';

    public array $properties = [];

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
    public array $quotes = [];

    /**
     * Used to calculate the tax.
     *
     * @var int
     */
    public int $tax_class = 1;

    public function __construct()
    {
        parent::__construct(Constants::MODULE_SHIPPING_NAME);

        $this->checkForUpdate(true);

        $this->addKey('SORT_ORDER');

        $this->addKey('WEIGHT');
        $this->addKey('SHIPPING');
        $this->addKey('SURCHARGES');
        $this->addKey('BULK_PRICE_CHANGE_PREVIEW');
    }

    public function install()
    {
        parent::install();

        $this->addConfiguration('ALLOWED', '', 6, 1);
        $this->addConfiguration('SORT_ORDER', 6, 6, 1);

        $this->addConfigurationWeight();
        $this->addConfigurationShipping();
        $this->addConfigurationSurcharges();
        $this->addConfigurationBulkPriceChangePreview();

        $this->setAdminAccess(self::class);
    }

    protected function updateSteps(): int
    {
        if (version_compare($this->getVersion(), self::VERSION, '<')) {
            $this->setVersion(self::VERSION);

            return self::UPDATE_SUCCESS;
        }

        return self::UPDATE_NOTHING;
    }

    public function remove()
    {
        parent::remove();

        $this->removeConfigurationAll();
        $this->deleteAdminAccess(self::class);
    }

    /**
     * Used by modified to show shipping costs. Will be ignored if the value is
     * not an array.
     *
     * @var ?array
     */
    public function quote(): ?array
    {
        $quote = (new Quote())->getQuote();

        if (is_array($quote)) {
            $this->quotes = $quote;
        } else {
            return null;
        }

        return $quote;
    }
}
