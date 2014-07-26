<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Get_pdf_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	

    public function get_campus_pdf($campus_id=0) {

        $this->db->select('*');
        $this->db->from('school_campus');        
		
		if($campus_id > 0)
			$this->db->where('campus_id',$campus_id);

        $query = $this->db->get();
        
        if($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
	
	 public function get_course_class_section($campus_id) 
	 {
        $this->db->select('course_section.*');
        $this->db->from('course_section');
		$this->db->join('course_class', 'course_class.section_id = course_section.section_id','left');
		$this->db->join('users', 'users.user_id = course_class.primary_teacher_id','left');
		$this->db->where('course_class.is_active = "Y"');
		
		if($this->session->userdata('role_id') > 4 && ($this->session->userdata('campus_id') > 0) || $this->session->userdata('campus') != "")
		{
			if($this->session->userdata('campus_id') > 0)
				$this->db->where('users.campus_id',$this->session->userdata('campus_id'));
			else if($this->session->userdata('campus') != "")
				$this->db->where('users.campus',$this->session->userdata('campus'));	
		}
		
		if($campus_id > 0)
		{
			$this->db->where('users.campus_id',$campus_id);
		}
		$this->db->order_by('section_title', 'ASC');	
		$query = $this->db->get();
        
        if($query->num_rows() > 0) {
            return $query;
        }
    }
	
	public function get_enable_school_time() {
		$this->db->select('*');
		$this->db->from('enable_school_time');
		return $this->db->get();
	}
	
	public function get_activation_time(){
		$this->db->select('*');
		$this->db->from('attendance_week_activation_time');
		$data_attendance_week_activation_time = $this->db->get();
		return $data_attendance_week_activation_time->result_array(); 
	}
	
	public function get_enableweek($activate_time){
		$this->db->select('week_id,last_date,no_of_day');
		$this->db->from('enable_school_week');
		$this->db->where('CONCAT(last_date," '.$activate_time.'") <= NOW()');
		$this->db->order_by('week_id', 'DESC');
		$this->db->limit(1);
		$data_enableweek = $this->db->get();
		return $data_enableweek->result();
	}
}

/* End of file list_course_model.php */