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

usort(
    $entries,
    function ($entry_a, $entry_b) {
        return $entry_a['weight'] <=> $entry_b['weight'];
    }
);

ob_start();
?>
<table data-function="inputPickPackChange">
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
        <?php foreach ($entries as $entry) { ?>
            <tr>
                <td><input data-name="weight-max" value="<?= $entry['weight-max'] ?>" type="number" step="any"></td>
                <td><input data-name="weight-costs" value="<?= $entry['weight-costs'] ?>" type="number" step="any"></td>
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
            <td><input type="button" class="button" value="Hinzufügen" data-url="<?= Constants::API_ENDPOINT_PICK_PACK_ADD ?>"></td>
        </tr>
    </tfoot>
</table>
<?php
$response = ob_get_clean();

header('Content-Type: text/html');
echo $response;
