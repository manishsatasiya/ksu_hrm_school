<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Department_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

	public function get_departmentdata($limit = 0, $offset = 0, $order_by = "department_name", $sort_order = "asc", $search_data,$count = false) {
		if (!empty($search_data)) {
        	!empty($search_data['department_name']) ? $data['department.department_name'] = $search_data['department_name'] : "";
        }
   
        $this->db->select('department.*',FALSE);
        $this->db->from('department');
					
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

        $query = $this->db->get();
		
		if($count == true)
			return $query->num_rows();
			
		if($query->num_rows() > 0) {
            return $query;
        }
    }
	
	public function get_departmentdata_by_id($id){
    	
		$this->db->select('department.*',FALSE);
        $this->db->from('department');
		$this->db->where('department.id', $id);
		
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
}

/* End of file department_model.php */
