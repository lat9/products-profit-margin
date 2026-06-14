<?php
// -----
// Part of the Products Profit Margin plugin by Cindy Merkin a.k.a. lat9
// Copyright (c) 2026 Vinos de Frutas Tropicales
//
// Last updated: v3.0.0
//
use Zencart\Traits\InteractsWithPlugins;

if (!defined('IS_ADMIN_FLAG') || IS_ADMIN_FLAG !== true) {
    die('Illegal Access');
}

class zcObserverProfitMargin extends base
{
    use Zencart\Traits\InteractsWithPlugins;

    protected $pInfo;

    // -----
    // On construction, this auto-loaded observer attaches to various
    // product-related notifications so that it can manage the `products_cost`
    // element of the `products` table.
    //
    public function __construct()
    {
        // -----
        // Only watching for notifications from a product's edit/update.
        //
        global $current_page;
        if ($current_page === FILENAME_PRODUCT . '.php') {
            $this->attach($this, [
                'NOTIFY_ADMIN_PRODUCT_COLLECT_INFO_EXTRA_INPUTS',   //-From collect_info.php
                'NOTIFY_MODULES_UPDATE_PRODUCT_END',        //- From update_product.php
                'NOTIFY_MODULES_COPY_TO_CONFIRM_DUPLICATE', //- From copy_product_confirm.php
                'NOTIFY_ADMIN_FOOTER_END',                  //- From footer.php
            ]);
        }
        if ($current_page === FILENAME_CATEGORY_PRODUCT_LISTING . '.php') {
            $this->attach($this, [
                'NOTIFY_ADMIN_PROD_LISTING_HEADERS_AFTER_QTY',
                'NOTIFY_ADMIN_PROD_LISTING_DATA_AFTER_QTY',
            ]);
        }
    }

    protected function updateNotifyAdminProductCollectInfoExtraInputs(&$class, string $e, $pInfo): void
    {
        $this->pInfo = $pInfo;
    }

    protected function updateNotifyAdminFooterEnd(&$class, string $e): void
    {
        if (($_GET['action'] ?? '') !== 'new_product') {
            return;
        }

        // -----
        // Use the base trait to determine this plugin's directory location.
        //
        $this->detectZcPluginDetails(__DIR__);
        $pmp_plugin_dir = $this->pluginManagerInstalledVersionDirectory;

        $pInfo = $this->pInfo;
        ob_start();
        require $pmp_plugin_dir . 'admin/' . DIR_WS_MODULES . 'products_profit_margin.php';
        $pmp_well = ob_get_clean();
        if ($pmp_well === false) {
            return;
        }
?>
<script id="products-profit">
$(function() {
    $('div.product-tax-prices').after(<?= json_encode($pmp_well) ?>);
});
</script>
<?php
    }

    protected function updateNotifyModulesUpdateProductEnd(&$class, string $e, array $action_pid): void
    {
        global $db;
        $db->Execute(
            "UPDATE " . TABLE_PRODUCTS . "
                SET products_cost = " . (float)($_POST['products_cost'] ?? 0) . ",
                    products_markup = " . (float)($_POST['products_markup'] ?? 0) . ",
                    products_margin_gross_dollar = " . (float)($_POST['products_margin_gross_dollar'] ?? 0) . ",
                    products_margin_gross_percent = " . (float)($_POST['products_margin_gross_percent'] ?? 0) . "
              WHERE products_id = " . (int)$action_pid['products_id'] . "
              LIMIT 1"
        );
    }

    protected function updateNotifyModulesCopyToConfirmDuplicate(&$class, string $e, array $from_to): void
    {
        global $db;
        $from = $db->Execute(
            "SELECT products_cost, products_markup, products_margin_gross_dollar, products_margin_gross_percent
               FROM " . TABLE_PRODUCTS . "
              WHERE products_id = " . (int)$from_to['products_id'] . "
              LIMIT 1"
        );
        if (!$from->EOF) {
            $db->Execute(
                "UPDATE " . TABLE_PRODUCTS . "
                    SET products_cost = " . $from->fields['products_cost'] . "
                        products_markup = " . $from->fields['products_markup'] . ",
                        products_margin_gross_dollar = " . $from->fields['products_margin_gross_dollar'] . ",
                        products_margin_gross_percent = " . $from->fields['products_margin_gross_percent'] . "
                  WHERE products_id = " . (int)$from_to['dup_products_id'] . "
                  LIMIT 1"
            );
        }
    }

    protected function updateNotifyAdminProdListingHeadersAfterQty(&$class, string $e, $unused, array|false &$extra_headings): void
    {
        if ($extra_headings === false) {
            $extra_headings = [];
        }
        $extra_headings[] = [
            'align' => 'right',
            'text' => TABLE_HEADING_COST,
        ];
    }

    protected function updateNotifyAdminProdListingDataAfterQty(&$class, string $e, array $product, array|false &$extra_data): void
    {
        global $db, $currencies;

        $cost_info = $db->Execute(
            "SELECT products_cost, products_tax_class_id
               FROM " . TABLE_PRODUCTS . "
              WHERE products_id = " . (int)$product['products_id'] . "
              LIMIT 1"
        );

        if ($extra_data === false) {
            $extra_data = [];
        }
        $extra_data[] = [
            'align' => 'right',
            'text' => $currencies->display_price($cost_info->fields['products_cost'], zen_get_tax_rate((int)$cost_info->fields['products_tax_class_id'])),
        ];
    }
}
