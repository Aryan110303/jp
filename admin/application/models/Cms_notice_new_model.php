<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cms_notice_new_model extends CI_Model {
 public $table = 'notice_new';
    public $id = 'id';
    public $order = 'DESC';
     function __construct() {
        
        parent::__construct();
//        $this->current_session = $this->setting_model->getCurrentSession();
      
    }
     function get_all(){
//        return; $this->db->get($this->table)->result_array();
         return $this->db->get($this->table)->result_array();
    }
    
    
     function addfeature($param=0) {
//        $this->db->insert($this->table,$param);
         $this->db->insert($this->table,$param);
    }
     function getBy_id($id){
        $this->db->where('id',$id);
        return  $this->db->get($this->table)->row_array();
    }
     function update($data) {
      $this->db->where('id',$data['id']);
      $this->db->update($this->table,$data);
    }
     function delete($id) {
        $this->db->where('id',$id);
        $this->db->delete($this->table);
        
    }

   
  
    

}
