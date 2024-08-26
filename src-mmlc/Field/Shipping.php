<?php

namespace Grandeljay\Fedex\Field;

use Grandeljay\Fedex\{Constants, Zone};

class Shipping
{
    public static function getNational(): string
    {
        $class = Field::getFieldClasses(array('shipping-national'));

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>National</summary>

            <div>
                <?= self::getNationalFirst(); ?>
            </div>
        </details>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public static function getNationalFirst(): string
    {
        $class = Field::getFieldClasses(array('fedex-first'));

        ob_start();
        ?>
        <details class="<?= $class ?>">
            <summary>
                FedEx® First - Zustellung am Vormittag
            </summary>

            <div>
                <div class="tables">
                    <table>
                        <caption>FedEx Envelope</caption>

                        <thead>
                            <tr>
                                <th class="align-right">Gewicht (Kg)</th>
                                <th class="align-right">Kosten (€)</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="align-right">0,5</td>
                                <td class="align-right">
                                    <?php
                                    $configuration_key   = \sprintf('%s_ENVELOPE_0_5_KG', Constants::MODULE_SHIPPING_NAME);
                                    $configuration_value = \defined($configuration_key) ? \constant($configuration_key) : null;
                                    $configuration_name  = \sprintf('configuration[%s]', $configuration_key);
                                    ?>
                                    <?= \xtc_draw_input_field($configuration_name, $configuration_value, '', false, 'number') ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table>
                        <caption>FedEx Pak</caption>

                        <thead>
                            <tr>
                                <th class="align-right">Gewicht (Kg)</th>
                                <th class="align-right">Kosten (€)</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="align-right">0,5</td>
                                <td class="align-right">18,94</td>
                            </tr>
                            <tr>
                                <td class="align-right">1,0</td>
                                <td class="align-right">19,54</td>
                            </tr>
                            <tr>
                                <td class="align-right">1,5</td>
                                <td class="align-right">19,54</td>
                            </tr>
                            <tr>
                                <td class="align-right">2,0</td>
                                <td class="align-right">19,54</td>
                            </tr>
                            <tr>
                                <td class="align-right">2,5</td>
                                <td class="align-right">19,54</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="align-center">Preise für den Versand in anderen Verpackungen</td>
                            </tr>
                            <tr>
                                <td class="align-right">5,0</td>
                                <td class="align-right">22,45</td>
                            </tr>
                            <tr>
                                <td class="align-right">10,0</td>
                                <td class="align-right">22,45</td>
                            </tr>
                            <tr>
                                <td class="align-right">15,0</td>
                                <td class="align-right">31,95</td>
                            </tr>
                            <tr>
                                <td class="align-right">20,0</td>
                                <td class="align-right">31,95</td>
                            </tr>
                            <tr>
                                <td class="align-right">25,0</td>
                                <td class="align-right">39,20</td>
                            </tr>
                            <tr>
                                <td class="align-right">30,0</td>
                                <td class="align-right">39,20</td>
                            </tr>
                            <tr>
                                <td class="align-right">35,0</td>
                                <td class="align-right">50,20</td>
                            </tr>
                            <tr>
                                <td class="align-right">40,0</td>
                                <td class="align-right">50,20</td>
                            </tr>
                            <tr>
                                <td class="align-right">45,0</td>
                                <td class="align-right">56,31</td>
                            </tr>
                            <tr>
                                <td class="align-right">50,0</td>
                                <td class="align-right">56,31</td>
                            </tr>
                            <tr>
                                <td class="align-right">60,0</td>
                                <td class="align-right">64,59</td>
                            </tr>
                            <tr>
                                <td class="align-right">70,0</td>
                                <td class="align-right">71,90</td>
                            </tr>
                            <tr>
                                <td class="align-right">80,0</td>
                                <td class="align-right">79,09</td>
                            </tr>
                            <tr>
                                <td class="align-right">90,0</td>
                                <td class="align-right">86,44</td>
                            </tr>
                            <tr>
                                <td class="align-right">100,0</td>
                                <td class="align-right">93,65</td>
                            </tr>
                        </tbody>
                    </table>

                    <table>
                        <caption>Preis pro Kg (mit dem Gesamtgewicht multiplizieren)</caption>

                        <thead>
                            <tr>
                                <th class="align-right">Gewicht (Kg)</th>
                                <th class="align-right">Kosten (€)</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="align-right">101+</td>
                                <td class="align-right">0,97</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
