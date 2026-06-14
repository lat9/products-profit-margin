<?php
// -----
// Admin-level installation script for the "encapsulated" Products Profit Margin plugin for Zen Cart, by lat9.
// Copyright (C) 2026, Vinos de Frutas Tropicales.
//
// Last updated: v2.0.0
//
use Zencart\PluginSupport\ScriptedInstaller as ScriptedInstallBase;

class ScriptedInstaller extends ScriptedInstallBase
{
    protected function executeInstall()
    {
        global $sniffer;

        // -----
        // Add the fields to the `products` table, if not already present.
        //
        if ($sniffer->field_exists(TABLE_PRODUCTS, 'products_cost') === false) {
            $sql =
                "ALTER TABLE " . TABLE_PRODUCTS . "
                   ADD products_cost decimal(15,4) NOT NULL DEFAULT 0.0000 AFTER products_price";
            $this->executeInstallerSql($sql);
        }
        if ($sniffer->field_exists(TABLE_PRODUCTS, 'products_markup') === false) {
            $sql =
                "ALTER TABLE " . TABLE_PRODUCTS . "
                   ADD products_markup decimal(15,4) NOT NULL DEFAULT 0.0000 AFTER products_cost";
            $this->executeInstallerSql($sql);
        }
        if ($sniffer->field_exists(TABLE_PRODUCTS, 'products_margin_gross_dollar') === false) {
            $sql =
                "ALTER TABLE " . TABLE_PRODUCTS . "
                   ADD products_margin_gross_dollar decimal(15,4) NOT NULL DEFAULT 0.0000 AFTER products_markup";
            $this->executeInstallerSql($sql);
        }
        if ($sniffer->field_exists(TABLE_PRODUCTS, 'products_margin_gross_percent') === false) {
            $sql =
                "ALTER TABLE " . TABLE_PRODUCTS . "
                   ADD products_margin_gross_percent decimal(15,4) NOT NULL DEFAULT 0.0000 AFTER products_margin_gross_dollar";
            $this->executeInstallerSql($sql);
        }

        zen_deregister_admin_pages(['reportsProfitMargin']);
        zen_register_admin_page('reportsProfitMargin', 'BOX_REPORTS_PRODUCTS_PROFIT', 'FILENAME_STATS_PRODUCTS_PROFIT', '', 'reports', 'Y');

        parent::executeInstall();

        return true;
    }

    // -----
    // Not used, initially, but included for the possibility of future upgrades!
    //
    // Note: This (https://github.com/zencart/zencart/pull/6498) Zen Cart PR must
    // be present in the base code or a PHP Fatal error is generated due to the
    // function signature difference.
    //
    protected function executeUpgrade($oldVersion)
    {
        parent::executeUpgrade($oldVersion);
    }

    protected function executeUninstall()
    {
        zen_deregister_admin_pages(['reportsProfitMargin']);
        parent::executeUninstall();
    }
}
