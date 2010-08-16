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
$query = "SELECT * FROM PurchaseOrders "
    . $query_extra
    . " ORDER BY ID DESC LIMIT 100";
$result = mysql_query($query) or die($query . "<br />" . mysql_error());
?>

<?= head("Purchase Order",basename(__FILE__)) ?>
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
    $query = "SELECT * FROM PurchaseOrderItems WHERE POID=".$_GET['id'];
    $result = mysql_query($query) or die($query . "<br />" .mysql_error());
?>
        <h2>Purchase Order Items</h2>
        <table>
            <thead>
                <?= create_table_header($result) ?>
            </thead>
            <tbody>
                <?= create_table_body($result,"poitem.php") ?>
            </tbody>
        </table>
<?
    $query = "SELECT * FROM PurchaseOrderReceipts WHERE POID=".$_GET['id'];
    $result = mysql_query($query) or die($query . "<br />" .mysql_error());
?>
        <h2>Purchase Order Receipts</h2>
        <table>
            <thead>
                <?= create_table_header($result) ?>
            </thead>
            <tbody>
                <?= create_table_body($result,"poreceipt.php") ?>
            </tbody>
        </table>
<? } ?>
<?= footer() ?>
