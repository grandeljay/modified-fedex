<?php

namespace Grandeljay\Fedex\Field;

use Grandeljay\Fedex\Constants;

class Surcharges
{
    public static function getSurcharges(string $option): string
    {
        ob_start();
        ?>
        <details>
            <summary>Aufschläge</summary>

            <div>
                <?php
                $configuration_key   = $option;
                $configuration_value = constant($configuration_key);
                ?>
                <textarea name="configuration[<?= $configuration_key ?>]" spellcheck="false" data-url="<?= Constants::API_ENDPOINT_SURCHARGES_GET ?>"><?= $configuration_value ?></textarea>
            </div>
        </details>
        <?php
        return ob_get_clean();
    }
}
