<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transport_custom_model
 *
 * @author 7 eye
 */
class Transport_custom_model extends CI_Model{
    
  public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }
    
    public function get_fee_group($id = null) {
        $this->db->select()->from('trans_fee_groups_feetype');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }
    
    public function get_feegroup($id = null) {
        $this->db->select()->from('fee_groups');
        $this->db->where('is_system', 0);
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
     public function add_edit($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('trans_fee_groups_feetype', $data);
        } else {
            $this->db->insert('trans_fee_groups_feetype', $data);
            return $this->db->insert_id();
        }
    }
    public function get_fees($student_session_id) {
       $this->db->select()->from('student_transportfees_master');
        $this->db->where('student_session_id', $student_session_id);
        $query = $this->db->get();
        return $query->result();
        
    }
    public function getStudenttransportFees($student_session_id) {
        $sql = "SELECT `student_transportfees_master`.*,transport_route.route_title FROM `student_transportfees_master` INNER JOIN trans_fee_session_groups on student_transportfees_master.fee_session_group_id=trans_fee_session_groups.id INNER JOIN transport_route on transport_route.id=trans_fee_session_groups.fee_groups_id  WHERE `student_session_id` = " . $student_session_id . " ORDER BY `student_transportfees_master`.`id`";
        $query = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            foreach ($result as $result_key => $result_value) {
                $fee_session_group_id = $result_value->fee_session_group_id;
                $student_fees_master_id = $result_value->id;
                $result_value->fees = $this->getDueFeeByFeeSessionGrouptransport($fee_session_group_id, $student_fees_master_id);
            }
        }

        return $result;
    }
    
    public function getStudenttransportFeesrow($student_session_id) {
        $sql = "SELECT `student_transportfees_master`.*,transport_route.route_title FROM `student_transportfees_master` INNER JOIN trans_fee_session_groups on student_transportfees_master.fee_session_group_id=trans_fee_session_groups.id INNER JOIN transport_route on transport_route.id=trans_fee_session_groups.fee_groups_id  WHERE `student_session_id` = " . $student_session_id . " ORDER BY `student_transportfees_master`.`id`";
        $query = $this->db->query($sql);
        $result_value = $query->row();
        if (!empty($result_value)) {
                $fee_session_group_id = $result_value->fee_session_group_id;
                $student_fees_master_id = $result_value->id;
                $result_value->fees = $this->getDueFeeByFeeSessionGrouptransport($fee_session_group_id, $student_fees_master_id);

        }

        return $result_value;
    }
    
    public function remove1($id, $sub_invoice) {
        $this->db->where('id', $id);
        $q = $this->db->get('student_transportfees_deposite');
        if ($q->num_rows() > 0) {
            $result = $q->row();
            $a = json_decode($result->amount_detail, true);
            unset($a[$sub_invoice]);
            if (!empty($a)) {
                $data['amount_detail'] = json_encode($a);
                $this->db->where('id', $id);
                $this->db->update('student_transportfees_deposite', $data);
            } else {
                $this->db->where('id', $id);
                $this->db->delete('student_transportfees_deposite');
            }
        }
    }
    
     public function add3($data) {

        $this->db->where('student_session_id', $data['student_session_id']);
//        $this->db->where('fee_session_group_id', $data['fee_session_group_id']);
        $q = $this->db->get('student_transportfees_master');

        if ($q->num_rows() > 11) {
            return $q->row()->id;
        } else {
            $this->db->insert('student_transportfees_master', $data);
            return $this->db->insert_id();
        }
    }
    public function getFeesByGroup2($id = null) {
        $this->db->select('trans_fee_session_groups.*,transport_route.route_title as `group_name`');
        $this->db->from('trans_fee_session_groups');
        $this->db->join('transport_route', 'transport_route.id = trans_fee_session_groups.fee_groups_id');
        $this->db->where('trans_fee_session_groups.session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('trans_fee_session_groups.id', $id);
        }
        $query = $this->db->get();

        $result = $query->result();
        foreach ($result as $key => $value) {
            $value->feetypes = $this->getfeeTypeByGroup2($value->fee_groups_id, $value->id);
        }
        return $result;
    }
    
    public function add2($data) {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        $parentid = $this->group_exists2($data['fee_groups_id']);
        $data['fee_session_group_id'] = $parentid;
        $this->db->insert('trans_fee_groups_feetype', $data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }
    
    
    public function getfeeTypeByGroup2($id = null, $fee_session_group_id) {
        $this->db->select('trans_fee_groups_feetype.*,transport_feetype.type,transport_feetype.code');
        $this->db->from('trans_fee_groups_feetype');
        $this->db->join('transport_feetype', 'transport_feetype.id=trans_fee_groups_feetype.feetype_id');
        $this->db->where('trans_fee_groups_feetype.fee_groups_id', $id);
        $this->db->where('trans_fee_groups_feetype.fee_session_group_id', $fee_session_group_id);
        $this->db->order_by('trans_fee_groups_feetype.id', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    
    function group_exists2($fee_groups_id) {
        $this->db->where('fee_groups_id', $fee_groups_id);
        $this->db->where('session_id', $this->current_session);
        $query = $this->db->get('trans_fee_session_groups');
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        } else {
            $data = array('fee_groups_id' => $fee_groups_id, 'session_id' => $this->current_session);
            $this->db->insert('trans_fee_session_groups', $data);
            return $this->db->insert_id();
        }
    }
    
    public function transportDeposit($data) {
        $sql = "SELECT transport_route.route_title as `fee_group_name`,transport_feetype.code as `fee_type_code`,trans_fee_groups_feetype.amount,IFNULL(student_transportfees_deposite.amount_detail,0) as `amount_detail` from student_transportfees_master 
               INNER JOIN trans_fee_session_groups on trans_fee_session_groups.id=student_transportfees_master.fee_session_group_id 
              INNER JOIN trans_fee_groups_feetype on trans_fee_groups_feetype.fee_groups_id=trans_fee_session_groups.fee_groups_id
              INNER JOIN transport_route on trans_fee_groups_feetype.fee_groups_id=transport_route.id
              INNER JOIN transport_feetype on trans_fee_groups_feetype.feetype_id=transport_feetype.id
         LEFT JOIN student_transportfees_deposite on student_transportfees_deposite.student_fees_master_id=student_transportfees_master.id and student_transportfees_deposite.fee_groups_feetype_id=trans_fee_groups_feetype.id WHERE student_transportfees_master.id =" . $data['student_fees_master_id'] . " and trans_fee_groups_feetype.id =" . $data['fee_groups_feetype_id'];
        $query = $this->db->query($sql);
        return $query->row();
    }
    
        public function transportDeposit2($data) {
        $this->db->select('student_transportfees_master.*,student_transportfees_deposite.amount_detail');
        $this->db->from('student_transportfees_master');
        $this->db->join('student_transportfees_deposite', 'student_transportfees_deposite.student_fees_master_id=student_transportfees_master.id','left');
        $this->db->where('student_transportfees_master.id', $data['student_fees_master_id']);
        $query = $this->db->get();
        return $query->row();
    }
    
     public function transportDeposit3($id) {
        $this->db->select('student_transportfees_master.*,student_transportfees_deposite.amount_detail,student_transportfees_deposite.id as student_fees_deposite_id');
        $this->db->from('student_transportfees_master');
        $this->db->join('student_transportfees_deposite', 'student_transportfees_deposite.student_fees_master_id=student_transportfees_master.id');
        $this->db->where('student_transportfees_master.id', $id);
        $query = $this->db->get();
        return $query->row();
    }
    
    public function update_student_transport($data) {
        $this->db->where('id',$data['id']);
        $this->db->update('student_transportfees_master',$data);
        
    }
    
    public function getFeeByInvoice1($invoice_id, $sub_invoice_id) {
        $this->db->select('`student_transportfees_deposite`.*,students.firstname,students.lastname,student_session.class_id,classes.class,sections.section,student_session.section_id,student_session.student_id,`transport_feetype`.`type`, `transport_feetype`.`code`,student_transportfees_master.student_session_id')->from('student_transportfees_deposite');
        $this->db->join('trans_fee_groups_feetype', 'trans_fee_groups_feetype.id = student_transportfees_deposite.fee_groups_feetype_id');
        $this->db->join('transport_route', 'transport_route.id = trans_fee_groups_feetype.fee_groups_id');
        $this->db->join('transport_feetype', 'transport_feetype.id = trans_fee_groups_feetype.feetype_id');
        $this->db->join('student_transportfees_master', 'student_transportfees_master.id=student_transportfees_deposite.student_fees_master_id');
        $this->db->join('student_session', 'student_session.id= student_transportfees_master.student_session_id');
        $this->db->join('classes', 'classes.id= student_session.class_id');
        $this->db->join('sections', 'sections.id= student_session.section_id');
        $this->db->join('students', 'students.id=student_session.student_id');
        $this->db->where('student_transportfees_deposite.id', $invoice_id);
        $q = $this->db->get();


        if ($q->num_rows() > 0) {
            $result = $q->row();
            $res = json_decode($result->amount_detail);
            $a = (array) $res;

            foreach ($a as $key => $value) {
                if ($key == $sub_invoice_id) {

                    return $result;
                }
            }
        }


        return false;
    }
    
    public function fee_deposit2($data, $send_to) {

        $this->db->where('student_fees_master_id', $data['student_fees_master_id']);
//      $this->db->where('fee_groups_feetype_id', $data['fee_groups_feetype_id']);
        $q = $this->db->get('student_transportfees_deposite');

        if ($q->num_rows() > 0) {
            $row = $q->row();
            $this->db->where('id', $row->id);
            $a = json_decode($row->amount_detail, true);
            $inv_no = max(array_keys($a)) + 1;
            $data['amount_detail']['inv_no'] = $inv_no;
            $a[$inv_no] = $data['amount_detail'];
            $data['amount_detail'] = json_encode($a);
            $this->db->update('student_transportfees_deposite', $data);

            return json_encode(array('invoice_id' => $row->id, 'sub_invoice_id' => $inv_no));
        } else {
            $data['amount_detail']['inv_no'] = 1;
            $data['amount_detail'] = json_encode(array('1' => $data['amount_detail']));
            $this->db->insert('student_transportfees_deposite', $data);
            $inserted_id = $this->db->insert_id();

            return json_encode(array('invoice_id' => $inserted_id, 'sub_invoice_id' => 1));
        }
    }
    
    public function getDueFeeByFeeSessionGrouptransport($fee_session_groups_id, $student_fees_master_id) {
        $sql = "SELECT student_transportfees_master.*,trans_fee_groups_feetype.id as `fee_groups_feetype_id`,trans_fee_groups_feetype.amount,trans_fee_groups_feetype.due_date,trans_fee_groups_feetype.fee_groups_id,transport_route.route_title,trans_fee_groups_feetype.feetype_id,transport_feetype.code,transport_feetype.type, IFNULL(student_transportfees_deposite.id,0) as `student_fees_deposite_id`, IFNULL(student_transportfees_deposite.amount_detail,0) as `amount_detail` FROM `student_transportfees_master` INNER JOIN trans_fee_session_groups on trans_fee_session_groups.id = student_transportfees_master.fee_session_group_id INNER JOIN trans_fee_groups_feetype on  trans_fee_groups_feetype.fee_session_group_id = trans_fee_session_groups.id  INNER JOIN transport_route on transport_route.id=trans_fee_groups_feetype.fee_groups_id INNER JOIN transport_feetype on transport_feetype.id=trans_fee_groups_feetype.feetype_id LEFT JOIN student_transportfees_deposite on student_transportfees_deposite.student_fees_master_id=student_transportfees_master.id and student_transportfees_deposite.fee_groups_feetype_id=trans_fee_groups_feetype.id WHERE student_transportfees_master.fee_session_group_id =" . $fee_session_groups_id . " and student_transportfees_master.id=" . $student_fees_master_id . " order by trans_fee_groups_feetype.due_date asc";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
     public function getDueFeeByFeeSessionGroupFeetype1($student_fees_master_id) {

//        $sql = "SELECT student_transportfees_master.*,students.firstname,students.lastname,student_session.class_id,classes.class,sections.section,students.guardian_name,students.father_name,student_session.section_id,student_session.student_id,trans_fee_groups_feetype.amount,trans_fee_groups_feetype.due_date,trans_fee_groups_feetype.fee_groups_id,transport_route.route_title,trans_fee_groups_feetype.feetype_id,transport_feetype.code,transport_feetype.type,
//          IFNULL(student_transportfees_deposite.id,0) as `student_fees_deposite_id`, IFNULL(student_transportfees_deposite.amount_detail,0) as `amount_detail` FROM `student_transportfees_master` INNER JOIN trans_fee_session_groups on trans_fee_session_groups.id = student_transportfees_master.fee_session_group_id INNER JOIN trans_fee_groups_feetype on  trans_fee_groups_feetype.fee_session_group_id = trans_fee_session_groups.id  INNER JOIN 
//          transport_route on transport_route.id=trans_fee_groups_feetype.fee_groups_id INNER JOIN transport_feetype on transport_feetype.id=trans_fee_groups_feetype.feetype_id LEFT JOIN student_transportfees_deposite on student_transportfees_deposite.student_fees_master_id=student_transportfees_master.id and student_transportfees_deposite.fee_groups_feetype_id=trans_fee_groups_feetype.id INNER JOIN student_session on student_session.id= student_transportfees_master.student_session_id INNER JOIN classes on classes.id= student_session.class_id INNER JOIN sections on sections.id= student_session.section_id INNER JOIN students on students.id=student_session.student_id  WHERE student_transportfees_master.fee_session_group_id =" . $fee_session_groups_id . " and student_transportfees_master.id=" . $student_fees_master_id . " and trans_fee_groups_feetype.id= " . $fee_groups_feetype_id;
//        
         $sql="SELECT student_transportfees_master.*,IFNULL(student_transportfees_deposite.id,0) as `student_fees_deposite_id`, IFNULL(student_transportfees_deposite.amount_detail,0) as `amount_detail`,student_session.class_id, student_session.section_id,student_session.student_id,classes.class,sections.section,students.firstname,students.lastname,students.guardian_name,students.father_name,transport_route.route_title

FROM `student_transportfees_master`

LEFT JOIN student_transportfees_deposite on student_transportfees_deposite.student_fees_master_id=student_transportfees_master.id 

INNER JOIN student_session on student_session.id= student_transportfees_master.student_session_id

INNER JOIN classes on classes.id= student_session.class_id 

INNER JOIN sections on sections.id= student_session.section_id 

INNER JOIN students on students.id=student_session.student_id 

LEFT JOIN transport_route on transport_route.id= student_session. route_id

WHERE student_transportfees_master.id=".$student_fees_master_id;
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    
    public function remove_group($id) {
        $this->db->trans_begin();
        $sql = "delete trans_fee_groups_feetype.* FROM trans_fee_groups_feetype JOIN trans_fee_session_groups ON trans_fee_session_groups.id = trans_fee_groups_feetype.fee_session_group_id WHERE trans_fee_session_groups.id = ?";
        $this->db->query($sql, array($id));
        $this->db->where('id', $id);
        $this->db->delete('trans_fee_session_groups');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
