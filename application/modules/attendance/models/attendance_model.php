<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Attendance_model extends CI_Model {
    public function __construct() 
    {
        parent::__construct();
    }
    public function get_student_class_attendance($classID,$student_id ='') 
    {
    	$arrRet = array();
    	$time = 0;
		
		$this->db->select('attendance_time_limit')->from('school');
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            $row = $query->row();
            $time = $row->attendance_time_limit;
        }
		
    	$time = constant("ATTENDANCE_TIME_LIMIT");
        $this->db->select('*, IF(created_date < DATE_SUB(NOW(), INTERVAL '.$time.' HOUR),1,0) AS editable',FALSE);
        $this->db->from('attendance_report');        
        $this->db->where('course_class_id', $classID);    
		if(trim($student_id) <> '' && $student_id > 0) {
			$this->db->where('attendance_report.student_id',$student_id); 
		}    
    
		$query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
        	foreach ($query->result_array() as $row)
			{
	        	$arrRet[$row["student_id"]][$row["attendeance_week"]] = array(
										            "student_id" => $row["student_id"],
										            "school_year_id" => $row["school_year_id"],
										            "attendeance_week" => $row["attendeance_week"],
										            "course_class_id" => $row["course_class_id"],
										            "absent_hour" => $row["absent_hour"],
										            "editable" => $row["editable"]
										           );
        	}
            
        }
        return $arrRet;
    }
    
     /**
     *
     * add_student_class_attendance: add attendance in attendance_report in the table
     *
     * @param $classID,$studentID,$weekNo,$weekAbsentHours
     * @return boolean
     *
     */
	public function update_student_class_attendance($classID,$studentID,$weekNo,$weekAbsentHours) 
	{
        $data = array('absent_hour' => $weekAbsentHours);
        $this->db->set('updated_date', 'NOW()', FALSE);
      	$this->db->where('course_class_id', $classID);
      	$this->db->where('student_id', $studentID);
      	$this->db->where('attendeance_week', $weekNo);
      	$this->db->update('attendance_report', $data);
        if($this->db->affected_rows() > 0)
		{
			if($this->db->affected_rows() > 1)
			{
				if($this->db->affected_rows()-1 >= 1)
				{
					$query = "DELETE FROM `attendance_report` WHERE `course_class_id` =$classID AND student_id =$studentID AND attendeance_week =$weekNo LIMIT ".($this->db->affected_rows()-1);
					$query_res = $this->db->query($query);
				}
			}
            return true;
        }   
		return false;
    }
    /**
     *
     * add_student_class_attendance: add attendance in attendance_report in the table
     *
     * @param $classID,$studentID,$weekNo,$weekAbsentHours,$schoolYearID,$schoolID,$teacherID
     * @return boolean
     *
     */
	public function add_student_class_attendance($classID,$studentID,$weekNo,$weekAbsentHours,$schoolYearID,$schoolID,$teacherID)
	{
        $data = array(
			            'course_class_id' => $classID, 
						'student_id' => $studentID,
			        	'attendeance_week' => $weekNo, 
						'absent_hour' => $weekAbsentHours,
						'school_year_id' => $schoolYearID,
						'school_id' => $schoolID,
						'teacher_id' => $teacherID
			         );
		
        $this->db->set('created_date', 'NOW()', FALSE);
        $this->db->insert('attendance_report', $data);
        
        if ($this->db->affected_rows() > 0)
            return true;
        
        return false;
    }
	
	public function count_all_late_attendance_class($search_data) 
	{
		if (!empty($search_data)) {
            !empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
            !empty($search_data['attendence_week']) ? $data['attendence_week'] = $search_data['attendence_week'] : "";
            !empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
            !empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";
            !empty($search_data['elsd_id']) ? $data['elsd_id'] = $search_data['elsd_id'] : "";
            
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
		
		if($this->session->userdata('ca_lead_teacher') > 0)
		{
			$this->db->where('course_section.ca_lead_teacher',$this->session->userdata('ca_lead_teacher'));
		}
		
		if(count(get_user_campus_privilages()) > 0)
		{	
			$this->db->where_in('users.campus_id',get_user_campus_privilages());
		}
		
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
            !empty($search_data['elsd_id']) ? $data['elsd_id'] = $search_data['elsd_id'] : "";
            
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
        
        if($limit != "" && $limit > 0)
			$this->db->limit($limit, $offset);
			
		$query = $this->db->get();
        
        if($query->num_rows() > 0)
			return $query;
    }
    
    public function count_all_late_attendance_report($search_data,$where=array())
    {
		$sql = "update attendance_report 
				join users on (student_id=user_id) 
				join course_class on(users.section_id=course_class.section_id) 
				set attendance_report.course_class_id = course_class.course_class_id 
				";
		$this->db->query($sql);
				
    	$user_id = $this->session->userdata('user_id');
    	$user_role = $this->session->userdata('role_id');
    	if (!empty($search_data)) {
    		!empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
    		!empty($search_data['week_id']) ? $data['week_id'] = $search_data['week_id'] : "";
    		!empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
    		!empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";
    		!empty($search_data['student_uni_id']) ? $data['s1.student_uni_id'] = $search_data['student_uni_id'] : "";
    		!empty($search_data['campus']) ? $data['s1.campus'] = $search_data['campus'] : "";
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
		
		$this->db->where('s1.discontinue != "inactive"');
		
		if($this->session->userdata('ca_lead_teacher') > 0)
		{
			$this->db->where('course_section.ca_lead_teacher',$this->session->userdata('ca_lead_teacher'));
		}
		
		if(count(get_user_campus_privilages()) > 0)
		{	
			$this->db->where_in('s1.campus_id',get_user_campus_privilages());
		}
		
    	if($where)
    	{
    		for($i=0;$i<count($where);$i++){
    			$this->db->where($where[$i]);
    		}
    	}
    	
    	if($user_role == '3')
		{
			$this->db->where('teacher_id = '.$user_id);
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
			!empty($search_data['campus']) ? $data['s1.campus'] = $search_data['campus'] : "";
    		!empty($search_data['class_room_title']) ? $data['class_room_title'] = $search_data['class_room_title'] : "";
    		!empty($search_data['teacher_name']) ? $data['t1.first_name'] = $search_data['teacher_name'] : "";
    
    	}
    	$arrRet = array();
		
		
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
				$totalhours += 5;
			}
			else
			{
				$totalhours += 5;
			}
		}
		
    	$this->db->select('s1.student_uni_id,s1.academic_status,course_class.*,s1.first_name AS student_name,s1.first_name_arabic AS student_arabicname,DATE_FORMAT(s1.student_schedule_date,"%a %D, %b, %Y ") AS stu_schedule_date,t1.first_name AS teacher_name,ts1.first_name AS sec_teacher_name,course_class_room.class_room_title,course_class.school_year_id,school_year_title,section_title,course_title,school_week,total_hours_all_weeks AS max_hours,IF(attendance_report.created_date < DATE_SUB(NOW(), INTERVAL 12 HOUR),1,0) AS editable,GROUP_CONCAT(attendance_report.absent_hour) AS all_hours,GROUP_CONCAT(attendance_report.attendeance_week) AS all_weeks,SUM(absent_hour) AS total_absent_hour,course_class.shift,s1.campus,s1.track',FALSE);
    	$this->db->from('attendance_report');
    	$this->db->join('course_class', 'attendance_report.course_class_id = course_class.course_class_id','left');
    	$this->db->join('users AS t1', 't1.user_id = course_class.primary_teacher_id','left');
    	$this->db->join('users AS ts1', 'ts1.user_id = course_class.secondary_teacher_id','left');
    	$this->db->join('courses', 'courses.course_id = course_class.course_id','left');      
		$this->db->join('course_section', 'course_section.section_id = course_class.section_id','left');      
		$this->db->join('course_class_room', 'course_class_room.class_room_id = course_class.class_room_id','left');      
        $this->db->join('users AS s1', 's1.user_id = attendance_report.student_id','left');     
		$this->db->join('school', 'school.school_id = course_class.school_id','left');
		$this->db->join('school_year', 'school_year.school_year_id = course_class.school_year_id','left');
		$this->db->group_by(array("s1.student_uni_id", "attendance_report.course_class_id"));
		
		$this->db->where('s1.discontinue != "inactive"');
		
		if($this->session->userdata('ca_lead_teacher') > 0)
		{
			$this->db->where('course_section.ca_lead_teacher',$this->session->userdata('ca_lead_teacher'));
		}
		
		if(count(get_user_campus_privilages()) > 0)
		{	
			$this->db->where_in('s1.campus_id',get_user_campus_privilages());
		}
		
    	if($where)
    	{
    		for($i=0;$i<count($where);$i++){
    			$this->db->where($where[$i]);
    		}
    	}
    	
    	if($user_role == '3')
		{
			$this->db->where('teacher_id = '.$user_id);
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
    
    public function getdata_teacher_attendance($limit = 0, $offset = 0, $order_by = "section_title", $sort_order = "asc", $search_data,$week)
    {
    	if (!empty($search_data)) {
    		!empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
    		!empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
    		!empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";
    		!empty($search_data['elsd_id']) ? $data['elsd_id'] = $search_data['elsd_id'] : "";
    	}
		$query = "SELECT 
					school_year_title,
					course_title,
					course_class_id,
					section_title,
					shift,
					campus,
					 ";
		$query .= " first_name,
					elsd_id
					FROM course_class
					left join school_year using(school_year_id)
					left join courses using(course_id)
					left join course_section using(section_id)
					left join users on(primary_teacher_id = user_id)
    			 ";
		$querywhere = "";
		if((!empty($data)))
		{
			foreach($data AS $key=>$val)
				$querywhere .= " ($key LIKE '$val') OR";
		}
		if(trim($querywhere) <> "")
		{
			$querywhere = trim(trim($querywhere),"OR");
			$query = $query." WHERE ".$querywhere;
		}
		
		if(trim($order_by) <> "")
			$query = $query." ORDER BY ".$order_by;
		if(trim($sort_order) <> "")
			$query = $query." ".$sort_order;	
		if($offset > 0)
			$query = $query." LIMIT ".$offset.",".$limit;
		else if($limit != "")
			$query = $query." LIMIT ".$limit;	
		
    	$query_res = $this->db->query($query);
    	$arrRes = $query_res->result_array();
    	$strCourseClassIDs = "";
    	$arrCourseClassIDs = "";
    	$arrOriData = array();
    	foreach($arrRes AS $arrData)
    	{
			$arrOriData[$arrData["course_class_id"]] = $arrData;
    		$arrCourseClassIDs[] = $arrData["course_class_id"];
    		$strCourseClassIDs .= $arrData["course_class_id"].",";
    	}
    	
    	$strCourseClassIDs = trim(trim($strCourseClassIDs),",");
    	$arrRes_att = array();
    	
    	if($strCourseClassIDs != "")
    	{
	    	$query_att = "
							SELECT course_class_id,attendeance_week,DATE_FORMAT(created_date,'%Y%m%d%H%i%s') AS lastdate 
							FROM attendance_report
							WHERE attendance_report.course_class_id IN($strCourseClassIDs)
							GROUP BY course_class_id,attendeance_week;
						  ";
			$query_att_res = $this->db->query($query_att);
    		$arrRes_att = $query_att_res->result_array();
    	}
    		
		$arrWeekAtt = array();
    	if($week > 0)
		{
			foreach($arrCourseClassIDs AS $classid)
			{
				for($i=1;$i<=$week;$i++)
				{
					$arrOriData[$classid]["week_".$i] = "";
					
					foreach($arrRes_att AS $arrDataAtt)
					{
						if($arrDataAtt["attendeance_week"] == $i && $arrDataAtt["course_class_id"] == $classid)
							$arrOriData[$classid]["week_".$i] = $arrDataAtt["lastdate"];
					}
				}	
			}
		}
		return $arrOriData;
    }
    
    public function count_teacher_attendance($search_data)
    {
    	if (!empty($search_data)) {
    		!empty($search_data['section_title']) ? $data['section_title'] = $search_data['section_title'] : "";
    		!empty($search_data['course_title']) ? $data['course_title'] = $search_data['course_title'] : "";
    		!empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";
    		!empty($search_data['elsd_id']) ? $data['elsd_id'] = $search_data['elsd_id'] : "";
    	}
		$query = "SELECT *
				FROM course_class
				left join school_year using(school_year_id)
				left join courses using(course_id)
				left join course_section using(section_id)
				left join users on(primary_teacher_id = user_id)
    			 ";
		$querywhere = "";
		if((!empty($data)))
		{
			foreach($data AS $key=>$val)
				$querywhere .= " ($key LIKE '$val') OR";
		}
		if(trim($querywhere) <> "")
		{
			$querywhere = trim(trim($querywhere),"OR");
			$query = $query." WHERE ".$querywhere;
		}
		$query_res = $this->db->query($query);
		return $query_res->num_rows();
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
	
	function get_attendance_log($student_id) {
		$this->db->select('DATE_FORMAT(attendance_report_log.logdate,"%Y-%m-%d") AS chndate,users.first_name,attendance_report_log.attendeance_week AS a_week,attendance_report_log.absent_hour AS a_hour,attendance_report_log.absent_hour_new AS a_hour_new',FALSE);
		$this->db->from('attendance_report_log');
		$this->db->join('users', 'users.user_id = attendance_report_log.logby','left');
    	$this->db->where('student_id',$student_id);
		$this->db->order_by('attendeance_week,logdate', 'DESC');
		$attendance_report_log = $this->db->get();
		
		$arrRet = array();
		foreach($attendance_report_log->result_array() AS $rowdata)
		{
			$arrRet[$rowdata['a_week']][] = array("first_name"=>$rowdata['first_name'],
												"chndate"=>$rowdata['chndate'],
												"a_hour"=>$rowdata['a_hour'],
												"a_hour_new"=>$rowdata['a_hour_new']
												);
		
		} 
		
		return $arrRet;
	}
	
	function get_log_enable_week() {
		$this->db->select('*');
		$this->db->from('attendance_week_activation_time');
		$data_attendance_week_activation_time = $this->db->get();
		$arrActivateTime = $data_attendance_week_activation_time->result_array(); 
		$activate_time = "00:00:00";
		
		if(isset($arrActivateTime[0]["activation_time"]))
			$activate_time = $arrActivateTime[0]["activation_time"];
						
		$this->db->select('*');
		$this->db->from('enable_school_week');
		$this->db->where('CONCAT(last_date," '.$activate_time.'") <= NOW()');
		$this->db->order_by('week_id', 'ASC');
		$data_enableweek = $this->db->get();
		return $data_enableweek->result(); 
	}
	
	public function attendance_log($classID,$studentID,$weekNo,$weekAbsentHours) {
		$user_id = $this->session->userdata('user_id');
		$change_date = date('Y-m-d H:i:s');
		$classID = (int)$classID;
		
		$query = "SELECT * 
				FROM `attendance_report`
				WHERE 
				`course_class_id` = $classID 
				AND `student_id` = $studentID
				AND `attendeance_week` = $weekNo
				AND absent_hour = $weekAbsentHours
    			 ";
		$query_res = $this->db->query($query);
		if(!$query_res->num_rows())
		{
			$sql = "INSERT INTO `attendance_report_log` (`attendeance_id` ,
				`student_id` ,
				`school_year_id` ,
				`school_id` ,
				`attendeance_date` ,
				`attendeance_week` ,
				`attendeance_month` ,
				`attendeance_year` ,
				`course_class_id` ,
				`absent_hour` ,
				absent_hour_new,
				`attendeance_code_id` ,
				`teacher_id` ,
				`attendance_reason` ,
				`created_date` ,
				`updated_date` ,
				`logby` ,
				`logdate`
				)
				SELECT `attendeance_id`, `student_id`, `school_year_id`, `school_id`, `attendeance_date`, `attendeance_week`, `attendeance_month`, `attendeance_year`, `course_class_id`, `absent_hour`,'$weekAbsentHours', `attendeance_code_id`, `teacher_id`, `attendance_reason`, `created_date`, `updated_date`,$user_id,'$change_date' FROM `attendance_report`
				WHERE 
				`course_class_id` =$classID 
				AND `student_id` =$studentID
				AND `attendeance_week` =$weekNo
				
				";
				$this->db->query($sql);
		}
    }
	
	public function get_activation_time(){
		$this->db->select('*');
		$this->db->from('attendance_week_activation_time');
		$data_attendance_week_activation_time = $this->db->get();
		return $data_attendance_week_activation_time->result_array(); 
	}
	
	public function get_enableweek($school_year_id,$activate_time){
		$this->db->select('*');
		$this->db->from('enable_school_week');
		$this->db->where('school_year_id',$school_year_id);
		$this->db->where('CONCAT(last_date," '.$activate_time.'") <= NOW()');
		$this->db->order_by('week_id', 'ASC');
		$data_enableweek = $this->db->get();
		return $data_enableweek->result();
	}
	public function get_schoolenableweek($pm_time,$am_time){
		$this->db->select('*,DATE_FORMAT(CONCAT(last_date," '.$pm_time.'"),"%Y%m%d") AS lastsubmitiondate_pm,DATE_FORMAT(CONCAT(last_date," '.$am_time.'"),"%Y%m%d") AS lastsubmitiondate_am',FALSE);
		$this->db->from('enable_school_week');
		$this->db->where('school_year_id',1);
		$this->db->where('last_date <= NOW()');
		$this->db->order_by('week_id', 'ASC');
		$data_enableweek = $this->db->get();
		return $data_enableweek->result();
	}
	public function truncate_late_attendance(){
		$this->db->truncate('late_attendance'); 
	}
	
	public function get_late_attendance_week(){
		$this->db->select('school_year_id,week_id,CONCAT(last_date," ",am_time) AS am_shift,CONCAT(last_date," ",pm_time) AS pm_shift',FALSE);
		$this->db->from('enable_school_week,enable_school_time');
		$this->db->where('school_year_id',1);
		$this->db->order_by('week_id', 'ASC');
		$data_enableweek = $this->db->get();
		return $data_enableweek->result();
	}
}
/* End of file attendance_model.php */