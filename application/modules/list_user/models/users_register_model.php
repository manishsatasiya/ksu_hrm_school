<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_register_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->helper('password');
        }

    /**
     *
     * create_member
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $first_name
     * @param string $student_uni_id
     * @return mixed
     *
     */

    public function create_member($username, $password, $email, $section_id = '0', $first_name, $middle_name, $student_uni_id,$name_suffix, $address1, $address2, $city, $state, $zip, $birth_date, $birth_place, $gender, $language_known, $work_phone, $home_phone, $cell_phone, $created_date, $user_roll_id = 2, $active = 0) {

        $nonce = md5(uniqid(mt_rand(), true));

        $data = array(
            'username' => $username, 
			'password' => hash_password($password, $nonce),
        	'nonce' => $nonce, 
			'email' => $email, 
			'section_id' => $section_id,
			'first_name' => $first_name, 
			'middle_name' => $middle_name, 
			'student_uni_id' => $student_uni_id,
			'name_suffix' => $name_suffix, 
			'address1' => $address1, 
			'address2' => $address2, 
			'city' => $city, 
			'state' => $state, 
			'zip' => $zip, 
			'birth_date' => $birth_date, 
			'birth_place' => $birth_place, 
			'gender' => $gender, 
			'language_known' => $language_known, 
			'work_phone' => $work_phone, 
			'home_phone' => $home_phone, 
			'cell_phone' => $cell_phone, 
			'created_date' => $created_date, 
			'user_roll_id' => $user_roll_id, 
			'active' => '1'
        );
		
       // $this->db->set('date_registered', 'NOW()', FALSE);
        //$this->db->set('last_login', 'NOW()', FALSE);
        $this->db->insert('users', $data);
        $lastinsertid = $this->db->insert_id();
        if ($this->db->affected_rows() == 1) {
            return $lastinsertid;
        }
        return false;
    }

    function create_teacher_username_pwd(){
    	 
    	$this->db->select('*');
    	$this->db->from('users');
    	$this->db->where('users.user_roll_id != ','1');
    	//$this->db->where('users.password','');
    	$query = $this->db->get();
    	
    	if($query->num_rows() > 0) {
    		foreach ($query->result() as $users){
    			$nonce = md5(uniqid(mt_rand(), true));
    			$data = array(
    					'password'    		=> hash_password('user12345', $nonce),
    					'nonce'				=> $nonce);
    			 
    			$this->db->where('user_id', $users->user_id);
    			$this->db->update('users', $data);
    			
    		}
    
    	}
    }
    
    function create_student_username_pwd(){
    
    	$this->db->select('*, user_roll.user_roll_name as role_name');
    	$this->db->from('users');
    	$this->db->where('users.user_roll_id','4');
    	$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id');
    	$query = $this->db->get();
    
    	if($query->num_rows() > 0) {
    		foreach ($query->result() as $users){
    			$nonce = md5(uniqid(mt_rand(), true));
    			$data = array(
    					'username'    		=> $users->student_uni_id,
    					'password'    		=> hash_password($users->student_uni_id, $nonce),
    					'nonce'				=> $nonce);
    
    			$this->db->where('user_id', $users->user_id);
    			$this->db->update('users', $data);
    			
    		}
    
    	}
    }

}

/* End of file user_register_model.php */
/* Location: ./application/models/membership/user_register_model.php */