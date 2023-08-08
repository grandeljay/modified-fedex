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
    'LONG_DESCRIPTION'  => 'Aggiunge il metodo di spedizione FedEx nel checkout.',
    'STATUS_TITLE'      => 'Stato',
    'STATUS_DESC'       => 'Selezioni Sì per attivare il modulo e No per disattivarlo.',

    /** Configuration */
    'ALLOWED_TITLE'     => '',
    'ALLOWED_DESC'      => '',

    'WEIGHT_TITLE'      => 'Peso',
    'WEIGHT_DESC'       => 'Determinare il peso ideale e massimo.',
    'SHIPPING_TITLE'    => 'Spedizione',
    'SHIPPING_DESC'     => 'Peso, prezzi e impostazioni dei vari metodi di spedizione DHL Express.',
    'SURCHARGES_TITLE'  => 'Impatti',
    'SURCHARGES_DESC'   => 'Impostazioni relative ai supplementi',
);

foreach ($translations as $key => $value) {
    $constant = Constants::MODULE_SHIPPING_NAME . '_' . $key;

    define($constant, $value);
}
