<?php
/*  Copyright 2012  Benjamin Piwowarski  (email : benjamim@bpiwowar.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
* Database management
* See http://codex.wordpress.org/Creating_Tables_with_Plugins
*/



global $wpdb;
global $papercite_db_version;
global $papercite_table_name;
$papercite_table_name = $GLOBALS["wpdb"]->prefix . "plugin_papercite";
$papercite_db_version = "1.3";


function papercite_msg_upgraded() {
    print "<p style='text-align: center; color: blue'>Papercite plugin: database updated to version $GLOBALS[papercite_db_version]</p>";
}

function papercite_install($force = false) {
    global $wpdb, $papercite_db_version, $papercite_table_name;

     $installed_ver = get_option( "papercite_db_version" );
     if ($force || $installed_ver != $papercite_db_version) {
         $exists =  sizeof($wpdb->get_col("SHOW TABLES LIKE '$papercite_table_name'")) == 1;
         
         $sql = "CREATE TABLE $papercite_table_name (
             url VARCHAR(300) CHARSET ASCII NOT NULL,  
             bibtexid VARCHAR(200) CHARSET ASCII NOT NULL,
             entrytype VARCHAR(80) CHARSET ASCII NOT NULL,
             year SMALLINT,
             data TEXT NOT NULL,
             PRIMARY KEY id (url, bibtexid),
             INDEX year (year),
             INDEX entrytype (entrytype)
          ) DEFAULT CHARACTER SET $wpdb->charset";
          
        // Install / upgrade
         require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
         
         ob_start();
         $wpdb->show_errors(true);
         dbDelta($sql, true);
         $output = ob_get_contents();
         ob_end_clean();
         
         if ($wpdb->last_result !== FALSE) {
             // Set the current version
             update_option("papercite_db_version", $papercite_db_version);
             add_action('admin_notices', 'papercite_msg_upgraded');
         }
         
         return array($wpdb->last_result !== FALSE, $output);
     }
     
     return true;
}

papercite_install();





?>