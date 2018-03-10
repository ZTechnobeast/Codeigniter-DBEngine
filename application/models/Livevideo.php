<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Livevideo Model
 *
 * @package	livevideo
 * @author	Zcodia Technologies (http://zcodia.com/)
 */
class Livevideo extends CI_Model {

    public $key = 'id'; //primary key for the table to set auto increment column
    public $table_name = 'livevideo'; //Table Name
    protected $select = '*';
    protected $where = array();
    protected $column = array();

    public function __construct() {
        parent::__construct();
        $this->load->database();
        if (!$this->db->table_exists($this->table_name)) {
            //to create table if not exiest
            $this->_create_table();
        }
    }

    public function _create_table() {
        $this->load->library('dbengine');
        $table_data['fields'] = $this->table_columns();
        $table_data['key'] = $this->key;
        $table_data['table_name'] = $this->table_name;
        $data = $this->dbengine->create_table($table_data);
    }

    public function table_columns() {
        $array = array(
            'id' => array('type' => 'INT', 'constraint' => '11', 'auto_increment' => TRUE, 'comment' => 'Auto Increment value'),
            'title' => array('type' => 'varchar', 'constraint' => '255', 'comment' => 'Video Title'),
            'url' => array('type' => 'longtext', 'comment' => 'url that should take us while click'),
            'start_date' => array('type' => 'date', 'comment' => 'Show Start Date'),
            'start_time' => array('type' => 'time', 'comment' => 'Show Start time'),
            'end_date' => array('type' => 'date', 'comment' => 'Show End Date'),
            'end_time' => array('type' => 'time', 'comment' => 'Show End Time'),
            'timezone' => array('type' => 'varchar', 'constraint' => '255', 'comment' => 'posted time zone'),
            'img' => array('type' => 'varchar', 'constraint' => '255', 'comment' => 'Image Name'),
            'added_on' => array('type' => 'datetime', 'comment' => 'Entry Made On'),
            'updated_on' => array('type' => 'datetime', 'comment' => 'Updated Date and Time'),
            'status' => array('type' => 'tinyint', 'constraint' => '1', 'default' => '1', 'comment' => '0=To Make this entry to inactive,1=To Make this entry to active'),
        );
        return $array;
    }

    public function set_select($param) {
        $this->select = $param;
        return true;
    }
    public function set_orderby($column, $order) { 
         $this->db->order_by($column, $order);
        return true;
    }

    public function set_where($data) {
        if (is_array($data) && !empty($data)) {
            $this->where = array_merge($this->where, $data);
            return true;
        } else
            $this->where = $data;
    }

    public function set_column($column) {
        $this->column = $column;
    }

    public function set_spl_column($column, $val) {
        $this->db->set($column, $val, FALSE);
    }

    public function get($debug = FALSE) {
        $this->db->select($this->select);
        $this->db->where($this->where);
        $query = $this->db->get($this->table_name);
        if ($debug) {
            echo $this->db->last_query();
            die;
        }
        if ($query->num_rows() > 0) {
            $this->where = array();
            $result = $query->result_array();
            return $result;
        } else {
            $this->where = array();
            return FALSE;
        }
    }

//    public function insert($title, $img, $url, $startdate, $starttime, $enddate, $endtime, $timezone, $status, $liveurl, $replaydate, $replaytime,$alltimezone) {       
    public function insert($title, $img, $url, $startdate, $starttime, $enddate, $endtime, $timezone, $status, $liveurl, $replaydate, $replaytime, $sdutc, $endutc, $reputc,$replayflag,$source = false) {
        $data['title'] = $title;
        $data['url'] = $url;
        $data['flag'] = $source; //to day weither livestream or youtube
        $data['start_date'] = date('Y-m-d', strtotime($startdate));
        $data['start_time'] = date('H:i:s', strtotime($starttime));
        $data['end_date'] = date('Y-m-d', strtotime($enddate));
        $data['end_time'] = date('H:i:s', strtotime($endtime));
        $data['timezone'] = $timezone;
        $data['img'] = $img;
        $data['added_on'] = date('Y-m-d H:i:s');
        $data['updated_on'] = date('Y-m-d H:i:s');
        $data['status'] = $status;
        $data['liveurl'] = $liveurl;
        $data['replay_date'] = date('Y-m-d', strtotime($replaydate));
        $data['replay_time'] = date('H:i:s', strtotime($replaytime));
        $data['replay_flag'] = $replayflag;
        $data['utc_replay_datetime'] = date('Y-m-d H:i:s', strtotime($reputc));
        $data['utc_startdate_time'] = date('Y-m-d H:i:s', strtotime($sdutc));
        $data['utc_enddate_time'] = date('Y-m-d H:i:s', strtotime($endutc));

        $this->db->insert($this->table_name, $data);

        $inserted_id = $this->db->insert_id();
//        $this->db->last_query();

        if ($inserted_id) {

            return $inserted_id;
        } else {

            return FALSE;
        }
    }

    public function update($debug = false) {
        $this->db->where($this->where);
        $this->db->update($this->table_name, $this->column);
        if ($debug) {
            print_r($this->db->last_query());
            die;
        }
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($debug = false) {
        $this->db->where($this->where);
        $this->db->delete($this->table_name);
        if ($debug) {
            print_r($this->db->last_query());
            die;
        }
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function deletesingle($debug = false) {
        $this->db->where("id", $debug);
        $this->db->delete($this->table_name);
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
}
