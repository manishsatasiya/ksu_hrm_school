<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	
	public function get_appointments($user_id){
		$school_setting = get_school_setting();
		$school_start_date = $school_setting->school_start_date;
		
		$this->db->select('appointments.subject as title,appointments.date as event_day,appointments.appointment_id as id,time,type',FALSE);
		$this->db->from('appointments');
		$this->db->where("user_id", $user_id);
		$this->db->where("date > ".date("Y-m-d",strtotime($school_start_date)));
		$this->db->order_by('date','asc');
		
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
        }
		return $data;
	}
	
	public function get_holidays(){
		
		$this->db->select('holidays.name as title,holidays.start_date as event_day,id',FALSE);
		$this->db->from('holidays');
		$this->db->where("start_date > ".date("Y-m-d"));
		$this->db->order_by('days','desc');
		$this->db->order_by('start_date','asc');
		
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
        }
		return $data;
	}
	
	public function get_holidays_end(){
		
		$this->db->select('holidays.name as title,holidays.end_date as event_day,id',FALSE);
		$this->db->from('holidays');
		$this->db->where("end_date > ".date("Y-m-d"));
		$this->db->order_by('days','desc');
		$this->db->order_by('end_date','asc');
		
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
        }
		return $data;
	}
	
	public function get_workshops($user_id){
		
		$this->db->select('workshops.title,workshops.start_date as event_day,workshops.time,workshops.venue,workshops.workshop_id as id',FALSE);
		$this->db->from('user_workshop');
		$this->db->join('workshops','user_workshop.workshop_id = workshops.workshop_id','left');
		$this->db->where("start_date > ".date("Y-m-d"));
		$this->db->where("workshops.presented", 2);
		$this->db->where('user_workshop.attendee',$user_id);
				
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
        }
		return $data;
	}
	
	public function get_user_workshops($user_id){
		
		$this->db->select('workshops.title,workshops.start_date as event_day,workshops.time,workshops.venue,workshops.workshop_id,workshops.workshop_type_id',FALSE);
		$this->db->from('user_workshop');
		$this->db->join('workshops','user_workshop.workshop_id = workshops.workshop_id','left');
		$this->db->where("start_date > ".date("Y-m-d"));
		$this->db->where("workshops.presented", 2);
		$this->db->where('user_workshop.attendee',$user_id);
				
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
        }
		return $data;
	}
	
	public function get_visa($user_id){		
		$this->db->select('visas.visa_type as title,visas.visa_expiry_date as event_day',FALSE);
		$this->db->from('visas');
		$this->db->where("user_id", $user_id);
		
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
        }
		return $data;
	}
	
	public function get_passport($user_id){		
		$this->db->select('passports.type as title,passports.passport_expiry_date as event_day',FALSE);
		$this->db->from('passports');
		$this->db->where("user_id", $user_id);
		
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
        }
		return $data;
	}
	
	public function next_appointments($user_id){
		
		$this->db->select('appointments.details as title,appointments.date as event_day,appointments.appointment_id as id,time,type',FALSE);
		$this->db->from('appointments');
		$this->db->where("user_id", $user_id);
		$this->db->where("date > ".date("Y-m-d"));
		$this->db->order_by('date','asc');
		$this->db->limit(7,0);
		
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row){
				$data[] = $row;
			}
        }
		return $data;
	}
	
}

/* End of file contractors_model.php */
