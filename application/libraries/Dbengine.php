<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileUpload
 *
 * @author Administrator
 */
class dbengine {

    public $CI;

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function create_table($data, $debug = false) {

        if (!empty($data) && isset($data['table_name']) && $data['table_name'] != '' && isset($data['fields']) && !empty($data['fields']) && isset($data['key']) && $data['key'] != '') {
            $this->CI->dbforge->add_field($data['fields']);
            $this->CI->dbforge->add_key($data['key'], TRUE);
            if ($this->CI->dbforge->create_table($data['table_name'], TRUE)) {
                if ($debug) {
                    echo $this->CI->db->last_query();die;
                }
                return TRUE;
            } else {
                if ($debug) {
                    echo $this->CI->db->last_query();die;
                }
                return false;
            }
        } else {
            return false;
        }
    }

}

?>
