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
                if (empty($tariff) || empty($tariff['weight-max']) || empty($tariff['weight-costs'])) {
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
                                <td class="align-right">8,66</td>
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
                                <td class="align-right">9,04</td>
                            </tr>
                            <tr>
                                <td class="align-right">1,0</td>
                                <td class="align-right">9,32</td>
                            </tr>
                            <tr>
                                <td class="align-right">1,5</td>
                                <td class="align-right">9,32</td>
                            </tr>
                            <tr>
                                <td class="align-right">2,0</td>
                                <td class="align-right">9,32</td>
                            </tr>
                            <tr>
                                <td class="align-right">2,5</td>
                                <td class="align-right">9,32</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="align-center">Preise für den Versand in anderen Verpackungen</td>
                            </tr>
                            <tr>
                                <td class="align-right">5,0</td>
                                <td class="align-right">9,55</td>
                            </tr>
                            <tr>
                                <td class="align-right">10,0</td>
                                <td class="align-right">9,55</td>
                            </tr>
                            <tr>
                                <td class="align-right">15,0</td>
                                <td class="align-right">15,20</td>
                            </tr>
                            <tr>
                                <td class="align-right">20,0</td>
                                <td class="align-right">15,20</td>
                            </tr>
                            <tr>
                                <td class="align-right">25,0</td>
                                <td class="align-right">21,95</td>
                            </tr>
                            <tr>
                                <td class="align-right">30,0</td>
                                <td class="align-right">21,95</td>
                            </tr>
                            <tr>
                                <td class="align-right">35,0</td>
                                <td class="align-right">31,45</td>
                            </tr>
                            <tr>
                                <td class="align-right">40,0</td>
                                <td class="align-right">31,45</td>
                            </tr>
                            <tr>
                                <td class="align-right">45,0</td>
                                <td class="align-right">36,45</td>
                            </tr>
                            <tr>
                                <td class="align-right">50,0</td>
                                <td class="align-right">36,45</td>
                            </tr>
                            <tr>
                                <td class="align-right">60,0</td>
                                <td class="align-right">43,24</td>
                            </tr>
                            <tr>
                                <td class="align-right">70,0</td>
                                <td class="align-right">49,34</td>
                            </tr>
                            <tr>
                                <td class="align-right">80,0</td>
                                <td class="align-right">55,40</td>
                            </tr>
                            <tr>
                                <td class="align-right">90,0</td>
                                <td class="align-right">61,54</td>
                            </tr>
                            <tr>
                                <td class="align-right">100,0</td>
                                <td class="align-right">67,59</td>
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
                                <td class="align-right">0,72</td>
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
                                <td class="align-right">5,35</td>
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
                                <td class="align-right">5,58</td>
                            </tr>
                            <tr>
                                <td class="align-right">1,0</td>
                                <td class="align-right">5,77</td>
                            </tr>
                            <tr>
                                <td class="align-right">1,5</td>
                                <td class="align-right">5,77</td>
                            </tr>
                            <tr>
                                <td class="align-right">2,0</td>
                                <td class="align-right">5,77</td>
                            </tr>
                            <tr>
                                <td class="align-right">2,5</td>
                                <td class="align-right">5,77</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="align-center">Preise für den Versand in anderen Verpackungen</td>
                            </tr>
                            <tr>
                                <td class="align-right">5,0</td>
                                <td class="align-right">6,40</td>
                            </tr>
                            <tr>
                                <td class="align-right">10,0</td>
                                <td class="align-right">6,40</td>
                            </tr>
                            <tr>
                                <td class="align-right">15,0</td>
                                <td class="align-right">7,75</td>
                            </tr>
                            <tr>
                                <td class="align-right">20,0</td>
                                <td class="align-right">7,75</td>
                            </tr>
                            <tr>
                                <td class="align-right">25,0</td>
                                <td class="align-right">10,15</td>
                            </tr>
                            <tr>
                                <td class="align-right">30,0</td>
                                <td class="align-right">10,15</td>
                            </tr>
                            <tr>
                                <td class="align-right">35,0</td>
                                <td class="align-right">27,05</td>
                            </tr>
                            <tr>
                                <td class="align-right">40,0</td>
                                <td class="align-right">27,05</td>
                            </tr>
                            <tr>
                                <td class="align-right">45,0</td>
                                <td class="align-right">31,29</td>
                            </tr>
                            <tr>
                                <td class="align-right">50,0</td>
                                <td class="align-right">31,29</td>
                            </tr>
                            <tr>
                                <td class="align-right">60,0</td>
                                <td class="align-right">38,04</td>
                            </tr>
                            <tr>
                                <td class="align-right">70,0</td>
                                <td class="align-right">44,39</td>
                            </tr>
                            <tr>
                                <td class="align-right">80,0</td>
                                <td class="align-right">49,93</td>
                            </tr>
                            <tr>
                                <td class="align-right">90,0</td>
                                <td class="align-right">55,33</td>
                            </tr>
                            <tr>
                                <td class="align-right">100,0</td>
                                <td class="align-right">60,84</td>
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
                                <td class="align-right">0,65</td>
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
                <div class="tables">
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
                                <td class="align-right">68 - 100</td>
                                <td class="align-right">1,48</td>
                            </tr>
                            <tr>
                                <td class="align-right">101 - 200</td>
                                <td class="align-right">1,48</td>
                            </tr>
                            <tr>
                                <td class="align-right">201+</td>
                                <td class="align-right">1,48</td>
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
                <div class="tables">
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
                                <td class="align-right">68 - 100</td>
                                <td class="align-right">0,99</td>
                            </tr>
                            <tr>
                                <td class="align-right">101 - 200</td>
                                <td class="align-right">0,99</td>
                            </tr>
                            <tr>
                                <td class="align-right">201+</td>
                                <td class="align-right">0,99</td>
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
