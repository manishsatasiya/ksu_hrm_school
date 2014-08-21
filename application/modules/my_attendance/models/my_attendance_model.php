<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class My_attendance_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

	public function get_my_attendance($limit = 0, $offset = 0, $order_by = "LogDate", $sort_order = "desc", $search_data,$count = false) {
		if (!empty($search_data)) {
        	!empty($search_data['dwMachineNumber']) ? $data['dwMachineNumber'] = $search_data['dwMachineNumber'] : "";
        }
   
        $user_id = $this->session->userdata('user_id');
		$user_role = $this->session->userdata('role_id');
		
        $this->db->select('teacher_attendance.*',FALSE);
        $this->db->from('teacher_attendance');
					
        if(!empty($data))
        {
        	$str_data_or_like = "";
        	foreach($data AS $data_key=>$data_val)
        	{
        		$str_data_or_like .= " $data_key LIKE '%$data_val%' OR ";	
        	}
        	$str_data_or_like = trim(trim($str_data_or_like),"OR");
        	
        	if($str_data_or_like != "")
        		$this->db->where("(".$str_data_or_like.")", null, false);
        }
		
        $this->db->order_by($order_by, $sort_order);
		if($count == false)
        	$this->db->limit($limit, $offset);

        if($user_role != '1' && $user_role != '4'){
		
			$this->db->where('teacher_attendance.user_id',$user_id);        
		}
			
        $query = $this->db->get();
		
		if($count == true)
			return $query->num_rows();
			
		if($query->num_rows() > 0) {
            return $query;
        }
    }
}

/* End of file department_model.php */
