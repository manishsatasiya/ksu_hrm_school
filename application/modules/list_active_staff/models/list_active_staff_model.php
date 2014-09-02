<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_active_staff_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    } 
	
	public function get_profile_note($id) {
		$this->db->select('profile_notes.*');
    	$this->db->from('profile_notes');
		$this->db->where('id',$id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
}

/* End of file list_user_model.php */
