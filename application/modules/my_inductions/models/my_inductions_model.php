<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class My_inductions_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

	public function get_my_inductions($user_unique_id=0) {
		$user_id = $this->session->userdata('user_id');
		$user_role = $this->session->userdata('role_id');
		
        $this->db->select('induction.*',FALSE);
        $this->db->from('induction');
					
        if($user_role != '1' && $user_role != '4'){
		
			$this->db->where('induction.user_id',$user_id);        
		}
			
		if($user_unique_id > 0)
		{
			$this->db->where('induction.user_id',$user_unique_id);
		}	
        $query = $this->db->get();
		
		if($query->num_rows() > 0) {
            return $query;
        }
    }
}

/* End of file department_model.php */
