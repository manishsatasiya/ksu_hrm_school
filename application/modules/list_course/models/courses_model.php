<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Courses_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        	$this->load->helper('password');
        }

    /**
     *
     * create_courses_subject
     *
     * @param string $school_id
     * @param string $school_year_id
     * @param string $subject_title
     * @param string $subject_code
     * @return mixed
     *
     */

    public function create_courses_subject($school_id, $school_year_id, $subject_title, $subject_code) {

        $nonce = md5(uniqid(mt_rand(), true));

        $data = array(
            'school_id' => $school_id, 
			'school_year_id' => $school_year_id,
        	'subject_title' => $subject_title, 
			'subject_code' => $subject_code
        );
		
        //$this->db->set('created_date', 'NOW()', FALSE);
        //$this->db->set('last_login', 'NOW()', FALSE);
        $this->db->insert('courses_subject', $data);
        $lastinsertid = $this->db->insert_id();
        if ($this->db->affected_rows() == 1) {
            return $lastinsertid;
        }
        return false;
    }
		
	public function delete_data($table,$where,$id) {
        $this->db->where($where, $id);
        $this->db->delete($table);
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }	
    /**
     *
     * create_course_category
     *
     * @param string $category_title
     * @return mixed
     *
     */
	public function create_course_category($category_title) {

        $nonce = md5(uniqid(mt_rand(), true));

        $data = array(
            'category_title' => $category_title
        );
		
        $this->db->insert('course_category', $data);
        $lastinsertid = $this->db->insert_id();
        if ($this->db->affected_rows() == 1) {
            return $lastinsertid;
        }
        return false;
    }
	
    /**
     *
     * create_course
     *
     * @param string $course_title
     * @param string $max_hours
     * @return mixed
     *
     */
	public function create_course($course_title,$max_hours) {

        $nonce = md5(uniqid(mt_rand(), true));

        $data = array(
			'course_title' => $course_title,								
			'max_hours' => $max_hours								
        );
		
        $this->db->insert('courses', $data);
        $lastinsertid = $this->db->insert_id();
        if ($this->db->affected_rows() == 1) {
            return $lastinsertid;
        }
        return false;
    }
	
    /**
     *
     * create_course_class
     *
     * @param string $course_id
     * @param string $category_id
     * @param string $school_year_id
     * @param string $primary_teacher_id
     * @param string $secondary_teacher_id
     * @param string $class_room_id
     * @param string $section_id
     * @param string $start_time
     * @param string $end_time
     * @param string $shift
     * @param string $restricted_hours
     * @return mixed
     *
     */
	public function create_course_class($course_id, $category_id, $school_year_id, $primary_teacher_id,$secondary_teacher_id,$class_room_id,$section_id,$start_time,$end_time,$shift,$restricted_hours) {

        $nonce = md5(uniqid(mt_rand(), true));

        $data = array(
            'course_id' => $course_id, 
			'category_id' => $category_id,
			'school_year_id' => $school_year_id,
			'primary_teacher_id' => $primary_teacher_id,
			'secondary_teacher_id' => $secondary_teacher_id,
			'class_room_id' => $class_room_id,
			'section_id' => $section_id,
			'shift' => $shift,
			'restricted_hours' => $restricted_hours								
        );
		
        $this->db->insert('course_class', $data);
        $lastinsertid = $this->db->insert_id();
        if ($this->db->affected_rows() == 1) {
            return $lastinsertid;
        }
        return false;
    }
	
    /**
     *
     * create_section
     *
     * @param string $section_title
     * @return mixed
     *
     */
	public function create_section($section_title) {

        $nonce = md5(uniqid(mt_rand(), true));

        $data = array(
            'section_title' => $section_title
        );
		
         $this->db->insert('course_section', $data);
         $lastinsertid = $this->db->insert_id();
        if ($this->db->affected_rows() == 1) {
            return $lastinsertid;
        }
        return false;
    }
    
    /**
     *
     * create_class_section
     *
     * @param string $section_title
     * @return mixed
     *
     */
    public function create_class_section($section_title) {
    
    	$nonce = md5(uniqid(mt_rand(), true));
    
    	$data = array(
    			'section_title' => $section_title
    	);
    
    	$this->db->insert('course_section', $data);
    	$lastinsertid = $this->db->insert_id();
    	if ($this->db->affected_rows() == 1) {
    		return $lastinsertid;
    	}
    	return false;
    }
	
    /**
     *
     * update_section
     *
     * @param string $section_id
     * @param string $section_title
     * @return mixed
     *
     */
	public function update_section($section_id,$section_title) {
        $data = array('section_title' => $section_title);

       
        $this->db->where('section_id', $section_id);
        $this->db->update('course_section', $data);

        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }
	
	public function get_section($limit = 0, $offset = 0, $order_by = "section_title", $sort_order = "asc", $search_data,$dropdown=0,$campus_id=0) {
        $fields = $this->db->list_fields('course_section');
        
        if (!empty($search_data)) {
            !empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
            !empty($search_data['ca_lead_teacher']) ? $data['ca_lead.first_name'] = $search_data['ca_lead_teacher'] : "";
            !empty($search_data['campus_name']) ? $data['campus_name'] = $search_data['campus_name'] : "";
        }
        $this->db->select('course_section.*,ca_lead.first_name AS lead_name,campus_name',FALSE);
        $this->db->from('course_section');        
        $this->db->join('school_campus','school_campus.campus_id = camps_id','left');  
        $this->db->join('users AS ca_lead','course_section.ca_lead_teacher = ca_lead.user_id','left');  
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by($order_by, $sort_order);
        $this->db->limit($limit, $offset);

        if($this->session->userdata('ca_lead_teacher') > 0)
		{
			$this->db->where('course_section.ca_lead_teacher',$this->session->userdata('ca_lead_teacher'));
		}
		
		if($this->session->userdata('role_id') > 4 && $this->session->userdata('ca_lead_teacher') == 0 && ($this->session->userdata('campus_id') > 0 || $this->session->userdata('campus') != ""))
		{
			$this->db->join('course_class','course_class.section_id = course_section.section_id','left');  
			$this->db->join('users','course_class.primary_teacher_id = users.user_id','left');  
			
			if($this->session->userdata('campus_id') > 0)
				$this->db->where('course_section.camps_id',$this->session->userdata('campus_id'));
			else if($this->session->userdata('campus') != "")
				$this->db->where('users.campus',$this->session->userdata('campus'));	
				
			$this->db->group_by(array("course_section.section_id"));	
		}
		
		if($campus_id > 0)
		{
			$this->db->where('school_campus.campus_id',$campus_id);	
		}
		$query = $this->db->get();
        
		if($dropdown == 1)
		{
			$dropdown_data = $query->result_array();
			$dropdown_arr = array();
			$dropdown_arr[0] = '--Select--';
			foreach ($dropdown_data as $dropdown_datas){
				$dropdown_arr[$dropdown_datas['section_id']."j"] = $dropdown_datas['section_title'];    		
			}
			return $dropdown_arr;
		}
		else
		{
			if($query->num_rows() > 0) {
				return $query;
			}
		}
    }
	
    /**
     *
     * create_class_room
     *
     * @param string $class_room_title
     * @return mixed
     *
     */
	public function create_class_room($class_room_title) {

        $nonce = md5(uniqid(mt_rand(), true));

        $data = array(
            'class_room_title' => $class_room_title
        );
		
         $this->db->insert('course_class_room', $data);
         $lastinsertid = $this->db->insert_id();
        if ($this->db->affected_rows() == 1) {
            return $lastinsertid;
        }
        return false;
    }
	
    /**
     *
     * create_class_room
     *
     * @param string $class_room_id
     * @param string $class_room_title
     * @return mixed
     *
     */
	public function update_class_room($class_room_id,$class_room_title) {
        $data = array('class_room_title' => $class_room_title);

       
        $this->db->where('class_room_id', $class_room_id);
        $this->db->update('course_class_room', $data);

        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }
	
	public function get_class_room($limit = 0, $offset = 0, $order_by = "class_room_title", $sort_order = "asc", $search_data,$dropdown=1,$campus_id=0) {
        $fields = $this->db->list_fields('course_class_room');
        if (!in_array($order_by, $fields)) return array();
        if (!empty($search_data)) {
            !empty($search_data['class_room_title']) ? $data['class_room_title'] = $search_data['class_room_title'] : "";
        }
        $this->db->select('*');
        $this->db->from('course_class_room');        
        $this->db->join('school_campus','school_campus.campus_id = camps_id','left');  
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by($order_by, $sort_order);
        $this->db->limit($limit, $offset);

		if($this->session->userdata('role_id') > 4 && ($this->session->userdata('campus_id') > 0 || $this->session->userdata('campus') != ""))
		{
			$this->db->join('course_class','course_class.class_room_id = course_class_room.class_room_id','left');  
			$this->db->join('users','course_class.primary_teacher_id = users.user_id','left');  
			
			if($this->session->userdata('campus_id') > 0)
				$this->db->where('users.campus_id',$this->session->userdata('campus_id'));
			else if($this->session->userdata('campus') != "")
				$this->db->where('users.campus',$this->session->userdata('campus'));	
		}
		
		if($campus_id > 0)
		{
			$this->db->where('school_campus.campus_id',$campus_id);	
		}
		
        $query = $this->db->get();
        
		if($dropdown == 1)
		{
			$dropdown_data = $query->result_array();
			$dropdown_arr = array();
			$dropdown_arr[0] = '--Select--';
			foreach ($dropdown_data as $dropdown_datas){
				$dropdown_arr[$dropdown_datas['class_room_id']."j"] = $dropdown_datas['class_room_title'];    		
			}
			return $dropdown_arr;
		}
		else
		{
			if($query->num_rows() > 0) {
				return $query;
			}
		}	
    }
	
	public function count_all_search_section($search_data) {
        $data = array();
        !empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";       

        $this->db->select('*');
        $this->db->from('course_section');
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by("section_id", "asc");
        return $this->db->count_all_results();
    }
	
	public function count_all_search_class_room($search_data) {
        $data = array();
        !empty($search_data['class_room_title']) ? $data['class_room_title'] = $search_data['class_room_title'] : "";       

        $this->db->select('*');
        $this->db->from('course_class_room');
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by("class_room_id", "asc");
        return $this->db->count_all_results();
    }
    
    public function get_school_week($school_year_id) {
    	$this->db->select('*');
    	$this->db->from('school_year');
    	$this->db->where('school_year_id',$school_year_id);
    	$query = $this->db->get();
    	 
    	if($query->num_rows() > 0) {
    		return $query;
    	}
    }
    
   
}

/* End of file courses_model.php */