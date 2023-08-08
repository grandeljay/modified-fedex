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
    'LONG_DESCRIPTION'  => 'Ajoute la méthode d\'expédition FedEx dans le checkout.',
    'STATUS_TITLE'      => 'Statut',
    'STATUS_DESC'       => 'Sélectionnez Oui pour activer le module et Non pour le désactiver.',

    /** Configuration */
    'ALLOWED_TITLE'     => '',
    'ALLOWED_DESC'      => '',

    'WEIGHT_TITLE'      => 'Poids',
    'WEIGHT_DESC'       => 'Déterminer le poids idéal et le poids maximal.',
    'SHIPPING_TITLE'    => 'Expédition',
    'SHIPPING_DESC'     => 'Poids, prix et paramètres des différents modes d\'expédition de DHL Express.',
    'SURCHARGES_TITLE'  => 'Suppléments',
    'SURCHARGES_DESC'   => 'Options relatives aux majorations',
);

foreach ($translations as $key => $value) {
    $constant = Constants::MODULE_SHIPPING_NAME . '_' . $key;

    define($constant, $value);
}
