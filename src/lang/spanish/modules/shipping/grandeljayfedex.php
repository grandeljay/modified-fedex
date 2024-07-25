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
    'LONG_DESCRIPTION'                               => 'Añade el método de envío FedEx en la caja.',
    'STATUS_TITLE'                                   => 'Estado',
    'STATUS_DESC'                                    => 'Seleccione Sí para activar el módulo y No para desactivarlo.',

    /** Configuration */
    'ALLOWED_TITLE'                                  => '',
    'ALLOWED_DESC'                                   => '',

    'SORT_ORDER_TITLE'                               => 'Orden de clasificación',
    'SORT_ORDER_DESC'                                => 'Determina la clasificación en Admin y Checkout. Los números más bajos se muestran primero.',

    'WEIGHT_TITLE'                                   => 'Peso',
    'WEIGHT_DESC'                                    => 'Determine el peso ideal y el peso máximo.',
    'SHIPPING_TITLE'                                 => 'Envío',
    'SHIPPING_DESC'                                  => 'Peso, precios y configuración de los distintos métodos de envío de DHL Express.',
    'SURCHARGES_TITLE'                               => 'Impactos',
    'SURCHARGES_DESC'                                => 'Ajustes relativos a los recargos',
    'BULK_PRICE_CHANGE_PREVIEW_TITLE'                => 'Cambio de precio a granel',
    'BULK_PRICE_CHANGE_PREVIEW_DESC'                 => 'Multiplica todos los precios de envío del módulo por un factor. Los cambios son sólo una vista previa. Los valores no son definitivos hasta que se guardan. Antes, el factor puede modificarse tantas veces como sea necesario sin que cambien realmente los precios.',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_PREVIEW_TITLE' => 'Vista previa',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_PREVIEW_DESC'  => 'La vista previa de los factores está activa. Compruebe todos los precios y haga clic en "Actualizar" para aplicar los ajustes de forma permanente. De lo contrario, haga clic en "Cancelar".',
    'BULK_PRICE_CHANGE_PREVIEW_FACTOR_RESET_TITLE'   => 'Restablecer',
);

foreach ($translations as $key => $value) {
    $constant = Constants::MODULE_SHIPPING_NAME . '_' . $key;

    define($constant, $value);
}
