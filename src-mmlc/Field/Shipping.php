<?php

namespace Grandeljay\Fedex\Field;

use Grandeljay\Fedex\Zone;

class Shipping
{
    public static function getInternational(array $international): string
    {
        $economy  = $international['economy'];
        $priority = $international['priority'];

        ob_start();
        ?>
        <details>
            <summary>International</summary>

            <div>
                <?= self::getEconomy($economy); ?>
                <?= self::getPriority($priority); ?>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public static function getEconomy(array $economy): string
    {
        ob_start();
        ?>
        <details>
            <summary>Economy</summary>

            <div>
                <?php foreach ($economy as $zone_name => $zones) { ?>
                    <details>
                        <summary>
                            <?php
                            $zone_letter = strtoupper(substr($zone_name, -1, 1));
                            $zone_text   = sprintf('Zone %s', $zone_letter);
                            $zone        = Zone::fromString($zone_letter);

                            echo $zone_text;
                            ?>
                        </summary>

                        <div>
                            <p>Betrifft die Länder: <?= implode(', ', Zone::getCountries($zone)) ?>.</p>

                            <table>
                                <thead>
                                    <tr>
                                        <th>Gewicht</th>
                                        <th>Kosten</th>
                                    </tr>
                                    <tr>
                                        <td>Maximal zulässiges Gewicht (in Kg) für diesen Preis.</td>
                                        <td>Versandkosten für Gewicht in EUR.</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($zones as $index => $zone) { ?>
                                        <?php
                                        $name_weight_max   = sprintf(
                                            '%s[shipping][international][economy][%s][%s][weight-max]',
                                            \grandeljayfedex::class,
                                            $zone_name,
                                            $index
                                        );
                                        $name_weight_costs = sprintf(
                                            '%s[shipping][international][economy][%s][%s][weight-costs]',
                                            \grandeljayfedex::class,
                                            $zone_name,
                                            $index
                                        );
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="number" step="any" name="<?= $name_weight_max ?>" value="<?= $zone['weight-max'] ?>">
                                            </td>
                                            <td>
                                                <input type="number" step="any" name="<?= $name_weight_costs ?>" value="<?= $zone['weight-costs'] ?>">
                                            </td>
                                            <td>
                                                <button type="button" class="remove">
                                                    <img src="images/icons/cross.gif">
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <?php
                                    $name_weight_max   = sprintf(
                                        '%s[shipping][international][economy][%s][%s][weight-max]',
                                        \grandeljayfedex::class,
                                        $zone_name,
                                        $index + 1
                                    );
                                    $name_weight_costs = sprintf(
                                        '%s[shipping][international][economy][%s][%s][weight-costs]',
                                        \grandeljayfedex::class,
                                        $zone_name,
                                        $index + 1
                                    );
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="number" step="any" name="<?= $name_weight_max ?>">
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="<?= $name_weight_costs ?>">
                                        </td>
                                        <td>
                                            <button type="button" class="remove">
                                                <img src="images/icons/cross.gif">
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </details>
                <?php } ?>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public static function getPriority(array $priority): string
    {
        ob_start();
        ?>
        <details>
            <summary>Priority</summary>

            <div>
                <?php foreach ($priority as $zone_name => $zones) { ?>
                    <details>
                        <summary>
                            <?php
                            $zone_letter = strtoupper(substr($zone_name, -1, 1));
                            $zone_text   = sprintf('Zone %s', $zone_letter);
                            $zone        = Zone::fromString($zone_letter);

                            echo $zone_text;
                            ?>
                        </summary>

                        <div>
                            <p>Betrifft die Länder: <?= implode(', ', Zone::getCountries($zone)) ?>.</p>

                            <table>
                                <thead>
                                    <tr>
                                        <th>Gewicht</th>
                                        <th>Kosten</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td>Maximal zulässiges Gewicht (in Kg) für diesen Preis.</td>
                                        <td>Versandkosten für Gewicht in EUR.</td>
                                        <td></td>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($zones as $index => $zone) { ?>
                                        <?php
                                        $name_weight_max   = sprintf(
                                            '%s[shipping][international][priority][%s][%s][weight-max]',
                                            \grandeljayfedex::class,
                                            $zone_name,
                                            $index
                                        );
                                        $name_weight_costs = sprintf(
                                            '%s[shipping][international][priority][%s][%s][weight-costs]',
                                            \grandeljayfedex::class,
                                            $zone_name,
                                            $index
                                        );
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="number" step="any" name="<?= $name_weight_max ?>" value="<?= $zone['weight-max'] ?>">
                                            </td>
                                            <td>
                                                <input type="number" step="any" name="<?= $name_weight_costs ?>" value="<?= $zone['weight-costs'] ?>">
                                            </td>
                                            <td>
                                                <button type="button" class="remove">
                                                    <img src="images/icons/cross.gif">
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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
