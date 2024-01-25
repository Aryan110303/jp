<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Messages_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($id = null) {
        $this->db->select()->from('messages');
        if ($id != null) {
            $this->db->where('messages.id', $id);
        } else {
            $this->db->order_by('messages.created_at', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('messages');
    }
     public function sms_count() {
        $this->db->where('sms_status', 0);
         $query = $this->db->get('messages');
        return $query->num_rows();
    }

    public function add($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('messages', $data);
        } else {
            $this->db->insert('messages', $data);
            // echo $this->db->last_query();
            return $this->db->insert_id();

        }
    }

    public function getPendingSmsList()
    {
        $this->db->select('*');
        $this->db->from('messages');
        $this->db->where('sms_status = 0');
         $this->db->order_by('id', 'desc');
        $this->db->limit(25);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function updateSmsStatus($data,$messageId)
    {
        $this->db->where('id',$messageId);
        $this->db->update('messages',$data);
        if ($this->db->affected_rows() > 0) {
            return "true";
        }
    }
    
     public function get_all_student_no($class,$section,$session) {
        $this->db->select('*')->from('student_all_numbers');
        $this->db->where('class_id', $class);
        $this->db->where('section_id', $section);
        $this->db->where('session_id', $session);
        $query = $this->db->get();
        return $query->row_array();
        
    }
    public function get_all_messages() {
        $this->db->select('*');
       $this->db->from('messages');
       $query = $this->db->get();
       return $query->result();
    }


}
