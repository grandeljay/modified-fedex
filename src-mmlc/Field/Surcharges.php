<?php

namespace Grandeljay\Fedex\Field;

class Surcharges
{
    public static function getSurcharges(array $surcharges): string
    {
        ob_start();
        ?>
        <details>
            <summary>Aufschläge</summary>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Kosten</th>
                            <th>Art</th>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Bezeichnung für den Aufschlag.</td>
                            <td>Wie hoch ist der Aufschlag?</td>
                            <td>Um was für einen Aufschlag handelt es sich?</td>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($surcharges as $index => $surcharge) { ?>
                            <?php
                            $name_name  = sprintf(
                                '%s[surcharges][%s][name]',
                                \grandeljayfedex::class,
                                $index
                            );
                            $name_costs = sprintf(
                                '%s[surcharges][%s][costs]',
                                \grandeljayfedex::class,
                                $index
                            );
                            $name_type  = sprintf(
                                '%s[surcharges][%s][type]',
                                \grandeljayfedex::class,
                                $index
                            );
                            ?>
                            <tr>
                                <td>
                                    <input type="text" name="<?= $name_name ?>" value="<?= $surcharge['name'] ?>">
                                </td>
                                <td>
                                    <input type="number" step="any" name="<?= $name_costs ?>" value="<?= $surcharge['costs'] ?>">
                                </td>
                                <td>
                                    <select name="<?= $name_type ?>">
                                        <option <?= 'fixed' === $surcharge['type'] ? 'selected' : '' ?> value="fixed">Fest</option>
                                        <option <?= 'percent' === $surcharge['type'] ? 'selected' : '' ?> value="percent">Prozentual</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="remove">
                                        <img src="images/icons/cross.gif">
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php
                        $name_name  = sprintf(
                            '%s[surcharges][%s][name]',
                            \grandeljayfedex::class,
                            $index + 1
                        );
                        $name_costs = sprintf(
                            '%s[surcharges][%s][costs]',
                            \grandeljayfedex::class,
                            $index + 1
                        );
                        $name_type  = sprintf(
                            '%s[surcharges][%s][type]',
                            \grandeljayfedex::class,
                            $index + 1
                        );
                        ?>
                        <tr>
                            <td>
                                <input type="text" name="<?= $name_name ?>">
                            </td>
                            <td>
                                <input type="number" step="any" name="<?= $name_costs ?>">
                            </td>
                            <td>
                                <select name="<?= $name_type ?>">
                                    <option value="fixed">Fest</option>
                                    <option value="percent">Prozentual</option>
                                </select>
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
        <?php
        return ob_get_clean();
    }
}
