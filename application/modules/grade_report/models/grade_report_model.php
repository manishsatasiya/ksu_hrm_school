<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grade_report_model extends CI_Model {

    public function __construct() 
    {
        parent::__construct();
    }

    public function get_student_class_grades($section_id,$student_uni_id="",$grade_type_exam_id=0) 
    {
    	$arrRet = array();
    	$time = 0;	
    	
		$this->db->select('grade_time_limit')->from('school');
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            $row = $query->row();
            $time = $row->grade_time_limit;
        }
		$user_select = ' ,u1.first_name as verified_by_name,u2.first_name as created_by_name';
    	if($time > 0 && $this->session->userdata('role_id') == '3')
        	$this->db->select('grade_report.*, IF(grade_report.created_date < DATE_SUB(NOW(),INTERVAL '.$time.' HOUR) && grade_report.created_date != "0000-00-00 00:00:00",1,0) AS editable,IF(verified!="No",1,0) AS verify_editable'.$user_select,FALSE);
        else	
        	$this->db->select('grade_report.*, 0 AS editable,0 AS verify_editable'.$user_select,FALSE);
        $this->db->from('grade_report');       
		$this->db->join('users AS u1','u1.user_id = grade_report.verified_by','left'); 
		$this->db->join('users AS u2','u2.user_id = grade_report.created_by','left'); 
        $this->db->where('grade_report.section_id', $section_id);
        
        if($student_uni_id <> "")
        	$this->db->where('grade_report.student_uni_id', $student_uni_id);
        	
        if($grade_type_exam_id <> "" && $grade_type_exam_id > 0)	
        	$this->db->where('grade_type_exam_id', $grade_type_exam_id);
        	
        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
        	foreach ($query->result_array() as $row)
			{
	        	$arrRet[$row["section_id"]][$row["grade_type_exam_id"]][$row["student_uni_id"]] = $row;
        	}
        }
        return $arrRet;
    }
    
    public function get_student_class_grades_reason($section_id,$student_uni_id="",$grade_type_exam_ids="") 
    {
    	$arrRet = array();
    	$this->db->select("reason_type AS 'Reason Type',reason AS 'Reason',first_name AS 'Updated By',reason_date AS 'Update Date'",FALSE);
        $this->db->from('grade_report');        
        $this->db->join('users',"reason_by=user_id","left");        
        $this->db->where('grade_report.section_id', $section_id);
        
        if($student_uni_id <> "")
        	$this->db->where('grade_report.student_uni_id', $student_uni_id);
        	
        if($grade_type_exam_ids <> "")	
        	$this->db->where('grade_type_exam_id IN('.$grade_type_exam_ids.')');
        	
        $this->db->where('reason_date != "0000-00-00 00:00:00"');
        $this->db->order_by("reason_date", "DESC");	
        $this->db->limit(1, 0);	
        $query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
        	foreach ($query->result_array() as $row)
			{
	        	$arrRet = $row;
        	}
        }
        return $arrRet;
    }
	
	public function get_grade_type($is_show=0){
		$arrRet = array();
    	
        $this->db->select('*',FALSE);
        $this->db->from('grade_type');   
		
		if($is_show == 1)   
			$this->db->where('is_show_grade', 'Yes');
			
		$query = $this->db->get();
        	
        if($query->num_rows() > 0)
        {
        	foreach ($query->result_array() as $row)
			{
	        	$arrRet[$row["grade_type_id"]] = $row;
        	}
        }
        return $arrRet;
	
	}
	
	public function get_grade_type_exam($is_show=0){
		$arrRet = array();
    	
        $this->db->select('*',FALSE);
        $this->db->from('grade_type_exam');    
        
        if($is_show == 1)   
			$this->db->where('is_show', 'Yes');
			
		$query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
        	foreach ($query->result_array() as $row)
			{
	        	$arrRet[$row["grade_type_id"]][$row["grade_type_exam_id"]] = $row;
        	}
        }
        return $arrRet;
	
	}
    
	public function get_grade_type_from_exam(){
		$arrRet = array();
    	
        $this->db->select('grade_type_exam.*,attendance_type,verification_type',FALSE);
        $this->db->from('grade_type_exam');   
		$this->db->join('grade_type','grade_type_exam.grade_type_id = grade_type.grade_type_id','left');		
		$query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
        	foreach ($query->result_array() as $row)
			{
	        	$arrRet[$row["grade_type_exam_id"]] = array('typeid'=>$row["grade_type_id"],'attendancetype'=>$row["attendance_type"],'verificationtype'=>$row["verification_type"]);
        	}
        }
        return $arrRet;
	
	}
     /**
     *
     * update_student_class_grade: add attendance in grade_report in the table
     *
     * @param $section_id,$studentUniID,$exam_type_id,$exam_mark
     * @return boolean
     *
     */
	public function update_student_class_grade($section_id,$studentUniID,$exam_type_id,$exam_mark,$exam_mark_2,$exam_mark_3,$exam_status) 
	{
		$user_id = $this->session->userdata('user_id');
        $data = array('exam_marks' => $exam_mark,"exam_status" => $exam_status);

		if($exam_mark_2 != "" && $exam_mark_2 != NULL && is_numeric($exam_mark_2))
		{
			 $data['exam_marks_2'] = $exam_mark_2;
		}
		
		if($exam_mark_3 != "" && $exam_mark_3 != NULL && $exam_mark_3 != "3rd" && $exam_mark_3 >= 0 && is_numeric($exam_mark_3))
		{
			 $data['exam_marks_3'] = $exam_mark_3;
		}
		
        $this->db->set('updated_date', 'NOW()', FALSE);
		$this->db->set('updated_by', $user_id, FALSE);
      	$this->db->where('section_id', $section_id);
      	$this->db->where('student_uni_id', $studentUniID);
      	$this->db->where('grade_type_exam_id', $exam_type_id);
      	$this->db->update('grade_report', $data);
		
		$this->db->select('*',FALSE);
		$this->db->from('grade_report');
		$this->db->where('section_id', $section_id);
      	$this->db->where('student_uni_id', $studentUniID);
      	$this->db->where('grade_type_exam_id', $exam_type_id);
		
		return $this->db->count_all_results();
    }
    
	/**
     *
     * update_student_class_grade: verify in grade_verify in the table
     *
     * @param $section_id,$studentUniID,$verified
     * @return boolean
     *
     */
	public function update_student_class_grade_verify($section_id,$studentUniID,$exam_type_id,$verified,$grade_lead_verify = '') 
	{
		$user_id = $this->session->userdata('user_id');
		$data = array();
		
		if($verified <> '')
		{
			$data = array("verified" => $verified,"verified_by"=>$user_id);
			$this->db->set('verified_date', 'NOW()', FALSE);
		}	
		if($grade_lead_verify <> '')
		{
			$data = array("lead_verified" => $grade_lead_verify);
			$this->db->set('lead_verify_date', 'NOW()', FALSE);
		}
      	$this->db->where('section_id', $section_id);
      	$this->db->where('student_uni_id', $studentUniID);
      	$this->db->where('grade_type_exam_id', $exam_type_id);
      	$this->db->update('grade_report', $data);

        $this->db->select('*',FALSE);
		$this->db->from('grade_report');
		$this->db->where('section_id', $section_id);
      	$this->db->where('student_uni_id', $studentUniID);
      	$this->db->where('grade_type_exam_id', $exam_type_id);
		
		return $this->db->count_all_results();
    }

	public function count_studentwithout_thirdmarks($search_data)
    {
		$arrCampusPrivilages = get_user_campus_privilages();
    	if (!empty($search_data)) {
        	!empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
            !empty($search_data['campus']) ? $data['users.campus'] = $search_data['campus'] : "";
            !empty($search_data['student_uni_id']) ? $data['users.student_uni_id'] = $search_data['student_uni_id'] : "";
            !empty($search_data['first_name']) ? $data['users.first_name'] = $search_data['first_name'] : ""; 
        }
       
    	$this->db->select('grade_report.student_uni_id ,
							section_title,
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
		
		if(count($arrCampusPrivilages) > 0)
		{	
			$this->db->where_in('users.campus_id',$arrCampusPrivilages);
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
	
	public function getstudentwithout_thirdmarks($limit = 0, $offset = 0, $order_by = "campus,section_title", $sort_order = "asc", $search_data) {
		$arrCampusPrivilages = get_user_campus_privilages();
        if (!empty($search_data)) {
        	!empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
            !empty($search_data['campus']) ? $data['users.campus'] = $search_data['campus'] : "";
            !empty($search_data['student_uni_id']) ? $data['users.student_uni_id'] = $search_data['student_uni_id'] : "";
            !empty($search_data['first_name']) ? $data['users.first_name'] = $search_data['first_name'] : ""; 
            !empty($search_data['email']) ? $data['users.email'] = $search_data['email'] : "";
        }
       
	   $this->db->select('grade_report.student_uni_id ,
							section_title,
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
		
		if(count($arrCampusPrivilages) > 0)
		{	
			$this->db->where_in('users.campus_id',$arrCampusPrivilages);
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
     * add_student_class_grade: add grades in grade_report in the table
     *
     * @param $section_id,$studentUniID,$exam_type_id,$exam_mark
     * @return boolean
     *
     */
	public function add_student_class_grade($section_id,$studentUniID,$exam_type_id,$exam_mark,$exam_mark_2,$exam_mark_3,$exam_status)
	{
		$user_id = $this->session->userdata('user_id');
        $data = array(
			            'section_id' => $section_id, 
						'student_uni_id' => $studentUniID,
			        	'grade_type_exam_id' => $exam_type_id, 
						'exam_marks' => $exam_mark,
						'exam_status' => $exam_status,
						'created_by' => $user_id
			         );
		
		if($exam_mark_2 != "" && $exam_mark_2 != NULL && is_numeric($exam_mark_2))
		{
			 $data['exam_marks_2'] = $exam_mark_2;
		}
		
		if($exam_mark_3 != "" && $exam_mark_3 != NULL && $exam_mark_3 != "3rd" && $exam_mark_3 >= 0 && is_numeric($exam_mark_3))
		{
			 $data['exam_marks_3'] = $exam_mark_3;
		}
        $this->db->set('created_date', 'NOW()', FALSE);
        $this->db->insert('grade_report', $data);
        
        if ($this->db->affected_rows() == 1)
            return true;
        
        return false;
    }
	
     /**
     *
     * add grades verification in grade_report in the table
     *
     * @param $section_id,$studentUniID,$exam_type_id,$exam_mark
     * @return boolean
     *
     */
	public function add_student_class_grade_verify($section_id,$studentUniID,$exam_type_id,$verified,$grade_lead_verify = '')
	{
		$user_id = $this->session->userdata('user_id');
		$data = array();
        if($grade_lead_verify <> '')
		{			
       			$data = array(
			            'section_id' => $section_id, 
						'student_uni_id' => $studentUniID,
			        	'grade_type_exam_id' => $exam_type_id, 
						'lead_verified' => $grade_lead_verify
			         );
		}
		if($verified <> '')
		{
			$data = array(
			            'section_id' => $section_id, 
						'student_uni_id' => $studentUniID,
			        	'grade_type_exam_id' => $exam_type_id, 
						'verified' => $verified,
						'verified_by' => $user_id,
						'verified_date' => date("Y-m-d h:i:s")
			         );
		}			 
		
		$this->db->insert('grade_report', $data);
        
        if ($this->db->affected_rows() == 1)
            return true;
        
        return false;
    }
    
	public function get_student_grade_ca_data($section_id,$geid,$stuid)
	{
		$this->db->select('*')->from('grade_report')->where('student_uni_id', $stuid)->where('section_id', $section_id)->where('grade_type_exam_id', $geid);
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
	}
	
	public function count_all_late_attendance_class($search_data) 
	{
		if (!empty($search_data)) {
            !empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
            !empty($search_data['attendence_week']) ? $data['attendence_week'] = $search_data['attendence_week'] : "";
            !empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
            !empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";
            
        }
		
    	$arrRet = array();
    	$this->db->select('*',FALSE);
		$this->db->from('late_attendance');
		$this->db->join('users','late_attendance.primary_teacher_id = users.user_id','left');
		$this->db->join('course_section','course_section.section_id = late_attendance.section_id','left');
		$this->db->join('school_year','school_year.school_year_id = late_attendance.school_year_id','left');
		$this->db->join('courses','late_attendance.course_id = courses.course_id','left');
		$this->db->join('course_class_room','late_attendance.class_room_id = course_class_room.class_room_id','left');
		$this->db->where('late_attendance.active = 1'); 
		$this->db->where('late_attendance.show_in_report = 1'); 
		!empty($data) ? $this->db->or_like($data) : "";
        
		return $this->db->count_all_results();
    }
	
	public function get_late_attendance_class($course_id, $school_year_id,$week_id) 
    {
    	$arrRet = array();
    	$this->db->select('course_class.section_id, 
							course_section.section_title, 
							course_class.primary_teacher_id, 
							attendance_report.created_date, 
							users.first_name
						  ',FALSE);
		$this->db->from('enable_school_week');
		$this->db->join('course_class','course_class.school_year_id = enable_school_week.school_year_id','left');
		$this->db->join('attendance_report','attendance_report.course_class_id = course_class.course_class_id AND attendeance_week=enable_school_week.week_id','left');
		$this->db->join('users','course_class.primary_teacher_id = users.user_id','left');
		$this->db->join('course_section','course_section.section_id = course_class.section_id','left');
		$this->db->join('school_year','school_year.school_year_id = course_class.school_year_id','left');
		$this->db->join('courses','course_class.course_id = courses.course_id','left');
		$this->db->where('attendance_report.course_class_id IS NULL'); 
		$this->db->where('course_class.course_class_id IS NOT NULL'); 
		$this->db->where('course_class.course_id',$course_id); 
		$this->db->where('course_class.school_year_id',$school_year_id); 
		$this->db->where('enable_school_week.week_id',$week_id); 
		$query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
        	return $query;
			/*
			foreach ($query->result_array() as $row)
			{
	        	$arrRet[$row["course_id"]][$row["course_title"]][$row["school_year_id"]][$row["school_year_title"]][$row["week_id"]][$row["section_title"]]
												= array(
													"school_year_id" => $row["school_year_id"],
													"school_year_title" => $row["school_year_title"],
													"week_id" => $row["week_id"],
													"course_id" => $row["course_id"],
													"course_title" => $row["course_title"],
													"section_id" => $row["section_id"],
													"section_title" => $row["section_title"],
													"primary_teacher_id" => $row["primary_teacher_id"],
													"first_name" => $row["first_name"]
										           );
        	}
          */  
        }
        return $arrRet;
    }
	
	public function get_late_attendance_course($limit = 0, $offset = 0, $order_by = "attendence_week", $sort_order = "desc", $search_data) 
    {
		if (!empty($search_data)) {
            !empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
            !empty($search_data['attendence_week']) ? $data['attendence_week'] = $search_data['attendence_week'] : "";
            !empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
            !empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";
            
        }
		
    	$arrRet = array();
    	$this->db->select('*',FALSE);
		$this->db->from('late_attendance');
		$this->db->join('users','late_attendance.primary_teacher_id = users.user_id','left');
		$this->db->join('course_section','course_section.section_id = late_attendance.section_id','left');
		$this->db->join('school_year','school_year.school_year_id = late_attendance.school_year_id','left');
		$this->db->join('courses','late_attendance.course_id = courses.course_id','left');
		$this->db->join('course_class_room','late_attendance.class_room_id = course_class_room.class_room_id','left');
		$this->db->where('late_attendance.active = 1'); 
		$this->db->where('late_attendance.show_in_report = 1'); 
		!empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by($order_by, $sort_order);
        
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
        
        if($query->num_rows() > 0)
			return $query;
    }
    
    public function count_all_late_attendance_report($search_data,$where=array())
    {
    	$user_id = $this->session->userdata('user_id');
    	$user_role = $this->session->userdata('role_id');
    	if (!empty($search_data)) {
    		!empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
    		!empty($search_data['week_id']) ? $data['week_id'] = $search_data['week_id'] : "";
    		!empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
    		!empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";
    		!empty($search_data['student_uni_id']) ? $data['s1.student_uni_id'] = $search_data['student_uni_id'] : "";
    		!empty($search_data['class_room_title']) ? $data['class_room_title'] = $search_data['class_room_title'] : "";
    		!empty($search_data['teacher_name']) ? $data['t1.first_name'] = $search_data['teacher_name'] : "";
    
    	}
    	$arrRet = array();
    	$this->db->select('s1.student_uni_id,course_class.*,s1.first_name AS student_name,t1.first_name AS teacher_name,course_class_room.class_room_title,course_class.school_year_id,section_title,course_title,school_week,max_hours,IF(attendance_report.created_date < DATE_SUB(NOW(), INTERVAL 12 HOUR),1,0) AS editable,GROUP_CONCAT(attendance_report.absent_hour) AS all_hours,GROUP_CONCAT(attendance_report.attendeance_week) AS all_weeks,SUM(absent_hour) AS total_absent_hour,shift,t1.campus',FALSE);
    	$this->db->from('attendance_report');
    	$this->db->join('course_class', 'attendance_report.course_class_id = course_class.course_class_id','left');
    	$this->db->join('users AS t1', 't1.user_id = course_class.primary_teacher_id','left');
    	$this->db->join('courses', 'courses.course_id = course_class.course_id','left');      
		$this->db->join('course_section', 'course_section.section_id = course_class.section_id','left');      
		$this->db->join('course_class_room', 'course_class_room.class_room_id = course_class.class_room_id','left');      
        $this->db->join('users AS s1', 's1.user_id = attendance_report.student_id','left');     
		$this->db->join('school', 'school.school_id = course_class.school_id','left');
		$this->db->join('school_year', 'school_year.school_year_id = course_class.school_year_id','left');
		$this->db->group_by(array("s1.student_uni_id", "attendance_report.course_class_id"));
    	if($where)
    	{
    		for($i=0;$i<count($where);$i++){
    			$this->db->where($where[$i]);
    		}
    	}
    	!empty($data) ? $this->db->or_like($data) : "";
    	
    	$query = $this->db->get();
    	return $query->num_rows();
    }
    
    public function get_late_attendance_report($limit = 0, $offset = 0, $order_by = "attendeance_id", $sort_order = "desc", $search_data,$where=array())
    {
    	$user_id = $this->session->userdata('user_id');
    	$user_role = $this->session->userdata('role_id');
    	$fields = $this->db->list_fields('course_class');
    	if (!empty($search_data)) {
    		!empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
    		!empty($search_data['week_id']) ? $data['week_id'] = $search_data['week_id'] : "";
    		!empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
    		!empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";
    		!empty($search_data['student_uni_id']) ? $data['s1.student_uni_id'] = $search_data['student_uni_id'] : "";
    		!empty($search_data['class_room_title']) ? $data['class_room_title'] = $search_data['class_room_title'] : "";
    		!empty($search_data['teacher_name']) ? $data['t1.first_name'] = $search_data['teacher_name'] : "";
    
    	}
    	$arrRet = array();
    	$this->db->select('s1.student_uni_id,course_class.*,s1.first_name AS student_name,t1.first_name AS teacher_name,course_class_room.class_room_title,course_class.school_year_id,section_title,course_title,school_week,max_hours,IF(attendance_report.created_date < DATE_SUB(NOW(), INTERVAL 12 HOUR),1,0) AS editable,GROUP_CONCAT(attendance_report.absent_hour) AS all_hours,GROUP_CONCAT(attendance_report.attendeance_week) AS all_weeks,SUM(absent_hour) AS total_absent_hour,shift,t1.campus',FALSE);
    	$this->db->from('attendance_report');
    	$this->db->join('course_class', 'attendance_report.course_class_id = course_class.course_class_id','left');
    	$this->db->join('users AS t1', 't1.user_id = course_class.primary_teacher_id','left');
    	$this->db->join('courses', 'courses.course_id = course_class.course_id','left');      
		$this->db->join('course_section', 'course_section.section_id = course_class.section_id','left');      
		$this->db->join('course_class_room', 'course_class_room.class_room_id = course_class.class_room_id','left');      
        $this->db->join('users AS s1', 's1.user_id = attendance_report.student_id','left');     
		$this->db->join('school', 'school.school_id = course_class.school_id','left');
		$this->db->join('school_year', 'school_year.school_year_id = course_class.school_year_id','left');
		$this->db->group_by(array("s1.student_uni_id", "attendance_report.course_class_id"));
    	if($where)
    	{
    		for($i=0;$i<count($where);$i++){
    			$this->db->where($where[$i]);
    		}
    	}
    	!empty($data) ? $this->db->or_like($data) : "";
    	$this->db->order_by($order_by, $sort_order);
    	if($this->router->fetch_method() != 'export_to_excel'){
    		$this->db->limit($limit, $offset);
    	}
    	
    	$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query;
    	}
		
    }
    
    public function add_late_attendance($course_class_id,
								    	$course_id,
								    	$category_id,
								    	$school_year_id,
								    	$school_id,
								    	$primary_teacher_id,
								    	$secondary_teacher_id,
								    	$class_room_id,
								    	$section_id,
								    	$shift,
								    	$attendence_week,
								    	$attendence_am_last_date,
								    	$attendence_pm_last_date
    									) 
    {	
        $data = array(
            'course_class_id' => "'".$course_class_id."'", 
	    	'course_id' => "'".$course_id."'", 
	    	'category_id' => "'".$category_id."'", 
	    	'school_year_id' => "'".$school_year_id."'", 
	    	'school_id' => "'".$school_id."'", 
	    	'primary_teacher_id' => "'".$primary_teacher_id."'", 
	    	'secondary_teacher_id' => "'".$secondary_teacher_id."'", 
	    	'class_room_id' => "'".$class_room_id."'", 
	    	'section_id' => "'".$section_id."'", 
	    	'shift' => "'".$shift."'", 
	    	'attendence_week' => "'".$attendence_week."'", 
	    	'attendence_am_last_date' => "'".$attendence_am_last_date."'", 
	    	'attendence_pm_last_date' => "'".$attendence_pm_last_date."'"
        );
		
        $fields = implode(",",array_keys($data));
        $values = implode(",",array_values($data));
        $query = "INSERT IGNORE INTO late_attendance($fields) VALUES ($values)";
		$this->db->query($query);

        $lastinsertid = $this->db->insert_id();
        if ($this->db->affected_rows() == 1) {
            return $lastinsertid;
        }
        return false;
    }
    
    public function update_show_flag_late_attendance()
    {
    	$query = "UPDATE late_attendance
    			  SET show_in_report = '1'
    			  WHERE (
    			  			(shift = 'AM' AND attendence_am_last_date < NOW()) 
    			  		OR 
    			  			(shift = 'PM' AND attendence_pm_last_date < NOW()) 
    			  		)
    			  		AND show_in_report = '0'
    			 ";
		$this->db->query($query);	
	}
    
    public function update_active_flag_late_attendance($id,$attendence_submitted_date)
    {
    	if($id > 0)
    	{
			$query = "UPDATE late_attendance 
					  SET active = '1',
					  attendence_submitted_date = '$attendence_submitted_date'
	    			  WHERE 
	    			  		id = '$id'
	    			  		AND active = '0'
	    			  		AND show_in_report = '1'
	    			 ";
			$this->db->query($query);
    	}
    }
    
    public function get_show_flag_late_attendance_data()
    {
		$this->db->select('*');
    	$this->db->from('late_attendance');
    	$this->db->where('late_attendance.show_in_report = 1');
    	$this->db->where('late_attendance.active = 0');
    	
    	$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query;
    	}
    }
    
    public function check_attendance_report_for_late_attendance($course_class_id,$attendence_week)
    {
    	if($course_class_id > 0 && $attendence_week > 0)
    	{
			$query = "SELECT COUNT(*) AS cnt,created_date FROM attendance_report
					  WHERE 
	    			  		course_class_id = '$course_class_id'
	    			  		AND attendeance_week = '$attendence_week'
	    			  GROUP BY course_class_id
	    			 ";
			$query_res = $this->db->query($query);
			return $query_res->result_array();
    	}
    }
    
    public function grade_report_log($section_id,$studentUniID,$exam_type_id,$exam_mark,$exam_mark_2,$exam_mark_3,$exam_status)
    {
    	$user_id = $this->session->userdata('user_id');
		$change_date = date('Y-m-d H:i:s');
		$section_id = (int)$section_id;
		$changed_fields = "";
		$exam_marks_2_val = '';
		$exam_marks_3_val = '';
		
		$query = "SELECT * 
				FROM `grade_report`
				WHERE 
				section_id = $section_id 
				AND student_uni_id = '$studentUniID'
				AND grade_type_exam_id = $exam_type_id
    			 ";
		$query_res = $this->db->query($query);
		
		if($query_res->num_rows())
		{
			foreach($query_res->result_array() AS $row)
			{
	        	if($exam_mark_2 != "" && $exam_mark_2 != NULL && is_numeric($exam_mark_2))
				{
					 $exam_marks_2_val = $exam_mark_2;
				}
				
				if($exam_mark_3 != "" && $exam_mark_3 != NULL && $exam_mark_3 != "3rd" && $exam_mark_3 >= 0 && is_numeric($exam_mark_3))
				{
					 $exam_marks_3_val = $exam_mark_3;
				}
				
				if($row["exam_marks"] != $exam_mark && $exam_mark != "blank")
					$changed_fields .= "exam_marks|";
				if($row["exam_marks_2"] != $exam_marks_2_val && $exam_marks_2_val != "")
					$changed_fields .= "exam_marks_2|";
				if($row["exam_marks_3"] != $exam_marks_3_val && $exam_marks_3_val != "")
					$changed_fields .= "exam_marks_3|";
				if($row["exam_status"] != $exam_status && $exam_status != "blank")
					$changed_fields .= "exam_status|";			
	    	}
		}
		$changed_fields = trim(trim($changed_fields),"|");
		
		if($changed_fields != "")
		{
			$sql = "INSERT INTO `grade_report_log` (`id` ,
					`student_uni_id` ,
					`section_id` ,
					`grade_type_exam_id` ,
					`exam_marks` ,
					`exam_marks_new` ,
					`exam_marks_2` ,
					`exam_marks_2_new` ,
					`exam_marks_3` ,
					`exam_marks_3_new` ,
					`exam_status` ,
					`exam_status_new` ,
					`created_date` ,
					`updated_date` ,
					`verified` ,
					`verified_by` ,
					`verified_date` ,
					`reason_type` ,
					`reason` ,
					`reason_date` ,
					`changed_fields` ,
					`logby` ,
					`logdate`
				)
				SELECT `id` ,
					`student_uni_id` ,
					`section_id` ,
					`grade_type_exam_id` ,
					`exam_marks` ,
					'$exam_mark' ,
					`exam_marks_2` ,
					'$exam_marks_2_val' ,
					`exam_marks_3` ,
					'$exam_marks_3_val' ,
					`exam_status` ,
					'$exam_status' ,
					`created_date` ,
					`updated_date` ,
					`verified` ,
					`verified_by` ,
					`verified_date` ,
					`reason_type` ,
					`reason` ,
					`reason_date` ,
					'$changed_fields' ,
					$user_id,
					'$change_date' 
				FROM `grade_report`
				WHERE 
				section_id = $section_id 
				AND student_uni_id = '$studentUniID'
				AND grade_type_exam_id = $exam_type_id
				";
				$this->db->query($sql);
		}
    }
    
    function get_grade_report_log($section_id,$student_uni_id,$grade_type_id)
    {
		$this->db->select('DATE_FORMAT(grade_report_log.logdate,"%Y-%m-%d") AS chndate,
					grade_type_exam.grade_type_id,
					users.first_name,
					grade_report_log.grade_type_exam_id,
					`exam_type_name` ,
					grade_report_log.exam_marks ,
					`exam_marks_new` ,
					`exam_marks_2` ,
					`exam_marks_2_new` ,
					`exam_marks_3` ,
					`exam_marks_3_new` ,
					`exam_status` ,
					`exam_status_new`,
					changed_fields,',FALSE);
		$this->db->from('grade_report_log');
		$this->db->join('grade_type_exam', 'grade_type_exam.grade_type_exam_id = grade_report_log.grade_type_exam_id','left');
		$this->db->join('users', 'users.user_id = grade_report_log.logby','left');
		
		if($section_id > 0)
    		$this->db->where('grade_report_log.section_id',$section_id);
    		
    	$this->db->where('grade_report_log.student_uni_id',$student_uni_id);
    	
    	if($grade_type_id > 0)	
    		$this->db->where('grade_type_exam.grade_type_id',$grade_type_id);
    		
		$this->db->order_by('grade_report_log.logdate', 'DESC');
		$grade_report_log = $this->db->get();
		
		$arrRet = array();
		if($grade_type_id > 0)
		{
			foreach($grade_report_log->result_array() AS $rowdata)
			{
				$arrRet[] = array("first_name"=>$rowdata['first_name'],
								"chndate"=>$rowdata['chndate'],
								"exam_type_name"=>$rowdata['exam_type_name'],
								"exam_marks"=>$rowdata['exam_marks'],
								"exam_marks_new"=>$rowdata['exam_marks_new'],
								"exam_marks_2"=>$rowdata['exam_marks_2'],
								"exam_marks_2_new"=>$rowdata['exam_marks_2_new'],
								"exam_marks_3"=>$rowdata['exam_marks_3'],
								"exam_marks_3_new"=>$rowdata['exam_marks_3_new'],
								"exam_status"=>$rowdata['exam_status'],
								"exam_status_new"=>$rowdata['exam_status_new'],
								"changed_fields"=>$rowdata['changed_fields']
								);
			
			} 
		}
		else 
		{
			foreach($grade_report_log->result_array() AS $rowdata)
			{
				$arrRet[$rowdata['grade_type_id']][] = array("first_name"=>$rowdata['first_name'],
								"chndate"=>$rowdata['chndate'],
								"exam_type_name"=>$rowdata['exam_type_name'],
								"exam_marks"=>$rowdata['exam_marks'],
								"exam_marks_new"=>$rowdata['exam_marks_new'],
								"exam_marks_2"=>$rowdata['exam_marks_2'],
								"exam_marks_2_new"=>$rowdata['exam_marks_2_new'],
								"exam_marks_3"=>$rowdata['exam_marks_3'],
								"exam_marks_3_new"=>$rowdata['exam_marks_3_new'],
								"exam_status"=>$rowdata['exam_status'],
								"exam_status_new"=>$rowdata['exam_status_new'],
								"changed_fields"=>$rowdata['changed_fields']
								);
			
			}
		}
		return $arrRet;
	}
	
	public function get_student_grades_log($section_id) 
    {
    	$arrRet = array();
    	
		$this->db->select('section_id,grade_type_id,student_uni_id,COUNT(*) AS cnt',FALSE);
        $this->db->from('grade_report_log');        
        $this->db->join('grade_type_exam', 'grade_type_exam.grade_type_exam_id = grade_report_log.grade_type_exam_id','left');
        $this->db->where('section_id', $section_id);
        $this->db->group_by(array("grade_type_id","section_id","student_uni_id"));
        
        $query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
        	foreach ($query->result_array() as $row)
			{
	        	$arrRet[$row["grade_type_id"]][$row["section_id"]][$row["student_uni_id"]]["cnt"] = $row["cnt"];
        	}
        }
        return $arrRet;
    }
	
	public function update_grade_range(){
		$query_update = "UPDATE grade_type SET show_grade_range= 'N'";
		return $this->db->query($query_update);
	}
	
	public function get_setting(){
		$query = "SELECT show_total_grade,show_grade_range FROM school";
		$query_res = $this->db->query($query);
		return $query_res->result_array();
	}
	
	public function get_grade_range(){
		$query = "SELECT * FROM grade_range";
		$query_res = $this->db->query($query);
		return $query_res->result_array();
	}
	
	public function add_reason($reason_type,$reason,$section_id,$student_uni_id,$grade_exam_id){
		$query = "UPDATE grade_report
				  SET reason_type = '$reason_type',	
				  reason = '$reason',
				  reason_by = '".$this->session->userdata('user_id')."',
				  reason_date = NOW()
				  WHERE 
						section_id = '$section_id'
						AND student_uni_id = '$student_uni_id'
						AND grade_type_exam_id = '$grade_exam_id'
				 ";
				
		$this->db->query($query);
	}
	public function count_grade_report($section_id,$student_uni_id,$grade_exam_id){
		$query = "SELECT COUNT(*) AS cnt FROM grade_report
				  WHERE 
						section_id = '$section_id'
						AND student_uni_id = '$student_uni_id'
						AND grade_type_exam_id = '$grade_exam_id'
				 ";
		$query_res = $this->db->query($query);
		return $query_res->result_array();
	}
	
	public function update_grade_report($columnName,$value,$section_id,$student_uni_id,$grade_exam_id){
		$query = "UPDATE grade_report
					  SET $columnName = '$value',
						  updated_date = NOW()	
					  WHERE 
							section_id = '$section_id'
							AND student_uni_id = '$student_uni_id'
							AND grade_type_exam_id = '$grade_exam_id'
					 ";
		$this->db->query($query);
	}
	
	public function insert_grade_report($fields,$values){
		$query = "INSERT IGNORE INTO grade_report($fields) VALUES ($values)";
		$this->db->query($query);
	}
	
	public function getstudentsectionid($stundet_uni_id){
		$this->db->select('section_id',FALSE);
		$this->db->from('users');
		$this->db->where('student_uni_id',$stundet_uni_id);
		$data_section = $this->db->get();
		return $data_section->result_array();
	}
}
/* End of file attendance_model.php */