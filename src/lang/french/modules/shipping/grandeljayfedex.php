<?php

/**
 * German translations
 *
 * @author  Jay Trees <fedex@grandels.email>
 * @link    https://github.com/grandeljay/modified-fedex
 * @package GrandeljayFedex
 */

use Grandeljay\Fedex\Constants;

$translations = [
    /** Module */
    'TITLE'                                          => 'grandeljay - FedEx',
    'TEXT_TITLE'                                     => 'Fedex',
    'TEXT_TITLE_WEIGHT'                              => 'Fedex (%s kg)',
    'LONG_DESCRIPTION'                               => 'Ajoute la méthode d\'expédition FedEx dans le checkout.',
    'STATUS_TITLE'                                   => 'Statut',
    'STATUS_DESC'                                    => 'Sélectionnez Oui pour activer le module et Non pour le désactiver.',

    /** Configuration */
    'ALLOWED_TITLE'                                  => '',
    'ALLOWED_DESC'                                   => '',

    'SORT_ORDER_TITLE'                               => 'Ordre de tri',
    'SORT_ORDER_DESC'                                => 'Détermine le tri dans Admin et Checkout. Les chiffres les plus bas sont affichés en premier.',

    'WEIGHT_TITLE'                                   => 'Poids',
    'WEIGHT_DESC'                                    => 'Déterminer le poids idéal et le poids maximal.',
    'SHIPPING_TITLE'                                 => 'Expédition',
    'SHIPPING_DESC'                                  => 'Poids, prix et paramètres des différents modes d\'expédition de DHL Express.',
    'SURCHARGES_TITLE'                               => 'Suppléments',
    'SURCHARGES_DESC'                                => 'Options relatives aux majorations',
    'BULK_PRICE_CHANGE_PREVIEW_TITLE'                => 'Changement de prix en vrac',
    'BULK_PRICE_CHANGE_PREVIEW_DESC'                 => 'Multiplie tous les prix d\'expédition du module par un facteur. Les modifications ne sont qu\'un aperçu. Ce n\'est qu\'au moment de l\'enregistrement que les valeurs sont définitivement prises en compte. Avant cela, le facteur peut être modifié autant de fois que nécessaire, sans que les prix ne changent réellement.',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_PREVIEW_TITLE' => 'Aperçu',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_PREVIEW_DESC'  => 'L\'aperçu des facteurs est actif ! Veuillez vérifier tous les prix et cliquer sur "Actualiser" afin d\'appliquer les paramètres de manière permanente. Dans le cas contraire, clique sur "Annuler".',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_RESET_TITLE'   => 'Réinitialiser',

    /** Method */
    'METHOD_FIRST_TITLE'                             => 'First 10:30',
    'METHOD_FIRST_DESC'                              => 'Livraison le matin, prévue à 10h30.',
    'METHOD_PRIORITY_EXPRESS_TITLE'                  => 'Priority Express 12:00',
    'METHOD_PRIORITY_EXPRESS_DESC'                   => 'Livraison à midi, probablement 12:00.',
    'METHOD_PRIORITY_TITLE'                          => 'Priority Express 18:00',
    'METHOD_PRIORITY_DESC'                           => 'Livraison avant la fin du jour ouvrable, probablement 18:00.',
];

foreach ($translations as $key => $value) {
    $constant = Constants::MODULE_SHIPPING_NAME . '_' . $key;

    define($constant, $value);
}
