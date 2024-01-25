<?php  
 /**
  * 
  */
 class Homemodel_api extends CI_model
 {


 	public function getByEmail($email) {
        $this->db->select('*');
        $this->db->from('staff');
        $this->db->where('email', $email);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
 	
 	public function checkloginapi($loginData)
 	{
 		$record = $this->getByEmail($loginData['email']);
        if ($record) {
            $pass_verify = $this->enc_lib->passHashDyc($loginData['password'], $record->password);
            
            if ($pass_verify) {     
                $roles = $this->staffroles_model->getStaffRoles($record->id);
                $record->roles = array($roles[0]->name => $roles[0]->role_id);
                return $record;
            }
        }
        return false;
 	}
 }
?>