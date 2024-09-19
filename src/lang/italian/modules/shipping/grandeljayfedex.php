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
    'LONG_DESCRIPTION'                               => 'Aggiunge il metodo di spedizione FedEx nel checkout.',
    'STATUS_TITLE'                                   => 'Stato',
    'STATUS_DESC'                                    => 'Selezioni Sì per attivare il modulo e No per disattivarlo.',

    /** Configuration */
    'ALLOWED_TITLE'                                  => '',
    'ALLOWED_DESC'                                   => '',

    'SORT_ORDER_TITLE'                               => 'Ordinamento',
    'SORT_ORDER_DESC'                                => 'Determina l\'ordinamento nell\'Admin e nel Checkout. I numeri più bassi vengono visualizzati per primi.',

    'WEIGHT_TITLE'                                   => 'Peso',
    'WEIGHT_DESC'                                    => 'Determinare il peso ideale e massimo.',
    'SHIPPING_TITLE'                                 => 'Spedizione',
    'SHIPPING_DESC'                                  => 'Peso, prezzi e impostazioni dei vari metodi di spedizione DHL Express.',
    'SURCHARGES_TITLE'                               => 'Impatti',
    'SURCHARGES_DESC'                                => 'Impostazioni relative ai supplementi',
    'BULK_PRICE_CHANGE_PREVIEW_TITLE'                => 'Variazione del prezzo alla rinfusa',
    'BULK_PRICE_CHANGE_PREVIEW_DESC'                 => 'Moltiplica tutti i prezzi di spedizione nel modulo per un fattore. Le modifiche sono solo un\'anteprima. I valori non sono definitivi finché non vengono salvati. Prima di allora, il fattore può essere modificato un numero qualsiasi di volte senza che i prezzi cambino effettivamente.',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_PREVIEW_TITLE' => 'Anteprima',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_PREVIEW_DESC'  => 'L\'anteprima del fattore è attiva! Controllare tutti i prezzi e fare clic su "Aggiorna" per applicare le impostazioni in modo permanente. Altrimenti, fare clic su "Annulla".',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_RESET_TITLE'   => 'Reset',

    /** Method */
    'METHOD_FIRST_TITLE'                             => 'Prima 10:30',
    'METHOD_FIRST_DESC'                              => 'Consegna al mattino, probabilmente alle 10:30.',
    'METHOD_PRIORITY_EXPRESS_TITLE'                  => 'Priority Express 12:00',
    'METHOD_PRIORITY_EXPRESS_DESC'                   => 'Consegna a mezzogiorno, probabilmente alle 12:00.',
    'METHOD_PRIORITY_TITLE'                          => 'Priority Express 18:00',
    'METHOD_PRIORITY_DESC'                           => 'Consegna entro la fine della giornata lavorativa, probabilmente alle 18:00.',
];

foreach ($translations as $key => $value) {
    $constant = Constants::MODULE_SHIPPING_NAME . '_' . $key;

    define($constant, $value);
}
