<?php

/**
 * Fedex
 *
 * @author  Jay Trees <fedex@grandels.email>
 * @link    https://github.com/grandeljay/modified-fedex
 * @package GrandeljayFedex
 */

namespace Grandeljay\Fedex;

use grandeljayfedex;

chdir('../../../../..');

require 'includes/application_top.php';
require DIR_WS_MODULES . '/shipping/grandeljayfedex.php';

if (!grandeljayfedex::userMayAccessAPI()) {
    http_response_code(403);

    echo 'Access denied.';

    return;
}

$jsonEncoded = file_get_contents('php://input');

if (false === $jsonEncoded) {
    return;
}

$jsonDecoded = json_decode($jsonEncoded, true, 512, JSON_THROW_ON_ERROR);
$entries     = json_decode($jsonDecoded['json'], true, 512, JSON_THROW_ON_ERROR);
$factor      = $jsonDecoded['factor'] ?? 1;

usort(
    $entries,
    function ($entry_a, $entry_b) {
        return $entry_a['weight-max'] <=> $entry_b['weight-max'];
    }
);

ob_start();
?>
<table data-function="inputWeightChange">
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
        <?php foreach ($entries as $data) { ?>
            <?php
            $weight_max   = $data['weight-max'];
            $weight_costs = $data['weight-costs'] * $factor;
            ?>
            <tr>
                <td><input type="number" step="any" value="<?= $weight_max ?>" data-name="weight-max"></td>
                <td><input type="number" step="any" value="<?= $weight_costs ?>" data-name="weight-costs"></td>
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
        <td><input type="button" class="button" value="Hinzufügen" data-url="<?= Constants::API_ENDPOINT_WEIGHT_ADD ?>"></td>
        </tr>
    </tfoot>
</table>
<?php
$response = ob_get_clean();

header('Content-Type: text/html');
echo $response;
