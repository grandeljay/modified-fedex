<?php

namespace Grandeljay\Fedex\Field;

use Grandeljay\Fedex\{Constants, Zone};

class Shipping
{
    private static function getConfigurationFilteredJson(string $configuration_key): string
    {
        $configuration_json    = defined($configuration_key)
                               ? constant($configuration_key)
                               : '[]';
        $configuration_decoded = \json_decode($configuration_json, true);
        $configuration_value   =  \array_filter(
            $configuration_decoded,
            function (array $tariff) {
                if (empty($tariff) || (empty($tariff['weight-min']) && empty($tariff['weight-max'])) || empty($tariff['weight-costs'])) {
                    return false;
                }

                return true;
            }
        );

        /** Apply Factor */
        $factor = $_GET['factor'] ?? 1;

        $configuration_value = \array_map(
            function (array $tariff) use ($factor) {
                $tariff['weight-costs'] *= $factor;

                return $tariff;
            },
            $configuration_value
        );
        /** */

        $configuration_json = \json_encode($configuration_value);

        return $configuration_json;
    }

    public static function getNational(): string
    {
        $class = Field::getFieldClasses(['shipping-national']);

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>National</summary>

            <div>
                <?= self::getNationalFirst(); ?>
                <?= self::getNationalPriorityExpress(); ?>
                <?= self::getNationalPriority(); ?>
                <?= self::getNationalPriorityExpressFreight(); ?>
                <?= self::getNationalPriorityFreight(); ?>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public static function getNationalFirst(): string
    {
        $class = Field::getFieldClasses(['fedex-first']);

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>
                First - Zustellung am Vormittag
            </summary>

            <div>
                <?php
                $configuration_key   = \sprintf(
                    '%s_SHIPPING_NATIONAL_FIRST',
                    Constants::MODULE_SHIPPING_NAME
                );
                $configuration_value = self::getConfigurationFilteredJson($configuration_key);
                ?>

                <textarea name="configuration[<?= $configuration_key ?>]" spellcheck="false" data-url="<?= Constants::API_ENDPOINT_WEIGHT_NATIONAL_GET ?>"><?= $configuration_value ?></textarea>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public static function getNationalPriorityExpress(): string
    {
        $class = Field::getFieldClasses(['fedex-priority-express']);

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>
                Priority Express - Zustellung bis Mittag
            </summary>

            <div>
                <?php
                $configuration_key   = \sprintf(
                    '%s_SHIPPING_NATIONAL_PRIORITY_EXPRESS',
                    Constants::MODULE_SHIPPING_NAME
                );
                $configuration_value = self::getConfigurationFilteredJson($configuration_key);
                ?>

                <textarea name="configuration[<?= $configuration_key ?>]" spellcheck="false" data-url="<?= Constants::API_ENDPOINT_WEIGHT_NATIONAL_GET ?>"><?= $configuration_value ?></textarea>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public static function getNationalPriority(): string
    {
        $class = Field::getFieldClasses(['fedex-priority']);

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>
                Priority - Zustellung bis zum Ende des Geschäftstages
            </summary>

            <div>
                <?php
                $configuration_key   = \sprintf(
                    '%s_SHIPPING_NATIONAL_PRIORITY',
                    Constants::MODULE_SHIPPING_NAME
                );
                $configuration_value = self::getConfigurationFilteredJson($configuration_key);
                ?>

                <textarea name="configuration[<?= $configuration_key ?>]" spellcheck="false" data-url="<?= Constants::API_ENDPOINT_WEIGHT_NATIONAL_GET ?>"><?= $configuration_value ?></textarea>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public static function getNationalPriorityExpressFreight(): string
    {
        $class = Field::getFieldClasses(['fedex-priority-express-freight']);

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>
                Priority Express Freight - Zustellung bis Mittag
            </summary>

            <div>
                <?php
                $configuration_key   = \sprintf(
                    '%s_SHIPPING_NATIONAL_PRIORITY_EXPRESS_FREIGHT',
                    Constants::MODULE_SHIPPING_NAME
                );
                $configuration_value = self::getConfigurationFilteredJson($configuration_key);
                ?>

                <textarea name="configuration[<?= $configuration_key ?>]" spellcheck="false" data-url="<?= Constants::API_ENDPOINT_WEIGHT_NATIONAL_FREIGHT_GET ?>"><?= $configuration_value ?></textarea>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public static function getNationalPriorityFreight(): string
    {
        $class = Field::getFieldClasses(['fedex-priority-freight']);

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>
                Priority Freight - Zustellung bis zum Ende des Geschäftstages
            </summary>

            <div>
                <?php
                $configuration_key   = \sprintf(
                    '%s_SHIPPING_NATIONAL_PRIORITY_FREIGHT',
                    Constants::MODULE_SHIPPING_NAME
                );
                $configuration_value = self::getConfigurationFilteredJson($configuration_key);
                ?>

                <textarea name="configuration[<?= $configuration_key ?>]" spellcheck="false" data-url="<?= Constants::API_ENDPOINT_WEIGHT_NATIONAL_FREIGHT_GET ?>"><?= $configuration_value ?></textarea>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public static function getInternational(): string
    {
        $class = Field::getFieldClasses();

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>International</summary>

            <div>
                <?= self::getInternationalEconomy(); ?>
                <?= self::getInternationalPriority(); ?>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public static function getInternationalEconomy(): string
    {
        $class = Field::getFieldClasses();

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>Economy</summary>

            <div>
                <?php foreach (Zone::cases() as $zone) { ?>
                    <?php
                    $zone_title          = \sprintf('Zone %s', $zone->name);
                    $configuration_key   = \sprintf(
                        '%s_SHIPPING_INTERNATIONAL_ECONOMY_ZONE%s',
                        Constants::MODULE_SHIPPING_NAME,
                        $zone->name
                    );
                    $configuration_value = self::getConfigurationFilteredJson($configuration_key);
                    ?>
                    <details class="<?= $class ?>">
                        <summary><?= $zone_title ?></summary>

                        <div>
                            <p>Betrifft die Länder: <?= implode(', ', Zone::getCountries($zone)) ?>.</p>

                            <textarea name="configuration[<?= $configuration_key ?>]" spellcheck="false" data-url="<?= Constants::API_ENDPOINT_WEIGHT_GET ?>" data-factor=<?= $_GET['factor'] ?? 1 ?>><?= $configuration_value ?></textarea>
                        </div>
                    </details>
                <?php } ?>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public static function getInternationalPriority(): string
    {
        $class = Field::getFieldClasses();

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>Priority</summary>

            <div>
                <?php foreach (Zone::cases() as $zone) { ?>
                    <?php
                    $zone_title          = \sprintf('Zone %s', $zone->name);
                    $configuration_key   = \sprintf(
                        '%s_SHIPPING_INTERNATIONAL_PRIORITY_ZONE%s',
                        Constants::MODULE_SHIPPING_NAME,
                        $zone->name
                    );
                    $configuration_value = self::getConfigurationFilteredJson($configuration_key);
                    ?>
                    <details class="<?= $class ?>">
                        <summary><?= $zone_title ?></summary>

                        <div>
                            <p>Betrifft die Länder: <?= implode(', ', Zone::getCountries($zone)) ?>.</p>

                            <textarea name="configuration[<?= $configuration_key ?>]" spellcheck="false" data-url="<?= Constants::API_ENDPOINT_WEIGHT_GET ?>" data-factor=<?= $_GET['factor'] ?? 1 ?>><?= $configuration_value ?></textarea>
                        </div>
                    </details>
                <?php } ?>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }
}
