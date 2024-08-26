<?php

namespace Grandeljay\Fedex\Traits;

use Grandeljay\Fedex\Field\BulkPriceChangePreview;
use Grandeljay\Fedex\Field\Shipping;
use Grandeljay\Fedex\Field\Surcharges;
use Grandeljay\Fedex\Field\Weight;

class SetFunctions
{
    public static function weight(): string
    {
        $html = Weight::getWeight();

        return $html;
    }

    public static function shipping(): string
    {
        $html  = '';
        $html .= Shipping::getNational();
        $html .= Shipping::getInternational();

        return $html;
    }

    public static function surcharges(string $value, string $option): string
    {
        $html = Surcharges::getSurchargesGroup($value, $option);

        return $html;
    }

    public static function bulkPriceChangePreview(string $value, string $option): string
    {
        $html = BulkPriceChangePreview::getBulkPriceChangePreviewGroup($value, $option);

        return $html;
    }
}
