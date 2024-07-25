<?php

namespace Grandeljay\Fedex\Field;

use Grandeljay\Fedex\{Constants, Zone};

class Shipping
{
    public static function getInternational(): string
    {
        $class = Field::getFieldClasses();

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>International</summary>

            <div>
                <?= self::getEconomy(); ?>
                <?= self::getPriority(); ?>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public static function getEconomy(): string
    {
        $class = Field::getFieldClasses();

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>Economy</summary>

            <div>
                <?php foreach (Zone::cases() as $zone) { ?>
                    <?php
                    $zone_title          = sprintf('Zone %s', $zone->name);
                    $configuration_key   = sprintf(
                        '%s_SHIPPING_INTERNATIONAL_ECONOMY_ZONE%s',
                        Constants::MODULE_SHIPPING_NAME,
                        $zone->name
                    );
                    $configuration_value = constant($configuration_key);

                    /** Apply factor */
                    $factor              = $_GET['factor'] ?? 1;
                    $tariffs_json        = \json_decode($configuration_value, true);
                    $tariffs             = \array_map(
                        function (array $tariff) use ($factor) {
                            $tariff['weight-costs'] *= $factor;

                            return $tariff;
                        },
                        $tariffs_json
                    );
                    $configuration_value = \json_encode($tariffs);
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

    public static function getPriority(): string
    {
        $class = Field::getFieldClasses();

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>Priority</summary>

            <div>
                <?php foreach (Zone::cases() as $zone) { ?>
                    <?php
                    $zone_title          = sprintf('Zone %s', $zone->name);
                    $configuration_key   = sprintf(
                        '%s_SHIPPING_INTERNATIONAL_PRIORITY_ZONE%s',
                        Constants::MODULE_SHIPPING_NAME,
                        $zone->name
                    );
                    $configuration_value = constant($configuration_key);

                    /** Apply factor */
                    $factor              = $_GET['factor'] ?? 1;
                    $tariffs_json        = \json_decode($configuration_value, true);
                    $tariffs             = \array_map(
                        function (array $tariff) use ($factor) {
                            $tariff['weight-costs'] *= $factor;

                            return $tariff;
                        },
                        $tariffs_json
                    );
                    $configuration_value = \json_encode($tariffs);

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
