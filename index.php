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

$db_data = Spyc::YAMLLoad("config/db.yml");
print_r($db_data);

$db = mysql_connect(
    $db_data["url"],
    $db_data["user"],
    $db_data["password"]
) or die ("Failed to connect to MySQL");

mysql_select_db($db_data["database"], $db) or die("Unable to select database");

$params = array();
if(!isset($_GET["object"])) {
    $query = "SHOW TABLES";
    $params["tables"] = true;
} elseif(!isset($_GET["id"])) {
    $object = mysql_escape_string($_GET["object"]);
    $query = "SELECT * FROM ".$object." LIMIT 100";
} else {
    $object = mysql_escape_string($_GET["object"]);
    $id = intval($_GET["id"]);
    $query = "SELECT * FROM ".$object." WHERE ID=".$id;
}
$result = mysql_query($query)
    or die(mysql_error());

/**
 *
 * Create a table from the results
 *
 */
function create_table($result,$params = array()) {
    $html = "<table>";
    $rows = mysql_num_rows($result);
    if($rows > 1) {
        $html .= create_table_header($result);
        $html .= create_table_body($result);
    } elseif($rows == 1) {
        $html .= create_single_item_table($result);
    } else {
        $html .= "No results";
    }
    $html .= "</table";
    return $html;
}

/**
 *
 * Create table header
 *
 */
function create_table_header($result) {
    if(mysql_num_rows($result) == 0) return "";
    $header = "<tr>\n";
    $fields = mysql_field_array($result);
    foreach($fields as $f) {
        $header .= "\t<th>".$f."</th>\n";
    }
    $header .= "</tr>\n";
    return $header;
}
/**
 *
 * Create a table for a single object
 *
 */
function create_single_item_table($result,$file="index.php") {
    $table_body = "";
    $zebra = true;
    $row = mysql_fetch_assoc($result);
    foreach($row as $k => $v) {
        $table_body .= "<tr class=\"";
        $table_body .= $zebra ? "dark" : "light";
        $table_body .= "\">\n";
        $table_body .= "\t<td>";
        $table_body .= $k;
        $table_body .= "</td>\n";
        $table_body .= "\t<td>";
        $table_body .= link_to($k,$v,$file);
        $table_body .= "</td>\n";
        $table_body .= "</tr>\n";
        $zebra = $zebra ? false : true;
    }
    return $table_body;
}

/**
 *
 * Create table rows and table cells from a MySQL result
 *
 */
function create_table_body($result,$file="index.php") {
    if(mysql_num_rows($result) == 0) return "No results";
    $table_body = "";
    $zebra = true;
    while($row = mysql_fetch_assoc($result)) {
        $table_body .= "<tr class=\"";
        $table_body .= $zebra ? "dark" : "light";
        $table_body .= "\">\n";
        foreach($row as $k => $v) {
            $table_body .= "\t<td>";
            $table_body .= link_to($k,$v,$file);
            $table_body .= "</td>\n";
        }
        $table_body .= "</tr>\n";
        $zebra = $zebra ? false : true;
    }
    return $table_body;
}

/**
 *
 * Grab the the field names of the table
 *
 */
function mysql_field_array( $res ) {
    $field = mysql_num_fields( $res );
    for ( $i = 0; $i < $field; $i++ ) {
        $names[] = mysql_field_name( $res, $i );
    }
    return $names;
}

/**
 *
 * Creates a link to the given source
 *
 */
function link_to($k, $v, $file) {
    return $v;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Object Explorer</title>
        <link href="style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h1><?= $object ?></h1>
        <?= create_table($result) ?>
    </body>
</html>
