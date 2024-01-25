<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class holidays_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_session_name = $this->setting_model->getCurrentSessionName();
        $this->start_month = $this->setting_model->getStartMonth();
    }

    function add($data) {
        $this->db->insert('school_holidays', $data);
        return $query = $this->db->insert_id();
    }


    public function holidays_list($id = null) {
        $this->db->select()->from('school_holidays');
        if ($id != null) {
            $this->db->where('school_holidays.id', $id);
        } else {
            $this->db->order_by('school_holidays.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('school_holidays');
        $this->session->set_flashdata('msg', '<div class="alert alert-success"> Visitor deleted successfully</div>');
        redirect('admin/holidays');
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('school_holidays', $data);
        $this->session->set_flashdata('msg', '<div class="alert alert-success"> Visitor Updated successfully</div>');
        redirect('admin/holidays');
    }

  



   

}
