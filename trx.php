<?php
/**
 *
 * This file is to help people explore trx's.
 *
 * Created by Rodolphe Courtier
 */

require_once 'config.php';

$query_extra = "";
if(isset($_GET['id'])) {
    $query_extra = "WHERE ID=" . $_GET['id'];
}
$query = "SELECT * FROM Transactions "
    . $query_extra
    . " ORDER BY ID DESC LIMIT 100";
$result = mysql_query($query) or die($query . "<br />" . mysql_error());;
?>

<?= head("Transaction",basename(__FILE__)) ?>
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
    $query = "SELECT * FROM Payments WHERE TrxID=".intval($_GET['id']);
    $result = mysql_query($query) or die($query . "<br />" . mysql_error());;
?>
        <h2>Payments</h2>
        <table>
            <thead>
                <?= create_table_header($result) ?>
            </thead>
            <tbody>
                <?= create_table_body($result,"payments.php") ?>
            </tbody>
        </table>
<?
    $query =  "SELECT * FROM TransactionItems WHERE TrxID=".intval($_GET['id']);
    $result = mysql_query($query) or die($query . "<br />" . mysql_error());
?>
        <h2>Transaction Items</h2>
        <table>
            <thead>
                <?= create_table_header($result) ?>
            </thead>
            <tbody>
                <?= create_table_body($result,"trxitem.php") ?>
            </tbody>
        </table>
<? } ?>
<?= footer() ?>
