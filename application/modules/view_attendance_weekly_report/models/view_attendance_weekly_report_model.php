<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View_attendance_weekly_report_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_members: get the school year data
     *
     * @param int $limit db limit (members per page)
     * @param int $offset db offset (current page)
     * @param int $order_by db sort order
     * @param string $sort_order asc or desc
     * @param array $search_data search input
     * @return mixed
     *
     */

    public function get_report_data($school_year_id,$week,$campus_id) 
	{
		$arrData = array();	
    	$this->db->select('attendance_weekly_report_temp.*,courses.course_title');
    	$this->db->from('attendance_weekly_report_temp');
    	$this->db->join('courses', 'attendance_weekly_report_temp.course_id = courses.course_id','left');
    	if($week){
    		$this->db->where('week_id', $week);
    	}
    	if($school_year_id){
    		$this->db->where('attendance_weekly_report_temp.school_year_id', $school_year_id);
    	}
		if($campus_id){
			$this->db->where('attendance_weekly_report_temp.campus_id', $campus_id);
		}
    	
    	$query = $this->db->get();
    	$arrResData = $query->result_array(); 
    	if($query->num_rows() > 0) 
		{
			foreach($arrResData AS $row)
			{
				if($row["number_without_absent_student"] < 0)
					$row["number_without_absent_student"] = 0;
					
				$arrData[$row["course_id"]][$row["course_title"]] = array("number_registered_student" => $row["number_registered_student"],
																			  "number_discontinued_student" => $row["number_discontinued_student"],	
																			  "number_regular_student" => $row["number_regular_student"],	
																			  "number_without_absent_student" => $row["number_without_absent_student"],	
																			  "number_student_less_than_10_per" => $row["number_student_less_than_10_per"],	
																			  "number_student_between_10_15_per" => $row["number_student_between_10_15_per"],	
																			  "number_student_between_10_20_per" => $row["number_student_between_10_20_per"],	
																			  "number_student_between_20_25_per" => $row["number_student_between_20_25_per"],	
																			  "number_student_more_than_25_per" => $row["number_student_more_than_25_per"],
																			  "course_title" => $row["course_title"]
																			 );
			}
    	}
		return $arrData;
    }
	
	public function set_course_class_with_enable_week($school_year_id,$week,$remain_temp_table=0)
    {
		$query = "TRUNCATE TABLE attendance_weekly_report ";
		$this->db->query($query);
		
		if($remain_temp_table == 0)
		{
			$query = "TRUNCATE TABLE attendance_weekly_report_temp ";
			$this->db->query($query);
		}
		
    	$query = "SELECT 
						course_class.course_class_id, 
						course_class.primary_teacher_id, 
						users.campus_id, 
						course_class.course_id, 
						course_class.school_year_id, 
						course_class.section_id, 
						week_id, 
						no_of_day, 
						total_hours_all_weeks AS max_hours,
						max_hours AS course_max_absent_hrs
				  FROM course_class
				  LEFT JOIN enable_school_week USING (school_year_id)
				  LEFT JOIN school_year USING (school_year_id)
				  LEFT JOIN courses USING (course_id)
				  LEFT JOIN users ON(user_id = primary_teacher_id)
				  WHERE course_class.school_year_id = '$school_year_id'
				 ";		 
		$query_res = $this->db->query($query);
		$arrCourseClass = $query_res->result_array();
		
		if(is_array($arrCourseClass) && count($arrCourseClass))
		{
			$cnt = 0;
			$ins_query = "";
			foreach($arrCourseClass AS $row)
			{
				$query = "INSERT IGNORE INTO  attendance_weekly_report
						(section_id,
						 course_class_id,
						 teacher_id,
						 campus_id,
						 school_year_id,
						 course_id,
						 course_max_hours,
						 course_weekly_absent_hrs,
						 course_max_absent_hrs,
					     attendance_week,
					     weekly_no_of_day
						) VALUES";
				$ins_query .= " 
					   (
						'".$row["section_id"]."',
						'".$row["course_class_id"]."',
						'".$row["primary_teacher_id"]."',
						'".$row["campus_id"]."',
						'".$row["school_year_id"]."',
						'".$row["course_id"]."',
						'".$row["max_hours"]."',
						'".(($row["course_max_absent_hrs"]/5)*$row["no_of_day"])."',
						'".$row["course_max_absent_hrs"]."',
						'".$row["week_id"]."',
						'".$row["no_of_day"]."'
					   ),";
				
				$cnt++;

				if($cnt%1000 == 0 && $ins_query != "")
				{
					$ins_query = trim(trim($ins_query),",");
					$mainsql = $query.$ins_query;	
					$this->db->query($mainsql);	
					$ins_query = "";
				}
			}
			if($ins_query != "")
			{
				$ins_query = trim(trim($ins_query),",");
				$mainsql = $query.$ins_query;	
				$this->db->query($mainsql);
			}
		}
    }
	
	public function update_attendance_student_report_count()
	{
		$query = "UPDATE attendance_weekly_report SET number_registered_student = 
				(SELECT count(*) FROM users WHERE users.section_id = attendance_weekly_report.section_id ) ";
		$this->db->query($query);
		
		$query = "UPDATE attendance_weekly_report SET number_discontinued_student = 
				(SELECT count(*) FROM users WHERE users.section_id = attendance_weekly_report.section_id AND MATCH(academic_status) AGAINST ('Withdrawn' IN BOOLEAN MODE)) ";
		$this->db->query($query);
		
		$this->update_discontinue();
		
		$query = "UPDATE attendance_weekly_report SET number_discontinued_student = number_discontinued_student+number_discontinued_student_absent";
		$this->db->query($query);
		
		$query = "UPDATE attendance_weekly_report SET number_regular_student = (number_registered_student-number_discontinued_student)";
		$this->db->query($query);
	}
	
	public function update_discontinue()
	{
		$query = "SELECT course_class.course_class_id FROM course_class ORDER BY course_class_id ASC";		 
		$query_res = $this->db->query($query);
		$arrCourseClass = $query_res->result_array();
		
		if(is_array($arrCourseClass) && count($arrCourseClass))
		{
			$current = 0;
			foreach($arrCourseClass AS $row)
			{
				$query = "SELECT attendance_week,course_weekly_absent_hrs FROM attendance_weekly_report WHERE course_class_id = '".$row["course_class_id"]."' ORDER BY attendance_week ASC";
				$query_res = $this->db->query($query);
				$arrWeekAttendance = $query_res->result_array();
				
				$queryWhere = "";
				foreach($arrWeekAttendance AS $row_week)
				{
					$current_week = $row_week["attendance_week"];
					$current_week_abs_hours = $row_week["course_weekly_absent_hrs"];
					
					$queryWhere .= " AND attendance_report_replica.absent_hour_week_$current_week >= '$current_week_abs_hours' ";
					
					$query = "UPDATE attendance_weekly_report SET number_discontinued_student_absent =  
							(SELECT count(*) 
							 FROM attendance_report_replica 
							 JOIN users ON(attendance_report_replica.student_id=users.user_id) 
							 WHERE attendance_report_replica.course_class_id = '".$row["course_class_id"]."' 
							 $queryWhere
							 AND users.academic_status != 'Withdrawn'
							) 
							WHERE attendance_weekly_report.course_class_id = '".$row["course_class_id"]."' 
							AND attendance_weekly_report.attendance_week = '".$row_week["attendance_week"]."' 
							";
					$this->db->query($query);
				}
			}
		}	
	}
	
	public function create_attendance_temp_table_using_class_with_enable_week()
	{
		$arrEnableWeek = $this->get_school_enable_week();
		$queryCreateWeek = "";
		$queryIndexWeek = "";
		
		$query = "DROP TABLE IF EXISTS `attendance_report_replica";
		$this->db->query($query);
		
		foreach($arrEnableWeek AS $rowEnableWeek)
		{
			$week = $rowEnableWeek["week_id"];
			
			$queryCreateWeek .= "absent_hour_week_$week double NOT NULL,";
			$queryIndexWeek .= "KEY `absent_hour_week_$week` (`absent_hour_week_$week`),";
		}
		
		if($queryCreateWeek != "")
		{
			$query = "CREATE TABLE `attendance_report_replica` (
					  `attendeance_id` bigint(20) NOT NULL auto_increment,
					  `course_class_id` bigint(20) NOT NULL,
					  `student_id` bigint(20) NOT NULL,
					  $queryCreateWeek
					  PRIMARY KEY  (`attendeance_id`),
					  UNIQUE KEY `course_class_id_2` (`course_class_id`,`student_id`),
					  KEY `student_id` (`student_id`),
					  $queryIndexWeek
					  KEY `course_class_id` (`course_class_id`)
					)";
			$this->db->query($query);
		}
		
		$query = "INSERT IGNORE INTO attendance_report_replica
				 (
					course_class_id,
				 	student_id
				 )
		         SELECT course_class_id,
				 		student_id
		         FROM attendance_report
		         ";
		$this->db->query($query);
		
		foreach($arrEnableWeek AS $rowEnableWeek)
		{
			$week = $rowEnableWeek["week_id"];
			
			$queryCreateWeek .= "absent_hour_week_$week double NOT NULL,";
			$queryIndexWeek .= "KEY `absent_hour_week_$week` (`absent_hour_week_$week`),";
			
			$query = "UPDATE attendance_report_replica
					  JOIN attendance_report 
					  ON(attendance_report_replica.course_class_id=attendance_report.course_class_id AND attendance_report_replica.student_id=attendance_report.student_id AND attendeance_week = '$week')
					  SET absent_hour_week_$week = absent_hour
			         ";
			$this->db->query($query);
		}
	}
	
	public function get_weekly_data_for_report()
	{
		$arrEnableWeek = $this->get_school_enable_week();
		$onlyonce = 1;
		$arrCourseAbsentHrs = array();
		
		foreach($arrEnableWeek AS $rowEnableWeek)
		{
			$school_year_id = $rowEnableWeek["school_year_id"];
			$week = $rowEnableWeek["week_id"];
			
			if($onlyonce == 1)
			{
				$this->create_attendance_temp_table_using_class_with_enable_week();
				$this->set_course_class_with_enable_week($school_year_id,$week);
				$this->update_attendance_student_report_count();
				
				$query = "SELECT course_id,attendance_week,course_weekly_absent_hrs FROM attendance_weekly_report GROUP BY course_id,attendance_week ORDER BY course_id,attendance_week ASC";
				$query_res = $this->db->query($query);
				$arrWeekAttendance = $query_res->result_array();
				
				foreach($arrWeekAttendance AS $row_week)
				{
					$arrCourseAbsentHrs[$row_week["course_id"]][$row_week["attendance_week"]] = $row_week["course_weekly_absent_hrs"];
				}
			}
				
			$onlyonce = 0;
			
			$arrData = array();
			$query = "SELECT 
							SUM(attendance_weekly_report.number_registered_student) AS number_registered_student,
							SUM(attendance_weekly_report.number_discontinued_student) AS number_discontinued_student,
							SUM(attendance_weekly_report.number_regular_student) AS number_regular_student,
							GROUP_CONCAT( DISTINCT course_class_id) AS course_class_ids,
							courses.course_id,
							courses.course_title,
							attendance_weekly_report.campus_id,
							attendance_weekly_report.course_max_hours
					  FROM 	attendance_weekly_report	
					  LEFT JOIN courses USING (course_id)
					  WHERE attendance_weekly_report.school_year_id = '$school_year_id'
					  AND attendance_weekly_report.attendance_week = '$week' GROUP BY attendance_weekly_report.course_id,campus_id ";		 
			$query_res = $this->db->query($query);
			$arrCourseClass = $query_res->result_array();
			foreach($arrCourseClass AS $row)
			{
				$course_class_ids = $row["course_class_ids"];
				$course_max_hours = $row["course_max_hours"];
				$arrCourseWeekAbsentHrs = array();
				
				if(isset($arrCourseAbsentHrs[$row["course_id"]]))
					$arrCourseWeekAbsentHrs = $arrCourseAbsentHrs[$row["course_id"]];
					
				$arrResData = $this->update_absent_student_report_count($school_year_id,$week,$course_class_ids,$course_max_hours,$arrCourseWeekAbsentHrs);
				$mainsql = "INSERT IGNORE INTO  attendance_weekly_report_temp
						(school_year_id,
						 week_id,
						 course_id,
						 campus_id,
						 number_registered_student,
						 number_discontinued_student,
					     number_regular_student,
					     number_without_absent_student,
					     number_student_less_than_10_per,
					     number_student_between_10_15_per,
					     number_student_between_10_20_per,
					     number_student_between_20_25_per,
					     number_student_more_than_25_per
						) VALUES
					   (
						'".$school_year_id."',
						'".$week."',
						'".$row["course_id"]."',
						'".$row["campus_id"]."',
						'".$row["number_registered_student"]."',
						'".$row["number_discontinued_student"]."',
						'".$row["number_regular_student"]."',
						'".($row["number_regular_student"]-$arrResData["number_with_absent_student"])."',
						'".$arrResData["number_student_less_than_10_per"]."',
						'".$arrResData["number_student_between_10_15_per"]."',
						'".$arrResData["number_student_between_10_20_per"]."',
						'".$arrResData["number_student_between_20_25_per"]."',
						'".$arrResData["number_student_more_than_25_per"]."'
					   )";	
				$this->db->query($mainsql);		   
			}
		}
	}
	
	public function update_weekly_data_for_report()
	{
		$arrEnableWeek = $this->get_school_enable_week();
		$onlyonce = 1;
		foreach($arrEnableWeek AS $rowEnableWeek)
		{
			$school_year_id = $rowEnableWeek["school_year_id"];
			$week = $rowEnableWeek["week_id"];
			
			if($onlyonce == 1)
			{
				$this->set_course_class_with_enable_week($school_year_id,$week,$remain_temp_table=1);
				$this->update_attendance_student_report_count($week);
			}
				
			$onlyonce = 0;
			
			$arrData = array();
			$query = "SELECT 
							SUM(attendance_weekly_report.number_registered_student) AS number_registered_student,
							SUM(attendance_weekly_report.number_discontinued_student) AS number_discontinued_student,
							SUM(attendance_weekly_report.number_regular_student) AS number_regular_student,
							GROUP_CONCAT( DISTINCT course_class_id) AS course_class_ids,
							courses.course_id,
							courses.course_title,
							attendance_weekly_report.course_max_hours
					  FROM 	attendance_weekly_report	
					  LEFT JOIN courses USING (course_id)
					  WHERE attendance_weekly_report.school_year_id = '$school_year_id'
					  AND attendance_weekly_report.attendance_week = '$week' GROUP BY attendance_weekly_report.course_id ";		 
			$query_res = $this->db->query($query);
			$arrCourseClass = $query_res->result_array();
			foreach($arrCourseClass AS $row)
			{
				$mainsql = "UPDATE attendance_weekly_report_temp
							SET
							 number_registered_student = '".$row["number_registered_student"]."',
							 number_discontinued_student = '".$row["number_discontinued_student"]."',
							 number_regular_student = '".$row["number_regular_student"]."'
							WHERE  
							school_year_id = '".$school_year_id."'
							AND week_id = '".$week."'
							AND course_id = '".$row["course_id"]."'
							";	
				$this->db->query($mainsql);		   
			}
		}
	}
	
	public function update_absent_student_report_count($school_year_id,$week,$course_class_ids,$course_max_hours,$arrCourseWeekAbsentHrs)
	{
		$arrResData = array();
		$ten = ROUND((10 * $course_max_hours )/100);
		$fifteen = ROUND((15 * $course_max_hours )/100);
		$twenty = ROUND((20 * $course_max_hours )/100);
		$twenty_five = ROUND((25 * $course_max_hours )/100);
		
		$queryWhere = "";
		$queryWhereForSum = "";
		
		if(is_array($arrCourseWeekAbsentHrs) && count($arrCourseWeekAbsentHrs) > 0)
		{
			foreach($arrCourseWeekAbsentHrs AS $courseWeek=>$courseAbsentHrs)
			{
				if($courseWeek <= $week){
					$queryWhere .= "  absent_hour_week_$courseWeek < '$courseAbsentHrs' OR ";	
					$queryWhereForSum .= "absent_hour_week_$courseWeek+";	
				}
			}
		}
             $queryWhere = trim(trim($queryWhere),'OR');
             $queryWhereForSum = trim(trim($queryWhereForSum),'+');
             
             if($queryWhere != '')
                     $queryWhere = 'AND ('.$queryWhere.')';
		//With absent
		$query = "SELECT *
				 FROM  attendance_report_replica 
				 LEFT JOIN users ON(user_id = student_id)
				 WHERE 
				 attendance_report_replica.course_class_id IN($course_class_ids)
				 AND NOT MATCH(academic_status) AGAINST ('Withdrawn' IN BOOLEAN MODE)
				 AND active = '1'
				 AND user_id IS NOT NULL
				 $queryWhere
				 GROUP BY student_id HAVING SUM($queryWhereForSum) >= '1'
				";
		$query_res = $this->db->query($query);
		$arrResData["number_with_absent_student"] = $query_res->num_rows();
		
		//Less than 10 %
		$query = "SELECT *
				 FROM  attendance_report_replica 
				 LEFT JOIN users ON(user_id = student_id)
				 WHERE 
				 attendance_report_replica.course_class_id IN($course_class_ids)
				 AND NOT MATCH(academic_status) AGAINST ('Withdrawn' IN BOOLEAN MODE)
				 AND active = '1'
				 AND user_id IS NOT NULL
				 $queryWhere
				 GROUP BY student_id HAVING SUM($queryWhereForSum) >= '1' AND SUM($queryWhereForSum) <  '$ten'
				";
		$query_res = $this->db->query($query);
		$arrResData["number_student_less_than_10_per"] = $query_res->num_rows();
		
		//Between 10 to 15 %
		$query = "SELECT *
				 FROM  attendance_report_replica 
				 LEFT JOIN users ON(user_id = student_id)
				 WHERE 
				 attendance_report_replica.course_class_id IN($course_class_ids)
				 AND NOT MATCH(academic_status) AGAINST ('Withdrawn' IN BOOLEAN MODE)
				 AND active = '1'
				 AND user_id IS NOT NULL
				 $queryWhere
				 GROUP BY student_id HAVING SUM($queryWhereForSum) >= '$ten' AND SUM($queryWhereForSum) < '$fifteen'
				";
		$query_res = $this->db->query($query);
		$arrResData["number_student_between_10_15_per"] = $query_res->num_rows();
		
		//Between 15 to 20 %
		$query = "SELECT *
				 FROM  attendance_report_replica 
				 LEFT JOIN users ON(user_id = student_id)
				 WHERE 
				 attendance_report_replica.course_class_id IN($course_class_ids)
				 AND NOT MATCH(academic_status) AGAINST ('Withdrawn' IN BOOLEAN MODE)
				 AND active = '1'
				 AND user_id IS NOT NULL
				 $queryWhere
				 GROUP BY student_id HAVING SUM($queryWhereForSum) >= '$fifteen' AND SUM($queryWhereForSum) < '$twenty'
				";
		$query_res = $this->db->query($query);
		$arrResData["number_student_between_10_20_per"] = $query_res->num_rows();
		
		//Between 20 to 25 %
		$query = "SELECT *
				 FROM  attendance_report_replica 
				 LEFT JOIN users ON(user_id = student_id)
				 WHERE 
				 attendance_report_replica.course_class_id IN($course_class_ids)
				 AND NOT MATCH(academic_status) AGAINST ('Withdrawn' IN BOOLEAN MODE)
				 AND active = '1'
				 AND user_id IS NOT NULL
				 $queryWhere
				 GROUP BY student_id HAVING SUM($queryWhereForSum) >= '$twenty' AND SUM($queryWhereForSum) < '$twenty_five'
				";
		$query_res = $this->db->query($query);
		$arrResData["number_student_between_20_25_per"] = $query_res->num_rows();
		
		//More than 25 %
		$query = "SELECT *
				 FROM  attendance_report_replica 
				 LEFT JOIN users ON(user_id = student_id)
				 WHERE 
				 attendance_report_replica.course_class_id IN($course_class_ids)
				 AND NOT MATCH(academic_status) AGAINST ('Withdrawn' IN BOOLEAN MODE)
				 AND active = '1'
				 AND user_id IS NOT NULL
				 $queryWhere
				 GROUP BY student_id HAVING SUM($queryWhereForSum) >= '$twenty_five'
				";
		$query_res = $this->db->query($query);
		$arrResData["number_student_more_than_25_per"] = $query_res->num_rows();
		return $arrResData;
	}
	
	public function get_school_enable_week()
	{
		$query = "SELECT enable_school_week.school_year_id,school_year_title,week_id
				  FROM enable_school_week
				  LEFT JOIN school_year USING (school_year_id)
				  WHERE enable_school_week.school_year_id = '1'
				  ORDER BY week_id
				 ";
		$query_res = $this->db->query($query);
		return $query_res->result_array();
	}
	
	public function getweek($id)
	{
		$this->db->select('*');
    	$this->db->from('enable_school_week');
    	$this->db->where('school_year_id',''.$id.'');
    	$this->db->order_by('week_id','asc');
    	$query = $this->db->get();
    	return $query->result_array();
	}
}

/* End of file list_school_year_model.php */
