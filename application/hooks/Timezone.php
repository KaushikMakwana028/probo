<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timezone {

    public function set_timezone() {
        $CI =& get_instance();

        // Ensure the database connection is loaded before trying to query it
        if (isset($CI->db) && is_object($CI->db)) {
            // Set the MySQL session timezone to India Standard Time (+05:30)
            // This ensures that all CURRENT_TIMESTAMP insertions and retrieved TIMESTAMP values 
            // are perfectly aligned with India time.
            $CI->db->query("SET time_zone = '+05:30'");
        }
    }

}
