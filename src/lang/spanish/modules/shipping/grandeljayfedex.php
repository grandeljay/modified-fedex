<?php

/**
 * German translations
 *
 * @author  Jay Trees <fedex@grandels.email>
 * @link    https://github.com/grandeljay/modified-fedex
 * @package GrandeljayFedex
 */

use Grandeljay\Fedex\Constants;

$translations = array(
    /** Module */
    'TITLE'             => 'grandeljay - FedEx',
    'TEXT_TITLE'        => 'Fedex',
    'TEXT_TITLE_WEIGHT' => 'Fedex (%s kg)',
    'LONG_DESCRIPTION'  => 'Añade el método de envío FedEx en la caja.',
    'STATUS_TITLE'      => 'Estado',
    'STATUS_DESC'       => 'Seleccione Sí para activar el módulo y No para desactivarlo.',

    /** Configuration */
    'ALLOWED_TITLE'     => '',
    'ALLOWED_DESC'      => '',

    'WEIGHT_TITLE'      => 'Peso',
    'WEIGHT_DESC'       => 'Determine el peso ideal y el peso máximo.',
    'SHIPPING_TITLE'    => 'Envío',
    'SHIPPING_DESC'     => 'Peso, precios y configuración de los distintos métodos de envío de DHL Express.',
    'SURCHARGES_TITLE'  => 'Impactos',
    'SURCHARGES_DESC'   => 'Ajustes relativos a los recargos',
);

foreach ($translations as $key => $value) {
    $constant = Constants::MODULE_SHIPPING_NAME . '_' . $key;

    define($constant, $value);
}
