<?php  
  /**
   * 
   */
  class Transfercertificate_model extends CI_model
  {
  	public function add($data)
  	{
  		$this->db->insert('transfer_certificate',$data);
  		return $this->db->insert_id();
  	}

  	public function getTc($tcid)
  	{
  		$this->db->select('GROUP_CONCAT(SU.name) AS subject_name,S.firstname,S.lastname,S.admission_no,S.created_at AS register_date,S.father_name,S.mother_name,S.dob,S.cast,S.id AS sId,C.*,SS.*,TS.*,CS.*,TC.leaving_reason,TC.created_at AS generateDate');
      $this->db->from('transfer_certificate AS TC');
      $this->db->join('students AS S','S.id = TC.student_id');
      $this->db->join('student_session SS','SS.student_id = S.id');
      $this->db->join('sessions AS SE','SE.id = SS.session_id');
      $this->db->join('classes AS C','C.id = SS.class_id');
      $this->db->join('class_sections AS CS','CS.class_id = C.id');
      $this->db->join('teacher_subjects AS TS','TS.class_section_id = CS.id');
      $this->db->join('subjects AS SU','SU.id = TS.subject_id');
      $this->db->where('TC.tc_id',$tcid);
      // echo $this->db->last_query()
  		$query = $this->db->get();
      // echo $this->db->last_query();
  		if ($query->num_rows() > 0) {
  			return $query->result_array();
  		}
  	}

    public function getcertificatelist($class,$section)
    {
      // print_r($class);
      $this->db->select('*');
      $this->db->from('transfer_certificate AS TC');
      $this->db->join('students AS S','S.id = TC.student_id');
      $this->db->join('student_session AS SS','SS.student_id = TC.student_id');
      $this->db->join('classes AS C','SS.class_id = C.id');
      $this->db->join('class_sections AS CS','SS.section_id = CS.id');
      $this->db->join('sections AS SE','CS.section_id = SE.id');
      $this->db->join('categories AS CA','CA.id = S.category_id','left');
      $this->db->where('SS.class_id',$class);
      $this->db->where('SS.section_id',$section);
      $this->db->where('TC.status = 0');
      $query = $this->db->get();
      // echo $this->db->last_query(); die();
      if ($query->num_rows() > 0) {
        return $query->result_array();
      }
      
      // echo $this->db->last_query(); die();
    }
  }
?>