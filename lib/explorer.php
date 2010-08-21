<?php
/**
 *
 * This file contains all the functions that do the work for the object
 * explorer.
 *
 */

/**
 *
 * This class will handle all the fun stuff of creating the proper view for
 * a $result for an object.
 *
 */
class ObjectView {
    public $links = array();
    public $related = array();
    public $data;
    public $object;

    /**
     *
     * Constructor. Expects a MySQL result and two arrays.
     *
     */
    function __construct($data, $links, $related, $object = "") {
        $this->object = $object;
        $this->data = $data;
        $this->links = $links;
        $this->related = $related;
    }

    /**
     *
     * Create a table from the results
     *
     */
    function generate() {
        $html = "<table>";
        $rows = mysql_num_rows($this->data);
        if($rows > 1) {
            $html .= $this->create_table_header();
            $html .= $this->create_table_body();
        } elseif($rows == 1) {
            $html .= $this->create_single_item_table();
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
    function create_table_header() {
        if(mysql_num_rows($this->data) == 0) return "";
        $header = "<tr>\n";
        $fields = $this->mysql_field_array($this->data);
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
    function create_single_item_table($file="index.php") {
        $table_body = "";
        $zebra = true;
        $row = mysql_fetch_assoc($this->data);
        foreach($row as $k => $v) {
            $table_body .= "<tr class=\"";
            $table_body .= $zebra ? "dark" : "light";
            $table_body .= "\">\n";
            $table_body .= "\t<td>";
            $table_body .= $k;
            $table_body .= "</td>\n";
            $table_body .= "\t<td>";
            $table_body .= $this->link_to($k, $v, $this->object);
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
    function create_table_body() {
        if(mysql_num_rows($this->data) == 0) return "No results";
        $table_body = "";
        $zebra = true;
        while($row = mysql_fetch_assoc($this->data)) {
            $table_body .= "<tr class=\"";
            $table_body .= $zebra ? "dark" : "light";
            $table_body .= "\">\n";
            foreach($row as $k => $v) {
                $table_body .= "\t<td>";
                $table_body .= $v;
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
    function link_to($column, $value, $object) {
        if(isset($this->links[$object][$column])) {
            $r = $this->links[$object][$column];
            $link = "<a href=\"?object=$r&id=$value\">$value</a>";
            return $link;
        }
        return $value;
    }
}
?>

