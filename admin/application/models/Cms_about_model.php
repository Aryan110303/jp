<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cms_about_model extends CI_Model {
 public $table = 'about_us';
    public $id = 'id';
    public $order = 'DESC';
     function __construct() {
        
        parent::__construct();
//        $this->current_session = $this->setting_model->getCurrentSession();
      
    }
     function get_all(){
//        return; $this->db->get($this->table)->result_array();
         return $this->db->get('about_us')->result_array();
    }
    
    
     function addfeature($param=0) {
//        $this->db->insert($this->table,$param);
         $this->db->insert('about_us',$param);
    }
     function getBy_id($id){
        $this->db->where('id',$id);
        return  $this->db->get('about_us')->row_array();
    }
     function update($data) {
      $this->db->where('id',$data['id']);
      $this->db->update('about_us',$data);
    }
     function delete($id) {
        $this->db->where('id',$id);
        $this->db->delete('about_us');
        
    }

   
  
    

}
