<?php

/**
 * FedEx
 *
 * @author  Jay Trees <fedex@grandels.email>
 * @link    https://github.com/grandeljay/modified-fedex
 * @package GrandeljayFedex
 */

namespace Grandeljay\Fedex;

require 'includes/application_top.php';

/**
 * Manipulate data to save
 */
$configuration_shipping_key   = Constants::MODULE_SHIPPING_NAME . '_SHIPPING';
$configuration_shipping_value = $_POST[\grandeljayfedex::class]['shipping'];

/** Remove empty fields */
foreach ($configuration_shipping_value as $key_nationality => $nationality) {
    foreach ($nationality as $key_method => $method) {
        foreach ($method as $key_zone => $zone) {
            foreach ($zone as $key_weight => $weight) {
                foreach ($weight as $data) {
                    if (empty($data)) {
                        unset($configuration_shipping_value[$key_nationality][$key_method][$key_zone][$key_weight]);

                        break;
                    }
                }
            }
        }
    }
}

xtc_db_query(
    sprintf(
        'UPDATE `%s`
            SET `configuration_value` = "%s"
          WHERE `configuration_key`   = "%s"',
        TABLE_CONFIGURATION,
        xtc_db_input(base64_encode(json_encode($configuration_shipping_value))),
        $configuration_shipping_key
    )
);

$configuration_surcharges_key   = Constants::MODULE_SHIPPING_NAME . '_SURCHARGES';
$configuration_surcharges_value = $_POST[\grandeljayfedex::class]['surcharges'];

/** Remove empty fields */
foreach ($configuration_surcharges_value as $key_surcharge => $surcharge) {
    foreach ($surcharge as $data) {
        if (empty($data)) {
            unset($configuration_surcharges_value[$key_surcharge]);

            break;
        }
    }
}

xtc_db_query(
    sprintf(
        'UPDATE `%s`
            SET `configuration_value` = "%s"
          WHERE `configuration_key`   = "%s"',
        TABLE_CONFIGURATION,
        xtc_db_input(base64_encode(json_encode($configuration_surcharges_value))),
        $configuration_surcharges_key
    )
);

/**
 * Redirect back to module settings
 */
$parameters = http_build_query(
    array(
        'set'    => 'shipping',
        'module' => \grandeljayfedex::class,
    )
);

xtc_redirect(
    xtc_href_link(FILENAME_MODULES, $parameters)
);
