<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workshops_staff_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_workshop($limit = 0, $offset = 0, $order_by = "workshop_id,title", $sort_order = "asc",$count = false) {
		$user_id = $this->session->userdata('user_id');
   
        $this->db->select('workshops.*,CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) AS presenter_name,workshop_types.type as workshop_type',FALSE);
        $this->db->from('workshops');  
        $this->db->join('users','users.user_id = workshops.presenter','left');
		$this->db->join('workshop_types','workshop_types.id = workshops.workshop_type_id','left');
       
		$this->db->where('workshops.status',1);	
		$this->db->where('presented',2);
		
        $this->db->order_by($order_by, $sort_order);
		//if($count == false)
        	//$this->db->limit($limit, $offset);

        $query = $this->db->get();
		//echo $this->db->last_query();
		if($count == true)
			return $query->num_rows();
			
		if($query->num_rows() > 0) {
            return $query;
        }
    }
	
}