<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_employee_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	
	public function get_company_employee($limit = 0, $offset = 0, $order_by = "username", $sort_order = "asc", $search_data,$count = false) {
    	if (!empty($search_data)) {
    		!empty($search_data['user_id']) ? $data['user_id'] = $search_data['user_id'] : "";
    		!empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";
    		!empty($search_data['elsd_id']) ? $data['elsd_id'] = $search_data['elsd_id'] : "";
    		!empty($search_data['scanner_id']) ? $data['scanner_id'] = $search_data['scanner_id'] : "";
    		!empty($search_data['gender']) ? $data['gender'] = $search_data['gender'] : "";
    		!empty($search_data['email']) ? $data['email'] = $search_data['email'] : "";
    		!empty($search_data['mobile']) ? $data['mobile'] = $search_data['mobile'] : "";
    		!empty($search_data['user_roll_name']) ? $data['user_roll_name'] = $search_data['user_roll_name'] : "";
			!empty($search_data['co_ordinator']) ? $data['co_ordinator'] = $search_data['co_ordinator'] : "";
    		!empty($search_data['campus']) ? $data['campus'] = $search_data['campus'] : "";
    		!empty($search_data['contractor']) ? $data['contractor'] = $search_data['contractor'] : "";
			!empty($search_data['returning']) ? $data['returning'] = $search_data['returning'] : "";
    	}
    	$this->db->select('users.*,user_profile.scanner_id,user_profile.scanner_id,user_profile.co_ordinator,user_profile.contractor,user_profile.returning');
    	$this->db->from('users');
    	$this->db->join('user_profile', 'user_profile.user_id = users.user_id','left');
		$this->db->where_not_in('users.user_roll_id',array('1','3','4'));
		//$this->db->where('users.status',1);
		
		if($this->session->userdata('contractor') > 0){
			$this->db->where('user_profile.contractor',$this->session->userdata('contractor'));
		}
		
    	!empty($data) ? $this->db->like($data) : "";
    	$this->db->order_by($order_by, $sort_order);
		
		if($count == false)
    		$this->db->limit($limit, $offset);
    
    	$query = $this->db->get();
		
		if($count == true)
			return $query->num_rows();
		
    	if($query->num_rows() > 0) {
    		return $query;
    	}
    }
	
}

/* End of file list_user_model.php */
