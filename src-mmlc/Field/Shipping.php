<?php

namespace Grandeljay\Fedex\Field;

use Grandeljay\Fedex\{Constants, Zone};

class Shipping
{
    public static function getInternational(): string
    {
        ob_start();
        ?>
        <details>
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
        ob_start();
        ?>
        <details>
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
                    ?>
                    <details>
                        <summary><?= $zone_title ?></summary>

                        <div>
                            <p>Betrifft die Länder: <?= implode(', ', Zone::getCountries($zone)) ?>.</p>

                            <textarea name="configuration[<?= $configuration_key ?>]" spellcheck="false" data-url="<?= Constants::API_ENDPOINT_WEIGHT_GET ?>"><?= $configuration_value ?></textarea>
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
        ob_start();
        ?>
        <details>
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
                    ?>
                    <details>
                        <summary><?= $zone_title ?></summary>

                        <div>
                            <p>Betrifft die Länder: <?= implode(', ', Zone::getCountries($zone)) ?>.</p>

                            <textarea name="configuration[<?= $configuration_key ?>]" spellcheck="false" data-url="<?= Constants::API_ENDPOINT_WEIGHT_GET ?>"><?= $configuration_value ?></textarea>
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
