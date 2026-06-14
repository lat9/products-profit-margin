<?php
// -----
// Part of the Products Profit Margin plugin by lat9 (and others).
//
// Last updated: v3.0.0
//
?>
<script>
var costValue;
var markupValue;
var markup;
var netValue;
var marginGrossDollar;
var marginGrossDollarValue;
var marginGrossPercent;

function updateCost() {
    costValue = $('input[name="products_cost"]').val();

    $('input[name="products_cost"]').val(doRound(costValue, 4));
}

function updateMarkup() {
    costValue = Number($('input[name="products_cost"]').val());
    markupValue = Number($('input[name="products_markup"]').val());
    markup = costValue + ((costValue * markupValue) / 100);

    $('input[name="products_price"]').val(doRound(markup, 4));
    updatemarginGrossDollar();
    updatemarginGrossPercent();
    updateTaxIncl();
}

function updatemarginGrossDollar() {
    costValue = Number($('input[name="products_cost"]').val());
    netValue = Number($('input[name="products_price"]').val());
    marginGrossDollar = netValue - costValue;

    $('input[name="products_margin_gross_dollar"]').val(doRound(marginGrossDollar, 4));
}

function updatemarginGrossPercent() {
    marginGrossDollarValue = Number($('input[name="products_margin_gross_dollar"]').val());
    netValue = Number($('input[name="products_price"]').val());
    marginGrossPercent = (marginGrossDollarValue / netValue) * 100;

    $('input[name="products_margin_gross_percent"]').val(doRound(marginGrossPercent, 4));
}
</script>