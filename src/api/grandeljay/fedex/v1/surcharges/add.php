<?php

/**
 * Fedex
 *
 * @author  Jay Trees <fedex@grandels.email>
 * @link    https://github.com/grandeljay/modified-fedex
 * @package GrandeljayFedex
 */

namespace Grandeljay\Fedex;

ob_start();
?>
<tr>
    <td><input data-name="name" type="text" ></td>
    <td><input data-name="costs" type="number" step="any"></td>
    <td>
        <select data-name="type">
            <option value="fixed">Fest</option>
            <option value="percent">Prozentual</option>
        </select>
    </td>
    <td><input data-name="date-from" type="date"></td>
    <td><input data-name="date-to" type="date"></td>
    <td>
        <button type="button" value="remove">
            <img src="images/icons/cross.gif">
        </button>
    </td>
</tr>
<?php

$response = ob_get_clean();

header('Content-Type: text/html');
echo $response;
