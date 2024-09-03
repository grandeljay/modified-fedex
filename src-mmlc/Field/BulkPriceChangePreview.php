<?php

namespace Grandeljay\Fedex\Field;

use Grandeljay\Fedex\Constants;

class BulkPriceChangePreview
{
    public const CONFIGURATION_KEY_FACTOR = Constants::MODULE_SHIPPING_NAME . '_FACTOR';

    public static function getBulkPriceChangePreviewGroup(): string
    {
        ob_start();
        ?>
        <details>
            <summary>Bulk Preisänderung</summary>

            <div>
                <?= self::getBulkPriceChangePreview() ?>
            </div>
        </details>
        <?php
        return ob_get_clean();
    }

    private static function getBulkPriceChangePreview(): string
    {
        $page               = \xtc_href_link_admin(\DIR_ADMIN . \FILENAME_MODULES);
        $factor             = $_GET['factor'] ?? 1;
        $reset_parameters   = [
            'set'    => $_GET['set'],
            'module' => $_GET['module'],
            'action' => $_GET['action'],
        ];
        $reset_href         = $page . '?' . \http_build_query($reset_parameters);
        $preview_parameters = \http_build_query(
            \array_merge(
                $reset_parameters,
                ['factor' => $factor]
            )
        );
        $preview_href       = $page . '?' . $preview_parameters;

        $text_preview_title = \constant(Constants::MODULE_SHIPPING_NAME . '_BULK_PRICE_CHANGE_PREVIEW_FACTOR_PREVIEW_TITLE');
        $text_preview_desc  = \constant(Constants::MODULE_SHIPPING_NAME . '_BULK_PRICE_CHANGE_PREVIEW_FACTOR_PREVIEW_DESC');
        $text_reset_title   = \constant(Constants::MODULE_SHIPPING_NAME . '_BULK_PRICE_CHANGE_PREVIEW_FACTOR_RESET_TITLE');

        \ob_start();
        ?>
        <input type="number" name="factor" value="<?= $factor ?>" step="any">

        <a class="button" href="<?= $preview_href ?>" id="factor-preview"><?= $text_preview_title ?></a>
        <a class="button" href="<?= $reset_href ?>"><?= $text_reset_title ?></a>

        <?php if (isset($_GET['factor'])) { ?>
            <p><?= $text_preview_desc ?></p>
        <?php } ?>

        <?php
        $html = \ob_get_clean();

        return $html;
    }
}
