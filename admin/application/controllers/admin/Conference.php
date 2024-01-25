<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Conference extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('mailsmsconf');
       // $this->load->model(array('conference_model', 'conferencehistory_model'));
    }

   
    public function gmeet()
    {
       $userdata= $this->session->userdata('admin');
   
        $uesrid=$userdata['id']; 
        $mydate = date('Y-m-d');
       $day=date('l', strtotime($mydate)); 
        $this->session->set_userdata('top_menu', 'conference');
        $this->session->set_userdata('sub_menu', 'conference/gmeet');
        $data['staffList'] =  $this->db->query("SELECT * from staff where 1 ")->result();
        $data['classes'] =  $this->db->query("SELECT * from classes where 1 ")->result();
        $data['sections'] =  $this->db->query("SELECT * from sections where 1 ")->result();
        
        if($uesrid==1){
            $data['conferences'] =  $this->db->query("SELECT meet_classroom.*,classes.class,sections.section,staff.name,staff.surname from meet_classroom join classes on classes.id = meet_classroom.class_id join sections on sections.id = meet_classroom.section_id join staff on staff.id = meet_classroom.teacher_id where  DATE_FORMAT(`class_date`, '%W') ='$day' ")->result();

        }else{
        $data['conferences'] =  $this->db->query("SELECT meet_classroom.*,classes.class,sections.section,staff.name,staff.surname from meet_classroom join classes on classes.id = meet_classroom.class_id join sections on sections.id = meet_classroom.section_id join staff on staff.id = meet_classroom.teacher_id where teacher_id=$uesrid and DATE_FORMAT(`class_date`, '%W') ='$day'")->result();

        }
        if (empty($_POST)){
            $data['title'] = 'G Meet List';
            $this->load->view('layout/header', $data);
            $this->load->view('admin/conference/gmeet', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $meetid=$this->input->post('meetid');
            if($meetid==''){
            $data_insert = array(
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'teacher_id' => $this->input->post('teacher_id'),
                'url' => $this->input->post('url'),
                'class_date'=>date('Y-m-d',strtotime($this->input->post('date11'))),
                'topic' => $this->input->post('topic'),
                'is_active' => 1 );
               
            $this->db->insert('meet_classroom',$data_insert);
        }else{
            $data_update2 = array(
                'teacher_id' => $this->input->post('teacher_id'),
                'url' => $this->input->post('url'),
                'class_date'=>date('Y-m-d',strtotime($this->input->post('date11'))),
                'topic' => $this->input->post('topic'),
                //'school_id' => $this->school_id,
                'is_active' => 1 );
               
            $this->db->where('id', $meetid);
            $this->db->update('meet_classroom', $data_update2);
        }
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('add_successfully') . '</div>');
            $response = array('status' => 1, 'message' => "Added Successfully");
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
        }
    }

  

    public function gmeet_new()
    {

        $userdata= $this->session->userdata('admin');
   
        $uesrid=$userdata['id']; 
        
        $this->session->set_userdata('top_menu', 'conference');
        $this->session->set_userdata('sub_menu', 'conference/gmeet');
        $data['staffList'] =  $this->db->query("SELECT * from staff where 1 ")->result();
        $data['classes'] =  $this->db->query("SELECT * from classes where 1 ")->result();
        $data['sections'] =  $this->db->query("SELECT * from sections where 1 ")->result();
        
        if($uesrid==1){
            $data['conferences'] =  $this->db->query("SELECT meet_classroom.*,classes.class,sections.section,staff.name,staff.surname from meet_classroom join classes on classes.id = meet_classroom.class_id join sections on sections.id = meet_classroom.section_id join staff on staff.id = meet_classroom.teacher_id where 1 ")->result();

        }else{
        $data['conferences'] =  $this->db->query("SELECT meet_classroom.*,classes.class,sections.section,staff.name,staff.surname from meet_classroom join classes on classes.id = meet_classroom.class_id join sections on sections.id = meet_classroom.section_id join staff on staff.id = meet_classroom.teacher_id where teacher_id=$uesrid ")->result();

        }
        if (empty($_POST)){
            $data['title'] = 'G Meet List';
            $this->load->view('layout/header', $data);
            $this->load->view('admin/conference/gmeet', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $meetid=$this->input->post('meetid');
            if($meetid==''){
            $data_insert = array(
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'teacher_id' => $this->input->post('teacher_id'),
                'url' => $this->input->post('url'),
                'class_date'=>date('Y-m-d',strtotime($this->input->post('date11'))),
                'class_time'=>$this->input->post('time12'),
                'topic' => $this->input->post('topic'),
                'school_id' => $this->school_id,
                'is_active' => 1 );
                $this->db->insert('meet_classroom',$data_insert);
                }else{
                    $data_update = array(
                        //'class_id' => $this->input->post('class_id'),
                        //'section_id' => $this->input->post('section_id'),
                        'teacher_id' => $this->input->post('teacher_id'),
                        'url' => $this->input->post('url'),
                        'class_date'=>date('Y-m-d',strtotime($this->input->post('date11'))),
                        //'class_time'=>$this->input->post('time12'),
                        'topic' => $this->input->post('topic'),
                        'school_id' => $this->school_id,
                        'is_active' => 1 );
                    $this->db->where('id', $meetid);
                    $this->db->update('meet_classroom',  $data_update);
                }
           
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('add_successfully') . '</div>');
            $response = array('status' => 1, 'message' => "Added Successfully");
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
        }
    }


    public function getEditdata()
    {
        $id=$_POST['id'];
        $dt=$this->db->query("SELECT * from meet_classroom where id=$id")->row_array();
      
        $json=array('id'=>$dt['id'],'title'=>$dt['topic'],'url'=>$dt['url'],'teacher_id'=>$dt['teacher_id'],'class_id'=>$dt['class_id'],'section_id'=>$dt['section_id'],
        'date11'=>$dt['class_date'],'time12'=>$dt['class_time']);
       echo json_encode($json);
    }
    

    public function deletegmeet($id) {
        $this->db->where('id',$id);
        $this->db->delete('meet_classroom');
        redirect('admin/Conference/gmeet');
 }
//==============================END CLass=================================
}
