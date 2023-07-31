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
    'LONG_DESCRIPTION'  => 'Fügt die FedEx Versandmethode im Checkout ein.',
    'STATUS_TITLE'      => 'Status',
    'STATUS_DESC'       => 'Wählen Sie Ja um das Modul zu aktivieren und Nein um es zu deaktivieren.',

    /** Configuration */
    'ALLOWED_TITLE'     => '',
    'ALLOWED_DESC'      => '',

    'SHIPPING_TITLE'    => 'Versand',
    'SHIPPING_DESC'     => 'Gewicht, Preise und Einstellungen zu den verschiedenen Versandarten von FedEx.',
    'SURCHARGES_TITLE'  => 'Aufschläge',
    'SURCHARGES_DESC'   => 'Einstellungen bezüglich der Aufschläge',
);

foreach ($translations as $key => $value) {
    $constant = Constants::MODULE_SHIPPING_NAME . '_' . $key;

    define($constant, $value);
}
