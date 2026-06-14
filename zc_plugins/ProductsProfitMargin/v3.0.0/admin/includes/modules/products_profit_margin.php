<?php
// -----
// Part of the Products Profit Margin plugin by Cindy Merkin a.k.a. lat9
// Copyright (c) 2026 Vinos de Frutas Tropicales
//
// Last updated: v3.0.0
//
?>
  <div class="well" style="color: #31708f;background-color: #f7f6ef;border-color: #bce8f1;;padding: 10px 10px 0 0;">
    <div class="col-sm-12 text"><?= TEXT_PRODUCTS_PRICE_MARGIN_CALCULATOR ?></div>
    <div class="form-group">
        <?= zen_draw_label(TEXT_PRODUCTS_PRICE_COST, 'products_cost', 'class="col-sm-3 control-label"') ?>
      <div class="col-sm-9 col-md-6">
          <?= zen_draw_input_field('products_cost', $pInfo->products_cost, 'onkeyup="updateMarkup();" class="form-control"') ?>
      </div>
    </div>
    <div class="form-group">
        <?= zen_draw_label(TEXT_PRODUCTS_PRICE_MARKUP, 'products_markup', 'class="col-sm-3 control-label"') ?>
      <div class="col-sm-9 col-md-6">
        <div class="input-group">
          <?= zen_draw_input_field('products_markup', $pInfo->products_markup, 'onkeyup="updateMarkup();" class="form-control"') ?>
          <span class="input-group-addon">%</span>
        </div>
      </div>
    </div>
    <div class="form-group">
        <?= zen_draw_label(TEXT_PRODUCTS_PRICE_MARGIN_GROSS, 'products_margin_gross_dollar', 'class="col-sm-3 control-label"') ?>
      <div class="col-sm-9 col-md-6">
          <?= zen_draw_input_field('products_margin_gross_dollar', $pInfo->products_margin_gross_dollar, 'onkeyup="updatemarginGrossDollar()" class="form-control" readonly') ?>
      </div>
    </div>
    <div class="form-group">
        <?= zen_draw_label(TEXT_PRODUCTS_PRICE_MARGIN_GROSS_PERCENT, 'products_margin_gross_percent', 'class="col-sm-3 control-label"') ?>
      <div class="col-sm-9 col-md-6">
        <div class="input-group">
          <?= zen_draw_input_field('products_margin_gross_percent', $pInfo->products_margin_gross_percent, 'onkeyup="updatemarginGrossPercent()" class="form-control" readonly') ?>
          <span class="input-group-addon">%</span>
        </div>
      </div>
    </div>
  </div>