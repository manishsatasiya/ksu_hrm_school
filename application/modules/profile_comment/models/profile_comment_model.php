<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_comment_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

   public function get_profile_comment($limit = 0, $offset = 0, $order_by = "username", $sort_order = "asc", $search_data) {
    	if (!empty($search_data)) {
    		!empty($search_data['user_id']) ? $data['user_id'] = $search_data['user_id'] : "";
    		!empty($search_data['staff_name']) ? $data['CONCAT_WS("",trim(users.first_name),trim(users.middle_name),trim(users.middle_name2),trim(users.last_name))'] = str_replace(" ","",trim($search_data['staff_name'])) : "";
    		!empty($search_data['note_type']) ? $data['note_type'] = $search_data['note_type'] : "";
    	}
		
		$user_id = $this->session->userdata('user_id');
		
    	$this->db->select('profile_notes.*,
						  CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) AS staff_name,
						  CONCAT_WS(" ",created_u.first_name,created_u.middle_name,created_u.middle_name2,created_u.last_name) AS created_name,
						  department.department_name,						  
						 ',FALSE);
    	$this->db->from('profile_notes');
		$this->db->join('users', 'users.user_id = profile_notes.user_id','left');
		$this->db->join('users as created_u', 'created_u.user_id = profile_notes.created_by','left');
		$this->db->join('department', 'department.id = profile_notes.department','left');
		if($this->session->userdata('role_id') == '3'){
			$this->db->where('profile_notes.user_id',$user_id);
			$this->db->where('profile_notes.show_to_employee','1');
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
	
	public function get_profile_note($id) {
		$this->db->select('profile_notes.*,
						  CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) AS staff_name,
						  CONCAT_WS(" ",created_u.first_name,created_u.middle_name,created_u.middle_name2,created_u.last_name) AS created_name,
						  department.department_name,						  
						 ',FALSE);
    	$this->db->from('profile_notes');
		$this->db->join('users', 'users.user_id = profile_notes.user_id','left');
		$this->db->join('users as created_u', 'created_u.user_id = profile_notes.created_by','left');
		$this->db->join('department', 'department.id = profile_notes.department','left');
		$this->db->where('profile_notes.id',$id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
}

/* End of file list_user_model.php */
