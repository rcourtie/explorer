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
    $query_extra = "WHERE MemID=" . $_GET['id'];
}
$query = "SELECT * FROM Members "
    . $query_extra
    . " ORDER BY MemID DESC LIMIT 100";
$result = mysql_query($query) or die($query . "<br />" . mysql_error());;
?>

<?= head("Members",basename(__FILE__)) ?>
        <table>
            <thead>
                <?= create_table_header($result) ?>
            </thead>
            <tbody>
                <?= create_table_body($result,basename(__FILE__)) ?>
            </tbody>
        </table>
<?= footer() ?>
