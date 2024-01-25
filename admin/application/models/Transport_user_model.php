<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transport_user_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null) {

        $this->db->select('transport_user.*,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`');
        $this->db->from('transport_user');
        $this->db->join('users', 'users.user_id = transport_user.id', 'left');
        $this->db->where('users.role', 'transport');

        if ($id != null) {
            $this->db->where('transport_user.id', $id);
        } else {
            $this->db->order_by('transport_user.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('transport_user');
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('transport_user', $data);
        } else {
            $this->db->insert('transport_user', $data);
            return $this->db->insert_id();
        }
    }

    public function searchNameLike($searchterm) {
        $this->db->select('transport_user.*')->from('transport_user');
        $this->db->group_start();
        $this->db->like('transport_user.name', $searchterm);
        $this->db->group_end();
        $this->db->order_by('transport_user.id');

        $query = $this->db->get();
        return $query->result_array();
    }

}
