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
    'LONG_DESCRIPTION'                               => 'Adds the FedEx shipping method in the checkout.',
    'STATUS_TITLE'                                   => 'Status',
    'STATUS_DESC'                                    => 'Select Yes to activate the module and No to deactivate it.',

    /** Configuration */
    'ALLOWED_TITLE'                                  => '',
    'ALLOWED_DESC'                                   => '',

    'SORT_ORDER_TITLE'                               => 'Sort order',
    'SORT_ORDER_DESC'                                => 'Determines the sorting in the Admin and Checkout. Lowest numbers are displayed first.',

    'WEIGHT_TITLE'                                   => 'Weight',
    'WEIGHT_DESC'                                    => 'Determine ideal and maximum weight.',
    'SHIPPING_TITLE'                                 => 'Shipping',
    'SHIPPING_DESC'                                  => 'Weight, prices and settings for the various DHL Express shipping methods.',
    'SURCHARGES_TITLE'                               => 'Impacts',
    'SURCHARGES_DESC'                                => 'Settings regarding the surcharges',
    'BULK_PRICE_CHANGE_PREVIEW_TITLE'                => 'Bulk price change',
    'BULK_PRICE_CHANGE_PREVIEW_DESC'                 => 'Multiplies all shipping prices in the module by a factor. The changes are only a preview. The values are not finalised until they are saved. Before that, the factor can be changed any number of times without the prices actually changing.',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_PREVIEW_TITLE' => 'Preview',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_PREVIEW_DESC'  => 'Factor preview is active! Please check all prices and click on "Update" to apply the settings permanently. Otherwise, click on "Cancel".',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_RESET_TITLE'   => 'Reset',
];

foreach ($translations as $key => $value) {
    $constant = Constants::MODULE_SHIPPING_NAME . '_' . $key;

    define($constant, $value);
}
