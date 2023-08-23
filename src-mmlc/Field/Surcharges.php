<?php

namespace Grandeljay\Fedex\Field;

use Grandeljay\Fedex\Constants;

class Surcharges
{
    public const CONFIGURATION_KEY_SURCHARGES = Constants::MODULE_SHIPPING_NAME . '_SURCHARGES';
    public const CONFIGURATION_KEY_PICK_PACK  = Constants::MODULE_SHIPPING_NAME . '_PICK_PACK';

    public static function getSurchargesGroup(): string
    {
        ob_start();
        ?>
        <details>
            <summary>Aufschläge</summary>

            <div>
                <?= self::getSurcharges() ?>
                <?= self::getPickPack() ?>
            </div>
        </details>
        <?php
        return ob_get_clean();
    }

    private static function getSurcharges(): string
    {
        $configuration_key   = self::CONFIGURATION_KEY_SURCHARGES;
        $configuration_value = constant($configuration_key);

        ob_start();
        ?>
        <details>
            <summary>Aufschläge</summary>

            <div>
                <textarea name="configuration[<?= $configuration_key ?>]" spellcheck="false" data-url="<?= Constants::API_ENDPOINT_SURCHARGES_GET ?>"><?= $configuration_value ?></textarea>
            </div>
        </details>
        <?php
        return ob_get_clean();
    }

    private static function getPickPack(): string
    {
        $configuration_key   = self::CONFIGURATION_KEY_PICK_PACK;
        $configuration_value = constant($configuration_key);

        ob_start();
        ?>
        <details>
            <summary>Pick & Pack</summary>

            <div>
                <textarea name="configuration[<?= $configuration_key ?>]" spellcheck="false" data-url="<?= Constants::API_ENDPOINT_PICK_PACK_GET ?>"><?= $configuration_value ?></textarea>
            </div>
        </details>
        <?php
        return ob_get_clean();
    }
}
