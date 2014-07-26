<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * is_valid_email: verify validity of e-mail addresses - is also used for AJAX calls
     *
     * @param string $email the e-mail address to be validated
     * @return boolean
     *
     */

    public function is_valid_email($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            /*if(checkdnsrr(array_pop(explode("@", $email)), "MX") != false) {
                return true;
            }*/
        }
        return true;
        //return false;
    }

    /**
     *
     * is_valid_password: verify whether password is strict enough
     *
     * @param string $password the password to be validated
     * @return boolean
     *
     */

    public function is_valid_password($password) {
        if (preg_match("/[\$\.\[\]\|\(\)\?\*\+\{\}\@\#\!]/", $password) && (strcspn($password, '0123456789') != strlen($password))) {
            return true;
        }
        return false;
    }

    /**
     *
     * is_valid_username: verify validity of username against regular expression: a-z, A-Z, 0-9, _, - are allowed
     *
     * @param string $username the username to be validated
     * @return boolean
     *
     */

    public function is_valid_username($username) {
        if (preg_match("/^[a-zA-Z0-9_-]+$/", $username)) {
            return true;
        }
        return false;
    }

    /**
     *
     * is_existing_unique_field: check for the existence of a unique field within a database table column
     *
     * @param string $field_value value to be examined
     * @param string $table database table to examine
     * @param string $column_name table column name
     * @return boolean
     *
     */

    public function is_existing_unique_field($value, $info) {

        list($table, $column) = explode('.', $info, 2);

        $this->CI->db->select($column);
        $this->CI->db->from($table);
        $this->CI->db->where($column, strtolower($value));
        $this->CI->db->limit(1);

        $query = $this->CI->db->get();

        if($query->num_rows() == 0) {
            return true;
        }
        return false;
    }

    /**
     *
     * check_captcha: verify the reCaptcha answer
     *
     * @param string $val the input to be validated
     * @return boolean
     *
     */

    function check_captcha($val) {
        if ($this->CI->recaptcha->check_answer($this->CI->input->ip_address(), $this->CI->input->post('recaptcha_challenge_field'), $val)) {
            return true;
        }
        return false;
	}

    /**
     *
     * is_member_password: check for the existence of a unique field within a database table column
     *
     * @param string $password the password to be checked
     * @return boolean
     *
     */

    public function is_member_password($password) {

        $this->CI->db->select('nonce, password');
        $this->CI->db->from('users');
        $this->CI->db->where('username', $this->CI->session->userdata('username'));
        $this->CI->db->limit(1);

        $query = $this->CI->db->get();

        if($query->num_rows() == 1) {
            $this->CI->load->helper('password');
            $row = $query->row();
            if (hash_password($password, $row->nonce) === $row->password) {
                return true;
            }
        }
        return false;
    }
    
    /**
     *
     * is_existing_field: check for the existence of a unique field within a database table column
     *
     * @param string $field_value value to be examined
     * @param string $table database table to examine
     * @param string $column_name table column name
     * @return boolean
     *
     */
    
    public function is_existing_field($value, $info) {
    
    $filedarr = explode('^', $info);
    	list($table, $column) = explode('.', $filedarr[0], 2);
    
		if($column == "section_id")
			$value = (int)$value;
			
    	$this->CI->db->select($column);
    	$this->CI->db->from($table);
    	$this->CI->db->where($column, strtolower($value));
    	if(isset($filedarr[1]) && isset($filedarr[2])){
    		$this->CI->db->where($filedarr[1], $filedarr[2]);
    	}
    	$this->CI->db->limit(1);
		$query = $this->CI->db->get();
    	
    	if($query->num_rows() == 0) {
    		return true;
    	}
    	$this->set_message('is_existing_field', str_replace('_',' ', $column) .' value already exist in database.');
    	return false;
    }
    
	 public function chk_combox_value() {
		if(isset($_POST["value"]) && ($_POST["value"] == 0 || $_POST["value"] == ""))
		{
			$col_name = "";
			if($_POST["columnName"] == "school_year_id")
				$col_name = "Year/Semester";
			if($_POST["columnName"] == "course_id")
				$col_name = "Course Name";
			if($_POST["columnName"] == "primary_teacher_id")
				$col_name = "Primary Teacher";
			if($_POST["columnName"] == "class_room_id")
				$col_name = "Class Room";
			if($_POST["columnName"] == "campus")
				$col_name = "Campus";
				
			$this->set_message('chk_combox_value', $col_name.' must required.');
			return false;
		}
		else		
		{
			return true;
		}	
    }
}