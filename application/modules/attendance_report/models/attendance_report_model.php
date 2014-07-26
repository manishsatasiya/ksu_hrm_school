<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance_report_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * get_attendance_report: get the student attendance report for login teacher
     *
     * @return array
     *
     */

    public function get_attendance_report($userid=0,$limit = 0, $offset = 0, $order_by = "student_id", $sort_order = "asc", $search_data) 
	{
		$arrRet = array();
        if(!empty($search_data)) 
		{
            !empty($search_data['student_id']) ? $data['student_id'] = $search_data['student_id'] : "";
            !empty($search_data['first_name']) ? $data['first_name'] = $search_data['first_name'] : "";
        }
        $this->db->select('attendance_report.attendeance_id, 
						  attendance_report.student_id,	
						  attendance_report.school_year_id, 
						  attendance_report.school_id, 
						  student.first_name AS student_name, 
						  section,
						  course_code,
						  classroom,
						  category_code,
						  shift,
						  teacher.first_name AS teacher_name, 
						  attendance_report.absent_hour, 
						  restricted_hours,
						  attendance_report.attendeance_week, 
						  attendance_report.attendeance_year, 
						  attendance_report.course_class_id, 
						  attendance_report.attendeance_code_id, 
						  attendance_report.teacher_id, 
						  attendance_report.created_date');
        $this->db->from('attendance_report');
        $this->db->join('users AS student', 'attendance_report.student_id = student.user_id','left');
		$this->db->join('users AS teacher', 'attendance_report.teacher_id = teacher.user_id','left');
		$this->db->join('course_class', 'attendance_report.course_class_id = course_class.course_class_id','left');
		$this->db->join('courses', 'course_class.course_id = courses.course_id','left');
		$this->db->join('course_category', 'course_class.category_id = course_category.category_id','left');
		$this->db->where('attendance_report.teacher_id',$userid);
        !empty($data) ? $this->db->or_like($data) : "";
        $this->db->order_by($order_by, $sort_order);
        $this->db->limit($limit, $offset);
		$query = $this->db->get();
		
        if($query->num_rows() > 0) 
		{
			foreach($query->result_array() AS $row)
			{
				$arrRet[$row["student_id"]]["attendeance_id"] = $row["attendeance_id"];
				$arrRet[$row["student_id"]]["school_year_id"] = $row["school_year_id"];
				$arrRet[$row["student_id"]]["school_id"] = $row["school_id"];
				$arrRet[$row["student_id"]]["student_name"] = $row["student_name"];
				$arrRet[$row["student_id"]]["section"] = $row["section"];
				$arrRet[$row["student_id"]]["course_code"] = $row["course_code"];
				$arrRet[$row["student_id"]]["classroom"] = $row["classroom"];
				$arrRet[$row["student_id"]]["category_code"] = $row["category_code"];
				$arrRet[$row["student_id"]]["shift"] = $row["shift"];
				$arrRet[$row["student_id"]]["teacher_name"] = $row["teacher_name"];
				$arrRet[$row["student_id"]]["absent_hour"] = $row["absent_hour"];
				$arrRet[$row["student_id"]]["attendeance_year"] = $row["attendeance_year"];
				$arrRet[$row["student_id"]]["course_class_id"] = $row["course_class_id"];
				$arrRet[$row["student_id"]]["attendeance_code_id"] = $row["attendeance_code_id"];
				$arrRet[$row["student_id"]]["teacher_id"] = $row["teacher_id"];
				$arrRet[$row["student_id"]]["created_date"] = $row["created_date"];
				$arrRet[$row["student_id"]]["restricted_hours"] = $row["restricted_hours"];
				$arrRet[$row["student_id"]]["week"][$row["school_year_id"]][$row["attendeance_week"]] = $row["attendeance_week"];
				$arrRet[$row["student_id"]]["week"][$row["school_year_id"]][$row["attendeance_week"]] = $row["absent_hour"];
			}
            return $arrRet;
        }
    }
	
	 public function get_teacher_course_class($userid=0) 
	{
		$arrRet = array();
        $this->db->select('course_class_id, 
						  course_class.school_year_id,
						  school_type,
						  school_week,
						  restricted_hours');
        $this->db->from('course_class');
        $this->db->join('school_year', 'course_class.school_year_id = school_year.school_year_id','left');
		$this->db->where('primary_teacher_id',$userid);
        $query = $this->db->get();
		
        if($query->num_rows() > 0) 
		{
			foreach($query->result_array() AS $row)
			{
				$arrRet["course_class_id"] = $row["course_class_id"];
				$arrRet["school_year_id"] = $row["school_year_id"];
				$arrRet["school_type"] = $row["school_type"];
				$arrRet["school_week"] = $row["school_week"];
				$arrRet["restricted_hours"] = $row["restricted_hours"];
			}
            return $arrRet;
        }
    }
}

/* End of file profile_model.php */