<?php
/**
 */
require 'includes/application_top.php';
?>
<!doctype html>
<html <?= HTML_PARAMS ?>>
  <head>
      <?php require DIR_WS_INCLUDES . 'admin_html_head.php'; ?>
    <link rel="stylesheet" media="print" href="includes/css/stylesheet_print.css">
  </head>
  <body>
    <!-- header //-->
    <div class="header-area">
        <?php require DIR_WS_INCLUDES . 'header.php'; ?>
    </div>
    <!-- header_eof //-->
<?php
$products_query_raw =
    "SELECT SUM(op.products_quantity) AS products_ordered, op.products_name,
            p.products_price, p.products_cost, op.products_id,
            SUM(p.products_cost * op.products_quantity) AS total_cost,
            (SUM(op.products_price) - SUM(p.products_cost)) AS total_profit
       FROM " . TABLE_ORDERS_PRODUCTS . " op
            LEFT JOIN " . TABLE_PRODUCTS . " p ON p.products_id = op.products_id
      GROUP BY op.products_id, op.products_name, p.products_price, p.products_cost
      ORDER BY products_ordered DESC, products_name";
$page_num = (int)($_GET['page'] ?? 1);
$products_split = new splitPageResults($page_num, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $products_query_raw, $products_query_numrows);

$products = $db->Execute($products_query_raw);
?>
    <!-- body //-->
    <div class="container-fluid">
        <h1><?= HEADING_TITLE ?></h1>
        <table class="table table-striped table-hover">
            <thead>
                <tr class="dataTableHeadingRow">
                    <th class="dataTableHeadingContent"><?= TABLE_HEADING_NUMBER ?></th>
                    <th class="dataTableHeadingContent"><?= TABLE_HEADING_PRODUCTS ?></th>
                    <th class="dataTableHeadingContent text-right"><?= TABLE_HEADING_PURCHASED ?></th>
                    <th class="dataTableHeadingContent text-right"><?= TABLE_HEADING_TOTAL_COST ?></th>
                    <th class="dataTableHeadingContent text-right"><?= TABLE_HEADING_TOTAL_PROFIT ?>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
<?php
foreach ($products as $product) {
?>
                <tr class="dataTableRow">
                    <td class="dataTableContent text-right"><?= $product['products_id'] ?></td>
                    <td class="dataTableContent">
                        <a href="<?= zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . zen_get_product_path($product['products_id']) . '&pID=' . $product['products_id']) ?>">
                            <?= zen_output_string_protected($product['products_name']) ?>
                        </a>
                    </td>
                    <td class="dataTableContent text-right"><?= $product['products_ordered'] ?></td>
                    <td class="dataTableContent text-right"><?= number_format((float)$product['total_cost'], 2) ?></td>
                    <td class="dataTableContent text-right"><?= number_format((float)$product['total_profit'], 2) ?></td>
                </tr>
<?php
}
?>
            </tbody>
        </table>
        <div class="row">
            <table class="table">
                <tr>
                    <td><?= $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $page_num, TEXT_DISPLAY_NUMBER_OF_PRODUCTS) ?></td>
                    <td class="text-right"><?= $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, MAX_DISPLAY_PAGE_LINKS, $page_num) ?></td>
                </tr>
            </table>
        </div>
        <!-- body_text_eof //-->
    </div>
    <!-- body_eof //-->

    <!-- footer //-->
    <div class="footer-area">
        <?php require DIR_WS_INCLUDES . 'footer.php'; ?>
    </div>
    <!-- footer_eof //-->
  </body>
</html>
<?php require DIR_WS_INCLUDES . 'application_bottom.php'; ?>
