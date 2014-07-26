<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contracted_numbers_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

	public function get_contracted_numbers($limit = 0, $offset = 0, $order_by = "contractor_id", $sort_order = "asc", $search_data,$count = false) {
		if (!empty($search_data)) {
        	!empty($search_data['user_roll_name']) ? $data['user_roll_name'] = $search_data['user_roll_name'] : "";
        }
   
        $this->db->select('contracted_numbers.*,user_roll.user_roll_name,school_campus.campus_name,contractors.contractor as contractor_name',FALSE);
        $this->db->from('contracted_numbers');
		$this->db->join('user_roll','user_roll.user_roll_id = contracted_numbers.user_roll_id','left');
		$this->db->join('contractors','contractors.id = contracted_numbers.contractor_id','left');
		$this->db->join('school_campus','school_campus.campus_id = contracted_numbers.campus_id','left');  
		
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
	
	public function get_contracted_numbersdata_by_id($id){
    	
		$this->db->select('contracted_numbers.*',FALSE);
        $this->db->from('contracted_numbers');
		$this->db->where('contracted_numbers.id', $id);
		
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
}

/* End of file contracted_numbers_model.php */
