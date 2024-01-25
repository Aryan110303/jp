<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transport_feemaster_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null) {
        $this->db->select('transport_feemasters.feetype_id,transport_feemasters.id,transport_feemasters.class_id,transport_feemasters.session_id,transport_feemasters.amount,transport_feemasters.description,classes.class,feetype.type,feetype.feecategory_id')->from('transport_feemasters');
        $this->db->join('classes', 'transport_feemasters.class_id = classes.id');
        $this->db->join('feetype', 'transport_feemasters.feetype_id = feetype.id');
        $this->db->where('transport_feemasters.session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('transport_feemasters.id', $id);
        } else {
            $this->db->order_by('transport_feemasters.id');
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
        $this->db->delete('transport_feemasters');
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
            $this->db->update('transport_feemasters', $data);
        } else {
            $data['session_id'] = $this->current_session;
            $this->db->insert('transport_feemasters', $data);
            return $this->db->insert_id();
        }
    }

    public function check_Exits_group($data) {
        $this->db->select('*');
        $this->db->from('transport_feemasters');
        $this->db->where('session_id', $this->current_session);
        $this->db->where('feetype_id', $data['feetype_id']);
        $this->db->where('class_id', $data['class_id']);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return false;
        } else {
            return true;
        }
    }

    public function getTypeByFeecategory($type, $class_id) {
        $this->db->select('transport_feemasters.id,transport_feemasters.session_id,transport_feemasters.amount,transport_feemasters.description,classes.class,feetype.type')->from('transport_feemasters');
        $this->db->join('classes', 'transport_feemasters.class_id = classes.id');
        $this->db->join('feetype', 'transport_feemasters.feetype_id = feetype.id');
        $this->db->where('transport_feemasters.class_id', $class_id);
        $this->db->where('transport_feemasters.feetype_id', $type);
        $this->db->where('transport_feemasters.session_id', $this->current_session);
        $this->db->order_by('transport_feemasters.id');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getByClass($class_id) {
        $this->db->select('transport_feemasters.id,transport_feemasters.session_id,transport_feemasters.amount,transport_feemasters.description,classes.class,feetype.type')->from('transport_feemasters');
        $this->db->join('classes', 'transport_feemasters.class_id = classes.id');
        $this->db->join('feetype', 'transport_feemasters.feetype_id = feetype.id');
        $this->db->where('transport_feemasters.class_id', $class_id);
        $this->db->where('transport_feemasters.session_id', $this->current_session);
        $this->db->order_by('transport_feemasters.id');
        $query = $this->db->get();
        return $query->result_array();
    }

}
