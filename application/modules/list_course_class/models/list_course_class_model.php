<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_course_class_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_course_class: get the course class data
     *
     * @param int $limit db limit (members per page)
     * @param int $offset db offset (current page)
     * @param int $order_by db sort order
     * @param string $sort_order asc or desc
     * @param array $search_data search input
     * @return mixed
     *
     */

    public function get_course_class($limit = 0, $offset = 0, $order_by = "course_class.course_class_id", $sort_order = "asc", $search_data) {
		$arrCampusPrivilages = get_user_campus_privilages();
        if (!empty($search_data)) {
        	
        	!empty($search_data['shift']) ? $data['shift'] = $search_data['shift'] : "";
        	!empty($search_data['school_year_title']) ? $data['school_year_title'] = $search_data['school_year_title'] : "";
            !empty($search_data['category_title']) ? $data['category_title'] = $search_data['category_title'] : "";
            !empty($search_data['users.first_name']) ? $data['users.first_name'] = $search_data['users.first_name'] : "";
            !empty($search_data['second_name']) ? $data['users_sec.first_name'] = $search_data['second_name'] : "";
            !empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
            !empty($search_data['class_room_title']) ? $data['class_room_title'] = $search_data['class_room_title'] : "";
            !empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
            !empty($search_data['campus_name']) ? $data['campus_name'] = $search_data['campus_name'] : "";
			!empty($search_data['track']) ? $data['track'] = $search_data['track'] : "";
			!empty($search_data['buildings']) ? $data['buildings'] = $search_data['buildings'] : "";
        }
       
        $this->db->select('*,CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) AS first_staff_name,
							CONCAT_WS(" ",users_sec.first_name,users_sec.middle_name,users_sec.middle_name2,users_sec.last_name) AS second_staff_name,
							course_section.*,courses.course_title,course_class.shift AS courses_shift,course_class_room.class_room_title,users.first_name,
							users_sec.first_name AS second_name,school.school_name,school_year.school_year_title,course_category.category_title,
							(SELECT COUNT(*) FROM users WHERE users.section_id=course_class.section_id) AS student_cnt,campus_name',false);
        $this->db->from('course_class');   
		$this->db->join('courses', 'courses.course_id = course_class.course_id','left');      
		$this->db->join('course_section', 'course_section.section_id = course_class.section_id','left');
		$this->db->join('course_category', 'course_category.category_id = course_class.category_id','left');
		$this->db->join('course_class_room', 'course_class_room.class_room_id = course_class.class_room_id','left'); 
		$this->db->join('users', 'users.user_id = course_class.primary_teacher_id','left');
		$this->db->join('school_campus','school_campus.campus_id = course_class.camps_id','left');  
		$this->db->join('users AS users_sec', 'users_sec.user_id = course_class.secondary_teacher_id','left');
		$this->db->join('school', 'school.school_id = course_class.school_id','left');
		$this->db->join('school_year', 'school_year.school_year_id = course_class.school_year_id','left');
		$this->db->where('is_active = 1');
		
		if($this->session->userdata('ca_lead_teacher') > 0)
		{
			$this->db->where('course_section.ca_lead_teacher',$this->session->userdata('ca_lead_teacher'));
		}
		
		if(count($arrCampusPrivilages) > 0)
		{	
			$this->db->where_in('course_class.camps_id',$arrCampusPrivilages);
			$this->db->group_by(array("course_class.course_class_id"));	
		}
		
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
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        
        if($query->num_rows() > 0) {
            return $query;
        }
    }

    /**
     *
     * count_all_course_class: count all course class in the table
     *
     *
     */
    
    public function count_all_course_class()
    {
    	$this->db->from('course_class');
    	$this->db->where('is_active = 1');
        return $this->db->count_all_results();
    }

    /**
     *
     * update_course_class: update course class data
     *
     * @param int $id the member id
     * @param string $username the member username
     * @param string $email the member e-mail address
     * @param string $first_name the member first name
     * @param string $last_name the member last name
     * @param bool $change_username do we want to change the username?
     * @param bool $change_email do we want to change the user e-mail?
     * @return mixed
     *
     */

    public function update_course_class($course_class_id, $course_id, $category_id, $school_year_id, $school_id,$primary_teacher_id,$secondary_teacher_id,$class_room_id,$section_id,$shift,$total_seats,$registered_student,$credits,$restricted_hours) {
        // if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.

        $data = array(                
                'course_id'    			=> $course_id,
				'category_id'    		=> $category_id,
				'school_year_id'    	=> $school_year_id,
				'school_id'				=> $school_id,
				'primary_teacher_id'	=> $primary_teacher_id,
				'secondary_teacher_id'	=> $secondary_teacher_id,
        		'class_room_id'			=> $class_room_id,
				'section_id'			=> $section_id,
				'shift'					=> $shift,
				'total_seats'			=> $total_seats,
				'registered_student'	=> $registered_student,
				'credits'				=> $credits,
				'restricted_hours'		=> $restricted_hours);

      
        $this->db->where('course_class_id', $course_class_id);
        $this->db->update('course_class', $data);
        
        $data2 = array(
        		'teacher_id'	=> $primary_teacher_id);
        $this->db->where('course_class_id', $course_class_id);
        $this->db->update('attendance_report', $data2);
        
        $data3 = array( 'course_id'    			=> $course_id,
						'category_id'    		=> $category_id,
						'school_year_id'    	=> $school_year_id,
						'school_id'				=> $school_id,
						'primary_teacher_id'	=> $primary_teacher_id,
						'secondary_teacher_id'	=> $secondary_teacher_id,
		        		'class_room_id'			=> $class_room_id,
						'section_id'			=> $section_id,
						'shift'					=> $shift);
        $this->db->where('course_class_id', $course_class_id);
        $this->db->update('late_attendance', $data3);
        
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * delete_course_class: count all course class in the table
     *
     * @param int $id the member id
     * @return boolean
     *
     */


    public function delete_course_class($course_class_id) {
        $this->db->where('course_class_id', $course_class_id);
        $this->db->delete('course_class');
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * get_classroom_by_id: return class_room_id by id
     *
     * @param int $id the member id
     * @return mixed
     *
     */

    public function get_classroom_by_id($course_class_id) {
        $this->db->select('class_room_id')->from('course_class')->where('course_class_id', $course_class_id);
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            $row = $query->row();
            return $row->class_room_id;
        }
        return "";
    }


    /**
     *
     * count_all_search_course_class: count all course class when performing search
     *
     * @param string $username the member username
     * @param string $first_name the member first name
     * @param string $last_name the member last name
     * @param string $email the member e-mail address 
     * @return mixed
     *
     */

    public function count_all_search_course_class($search_data) {
        $data = array();
        !empty($search_data['classroom']) ? $data['classroom'] = $search_data['classroom'] : "";
        !empty($search_data['section']) ? $data['section'] = $search_data['section'] : "";        

        $this->db->select('*');
        $this->db->from('course_class');
		$this->db->join('courses', 'courses.course_id = course_class.course_id','left');      
               
		$this->db->join('school', 'school.school_id = course_class.school_id','left');
		$this->db->join('school_year', 'school_year.school_year_id = course_class.school_year_id','left');
		$this->db->where('is_active = 1');
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by("course_class.course_class_id", "asc");
        return $this->db->count_all_results();
    }
    
    public function count_all_search_course_class_grid($search_data) {
    	$arrCampusPrivilages = get_user_campus_privilages();
    	if (!empty($search_data)) {
        	
        	!empty($search_data['shift']) ? $data['shift'] = $search_data['shift'] : "";
        	!empty($search_data['school_year_title']) ? $data['school_year_title'] = $search_data['school_year_title'] : "";
            !empty($search_data['category_title']) ? $data['category_title'] = $search_data['category_title'] : "";
            !empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";
            !empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
            !empty($search_data['class_room_title']) ? $data['class_room_title'] = $search_data['class_room_title'] : "";
            !empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
			!empty($search_data['campus_name']) ? $data['campus_name'] = $search_data['campus_name'] : "";
        }
       
        $this->db->select('*,course_section.*,courses.course_title,course_class_room.class_room_title,users.first_name,school.school_name,school_year.school_year_title,course_category.category_title');
        $this->db->from('course_class');   
		$this->db->join('courses', 'courses.course_id = course_class.course_id','left');      
		$this->db->join('course_section', 'course_section.section_id = course_class.section_id','left');
		$this->db->join('course_category', 'course_category.category_id = course_class.category_id','left');
		$this->db->join('course_class_room', 'course_class_room.class_room_id = course_class.class_room_id','left'); 
		$this->db->join('users', 'users.user_id = course_class.primary_teacher_id','left');
		$this->db->join('school_campus','school_campus.campus_id = course_class.camps_id','left');  
		$this->db->join('school', 'school.school_id = course_class.school_id','left');
		$this->db->join('school_year', 'school_year.school_year_id = course_class.school_year_id','left');
		$this->db->where('is_active = 1');
		
		if($this->session->userdata('ca_lead_teacher') > 0)
		{
			$this->db->where('course_section.ca_lead_teacher',$this->session->userdata('ca_lead_teacher'));
		}
		
		if(count($arrCampusPrivilages) > 0)
		{	
			$this->db->where_in('course_class.camps_id',$arrCampusPrivilages);
			
		}
		
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
    	return $this->db->count_all_results();
    }

   

    /**
     *
     * toggle_active: (de)activate course class
     *
     * @param int $id the member id
     * @param string $active activate or deactivate?
     * @return boolean
     *
     */

    public function toggle_active($id, $active) {
        $data = array('active' => ($active ? '0' : '1'));
        $this->db->where('user_id', $id);
        $this->db->update('users', $data);                  
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;

    }

    public function get_course_class_data($course_class_id){
    	$this->db->select('*,course_class.school_id,course_class.shift AS courses_shift');
		$this->db->from('course_class');
		$this->db->where('course_class_id', $course_class_id);
    	$query = $this->db->get();
    	
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }
	
	public function update_attendance_report($id,$data2){
		$this->db->where('course_class_id', $id);
		$this->db->update('attendance_report', $data2);
	}
	
	public function update_late_attendance($id,$data3){
		$this->db->where('course_class_id', $id);
		$this->db->update('late_attendance', $data3);
	}
	
	public function update_course_class_log($reason,$last_log_id){
		$query = "UPDATE `course_class_log` SET `reason` = '$reason' WHERE `course_class_log_id` ='$last_log_id'";
		$this->db->query($query);
	}
	
	public function update_users_log($reason,$log_id){
		$query = "UPDATE `users_log` SET `reason` = '$reason' WHERE `user_log_id` ='$log_id'";
		$this->db->query($query);
	}
}

/* End of file list_course_class_model.php */