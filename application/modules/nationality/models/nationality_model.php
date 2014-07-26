<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nationality_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

	public function get_nationalitydata($limit = 0, $offset = 0, $order_by = "nationality", $sort_order = "asc", $search_data,$count = false) {
		if (!empty($search_data)) {
        	!empty($search_data['nationality']) ? $data['nationality'] = $search_data['nationality'] : "";
			!empty($search_data['native']) ? $data['native'] = $search_data['native'] : "";
        }
   
        $this->db->select('countries.*',FALSE);
        $this->db->from('countries');
		
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
	
	public function get_nationalitydata_by_id($id){
    	
		$this->db->select('countries.*',FALSE);
        $this->db->from('countries');
		$this->db->where('countries.id', $id);
		
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
}

/* End of file nationality_model.php */
