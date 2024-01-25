<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Onlinetest_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

 
    public function get($date ='') {
         if($date)
          $sql = "SELECT onlineexams.*,classes.class FROM `onlineexams` join classes on onlineexams.class_id = classes.id where onlineexams.startDate >= '$date' and onlineexams.status=1";
          else
          $sql = "SELECT onlineexams.*,classes.class FROM `onlineexams` join classes on onlineexams.class_id = classes.id where 1 and onlineexams.status=1  order by id desc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
 

    public function getexamqestions($examid) {        
        $sql = "SELECT examquestions.* FROM `examquestions` join onlineexams on onlineexams.id = examquestions.examid WHERE examquestions.examid = " . $examid;
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function selectall_questions()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('examquestions');
		return $query;
	}

	function insertexceldata($data)
	{
		$this->db->insert_batch('examquestions', $data);
    }
    
   


}
