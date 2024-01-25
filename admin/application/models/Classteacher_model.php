<?php

class Classteacher_model extends CI_Model {

    public function getClassTeacher($id = null) {

        if (!empty($id)) {

            $query = $this->db->select('staff.*,class_teacher.id as ctid,class_teacher.class_id,class_teacher.section_id,classes.class,sections.section')->join("staff", "class_teacher.staff_id = staff.id")->join("classes", "class_teacher.class_id = classes.id")->join("sections", "class_teacher.section_id = sections.id")->where("class_teacher.id", $id)->get("class_teacher");

            return $query->row_array();
        } else {

            $query = $this->db->select('staff.*,class_teacher.id as ctid,classes.class,sections.section')->join("staff", "class_teacher.staff_id = staff.id")->join("classes", "class_teacher.class_id = classes.id")->join("sections", "class_teacher.section_id = sections.id")->get("class_teacher");

            return $query->row_array();
        }
    }

    public function addClassTeacher($data) {

        if (isset($data["id"])) {

            $this->db->where("id", $data["id"])->update("class_teacher", $data);
        } else {

            $this->db->insert("class_teacher", $data);
        }
    }

    function teacherByClassSection($class_id, $section_id) {


        $query = $this->db->select('staff.*,class_teacher.id as ctid,class_teacher.class_id,class_teacher.section_id,classes.class,sections.section')->join("staff", "class_teacher.staff_id = staff.id")->join("classes", "class_teacher.class_id = classes.id")->join("sections", "class_teacher.section_id = sections.id")->where("class_teacher.class_id", $class_id)->where("class_teacher.section_id", $section_id)->get("class_teacher");

        return $query->result_array();
    }

    public function getclassbyuser($id) {

        $query = $this->db->select("classes.*")->join("classes", "class_teacher.class_id = classes.id")->where("class_teacher.staff_id", $id)->get("class_teacher");

        return $query->result_array();
    }

    function classbysubjectteacher($id, $classes) {
        $query = $this->db->select("classes.*,teacher_subjects.subject_id")->join("class_sections", "class_sections.id = teacher_subjects.class_section_id")->join("classes", "class_sections.class_id = classes.id ")->where("teacher_subjects.teacher_id", $id)->where_not_in("class_sections.class_id", $classes)->group_by("class_sections.class_id")->get("teacher_subjects");

        return $query->result_array();
    }

    public function delete($class_id, $section_id, $array) {

        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        if (!empty($array)) {
            $this->db->where_in('staff_id', $array);
        }
        $this->db->delete('class_teacher');
        echo $this->db->last_query();
    }

    public function getsubjectbyteacher($id) {

        $query = $this->db->select("classes.*,teacher_subjects.subject_id")->join("class_sections", "class_sections.id = teacher_subjects.class_section_id")->join("classes", "class_sections.class_id = classes.id ")->where("teacher_subjects.teacher_id", $id)->group_by("class_sections.class_id")->get("teacher_subjects");
    }



    public function findfield($table,$fieldname1,$fieldvalue1,$returnfield)
          {
            $this->db->select($returnfield);
            $this->db->from($table);
            $this->db->where($fieldname1,$fieldvalue1);
            $query = $this->db->get();
            foreach ($query->result() as $value)
            {}
            return @$value->$returnfield;
          }

          public function get_data_by_query($qry)
        {
            $query = $this->db->query($qry);    
            return $query->result_array();
        }
          public function get_data_by_query1($qry)
        {
            $query = $this->db->query($qry);    
            return $query->row_array();
        }
        
        public function get_phone_no($phone,$class,$section) {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('students.guardian_phone',$phone);
        $this->db->where('classes.id',$class);
        $this->db->where('sections.id',$section);
        $query = $this->db->get();
        return $query->row_array();
            
        }
        public function get_father_no($phone,$class,$section) {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no ,students.father_phone , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id', 'left');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('students.father_phone',$phone);
        $this->db->where('classes.id',$class);
        $this->db->where('sections.id',$section);
        $query = $this->db->get();
        return $query->row_array();
            
        }

}

?>