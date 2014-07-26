<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->output->enable_profiler(FALSE);
    }

    /**
     *
     * get_profile: get the member pages
     *
     * @return array
     *
     */

    public function get_profile() {
        $this->db->select('first_name, last_name, email, address1, city, state, zip, DATE_FORMAT(birth_date,"%m/%d/%Y") AS birth_date, language_known, work_phone, profile_picture',FALSE);
        $this->db->from('users');
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->limit(1);
        
        $query = $this->db->get();

        if($query->num_rows() == 1) {
            $row = $query->row();
            $curr_dir = str_replace("\\","/",getcwd()).'/';
            $filepath = $curr_dir.'images/profile_picture/thumb7575/'.$row->profile_picture;
            
            if( file_exists($filepath) && $row->profile_picture != ''){
            	$picture = base_url()."images/profile_picture/thumb7575/".$row->profile_picture;
            }else{
            	$picture = base_url()."images/noimage.jpg";
            }
            
            $array['first_name'] = $row->first_name;
            $array['last_name'] = $row->last_name;
            $array['email'] = $row->email;
            $array['address1'] = $row->address1;
            $array['city'] = $row->city;
            $array['state'] = $row->state;
            $array['zip'] = $row->zip;
            $array['birth_date'] = $row->birth_date;
            $array['language_known'] = $row->language_known;
            $array['work_phone'] = $row->work_phone;
            $array['profile_picture'] = $picture;
            return $array;
        }
        return array();
    }

    /**
     *
     * set_profile: update pages
     *
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @return boolean
     *
     */
    public function set_profile($first_name, $last_name, $email,$address1, $city, $state, $zip, $birth_date, $language_known, $work_phone){
        $data = array(
               	'first_name' => $first_name,
               	'last_name' => $last_name,
        		'email' => $email,
        		'address1' => $address1,
        		'city' => $city,
        		'state' => $state,
        		'zip' => $zip,
        		'birth_date' => date('Y-m-d', strtotime($birth_date)),
        		'language_known' => $language_known,
        		'work_phone' => $work_phone,
            );
        if (!empty($email)) {
            $data['email'] = $email;
        }
        
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->update('users', $data);

        if ($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * set_password: update member password
     *
     * @param string $password
     * @return boolean
     *
     */

    public function set_password($password) {
        $this->load->helper('password');
        $new_nonce = md5(uniqid(mt_rand(), true));
        $data = array(
               'password' => hash_password($password, $new_nonce),
               'nonce' => $new_nonce
            );

        $this->db->where('username', $this->session->userdata('username'));
        $this->db->update('users', $data);

        if ($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }
	
	/**
     *
     * get_teacher_class_info: get teacher cource detail
     *
     * @param none
     * @return array
     *
     */
    public function get_teacher_class_info(){
    	$usr_role = $this->session->userdata('role_id');
    	$user_id = $this->session->userdata('user_id');
    	
    	$temparr = array();
    	$this->db->select('*');
        $this->db->from('course_class');
        $this->db->join('course_section','course_section.section_id = course_class.section_id','left');
        $this->db->join('courses','course_class.course_id = courses.course_id','left');
        $this->db->join('course_class_room','course_class.class_room_id = course_class_room.class_room_id','left');
        $this->db->where('primary_teacher_id', $user_id);
        $this->db->where('is_active = 1');
        $query = $this->db->get();
        
        if($query->num_rows() > 0) {
        	foreach ($query->result() as $data){
        		
        		$this->db->select('*');
        		$this->db->from('users');
        		$this->db->where('section_id', $data->section_id);
        		$student = $this->db->get();
        		
        		$temparr[] = array(
        					'course_name'=>$data->course_title,
        					'section'=>$data->section_title,
        					'class_room'=>$data->class_room_title,
        					'shift'=>$data->shift,
        					'no_of_student'=>$student->num_rows()
        				);
        	}	
        	return $temparr;
        }
    }
}

/* End of file profile_model.php */