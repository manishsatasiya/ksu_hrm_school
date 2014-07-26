<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_student_class_model extends CI_Model {

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

     public function get_course_class($limit = 0, $offset = 0, $order_by = "course_class_id", $sort_order = "asc", $search_data,$where=array(),$without_third_marks="",$where2=array()) {
		$user_id = $this->session->userdata('user_id');
		$user_role = $this->session->userdata('role_id');
        $section_title = "";
		
        if (!empty($search_data)) {
            !empty($search_data['class_room_title']) ? $data['class_room_title'] = $search_data['class_room_title'] : "";
            !empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
            !empty($search_data['section_title']) ? $section_title = $search_data['section_title'] : "";
            !empty($search_data['section_id']) ? $data['course_section.section_id'] = $search_data['section_id'] : "";
			!empty($search_data['campus_id']) ? $data['p1.campus_id'] = $search_data['campus_id'] : "";
        }
		
		$this->db->select('school_week');
        $this->db->from('school_year');   
		$this->db->where('school_year_id = 1');
		$query = $this->db->get();
		
		$total_week = 0;
		foreach($query->result_array() AS $row)
			$total_week = $row["school_week"];
		
		$this->db->select('week_id,no_of_day');
        $this->db->from('enable_school_week');   
		$query = $this->db->get();
		
		$totalhours = 0;
             
		$arrWeekHours = array();
		foreach($query->result_array() AS $row)
		{
			$arrWeekHours[$row["week_id"]] = $row["no_of_day"];
		}
		
		for($i=1;$i<=$total_week;$i++)
		{
			if(array_key_exists($i,$arrWeekHours))
			{
				//$totalhours += $arrWeekHours[$i];
                $totalhours +=5;
			}
			else
			{
				$totalhours += 5;
			}
		}
		$arrSecIDs = array();
		
		if($without_third_marks == "Yes")
		{
			$arrDataWithoutThirdMarks = $this->getstudentwithout_thirdmarks($search_data);
			
			if(!empty($arrDataWithoutThirdMarks)){
				foreach($arrDataWithoutThirdMarks->result_array() AS $datarow)
				{
					$arrSecIDs[] = $datarow["section_id"];
				}
			}
		}
		
        $this->db->select('course_class.*,p1.track,p1.buildings,p1.first_name,s1.first_name AS sec_name,p1.campus,course_class_room.class_room_title,section_title,course_title,school_week,total_hours_all_weeks AS max_hours, max_hours AS week_max_hours,course_class.shift AS courses_shift');

        $this->db->from('course_class');   
		if($user_role == '3' && count($where2) == 0){
		
			$this->db->where('course_class.primary_teacher_id',$user_id);        
		}
		
		$this->db->join('courses', 'courses.course_id = course_class.course_id','left');      
		$this->db->join('course_section', 'course_section.section_id = course_class.section_id','left');      
		$this->db->join('course_class_room', 'course_class_room.class_room_id = course_class.class_room_id','left');      
        $this->db->join('users AS p1', 'p1.user_id = course_class.primary_teacher_id','left');     
        $this->db->join('users AS s1', 's1.user_id = course_class.secondary_teacher_id','left');     
		$this->db->join('school', 'school.school_id = course_class.school_id','left');
		$this->db->join('school_year', 'school_year.school_year_id = course_class.school_year_id','left');
		$this->db->where('is_active = 1');
		
		if($without_third_marks == "Yes"){
			if(is_array($arrSecIDs) && count($arrSecIDs) > 0)
			{
				$this->db->where_in('course_class.section_id', $arrSecIDs);
			}
			else
			{
				$this->db->where_in('course_class.section_id', array(1999999999999999999));
			}
		}	
		
		if($this->session->userdata('ca_lead_teacher') > 0)
		{
			$this->db->where('course_section.ca_lead_teacher',$this->session->userdata('ca_lead_teacher'));
		}
		
		if($this->session->userdata('role_id') > 4 && ($this->session->userdata('campus_id') > 0) || $this->session->userdata('campus') != "")
		{
			if($this->session->userdata('campus_id') > 0)
				$this->db->where('p1.campus_id',$this->session->userdata('campus_id'));
			else if($this->session->userdata('campus') != "")
				$this->db->where('p1.campus',$this->session->userdata('campus'));	
		}
		if($where)
		{
			for($i=0;$i<count($where);$i++){
				$this->db->where($where[$i]);
			}
		}
		
		if($where2)
		{
			for($i=0;$i<count($where2);$i++){
				if($i==0)
					$this->db->where($where2[$i]);
				else	
					$this->db->or_where($where2[$i]);
			}
		}
		$section_title = trim($section_title);
		if($section_title != "")
			$this->db->where('section_title',$section_title);
			
		!empty($data) ? $this->db->like($data) : "";
        $this->db->order_by($order_by, $sort_order);
        
        if($limit != "" && $limit > 0)
        	$this->db->limit($limit, $offset);

        $query = $this->db->get();

        if($query->num_rows() > 0) {
            return $query;
        }
    }

	public function getstudentwithout_thirdmarks($search_data=array()) {
        if (!empty($search_data)) {
        	!empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
            !empty($search_data['campus']) ? $data['users.campus'] = $search_data['campus'] : "";
            !empty($search_data['student_uni_id']) ? $data['users.student_uni_id'] = $search_data['student_uni_id'] : "";
            !empty($search_data['first_name']) ? $data['users.first_name'] = $search_data['first_name'] : ""; 
            !empty($search_data['email']) ? $data['users.email'] = $search_data['email'] : "";
        }
       
	   $this->db->select('grade_report.student_uni_id ,
							grade_report.section_id,
							users.campus,
							users.first_name,
							exam_type_name');
        $this->db->from('grade_report');  
        $this->db->join('users','users.student_uni_id=grade_report.student_uni_id','left');
		$this->db->join('course_class','users.section_id=course_class.section_id','left');  
        $this->db->join('users AS p1','p1.user_id=course_class.primary_teacher_id','left');  		
        $this->db->join('course_section','grade_report.section_id=course_section.section_id','left');  
        $this->db->join('grade_type_exam','grade_type_exam.grade_type_exam_id=grade_report.grade_type_exam_id','left');  
		$this->db->where('is_two_marker','Yes');        
		$this->db->where('abs(grade_report.exam_marks-grade_report.exam_marks_2) >= two_mark_difference');
		$this->db->where('grade_report.exam_marks_3 IS NULL');
		
		if($this->session->userdata('ca_lead_teacher') > 0)
		{
			$this->db->where('course_section.ca_lead_teacher',$this->session->userdata('ca_lead_teacher'));
		}
		
		if($this->session->userdata('role_id') > 4 && ($this->session->userdata('campus_id') > 0 || $this->session->userdata('campus') != ""))
		{
			if($this->session->userdata('campus_id') > 0)
				$this->db->where('users.campus_id',$this->session->userdata('campus_id'));
			else if($this->session->userdata('campus') != "")
				$this->db->where('users.campus',$this->session->userdata('campus'));	
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

        $this->db->limit(1000000, 0);

        $query = $this->db->get();
        
		if($query->num_rows() > 0) {
            return $query;
        }
    }
	
    /**
     *
     * get_class_wise_student: get the class wise student
     *
     * @param int $section_id
     * @return mixed
     *
     */
	public function get_class_wise_student($section_id = '',$student_uni_id ='',$without_third_marks='') 
	{
		$arrStudIDs = array();
		if($without_third_marks == "Yes")
		{
			$arrDataWithoutThirdMarks = $this->getstudentwithout_thirdmarks();
			
			if(!empty($arrDataWithoutThirdMarks)){
				foreach($arrDataWithoutThirdMarks->result_array() AS $datarow)
				{
					$arrStudIDs[] = $datarow["student_uni_id"];
				}
			}
		}
		if(is_array($arrStudIDs) && count($arrStudIDs) > 0)
		{
			$this->db->where_in('users.student_uni_id', $arrStudIDs);
		}
		
	    $this->db->select('*, user_roll.user_roll_name as role_name,course_section.section_title,(select count(*)from attendance_report_log where student_id = users.user_id) as log_cnt',FALSE);
        $this->db->from('users');  
		$this->db->where('users.section_id',$section_id); 
		$this->db->where('users.discontinue != "inactive"');
		if(trim($student_uni_id) <> '') {
			$this->db->where('users.student_uni_id',$student_uni_id); 
		}
		$this->db->join('course_section', 'course_section.section_id = users.section_id');       
		$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id');        
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by('student_uni_id', 'ASC');
        $query = $this->db->get();
        if($query->num_rows() > 0)
            return $query;
    }
	
    /**
     *
     * get_student: get the student
     *
     * @param int $section_id
     * @return mixed
     *
     */
	public function get_student($section_id = '') {
		
        $fields = $this->db->list_fields('users');
        
        $user_id = $this->session->userdata('user_id');
		$user_role = $this->session->userdata('role_id');
        $this->db->select('*, user_roll.user_roll_name as role_name');
        $this->db->from('users');  
		$this->db->where('users.user_roll_id','4');        
		if($user_role == '3' && $user_role != '4'){
			$this->db->where('users.section_id',$section_id);        
		}
		if($user_role == '1' && $user_role != '4'){
			$this->db->where('users.section_id',$section_id); 
		}
        $this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id');        
        !empty($data) ? $this->db->or_like($data) : "";
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

	public function count_all_course_class($where=array(),$search_data,$without_third_marks='',$where2=array()) 
	{
		$section_title = "";
		if (!empty($search_data)) {
			$user_id = $this->session->userdata('user_id');
			$user_role = $this->session->userdata('role_id');
			if (!empty($search_data)) {
				!empty($search_data['class_room_title']) ? $data['class_room_title'] = $search_data['class_room_title'] : "";
				!empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
				!empty($search_data['section_title']) ? $section_title = $search_data['section_title'] : "";
				
			}
			
			$arrSecIDs = array();
			if($without_third_marks == "Yes")
			{
				$arrDataWithoutThirdMarks = $this->getstudentwithout_thirdmarks($search_data);
				
				if(!empty($arrDataWithoutThirdMarks)){
					foreach($arrDataWithoutThirdMarks->result_array() AS $datarow)
					{
						$arrSecIDs[] = $datarow["section_id"];
					}
				}
			}
			
			$this->db->select('course_class.*,users.first_name,course_class_room.class_room_title,section_title,course_title,school_week,max_hours');
			$this->db->from('course_class');   
			if($user_role == '3'){
			
				$this->db->where('course_class.primary_teacher_id',$user_id);        
			}
			
			$this->db->join('courses', 'courses.course_id = course_class.course_id','left');      
			$this->db->join('course_section', 'course_section.section_id = course_class.section_id','left');      
			$this->db->join('course_class_room', 'course_class_room.class_room_id = course_class.class_room_id','left');      
			$this->db->join('users', 'users.user_id = course_class.primary_teacher_id','left');     
			$this->db->join('school', 'school.school_id = course_class.school_id','left');
			$this->db->join('school_year', 'school_year.school_year_id = course_class.school_year_id','left');
			$this->db->where('is_active = 1');

			if($without_third_marks == "Yes"){
				if(is_array($arrSecIDs) && count($arrSecIDs) > 0)
				{
					$this->db->where_in('course_class.section_id', $arrSecIDs);
				}
				else
				{
					$this->db->where_in('course_class.section_id', array(1999999999999999999));
				}
			}	
			if($this->session->userdata('ca_lead_teacher') > 0)
			{
				$this->db->where('course_section.ca_lead_teacher',$this->session->userdata('ca_lead_teacher'));
			}
			
			if($this->session->userdata('role_id') > 4 && ($this->session->userdata('campus_id') > 0) || $this->session->userdata('campus') != "")
			{
				if($this->session->userdata('campus_id') > 0)
					$this->db->where('users.campus_id',$this->session->userdata('campus_id'));
				else if($this->session->userdata('campus') != "")
					$this->db->where('users.campus',$this->session->userdata('campus'));	
			}
			if($where)
			{
				for($i=0;$i<count($where);$i++){
					$this->db->where($where[$i]);
				}
			}
			if($where2)
			{
				for($i=0;$i<count($where2);$i++){
					if($i==0)
					$this->db->where($where2[$i]);
				else	
					$this->db->or_where($where2[$i]);
				}
			}
			$section_title = trim($section_title);
			if($section_title != "")
				$this->db->where('section_title',$section_title);
				
			!empty($data) ? $this->db->like($data) : "";
				return $this->db->count_all_results();
        }
		else
		{
			$this->db->select('*');
			$this->db->from('course_class');
			if($where)
			{
				for($i=0;$i<count($where);$i++){
					$this->db->where($where[$i]);
				}
			}
			
			if($where2)
			{
				for($i=0;$i<count($where2);$i++){
					if($i==0)
					$this->db->where($where2[$i]);
				else	
					$this->db->or_where($where2[$i]);
				}
			}
			$this->db->where('is_active = 1');
			return $this->db->count_all_results();
		}	
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

    public function update_course_class($course_class_id, $course_id, $category_id, $school_year_id, $school_id,$primary_teacher_id,$secondary_teacher_id,$classroom,$section,$start_time,$end_time,$shift,$total_seats,$registered_student,$credits,$restricted_hours) {
        // if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.

        $data = array(                
                'course_id'    			=> $course_id,
				'category_id'    		=> $category_id,
				'school_year_id'    	=> $school_year_id,
				'school_id'				=> $school_id,
				'primary_teacher_id'	=> $primary_teacher_id,
				'secondary_teacher_id'	=> $secondary_teacher_id,
				'class_room_id'			=> $classroom,
				'section_id'			=> $section,
				'start_time'			=> $start_time,
				'shift'					=> $shift,
				'total_seats'			=> $total_seats,
				'registered_student'	=> $registered_student,
				'credits'				=> $credits,
				'restricted_hours'		=> $restricted_hours);

      
        $this->db->where('course_class_id', $course_class_id);
        $this->db->update('course_class', $data);

        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }
	
	public function update_student_class($course_class_id, $student_id) {
        // if there are more fields you can turn the data into an array. The reason I don't do this is because it's an extra array in controller List_members.

        $data = array(                
                'course_class_id'    			=> $course_class_id,
				'student_id'    		=> $student_id);

      	$this->db->insert('student_class', $data);
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * delete_member: count all members in the table
     *
     * @param int $id the member id
     * @return boolean
     *
     */

	public function delete_student_class($student_class_id) {
        $this->db->where('course_class_id', $student_class_id);
        $this->db->delete('student_class');
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    
    /**
     *
     * get_username_by_id: return username by id
     *
     * @param int $id the member id
     * @return mixed
     *
     */

    public function get_classroom_by_id($course_class_id) {
        $this->db->select('classroom')->from('course_class')->where('course_class_id', $course_class_id);
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            $row = $query->row();
            return $row->classroom;
        }
        return "";
    }

    /**
     *
     * demote_member: demote member
     *
     * @param int $id the member id
     * @return boolean
     *
     */

    public function demote_member($id) {
        $data = array('user_roll_id' => "2");
        $this->db->where('user_id', $id);
        $this->db->update('users', $data);
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * promote_member: promote member
     *
     * @param int $id the member id
     * @return boolean
     *
     */

    public function promote_member($id) {
        $data = array('user_roll_id' => "1");
        $this->db->where('user_id', $id);
        $this->db->update('users', $data);
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * count_all_search_members: count all members when performing search
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

    /**
     *
     * toggle_ban: (un)ban member
     *
     * @param int $id the member id
     * @param bool $banned ban or unban?
     * @return boolean
     *
     */

    public function toggle_ban($id, $banned) {
        $data = array('banned' => ($banned ? false : true));
        $this->db->where('id', $id);
        $this->db->update('user', $data);
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     *
     * toggle_active: (de)activate member
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

}

/* End of file list_members_model.php */
