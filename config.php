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

$db = mysql_connect(
    "localhost",
    "clicker",
    "Cl!ckaway4916"
) or die ("Failed to connect to MySQL");

mysql_select_db("clicker_SQL07", $db) or die("Unable to select database");

/**
 *
 * Create a generic link to some target with a body, sometimes with
 * an id to it.
 *
 */
function linkify($target,$body,$id = false) {
    $link = "<a href=\"./". $target;
    if($id) $link .= "?id=" . $id;
    $link .= "\">". $body . "</a>";
    return $link;
}

/**
 *
 * Create a special link to one of my explorer pages
 *
 */
function link_to($k,$v,$source) {
    switch($k) {
        case "ID":
            $link = linkify($source,$v,$v);
            break;
        case "MemID":
            $link = linkify("member.php",$v,$v);
            break;
        case "MemberID":
            $link = linkify("member.php",$v,$v);
            break;
        case "TransactionID":
            if($v != 0) $link = linkify("trx.php",$v,$v);
            else $link = $v;
            break;
        case "TrxID":
            $link = linkify("trx.php",$v,$v);
            break;
        case "Creator":
            $link = linkify("member.php",$v,$v);
            break;
        case "ItemID":
            $link = linkify("item.php",$v,$v);
            if($source == "poreceiptitem.php") $link = linkify("poitem.php",$v,$v);
            break;
        case "ItemNo":
            $link = linkify("item.php",$v,$v);
            break;
        case "Pass":
            $link = "yeah right";
            break;
        case "POID":
            $link = linkify("po.php",$v,$v);
            break;
        case "ReceiptID":
            $link = linkify("poreceipt.php",$v,$v);
            break;
        default:
            $link = $v;
    }
    return $link;
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
    echo $header;
}

/**
 *
 * Create table rows and table cells from a MySQL result
 *
 */
function create_table_body($result,$file) {
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
 * Shows the header
 *
 */
function head($page_name,$file) {
$head =  <<<HEAD
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>$page_name</title>
        <link href="style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
HEAD;
$head .= "<h1>".linkify($file,$page_name)."</h1>";
return $head;
}


/**
 *
 * Shows the footer
 *
 */
function footer() {
return <<<FOOTER
    </body>
</html>
FOOTER;
}

?>
