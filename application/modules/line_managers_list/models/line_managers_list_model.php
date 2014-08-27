<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Line_managers_list_model extends CI_Model {
    
	public function __construct() {
        parent::__construct();
    }
	public function get_line_managers($limit = 0, $offset = 0, $order_by = "username", $sort_order = "asc", $search_data) {
    	if (!empty($search_data)) {
    		!empty($search_data['user_id']) ? $data['user_id'] = $search_data['user_id'] : "";
			!empty($search_data['elsd_id']) ? $data['elsd_id'] = $search_data['elsd_id'] : "";
    		!empty($search_data['staff_name']) ? $data['CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name)'] = $search_data['staff_name'] : "";
    		!empty($search_data['email']) ? $data['email'] = $search_data['email'] : "";
    		!empty($search_data['personal_email']) ? $data['personal_email'] = $search_data['personal_email'] : "";
    		!empty($search_data['user_roll_name']) ? $data['user_roll_name'] = $search_data['user_roll_name'] : "";
    		!empty($search_data['campus_name']) ? $data['campus_name'] = $search_data['campus_name'] : "";
    		!empty($search_data['contractor']) ? $data['contractors.contractor'] = $search_data['contractor'] : "";
			!empty($search_data['nationality']) ? $data['countries.nationality'] = $search_data['nationality'] : "";
    		!empty($search_data['department_name']) ? $data['department.department_name'] = $search_data['department_name'] : "";
    		!empty($search_data['scanner_id']) ? $data['scanner_id'] = $search_data['scanner_id'] : "";
			!empty($search_data['returning']) ? $data['returning'] = $search_data['returning'] : "";
    	}
		
		$user_id = $this->session->userdata('user_id');
		
    	$this->db->select('users.user_id,
						  users.elsd_id,
						  CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) AS staff_name,
						  users.email,
						  users.personal_email,
						  users.cell_phone,
						  user_profile.job_title,
						  user_roll.user_roll_name,
						  school_campus.campus_name,
						  contractors.contractor,
						  countries.nationality,
						  department.department_name,
						  user_profile.scanner_id,
						  IF(user_profile.returning = 1,"Yes","No") AS returning,	
						  users.created_date,
						  users.updated_date
						 ',FALSE);
    	$this->db->from('users');
		$this->db->join('user_profile', 'user_profile.user_id = users.user_id','left');
		$this->db->join('school_campus', 'school_campus.campus_id = users.campus_id','left');
		$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id','left');
		$this->db->join('department', 'department.id = user_profile.department_id','left');
		$this->db->join('contractors', 'contractors.id = user_profile.contractor','left');
		$this->db->join('countries', 'countries.id = user_profile.nationality','left');
		$this->db->where('users.coordinator',$user_id);
		
		
		if($this->session->userdata('role_id') != 1 && ($this->session->userdata('campus_id') > 0 || $this->session->userdata('campus') != ""))
		{
			if($this->session->userdata('campus_id') > 0){
				$this->db->where('(users.campus_id = '.$this->session->userdata('campus_id').' OR users.campus_id = 0)');
			}	
		}
		
		
		
    	!empty($data) ? $this->db->like($data) : "";
		
		if($order_by != "")
			$this->db->order_by($order_by, $sort_order);
		
		if($limit > 0)
			$this->db->limit($limit, $offset);
    
    	$query = $this->db->get();
		//echo $this->db->last_query();	
		
		if($limit == 0)
			return $query->num_rows();
			
    	if($query->num_rows() > 0) {
    		return $query;
    	}
    }
	
	public function set_user_attendance($data) {
		$query = 'INSERT INTO co_ordinator_meetings (user_id,
													attendance,
													date) 
													VALUES ("'.$data['user_id'].'",
															"'.$data['attendance'].'",
															"'.date('Y-m-d H:i:s').'"
															)
					  ON DUPLICATE KEY UPDATE user_id="'.$data['user_id'].'",
					  						attendance="'.$data['attendance'].'",
											date="'.date('Y-m-d H:i:s').'"';
					 
		$this->db->query($query);
		
		//echo $this->db->last_query();
		//exit;
	}
}
/* End of file list_user_model.php */
