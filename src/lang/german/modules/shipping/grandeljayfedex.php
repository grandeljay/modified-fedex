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
    'TITLE'                                          => 'grandeljay - FedEx',
    'TEXT_TITLE'                                     => 'Fedex',
    'TEXT_TITLE_WEIGHT'                              => 'Fedex (%s kg)',
    'LONG_DESCRIPTION'                               => 'Fügt die FedEx Versandmethode im Checkout ein.',
    'STATUS_TITLE'                                   => 'Status',
    'STATUS_DESC'                                    => 'Wählen Sie Ja um das Modul zu aktivieren und Nein um es zu deaktivieren.',

    /** Configuration */
    'ALLOWED_TITLE'                                  => '',
    'ALLOWED_DESC'                                   => '',

    'SORT_ORDER_TITLE'                               => 'Sortierreihenfolge',
    'SORT_ORDER_DESC'                                => 'Bestimmt die Sortierung im Admin und Checkout. Niedrigste Zahlen werden zuerst angezeigt.',

    'WEIGHT_TITLE'                                   => 'Gewicht',
    'WEIGHT_DESC'                                    => 'Ideal- und Maximalgewicht bestimmen.',
    'SHIPPING_TITLE'                                 => 'Versand',
    'SHIPPING_DESC'                                  => 'Gewicht, Preise und Einstellungen zu den verschiedenen Versandarten von DHL Express.',
    'SURCHARGES_TITLE'                               => 'Aufschläge',
    'SURCHARGES_DESC'                                => 'Einstellungen bezüglich der Aufschläge',
    'BULK_PRICE_CHANGE_PREVIEW_TITLE'                => 'Bulk Preisänderung',
    'BULK_PRICE_CHANGE_PREVIEW_DESC'                 => 'Multipliziert alle Versandpreise im Modul um einen Faktor. Die Änderungen sind hierbei lediglich eine Vorschau. Erst beim Speichern werden die Werte final übernommen. Davor kann der Faktor beliebig oft geändert werden, ohne dass sich die Preise tatsächlich verändern.',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_PREVIEW_TITLE' => 'Vorschau',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_PREVIEW_DESC'  => 'Faktor-Vorschau ist aktiv! Bitte prüfe alle Preise und klicke auf "Aktualisieren", um die Einstellungen dauerhaft zu übernehmen. Andernfalls, klicke auf "Abbrechen".',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_RESET_TITLE'   => 'Zurücksetzen',
);

foreach ($translations as $key => $value) {
    $constant = Constants::MODULE_SHIPPING_NAME . '_' . $key;

    define($constant, $value);
}
