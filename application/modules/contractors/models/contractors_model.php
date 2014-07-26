<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contractors_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

	public function get_contractorsdata($limit = 0, $offset = 0, $order_by = "contractor", $sort_order = "asc", $search_data,$count = false) {
		if (!empty($search_data)) {
        	!empty($search_data['contractor']) ? $data['contractors.contractor'] = $search_data['contractor'] : "";
        }
   
        $this->db->select('contractors.*',FALSE);
        $this->db->from('contractors');
					
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
	
	public function get_contractorsdata_by_id($id){
    	
		$this->db->select('contractors.*',FALSE);
        $this->db->from('contractors');
		$this->db->where('contractors.id', $id);
		
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
}

/* End of file contractors_model.php */
