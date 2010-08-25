<?php
/**
 *
 * This contains configuration for the object explorer
 *
 * Created by Rodolphe Courtier
 *
 */

$doNotRunTemplateProcessing = true;
require_once '../../process.php';
require_once 'lib/spyc.php';
require_once 'lib/explorer.php';

$db_data = Spyc::YAMLLoad("config/db.yml");
$links = Spyc::YAMLLoad("config/links.yml");
$relations = Spyc::YAMLLoad("config/related.yml");

$db = mysql_connect(
    $db_data["url"],
    $db_data["user"],
    $db_data["password"]
) or die ("Failed to connect to MySQL");

mysql_select_db($db_data["database"], $db) or die("Unable to select database");

$params = array();
$id = 0;
if(!isset($_GET["object"])) {
    $object = "";
    $query = "SHOW TABLES";
    $params["tables"] = true;
} elseif(!isset($_GET["id"])) {
    $object = mysql_escape_string($_GET["object"]);
    $params["object"] = $object;
    $query = "SELECT * FROM ".$object." LIMIT 100";
} else {
    $object = mysql_escape_string($_GET["object"]);
    $id = intval($_GET["id"]);
    $params["object"] = $object;
    $params["id"] = $id;
    $query = "SELECT * FROM ".$object." WHERE ID=".$id;
}
$result = mysql_query($query)
    or die(mysql_error());
$view = new ObjectView($result, $links, $related, $object);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Object Explorer</title>
        <link href="style/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <!-- <pre><? print_r($links); ?> -->
        <h1><?= $object ?></h1>
        <?= $view->generate() ?>
    </body>
</html>
