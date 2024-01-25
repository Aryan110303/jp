<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cms_feature_model extends CI_Model {
 public $table = 'feature';
    public $id = 'id';
    public $order = 'DESC';
     function __construct() {
        
        parent::__construct();
//        $this->current_session = $this->setting_model->getCurrentSession();
      
    }
     function get_all(){
//        return; $this->db->get($this->table)->result_array();
         return $this->db->get('feature')->result_array();
    }
    
    
     function addfeature($param=0) {
//        $this->db->insert($this->table,$param);
         $this->db->insert('feature',$param);
    }
     function getBy_id($id){
        $this->db->where('id',$id);
        return  $this->db->get('feature')->row_array();
    }
     function update($data) {
      $this->db->where('id',$data['id']);
      $this->db->update('feature',$data);
    }
     function delete($id) {
        $this->db->where('id',$id);
        $this->db->delete('feature');
        
    }

   
  
    

}
