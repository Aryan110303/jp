<?php  
 /**
  * 
  */
 class SmsModel extends CI_model
 {
 	public function get()
 	{
 		$this->db->select('*');
 		$this->db->from('sms_template');
 		$query = $this->db->get();
 		return $query->result();
 	}

 	public function getTemplateMsgModel($id)
    {
       $this->db->select('*');
       $this->db->from('sms_template');
       $this->db->where('template_id',$id);
       $query = $this->db->get();
       return $query->row();
    }

    public function updateTemplateModel($tempId,$updateData)
    {
    	$this->db->where('template_id',$tempId);
    	$this->db->update('sms_template',$updateData);
    	return $this->db->affected_rows();
    }

    public function addTemplateModel($insertData)
    {
    	$this->db->insert('sms_template',$insertData);
    	return $this->db->insert_id();
    }

    public function delete_by_id($id)
    {
      $this->db->where('template_id', $id);
      $this->db->delete('sms_template');
    }
 }
?>