<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Questionnaire_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

	public function check_submitted($user_id) {
		
        $this->db->select('user_profile.academic_returning',FALSE);
        $this->db->from('user_profile');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
	//	echo $this->db->last_query();
		if($query->num_rows() > 0) {
    		return $query->row();
    	}
		return false;
    }

}

/* End of file contractors_model.php */
