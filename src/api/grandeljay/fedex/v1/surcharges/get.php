<?php

/**
 * Fedex
 *
 * @author  Jay Trees <fedex@grandels.email>
 * @link    https://github.com/grandeljay/modified-fedex
 * @package GrandeljayFedex
 */

namespace Grandeljay\Fedex;

chdir('../../../../..');

require 'includes/application_top.php';

if (rth_is_module_disabled(Constants::MODULE_SHIPPING_NAME)) {
    http_response_code(403);

    return;
}

$jsonEncoded = file_get_contents('php://input');

if (false === $jsonEncoded) {
    return;
}

$jsonDecoded = json_decode($jsonEncoded, true, 512, JSON_THROW_ON_ERROR);
$entries     = json_decode($jsonDecoded['json'], true, 512, JSON_THROW_ON_ERROR);

usort(
    $entries,
    function ($entry_a, $entry_b) {
        return $entry_a['name'] <=> $entry_b['name'];
    }
);

ob_start();
?>
<table data-function="inputSurchargeChange">
    <thead>
        <tr>
            <th>Name</th>
            <th>Kosten</th>
            <th>Art</th>
            <th>Von</th>
            <th>Bis</th>
        </tr>
        <tr>
            <td>Bezeichnung für den Aufschlag.</td>
            <td>Wie hoch ist der Aufschlag?</td>
            <td>Um was für einen Aufschlag handelt es sich?</td>
            <td>Optional. Ab wann der Zuschlag gelten soll.</td>
            <td>Optional. Bis wann der Zuschlag gelten soll.</td>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($entries as $key => $data) { ?>
            <tr>
                <td><input data-name="name" value="<?= $data['name'] ?>" type="text" ></td>
                <td><input data-name="costs" value="<?= $data['costs'] ?>" type="number" step="any"></td>
                <td>
                    <?php
                    $selectionFixed   = 'fixed' === $data['type'] ? 'selected' : '';
                    $selectionPercent = 'percent' === $data['type'] ? 'selected' : '';
                    ?>
                    <select data-name="type">
                        <option <?= $selectionFixed ?> value="fixed">Fest</option>
                        <option <?= $selectionPercent ?> value="percent">Prozentual</option>
                    </select>
                </td>
                <td><input data-name="date-from" type="date" value="<?= $data['date-from'] ?? '' ?>"></td>
                <td><input data-name="date-to" type="date" value="<?= $data['date-to'] ?? '' ?>"></td>
                <td>
                    <button type="button" value="remove">
                        <img src="images/icons/cross.gif">
                    </button>
                </td>
            </tr>
        <?php } ?>
    </tbody>

    <tfoot>
        <tr>
            <td><input type="button" class="button" value="Hinzufügen" data-url="<?= Constants::API_ENDPOINT_SURCHARGES_ADD ?>"></td>
        </tr>
    </tfoot>
</table>
<?php
$response = ob_get_clean();

header('Content-Type: text/html');
echo $response;
