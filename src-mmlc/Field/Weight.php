<?php

namespace Grandeljay\Fedex\Field;

use Grandeljay\Fedex\Constants;

class Weight
{
    public static function getWeight(): string
    {
        $configuration_key         = Constants::MODULE_SHIPPING_NAME . '_WEIGHT_';
        $configuration_key_ideal   = $configuration_key . 'IDEAL';
        $configuration_key_maximum = $configuration_key . 'MAXIMUM';

        $configuration_value_ideal   = constant($configuration_key_ideal);
        $configuration_value_maximum = constant($configuration_key_maximum);

        ob_start();
        ?>
        <details>
            <summary>Gewicht</summary>

            <div>
            <details>
                    <summary>Ideal</summary>

                    <div>
                        <p>Zielgewicht beim packen der Bestellung um z. B. die Transportsicherheit zu erhöhen. Pakete werden möglichst bis zu diesem Wert gepackt.</p>

                        <input type="number" step="any" name="configuration[<?= $configuration_key_ideal ?>]" value="<?= $configuration_value_ideal ?>">
                    </div>
                </details>

                <details>
                    <summary>Maximal</summary>

                    <div>
                        <p>Maximalgewicht in Kilogramm, das ein Paket haben darf. Wenn ein Paket im Warenkorb diesen Wert überschreitet, wird die Versandart ausgeblendet.</p>

                        <input type="number" step="any" name="configuration[<?= $configuration_key_maximum ?>]" value="<?= $configuration_value_maximum ?>">
                    </div>
                </details>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }
}
