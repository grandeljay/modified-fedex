<?php

namespace Grandeljay\Fedex;

class Constants
{
    public const MODULE_SHIPPING_NAME = 'MODULE_SHIPPING_GRANDELJAYFEDEX';

    public const API_ENDPOINT = HTTPS_SERVER . '/api/grandeljay/fedex/v1';

    public const API_ENDPOINT_WEIGHT     = self::API_ENDPOINT . '/weight';
    public const API_ENDPOINT_WEIGHT_ADD = self::API_ENDPOINT_WEIGHT . '/add.php';
    public const API_ENDPOINT_WEIGHT_GET = self::API_ENDPOINT_WEIGHT . '/get.php';

    public const API_ENDPOINT_SURCHARGES     = self::API_ENDPOINT . '/surcharges';
    public const API_ENDPOINT_SURCHARGES_ADD = self::API_ENDPOINT_SURCHARGES . '/add.php';
    public const API_ENDPOINT_SURCHARGES_GET = self::API_ENDPOINT_SURCHARGES . '/get.php';

    public const API_ENDPOINT_PICK_PACK     = self::API_ENDPOINT . '/pick_pack';
    public const API_ENDPOINT_PICK_PACK_ADD = self::API_ENDPOINT_PICK_PACK . '/add.php';
    public const API_ENDPOINT_PICK_PACK_GET = self::API_ENDPOINT_PICK_PACK . '/get.php';

    public const API_ENDPOINT_WEIGHT_NATIONAL     = self::API_ENDPOINT . '/weight_national';
    public const API_ENDPOINT_WEIGHT_NATIONAL_ADD = self::API_ENDPOINT_WEIGHT_NATIONAL . '/add.php';
    public const API_ENDPOINT_WEIGHT_NATIONAL_GET = self::API_ENDPOINT_WEIGHT_NATIONAL . '/get.php';

    public const API_ENDPOINT_WEIGHT_NATIONAL_FREIGHT     = self::API_ENDPOINT . '/weight_national_freight';
    public const API_ENDPOINT_WEIGHT_NATIONAL_FREIGHT_ADD = self::API_ENDPOINT_WEIGHT_NATIONAL_FREIGHT . '/add.php';
    public const API_ENDPOINT_WEIGHT_NATIONAL_FREIGHT_GET = self::API_ENDPOINT_WEIGHT_NATIONAL_FREIGHT . '/get.php';
}
