<?php
/**
 *
 * This file is to help people explore PO's.
 *
 * Created by Rodolphe Courtier
 */

require_once 'config.php';

$query_extra = "";
if(isset($_GET['id'])) {
    $query_extra = "WHERE ID=" . $_GET['id'];
}
$query = "SELECT * FROM PurchaseOrderReceipts "
    . $query_extra
    . " ORDER BY ID DESC LIMIT 100";
$result = mysql_query($query) or die($query . "<br />" . mysql_error());
?>

<?= head("Purchase Order Receipt",basename(__FILE__)) ?>
        <table>
            <thead>
                <?= create_table_header($result) ?>
            </thead>
            <tbody>
                <?= create_table_body($result,basename(__FILE__)) ?>
            </tbody>
        </table>
<?
if(isset($_GET['id'])) {
    $query = "SELECT * FROM PurchaseOrderReceiptItems WHERE ReceiptID=".$_GET['id'];
    $result = mysql_query($query) or die($query . "<br />" .mysql_error());
?>
        <h2>Purchase Order Receipt Items</h2>
        <table>
            <thead>
                <?= create_table_header($result) ?>
            </thead>
            <tbody>
                <?= create_table_body($result,"poreceiptitem.php") ?>
            </tbody>
        </table>
<? } ?>
<?= footer() ?>
