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
$query = "SELECT * FROM Items "
    . $query_extra
    . " ORDER BY ID DESC LIMIT 100";
$result = mysql_query($query) or die($query . "<br />" . mysql_error());;
?>

<?= head("Items",basename(__FILE__)) ?>
        <table>
            <thead>
                <?= create_table_header($result) ?>
            </thead>
            <tbody>
                <?= create_table_body($result,basename(__FILE__)) ?>
            </tbody>
        </table>
<?= footer() ?>
