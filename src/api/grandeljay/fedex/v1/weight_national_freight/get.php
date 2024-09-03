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
        return $entry_a['weight-min'] <=> $entry_b['weight-min'];
    }
);

ob_start();
?>
<table data-function="inputShippingNationalFreightChange">
    <thead>
        <tr>
            <th>Gewicht (Minimum)</th>
            <th>Gewicht (Maximum)</th>
            <th>Kosten</th>
        </tr>
        <tr>
            <td>Minimal zulässiges Gewicht (in Kg) für diesen Preis.</td>
            <td>Maximal zulässiges Gewicht (in Kg) für diesen Preis.</td>
            <td>Kilogramm-Preis für Gewicht in EUR.</td>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($entries as $data) { ?>
            <?php
            $weight_min   = $data['weight-min'] ?? 0;
            $weight_max   = $data['weight-max'] ?? 0;
            $weight_costs = $data['weight-costs'] * $factor;
            ?>
            <tr>
                <td><input type="number" step="any" value="<?= $weight_min ?>" data-name="weight-min" min="0"></td>
                <td><input type="number" step="any" value="<?= $weight_max ?>" data-name="weight-max" min="0"></td>
                <td><input type="number" step="any" value="<?= $weight_costs ?>" data-name="weight-costs" min="0"></td>
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
            <td>
                <input type="button" class="button" value="Hinzufügen" data-url="<?= Constants::API_ENDPOINT_WEIGHT_NATIONAL_FREIGHT_ADD ?>">
            </td>
        </tr>
    </tfoot>
</table>
<?php
$response = ob_get_clean();

header('Content-Type: text/html');
echo $response;
