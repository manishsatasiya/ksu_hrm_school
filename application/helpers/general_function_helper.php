<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('get_ca_lead_teacher_list')) {
    /**
     *
     * get_ca_lead_teacher_list: it's used to get list of teacher
     *
     * @param 
     * @return array
     *
     */
    function get_ca_lead_teacher_list() {
    	$ci =& get_instance();
    	$ci->db->select('*,CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) AS staff_name',FALSE);
    	$ci->db->from('users');
    	$ci->db->join('user_roll','users.user_roll_id = user_roll.user_roll_id','left');    	
    	//$ci->db->where('users.user_roll_id IN(12)');   
    	$ci->db->where('user_roll.is_ca_lead','Y');
    	if($ci->session->userdata('role_id') > 4 && ($ci->session->userdata('campus_id') > 0 || $ci->session->userdata('campus') != ""))
		{
			if($ci->session->userdata('campus_id') > 0)
				$ci->db->where('users.campus_id',$ci->session->userdata('campus_id'));
			else if($ci->session->userdata('campus') != "")
				$ci->db->where('users.campus',$ci->session->userdata('campus'));	
		}
		
		$ci->db->order_by('first_name', 'ASC');	
    	$query = $ci->db->get();
		
    	$teacher_data = $query->result_array();
    	$teacher_arr = array();
    	$teacher_arr[0] = '--Select--';
    	foreach ($teacher_data as $teacher_datas){
    		$teacher_arr[$teacher_datas['user_id']."j"] = $teacher_datas['staff_name'];    		
    	}
        return $teacher_arr;
    }
}

if (!function_exists('get_teacher_list')) {
    /**
     *
     * get_teacher_list: it's used to get list of teacher
     *
     * @param 
     * @return array
     *
     */
    function get_teacher_list($campus_id=0) {
    	$ci =& get_instance();
    	$ci->db->select('*,CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) AS staff_name',FALSE);
    	$ci->db->from('users');
    	$ci->db->join('school_campus','school_campus.campus_id = users.campus_id','left');  
    	$ci->db->join('user_roll','users.user_roll_id = user_roll.user_roll_id','left');    	
    	$ci->db->where('users.user_roll_id', '3');   
    	
    	if($campus_id > 0)
		{
			$ci->db->where('school_campus.campus_id',$campus_id);	
		}
		
    	if($ci->session->userdata('role_id') > 4 && ($ci->session->userdata('campus_id') > 0 || $ci->session->userdata('campus') != ""))
		{
			if($ci->session->userdata('campus_id') > 0)
				$ci->db->where('users.campus_id',$ci->session->userdata('campus_id'));
			else if($ci->session->userdata('campus') != "")
				$ci->db->where('users.campus',$ci->session->userdata('campus'));	
		}
		
		$ci->db->order_by('first_name', 'ASC');	
    	$query = $ci->db->get();
    	$teacher_data = $query->result_array();
    	$teacher_arr = array();
    	$teacher_arr[0] = '--Select--';
    	foreach ($teacher_data as $teacher_datas){
    		$teacher_arr[$teacher_datas['user_id']."j"] = $teacher_datas['staff_name'];    		
    	}
        return $teacher_arr;
    }
}

if (!function_exists('get_student_list')) {
	/**
	 *
	 * get_teacher_list: it's used to get list of teacher
	 *
	 * @param
	 * @return array
	 *
	 */
	function get_student_list() {
		$ci =& get_instance();
		$ci->db->select('*,CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) AS staff_name',FALSE);
		$ci->db->from('users');
		$ci->db->join('user_roll','users.user_roll_id = user_roll.user_roll_id','left');
		$ci->db->where('users.user_roll_id', '4');
		$ci->db->order_by('first_name', 'ASC');	
		$query = $ci->db->get();
		$student_data = $query->result_array();
		$student_arr = array();
		$student_arr[0] = '--Select--';
		foreach ($student_data as $student_datas){
			$student_arr[$student_datas['user_id']."j"] = $student_datas['staff_name'];
		}
		return $student_arr;
	}
}


if (!function_exists('get_other_user_list')) {
	/**
	 *
	 * get_teacher_list: it's used to get list of teacher
	 *
	 * @param
	 * @return array
	 *
	 */
	function get_other_user_list() {
		$ci =& get_instance();
		$ci->db->select('*,CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) AS staff_name',FALSE);
		$ci->db->from('users');
		$ci->db->where_not_in('users.user_roll_id',array('1','3'));
		$ci->db->order_by('first_name', 'ASC');	
		$query = $ci->db->get();
		$student_data = $query->result_array();
		$student_arr = array();
		$student_arr[0] = '--Select--';
		foreach ($student_data as $_student_data){
			$student_arr[$_student_data['user_id']] = $_student_data['staff_name'].' - '.$_student_data['elsd_id'];
		}
		return $student_arr;
	}
}


if (!function_exists('get_school_year_list')) {
    /**
     *
     * get_teacher_list: it's used to get list of teacher
     *
     * @param 
     * @return array
     *
     */
    function get_school_year_list($where=array()) {
    	$ci =& get_instance();
    	$ci->db->select('*');
		//$ci->db->join('school','school.school_id = school_year.school_id','left');
    	$ci->db->from('school_year');
		if($where){
			foreach($where as $key => $val){
				$ci->db->where(''.$key.'',''.$val.'');
			}
		}
		$ci->db->order_by('school_year_title', 'ASC');	
    	$query = $ci->db->get();
		$school_year_data = $query->result_array();
		$school_year_arr = array();
    	$school_year_arr[0] = '--Select--';
    	foreach ($school_year_data as $school_year_datas){
    		$school_year_arr[$school_year_datas['school_year_id']."j"] = $school_year_datas['school_year_title'];    		
    	}
        return $school_year_arr;
    }
}

if (!function_exists('get_school_year_title')) {
    /**
     *
     * get_teacher_list: it's used to get list of teacher
     *
     * @param 
     * @return array
     *
     */
    function get_school_year_title($where=array()) {
    	$ci =& get_instance();
    	$ci->db->select('*');
		//$ci->db->join('school','school.school_id = school_year.school_id','left');
    	$ci->db->from('school_year');
		if($where){
			foreach($where as $key => $val){
				$ci->db->where(''.$key.'',''.$val.'');
			}
		}
		$ci->db->order_by('school_year_title', 'ASC');
    	$query = $ci->db->get();
		$school_year_data = $query->result_array();
		$school_year_arr = array();
    	$school_year_arr[0] = '--Select--';
    	foreach ($school_year_data as $school_year_datas){
    		$school_year_arr[$school_year_datas['school_year_id']."j"] = $school_year_datas['school_year_title'];    		
    	}
        return $school_year_arr;
    }
}

if (!function_exists('get_school_list')) {
    /**
     *
     * get_teacher_list: it's used to get list of teacher
     *
     * @param 
     * @return array
     *
     */
    function get_school_list($where=array()) {
    	$ci =& get_instance();
    	$ci->db->select('*');
    	$ci->db->from('school');
		if($where){
			foreach($where as $key => $val){
				$ci->db->where(''.$key.'',''.$val.'');
			}
		}
    	$query = $ci->db->get();
    	$school_data = $query->result_array();
    	$school_arr = array();
    	$school_arr[0] = '--Select--';
    	foreach ($school_data as $school_datas){
    		$school_arr[$school_datas['school_id']."j"] = $school_datas['school_name'];    		
    	}
        return $school_arr;
    }
}

if (!function_exists('get_course_subject')) {
    /**
     *
     * get_teacher_list: it's used to get list of teacher
     *
     * @param 
     * @return array
     *
     */
    function get_course_subject($where=array()) {
    	$ci =& get_instance();
    	$ci->db->select('*');
    	$ci->db->from('courses_subject');
		if($where){
			foreach($where as $key => $val){
				$ci->db->where(''.$key.'',''.$val.'');
			}
		}
    	$query = $ci->db->get();
    	$course_data = $query->result_array();
    	$course_arr = array();
    	$course_arr[0] = '--Select--';
    	foreach ($course_data as $course_datas){
    		$course_arr[$course_datas['course_subject_id']] = $course_datas['subject_title'];    		
    	}
        return $course_arr;
    }
}

if (!function_exists('get_campus')) {
    /**
     *
     * get_campus: it's used to get list of campus
     *
     * @param 
     * @return array
     *
     */
    function get_campus($where=array()) {
    	$ci =& get_instance();
    	$ci->db->select('*');
    	$ci->db->from('school_campus');
		if($where){
			for($i=0;$i<count($where);$i++){
				$ci->db->where($where[$i]);
			}
		}
		$ci->db->order_by('campus_name', 'ASC');	
    	$query = $ci->db->get();
    	$campus_data = $query->result_array();
    	$campus_arr = array();
    	$campus_arr[0] = '--Select--';
    	foreach ($campus_data as $campus_datas){
    		$campus_arr[$campus_datas['campus_id']."j"] = $campus_datas['campus_name'];    		
    	}
        return $campus_arr;
    }
}

if (!function_exists('get_campus_list')) {
    /**
     *
     * get_campus_list: it's used to get list of campus
     *
     * @param 
     * @return array
     *
     */
    function get_campus_list($want_select=0) {
    	$ci =& get_instance();
    	$ci->db->select('*');
    	$ci->db->from('school_campus');
		/*if($where){
			for($i=0;$i<count($where);$i++){
				$ci->db->where($where[$i]);
			}
		}*/
		
		if($ci->session->userdata('role_id') > 4 && ($ci->session->userdata('campus_id') > 0 || $ci->session->userdata('campus') != ""))
		{
			if($ci->session->userdata('campus_id') > 0)
				$ci->db->where('school_campus.campus_id',$ci->session->userdata('campus_id'));
			else if($ci->session->userdata('campus') != "")
				$ci->db->where('school_campus.campus_name',$ci->session->userdata('campus'));	
		}
		$ci->db->order_by('campus_name', 'ASC');	
    	$query = $ci->db->get();
    	$campus_data = $query->result_array();
    	$campus_arr = array();
    	
		if($want_select == 1)
			$campus_arr["0j"] = '--Select--';
			
    	foreach ($campus_data as $campus_datas){
    		$campus_arr[$campus_datas['campus_id']."j"] = $campus_datas['campus_name'];    		
    	}
    	
    	return $campus_arr;
    }
}

if (!function_exists('get_campus_name')) {
    function get_campus_name($where=array()) {
    	$ci =& get_instance();
    	$ci->db->select('*');
    	$ci->db->from('school_campus');
		if($where){
			for($i=0;$i<count($where);$i++){
				$ci->db->where($where[$i]);
			}
		}
    	$query = $ci->db->get();
    	$campus_data = $query->result_array();
    	$campus_arr = array();
    	foreach ($campus_data as $campus_datas){
    		$campus_arr["campusname"] = $campus_datas['campus_name'];    		
    	}
        return $campus_arr;
    }
}

if (!function_exists('get_course')) {
    /**
     *
     * get_teacher_list: it's used to get list of teacher
     *
     * @param 
     * @return array
     *
     */
    function get_course($where=array()) {
    	$ci =& get_instance();
    	$ci->db->select('*');
    	$ci->db->from('courses');
		if($where){
			for($i=0;$i<count($where);$i++){
				$ci->db->where($where[$i]);
			}
		}
		$ci->db->order_by('course_title', 'ASC');	
    	$query = $ci->db->get();
    	$course_data = $query->result_array();
    	$course_arr = array();
    	$course_arr[0] = '--Select--';
    	foreach ($course_data as $course_datas){
    		$course_arr[$course_datas['course_id']."j"] = $course_datas['course_title'];    		
    	}
        return $course_arr;
    }
}

if (!function_exists('get_course_category')) {
    /**
     *
     * get_teacher_list: it's used to get list of teacher
     *
     * @param 
     * @return array
     *
     */
    function get_course_category($where=array()) {
    	$ci =& get_instance();
    	$ci->db->select('*');
    	$ci->db->from('course_category');
		if($where){
			for($i=0;$i<count($where);$i++){
				$ci->db->where($where[$i]);
			}
		}
    	$query = $ci->db->get();
    	$course_data = $query->result_array();
    	$course_arr = array();
    	$course_arr[0] = '--Select--';
    	foreach ($course_data as $course_datas){
    		$course_arr[$course_datas['category_id']] = $course_datas['category_title'];    		
    	}
        return $course_arr;
    }
	
}
if (!function_exists('get_student_class')) {
    /**
     *
     * get_teacher_list: it's used to get list of teacher
     *
     * @param 
     * @return array
     *
     */
    function get_student_class($where=array()) {
    	$ci =& get_instance();
    	$ci->db->select('student_class.*');
    	$ci->db->from('student_class');
    	$ci->db->join('users','student_class.student_id = users.user_id');
		if($where){
			for($i=0;$i<count($where);$i++){
				$ci->db->where($where[$i]);
			}
		}
    	$query = $ci->db->get();
    	$course_data = $query->result_array();
    	$course_arr = array();
    	
    	foreach ($course_data as $course_datas){
    		$course_arr[$course_datas['student_id']] = $course_datas['course_class_id'];    		
    	}
        return $course_arr;
    }
	
}

if (!function_exists('callback_combobox_check')) {
    /**
     *
     * get_teacher_list: it's used to get list of teacher
     *
     * @param 
     * @return array
     *
     */
    function callback_combobox_check($value) {
		if($value == '0'){
			return FALSE;
		}else{
			return FALSE;
		}
    }
}
if (!function_exists('get_grade_type')) {
	/**
	 *
	 * get_section: it's used to get list of teacher
	 *
	 * @param
	 * @return array
	 *
	 */
	function get_grade_type() {
		$ci =& get_instance();
		$ci->db->select('*');
		$ci->db->from('grade_type');
		$query = $ci->db->get();
		$grade_data = $query->result_array();
		$grade_arr = array();
		$grade_arr[0] = '--Select--';
		foreach ($grade_data as $grade_datas){
			$grade_arr[$grade_datas['grade_type_id']] = $grade_datas['grade_type'];
		}
		return $grade_arr;
	}
}
if (!function_exists('not_access_for')) {
    /**
     *
     * get_teacher_list: it's used to get list of teacher
     *
     * @param 
     * @return array
     *
     */
    function not_access_for($role_ids = array(),$redirect_url='') {
		$ci =& get_instance();
		$usr_role = $ci->session->userdata('role_id');
		
		if (in_array($usr_role,$role_ids)) {
			if($redirect_url){
            	
			}else{
				redirect("/private/no_access");
			}
        }
    }
}


if (!function_exists('get_section')) {
	/**
	 *
	 * get_section: it's used to get list of teacher
	 *
	 * @param
	 * @return array
	 *
	 */
	function get_section($where=array()) {
    	$ci =& get_instance();
    	$ci->db->select('course_section.*');
    	$ci->db->from('course_section');    	
		if($where){
			for($i=0;$i<count($where);$i++){
				$ci->db->where($where[$i]);
			}
		}
		$ci->db->order_by('section_title', 'ASC');	
    	$query = $ci->db->get();
    	$section_data = $query->result_array();
    	$section_arr = array();
    	$section_arr[0] = '--Select--';
    	foreach ($section_data as $section_datas){
    		$section_arr[$section_datas['section_id']."j"] = $section_datas['section_title'];    		
    	}
        return $section_arr;
    }
}

if (!function_exists('download_file')) {
	function download_file($document_path,$file_name) {
		// place this code inside a php file and call it f.e. "download.php"
		$path = $document_path; // change the path to fit your websites document structure
		$fullPath = $path.$file_name;

		if ($fd = fopen ($fullPath, "r")) {
			$fsize = filesize($fullPath);
			$path_parts = pathinfo($fullPath);
			$ext = strtolower($path_parts["extension"]);
			switch ($ext) {
				case "pdf":
					header("Content-type: application/pdf"); // add here more headers for diff. extensions
					header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
					break;
				default;
				header("Content-type: application/octet-stream");
				header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
			}
			header("Content-length: $fsize");
			header("Cache-control: private"); //use this to open files directly
			while(!feof($fd)) {
				$buffer = fread($fd, 2048);
				echo $buffer;
			}
		}
		fclose ($fd);
		exit;
	}
}

if (!function_exists('set_activity_log')) {
	/**
	 *
	 * get_section: it's used to get list of teacher
	 *
	 * @param
	 * @return array
	 *
	 */
	function set_activity_log($datd_id,$action,$parent_menu,$sub_menu,$user_id='') {
		$ci =& get_instance();
		if($user_id != ''){
			$user_id = $user_id;
		}else{
			$user_id = $ci->session->userdata('user_id');
		}
		$session_ID = $ci->session->userdata('session_id');
		$data = array(
				'session_id' => $session_ID,
				'user_id' => $user_id,
				'data_id' => $datd_id,
				'parent_menu' => $parent_menu,
				'sub_menu' => $sub_menu,
				'action' => $action,
				'user_ip' => $_SERVER['REMOTE_ADDR'],
				'created_date' => date('Y-m-d H:i:s')
		);

		$ci->db->insert('user_activity_log', $data);

		return true;
	}
}

if (!function_exists('set_activity_data_log')) {
	/**
	 *
	 * get_section: it's used to get list of teacher
	 *
	 * @param
	 * @return array
	 *
	 */
	function set_activity_data_log($datd_id,$action,$parent_menu,$sub_menu,$tablename,$whrid_column,$user_id='') {
		$ci =& get_instance();
		
		//Get Array data
		$ci->db->select('*');
		$ci->db->from($tablename);
		$ci->db->where($whrid_column,$datd_id);
		$query = $ci->db->get();
		$data_array = $query->result_array();
		
		if($user_id != ''){
			$user_id = $user_id;
		}else{
			$user_id = $ci->session->userdata('user_id');
		}
		$session_ID = $ci->session->userdata('session_id');
		$data = array(
				'session_id' => $session_ID,
				'user_id' => $user_id,
				'data_id' => $datd_id,
				'parent_menu' => $parent_menu,
				'sub_menu' => $sub_menu,
				'action' => $action,
				'user_ip' => $_SERVER['REMOTE_ADDR'],
				'data_array' => serialize($data_array),
				'tablename' => $tablename,
				'primary_field' => $whrid_column,
				'created_date' => date('Y-m-d H:i:s')
		);

		$ci->db->insert('user_activity_log', $data);

		return true;
	}
}

if (!function_exists('time_ago')) {
	/**
	 *
	 * get_section: it's used to get list of teacher
	 *
	 * @param
	 * @return array
	 *
	 */
	function time_ago($date)
 	{
 		if(empty($date)) {
 			return "No date provided";
 		}
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
 		$lengths = array("60","60","24","7","4.35","12","10");
 		$now = time();
 		$unix_date = strtotime($date);
 		// check validity of date
 		if(empty($unix_date)) {
 			return "Bad date";
		}
		// is it future date or past date
		if($now > $unix_date) {
			$difference = $now - $unix_date;
			$tense = "ago";
		} else {
			$difference = $unix_date - $now;
			$tense = "from now";
		}
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}
		$difference = round($difference);
		if($difference != 1) {
			$periods[$j].= "s";
		}
		return "$difference $periods[$j] {$tense}";
 
	}
}

if (!function_exists('get_user_roll')) {
	/**
	 *
	 * get_section: it's used to get list of teacher
	 *
	 * @param
	 * @return array
	 *
	 */
	function get_user_roll() {
		$ci =& get_instance();
		$ci->db->select('user_roll.*');
    	$ci->db->from('user_roll');    	
		$ci->db->where_not_in('user_roll.user_roll_id',array('1'));
		$query = $ci->db->get();
    	$user_roll_data = $query->result_array();
    	$user_roll_arr = array();
    	$user_roll_arr[0] = '--Select--';
    	foreach ($user_roll_data as $user_roll_datas){
    		$user_roll_arr[$user_roll_datas['user_roll_id']] = $user_roll_datas['user_roll_name'];    		
    	}
        return $user_roll_arr;

	}
}

if (!function_exists('get_course_class')) {
	/**
	 *
	 * get_course_class: it's used to get list of teacher
	 *
	 * @param
	 * @return array
	 *
	 */
	function get_course_class() {
		$ci =& get_instance();
		$ci->db->select('course_class.*,course_section.section_title');
		$ci->db->from('course_class');
		$ci->db->join('course_section','course_class.section_id = course_section.section_id');
		$query = $ci->db->get();
		$course_class = $query->result_array();
		$course_class_arr = array();
		$course_class_arr[0] = '--Select--';
		foreach ($course_class as $course_classes){
			$course_class_arr[$course_classes['course_class_id']] = $course_classes['section_title'];
		}
		return $course_class_arr;

	}
	
	if (!function_exists('get_event')) {
		/**
		 *
		 * get_course_class: it's used to get list of teacher
		 *
		 * @param
		 * @return array
		 *
		 */
		function get_event() {
			$ci =& get_instance();
			$usr_role = $ci->session->userdata('role_id');
			$user_id = $ci->session->userdata('user_id');
			
			$ci->db->select('event.event_id');
			$ci->db->from('event');
			$ci->db->join('event_group_wise','event.event_id = event_group_wise.event_id','left');
			$ci->db->join('event_user_wise','event.event_id = event_user_wise.event_id','left');
			$ci->db->where('event_group_wise.event_group','3');
			$ci->db->or_where('event_user_wise.user_id','120');
			$query = $ci->db->get();
			$events = $query->result_array();
			
			return $events;
	
		}
	}
	
	if (!function_exists('check_access')) {
		/**
		 *
		 * check_access: it's used to get list of teacher
		 *
		 * @param
		 * @return array
		 *
		 */
		function check_access() {
			$arrController = array();
			$ci =& get_instance();
			$usr_role = $ci->session->userdata('role_id');
			$cotroller = $ci->router->fetch_class();
			$action = $ci->router->fetch_method();
			
			if($action != "no_access" && $usr_role != '1' && $cotroller != "login" && $cotroller != "logout" && 
				$cotroller != "profile" && $cotroller != "reset_password" && $cotroller != "forgot_password" 
				&& $cotroller != "forgot_username" && $action != "update_account" && $action != "update_password" 
				&& $action != "upload_profile_pic" && $action != "reset" && $action != "index_json" && 
				$action != "getdata" && $action != "show_comment" && $action != "export_to_excel" && $action != "get_existing_privilege" 
				&& $action != "get_listbox" && $action != "view_report" && $action != "update_student" 
				&& $action != "delete" && $action != "viewlog" && $cotroller != "add_privilege" && $action != "add_reason" 
				&& $action != "update_course_class" && $action != "update_course" && $action != "update_section" 
				&& $action != "update_class_room" && $action != "add_ca_mark" && $action != "callthirdmarkerpage" 
				&& $action != "list_thirdmarker_stu" && $action != "view_attendance_log" && $action != "add_reason" && 
				$cotroller != "createpdf" && $action != "get_campus_section" &&
				$cotroller != "home" &&	
				$action != "edit_profile" &&	
				$cotroller != "my_attendance" &&	
				$cotroller != "my_inductions" &&	
				$cotroller != "schedule" &&	
				$action != "get_viewstatuslog_json"  &&
				$action != "get_observations_json"  &&
				$action != "get_user_existing_privilege"  &&
				$action != "view_grade_report_log"  && $cotroller != "list_enable_week" && $action != "add_user_privilege" 
				&& $action != "delete" && $cotroller != "add_employee" && $cotroller != "list_course_class"
				)
			{
				if(get_priviledge_action($cotroller,$action)){
					return true;
				}else{
					redirect("/home/no_access");
				}
			}
		}
	}
	
}

if (!function_exists('get_master_menu')) {
	/**
	 *
	 * get_course_class: it's used to get list of teacher
	 *
	 * @param
	 * @return array
	 *
	 */
	function get_master_menu() {
		$ci =& get_instance();
		$ci->db->select('*');
		$ci->db->where('parent_id','0');
		$ci->db->from('menu_master');
		$query = $ci->db->get();
		$menu_parent = $query->result_array();
		$menu_master_arr = array();
		$menu_master_arr[0] = '--Select--';
		foreach ($menu_parent as $menu_parentes){
			$menu_master_arr[$menu_parentes['menu_id']] = $menu_parentes['name'];
		}
		return $menu_master_arr;

	}
}

if (!function_exists('get_master_all_menu')) {
	/**
	 *
	 * get_course_class: it's used to get list of teacher
	 *
	 * @param
	 * @return array
	 *
	 */
	function get_master_all_menu() {
		$ci =& get_instance();
		$ci->db->select('*');
		$ci->db->where('parent_id','0');
		$ci->db->from('menu_master');
		$query = $ci->db->get();
		$menu_parent = $query->result_array();
		$menu_master_arr = array();
		$menu_master_arr[0] = '--Select--';
		foreach ($menu_parent as $menu_parentes){
			$ci->db->select('*');
			$ci->db->where('parent_id',$menu_parentes['menu_id']);
			$ci->db->from('menu_master');
			$subquery = $ci->db->get();
			$menu_sub = $subquery->result_array();
			
			$menu_master_arr[$menu_parentes['menu_id']] = $menu_parentes['name'];
			
			foreach ($menu_sub as $menu_subs){
				$menu_master_arr[$menu_subs['menu_id']] = '---'.$menu_subs['name'];
			}
		}
		return $menu_master_arr;

	}
}

if (!function_exists('get_previlege_action')) {
	/**
	 *
	 * get_course_class: it's used to get list of teacher
	 *
	 * @param
	 * @return array
	 *
	 */
	function get_previlege_action() {
		$ci =& get_instance();
		$ci->db->select('*');
		$ci->db->from('menu_master');
		$ci->db->where('parent_id','0');
		$query = $ci->db->get();
		$menu_parent = $query->result_array();
		$i =0; 
		foreach ($menu_parent as $menu_parentes){
			$ci->db->select('*');
			$ci->db->where('menu_id',$menu_parentes['menu_id']);
			$ci->db->from('actions');
			$actquery = $ci->db->get();
			$menu_act = $actquery->result_array();
			$menu_parent[$i]['menu_action'] = $menu_act;
			
			$j=0;
			
			$ci->db->select('*');
			$ci->db->from('menu_master');
			$ci->db->where('parent_id',$menu_parentes['menu_id']);
			$query = $ci->db->get();
			$sub_menu_parent = $query->result_array();
			
			foreach ($sub_menu_parent as $sub_menu_parentes)
			{
				$ci->db->select('*');
				$ci->db->where('menu_id',$sub_menu_parentes['menu_id']);
				$ci->db->from('actions');
				$actquery = $ci->db->get();
				$menu_act = $actquery->result_array();
				
					$sub_menu_parent[$j]['menu_action'] = $menu_act;
				
				$j++;
			}
			
			$menu_parent[$i]['sub_menu'] = $sub_menu_parent;
			$i++;
		}
		return $menu_parent;

	}
}

function get_rolewise_priviledge(){
	$arrMenu = array();
	$ci =& get_instance();
	$usr_role = $ci->session->userdata('role_id');
	$user_id = $ci->session->userdata('user_id');
	$ci->db->select('*');
	$ci->db->from('user_custom_privilege');
	$ci->db->where('user_id',$user_id);
	$query_users = $ci->db->get();
	
	
		$query = "
				SELECT 
			        IF(M2.menu_id IS NOT NULL,M2.menu_id,M1.menu_id) AS parent_menu_id,
			        IF(M2.name IS NOT NULL,M2.name,M1.name) AS parent_menu_name,
					IF(M2.lang_menu_name IS NOT NULL,M2.lang_menu_name,M1.lang_menu_name) AS parent_lang_menu_name,
					M1.menu_id,
					M1.name AS menu_master,
					M1.lang_menu_name AS menu_master_lan, 
					M1.parent_id AS parent_id, 
					menu_action.controller AS controller,
					menu_action.menu_action_id,
					menu_action.is_display,
					menu_action.lang_name AS menu_action_lan, 
					menu_action.action AS action, 
					menu_action.theme	";
					
			if($usr_role == '1'){		
				$query .= " FROM menu_action ";
			}else{
				if($query_users->num_rows() > 0)
				{
					$query .= " FROM user_custom_privilege
							LEFT JOIN menu_action ON (user_custom_privilege.menu_action_id = menu_action.menu_action_id) ";
				}else
				{
					$query .= " FROM user_privilege
							LEFT JOIN menu_action ON (user_privilege.menu_action_id = menu_action.menu_action_id) ";
				}			
			}					
			
			$query .= " 		
				LEFT JOIN menu_master AS M1 ON (menu_action.menu_id = M1.menu_id) 
				LEFT JOIN menu_master AS M2 ON (M1.parent_id = M2.menu_id) 
				";
				
			$query .= " WHERE menu_action.is_display = '1' ";
			
			if($usr_role != '1') {	
				if($query_users->num_rows() > 0)
				{
					$query .= " AND user_custom_privilege.user_id = '$user_id' ";		
				}else
				{
					$query .= " AND user_privilege.user_roll_id = '$usr_role' ";
				}
			}	
	
			$query .= " ORDER BY menu_action.display_order";	
	$query_res = $ci->db->query($query);
	$arrResMenu = $query_res->result_array();
	$i = 0;
	foreach($arrResMenu AS $row)
	{
		$arrMenu[$row["parent_lang_menu_name"]]['lang_name'] = $row["parent_lang_menu_name"];
		$arrMenu[$row["parent_lang_menu_name"]]['menu_name'] = $row["parent_menu_name"];
		$arrMenu[$row["parent_lang_menu_name"]]['controller'] = $row["controller"];
		$arrMenu[$row["parent_lang_menu_name"]]['parent_id'] = $row["parent_id"];
		
		if($row["action"] == "inactive" || $row["action"] == "add_document")
			$row["controller"] = $row["controller"].'/'.$row["action"];
		
		$arrMenu[$row["parent_lang_menu_name"]]['sub_menu'][$row["menu_master_lan"]][$row["controller"]] = $row["menu_action_lan"];
		//$arrMenu[$row["parent_lang_menu_name"]][$row["menu_master_lan"]][$row["controller"]][$row["action"]] = $row["menu_action_lan"];
		$i++;
	}
	
	return $arrMenu;
}

function get_priviledge_action($controller_name,$action=""){
	
	if($controller_name == "") return array();
	
	$arrMenu = array();
	$ci =& get_instance();
	$usr_role = $ci->session->userdata('role_id');
	$user_id = $ci->session->userdata('user_id');
	$ci->db->select('*');
	$ci->db->from('user_custom_privilege');
	$ci->db->where('user_id',$user_id);
	$query_users = $ci->db->get();
	
	$query = "
	SELECT
	IF(M2.menu_id IS NOT NULL,M2.menu_id,M1.menu_id) AS parent_menu_id,
	IF(M2.name IS NOT NULL,M2.name,M1.name) AS parent_menu_name,
	IF(M2.lang_menu_name IS NOT NULL,M2.lang_menu_name,M1.lang_menu_name) AS parent_lang_menu_name,
	M1.menu_id,
	M1.name AS menu_master,
	M1.lang_menu_name AS menu_master_lan,
	M1.parent_id AS parent_id,
	menu_action.controller AS controller,
	menu_action.menu_action_id,
	menu_action.is_display,
	menu_action.lang_name AS menu_action_lan,
	menu_action.action AS action,
	controller_action,
	menu_action.theme
	FROM user_privilege
	LEFT JOIN menu_action ON (user_privilege.menu_action_id = menu_action.menu_action_id)
	LEFT JOIN menu_master AS M1 ON (menu_action.menu_id = M1.menu_id)
	LEFT JOIN menu_master AS M2 ON (M1.parent_id = M2.menu_id)
	WHERE user_privilege.user_roll_id = '$usr_role'
	AND menu_action.controller = '$controller_name'
	";
	
	if($query_users->num_rows() > 0)
	{
		$query = "
			SELECT
			IF(M2.menu_id IS NOT NULL,M2.menu_id,M1.menu_id) AS parent_menu_id,
			IF(M2.name IS NOT NULL,M2.name,M1.name) AS parent_menu_name,
			IF(M2.lang_menu_name IS NOT NULL,M2.lang_menu_name,M1.lang_menu_name) AS parent_lang_menu_name,
			M1.menu_id,
			M1.name AS menu_master,
			M1.lang_menu_name AS menu_master_lan,
			M1.parent_id AS parent_id,
			menu_action.controller AS controller,
			menu_action.menu_action_id,
			menu_action.is_display,
			menu_action.lang_name AS menu_action_lan,
			menu_action.action AS action,
			controller_action,
			menu_action.theme
			FROM user_custom_privilege
			LEFT JOIN menu_action ON (user_custom_privilege.menu_action_id = menu_action.menu_action_id)
			LEFT JOIN menu_master AS M1 ON (menu_action.menu_id = M1.menu_id)
			LEFT JOIN menu_master AS M2 ON (M1.parent_id = M2.menu_id)
			WHERE user_custom_privilege.user_id = '$user_id'
			AND menu_action.controller = '$controller_name'
			";	
	}	
		
	if($controller_name != "")
		$query .= " AND menu_action.controller = '$controller_name' ";
	
	if($action != "")
		$query .= " AND (menu_action.action = '$action' OR controller_action = '$action') ";
				
	$query_res = $ci->db->query($query);
	$arrResMenu = $query_res->result_array();
	foreach($arrResMenu AS $row)
	{
		$arrMenu[]= $row["action"];
		
		if($row["controller_action"] != "")
			$arrMenu[]= $row["controller_action"];
	}
	
	if($action != "" && count($arrMenu) > 0)
	  return true;
	if($action != "" && count($arrMenu) == 0)
		return false;
	else
		return $arrMenu;
}

if (!function_exists('get_other_user_roll')) {
	/**
	 *
	 * get_other_user_roll: it's used to get list of teacher
	 *
	 * @param
	 * @return array
	 *
	 */
	function get_other_user_roll() {
		$ci =& get_instance();
		$ci->db->select('user_roll.*');
		$ci->db->from('user_roll');
		$ci->db->where_not_in('user_roll.user_roll_id',array('4'));
		$query = $ci->db->get();
		$user_roll_data = $query->result_array();
		$user_roll_arr = array();
		$user_roll_arr[0] = '--Select--';
		foreach ($user_roll_data as $user_roll_datas){
			$user_roll_arr[$user_roll_datas['user_roll_id']] = $user_roll_datas['user_roll_name'];
		}
		return $user_roll_arr;

	}
}


if (!function_exists('hash_password')) {
    /**
     *
     * hash_password: obscure password with specially designed salt - site_key combo in sha512
     *
     * @param string $password the password to be validated
     * @param string $nonce the nonce that is unique to this member
     * @return string
     *
     */
    function hash_password($password, $nonce) {
    	return hash_hmac('sha512', $password . $nonce, SITE_KEY);
    }
}

if (!function_exists('get_log_data_field_array')) {
	
	function get_log_data_field_array() {
		$arrLogData = array();

		$arrLogData["school_campus"] = array("campus_id"=>"ID",
							"campus_name"=>"Campus Name",
							"campus_location"=>"Campus Location"
							);

		$arrLogData["school_year"] = array("school_year_id"=>"ID",
							"school_id"=>"University Name",
							"school_year"=>"University Year",
							"school_year_title"=>"University Year Title",
							"school_type"=>"University Type",
							"school_week"=>"University Week"
							);

		$arrLogData["users"] = array("user_id"=>"ID",
								"username"=>"Username",
								"password"=>"Password",
								"section_id"=>"Section",
								"email"=>"E-mail address",
								"student_uni_id"=>"Student University ID",
								"first_name"=>"Full Name",
								"address1"=>"Address1",
								"address2"=>"Address2",
								"city"=>"City",
								"state"=>"State",
								"zip"=>"Zip",
								"birth_date"=>"Birth Date",
								"birth_place"=>"Birth Place",
								"language_known"=>"Language Known",
								"work_phone"=>"Work Phone",
								"home_phone"=>"Home Phone",
								"cell_phone"=>"Cell Phone",
								"name_suffix"=>"Name Suffix",
								"user_roll_id"=>"Role"
							);

		$arrLogData["course_category"] = array("category_id"=>"ID",
							"category_title"=>"Category Title"
							);

		$arrLogData["courses"] = array("course_id"=>"ID",
							"course_title"=>"Course Title",
							"max_hours"=>"Max Hours"
							);

		$arrLogData["course_section"] = array("section_id"=>"ID",
							"section_title"=>"Section"
							);

		$arrLogData["course_class_room"] = array("class_room_id"=>"ID",
							"class_room_title"=>"Class Room"
							);

		$arrLogData["course_class"] = array("course_class_id"=>"ID",
							"course_id"=>"Course Name",
							"category_id"=>"Course Category",
							"primary_teacher_id"=>"Primary Teacher",
							"secondary_teacher_id"=>"Secondary Teacher",
							"class_room_id"=>"Class Room",
							"section_id"=>"Section",
							"shift"=>"Shift",
							"school_year_id"=>"University Year Title"
							);

		$arrLogData["grade_type"] = array("grade_type_id"=>"ID",
						"grade_type"=>"Type",
						"total_markes"=>"Marks",
						"total_percentage"=>"Percentage",
						"show_grade_range"=>"Show Grade Range",
						"attendance_type"=>"Attandance Type"
		);

		$arrLogData["grade_type_exam"] = array("grade_type_exam_id"=>"ID",
					"grade_type_id"=>"Type",
					"exam_type_name"=>"Name",
					"exam_marks"=>"Marks",
					"exam_percentage"=>"Percentage"
		);

		$arrLogData["grade_range"] = array("grade_range_id"=>"ID",
				"grade_min_range"=>"Min Range",
				"grade_max_range"=>"Max Range",
				"grade_name"=>"Grade"
		);

		$arrLogData["user_roll"] = array("user_roll_id"=>"ID",
				"user_roll_name"=>"Role"
		);

		$arrLogData["user_roll"] = array("user_roll_id"=>"ID",
				"user_roll_name"=>"Role"
		);
		
		return $arrLogData;
	}
}

function update_student_moved_attendace($id,$section_id)
{
	$ci =& get_instance();
	//Get Course class id
	$ci->db->select('course_class_id,primary_teacher_id');
	$ci->db->from('course_class');
	$ci->db->where('section_id',$section_id);
	$query_course_class = $ci->db->get();
	//End here
	
	//Update attendance report table
	if($query_course_class){
		foreach ($query_course_class->result() as $course_class ):
			$course_class_id = $course_class->course_class_id;
			$primary_teacher_id = $course_class->primary_teacher_id;
			$data_attend = array(
				'course_class_id' => $course_class_id,
				'teacher_id' => $primary_teacher_id);
			$ci->db->where('student_id', $id);
			$ci->db->update('attendance_report', $data_attend);
			
		endforeach;
	}
	//End here
}

function get_buildings() {
	return array(''=>'Select','Humanities'=>'Humanities','Science'=>'Science','4'=>'4');
}

function get_track() {
	return array(''=>'Select','Humanities'=>'Humanities','Science'=>'Science','Diploma'=>'Diploma');
}

function get_countries() {
	$ci =& get_instance();
	$ci->db->select('countries.*');
	$ci->db->from('countries');
	$query = $ci->db->get();
	$countries_data = $query->result_array();
	$countries_arr = array();
	$countries_arr[0] = '--Select--';
	foreach ($countries_data as $countries_datas){
		$countries_arr[$countries_datas['id']] = $countries_datas['country'];
	}
	return $countries_arr;

}

function get_nationality_list() {
	$ci =& get_instance();
	$ci->db->select('countries.*');
	$ci->db->from('countries');
	$ci->db->order_by("native", "asc");
	$ci->db->order_by("nationality", "asc");
	
	$query = $ci->db->get();
	$nationality_data = $query->result_array();
	$nationality_arr = array();
	$nationality_arr[0] = '--Select--';
	foreach ($nationality_data as $nationality_datas){
		$nationality_arr[$nationality_datas['id']] = $nationality_datas['nationality'];
	}
	return $nationality_arr;

}

function get_qualifications_list() {
	$ci =& get_instance();
	$ci->db->select('qualifications.*');
	$ci->db->from('qualifications');
	$ci->db->where('type','qual');
	$query = $ci->db->get();
	$qualifications_data = $query->result_array();
	$qualifications_arr = array();
	$qualifications_arr[''] = '--Select--';
	foreach ($qualifications_data as $qualifications_datas){
		$qualifications_arr[$qualifications_datas['id']] = $qualifications_datas['qualification'];
	}
	return $qualifications_arr;
}

function get_certificate_list() {
	$ci =& get_instance();
	$ci->db->select('qualifications.*');
	$ci->db->from('qualifications');
	$ci->db->where('type','cert');
	$query = $ci->db->get();
	$result_data = $query->result_array();
	$return_arr = array();
	$return_arr[''] = '--Select--';
	foreach ($result_data as $result_row){
		$return_arr[$result_row['id']] = $result_row['qualification'];
	}
	return $return_arr;
}

function get_school_setting() {
	$ci =& get_instance();
	$ci->db->select('school_year.*');
	$ci->db->from('school_year');
	//$ci->db->where('type','cert');
	$query = $ci->db->get();
	$return_arr = $query->row();
	
	return $return_arr;
}
	
if (!function_exists('get_contractors')) {
    function get_contractors($where=array()) {
    	$ci =& get_instance();
    	$ci->db->select('*');
    	$ci->db->from('contractors');
		if($where){
			for($i=0;$i<count($where);$i++){
				$ci->db->where($where[$i]);
			}
		}
		$ci->db->order_by('contractor', 'ASC');	
    	$query = $ci->db->get();
    	$campus_data = $query->result_array();
    	$campus_arr = array();
    	$campus_arr[0] = '--Select--';
    	foreach ($campus_data as $campus_datas){
    		$campus_arr[$campus_datas['id']] = $campus_datas['contractor'];    		
    	}
        return $campus_arr;
    }
}

function get_profile_pic($user_id = 0) {
	$ci =& get_instance();
	$profile_pic = array('150'=> base_url()."images/noimage.jpg",'75'=> base_url()."images/noimage.jpg");
	
	if($user_id == 0)
		$user_id = $ci->session->userdata('user_id');
	
	$ci->db->select('profile_picture,elsd_id,job_title.job_title AS job_title_name');
	$ci->db->from('users');
	$ci->db->join('user_profile', 'user_profile.user_id = users.user_id','left');
	$ci->db->join('job_title', 'job_title.job_title_id = user_profile.job_title','left');
	$ci->db->where('users.user_id', $user_id);
	$ci->db->limit(1);
	$query = $ci->db->get();
	if($query->num_rows() == 1) {
    	$row = $query->row();
		
		$profile_picture = $row->profile_picture;
		$curr_dir = str_replace("\\","/",getcwd()).'/';
		$filepath = $curr_dir.'images/profile_picture/thumb7575/'.$profile_picture;
		if( file_exists($filepath) && $profile_picture != ''){
			$profile_picture_150 = base_url()."images/profile_picture/thumb150150/".$profile_picture;
			$profile_picture_75 = base_url()."images/profile_picture/thumb7575/".$profile_picture;
			$profile_pic[150] = $profile_picture_150;
			$profile_pic[75] = $profile_picture_75;
		}
		$profile_pic['elsd_id'] = $row->elsd_id;
		$profile_pic['job_title_name'] = $row->job_title_name;
	}
		
	return $profile_pic;	   
}

function getCertificateType($get_name = false){
	if($get_name) {
		return array(1=>"Photo",
				2=>"Passport",
				3=>"Degree Certificate",
				4=>"Master Certificate",
				5=>"PHD Certificate",
				6=>"Teaching Certificate",
				7=>"Diploma Certificate",
				8=>"Other Certificate",
				9=>"cv",
				10=>"Interview evaluation form",
				11=>"Reference 1",
				12=>"Reference 2",
				13=>"Lesson Plan",
				14=>"Writing Sample"
				);
	}else{
		return $arrCertificateType = array("photo" => 1,
											"passport" => 2,
											"Degree_Certificate" => 3,
											"Master_Certificate" => 4,
											"Phd_Certificate" => 5,
											"Teaching_Certificate" => 6,
											"Diploma_Certificate" => 7,
											"Other_Certificate" => 8,
											"cv" => 9,
											"interview_evaluation_form" => 10,
											"Reference_1" => 11,
											"Reference_2" => 12,
											"Lesson_Plan" => 13,
											"Writing_Sample" => 14
											);
	}	
}

function generateElsdId($gender){
	$ci =& get_instance();
	
	$school_settings = array();
	$ci->db->select('*')->from('school')->where('school_id', 1);
	$query = $ci->db->get();
	if($query->num_rows() == 1) {
		$school_settings = $query->row();
	}
	
	if(isset($school_settings->elsd_flag) && $school_settings->elsd_flag == 1){
		$ret = $gender.$school_settings->elsd_year.$school_settings->elsd_number;
		
		$data = array('elsd_flag' => 0);
		$ci->db->where('school_id', 1);
		$ci->db->update('school', $data);
	}else{
		$ci->db->select('SUBSTR(elsd_id,4) as elddt',FALSE);
		$ci->db->from('users');
		$ci->db->where('gender', $gender);
		$ci->db->order_by('elddt', 'DESC');
		$ci->db->limit(1);	
		$query = $ci->db->get();		 
		$row = $query->row();
		//$ci->db->last_query();
		$elddt = "";
		if(isset($row->elddt))
			$elddt = $row->elddt;
		$ret = '';
		if($elddt == ''){
			$ret = $gender.$school_settings->elsd_year.$school_settings->elsd_number;
		}else{
			$ret = $gender.$school_settings->elsd_year.($elddt+1);
		}
	}
	return $ret;
}

function user_profile_status($type = "") 
{
		$ret = array();
		
		$ci =& get_instance();
		$ci->db->select('*');
		$ci->db->from('user_status_master');
		$query = $ci->db->get();
		$status_master = $query->result_array();
		$status_master_arr = array();
		foreach($status_master as $statuses){
			$status_master_arr[$statuses['id']] = $statuses['status_desc'];
		}
		
		if($type == "")
		{
			$ret = array('1'=>$status_master_arr[1],//'New employee added by contractor',
					 '2'=>$status_master_arr[2],//'New employee deleted by AHR',
					 '3'=>$status_master_arr[3],//'AHR rejects employee (missing documents)',
					 '4'=>$status_master_arr[4],//'AHR rejects employee (does not meet requirements)',
					 '5'=>$status_master_arr[5],//'Pending 1',
					 '6'=>$status_master_arr[6],//'Pending 2',
					 '7'=>$status_master_arr[7],//'Pending 3',
					 '8'=>$status_master_arr[8],//'Pending 4',
					 '9'=>$status_master_arr[9],//'Pre-Approval successful',
					 '10'=>$status_master_arr[10],//'On Hold',
					 '11'=>$status_master_arr[11],//'PY Interview unsuccessful',
					 '12'=>$status_master_arr[12],//'PY Interview successful',
					 '13'=>$status_master_arr[13],//'Ready for timetable',
					 '14'=>$status_master_arr[14],//'Removed from timetable',
					 '15'=>$status_master_arr[15],//'Suspended',
					 '16'=>$status_master_arr[16],//'Dismissed',
					 '17'=>$status_master_arr[17],//'Resigned',
					 '18'=>$status_master_arr[18],//'Contract not renewed by staff',
					 '19'=>$status_master_arr[19],//'Contract not renewed by ELSD',
					 '20'=>$status_master_arr[20]//'Other'
					 );
		}
		else if($type == "companyemployee")
		{
			$ret = array('1'=>$status_master_arr[1],//'New employee added by contractor',
					 '2'=>$status_master_arr[2],//'New employee deleted by AHR',
					 '3'=>$status_master_arr[3],//'AHR rejects employee (missing documents)',
					 '4'=>$status_master_arr[4],//'AHR rejects employee (does not meet requirements)',
					 '5'=>'Pending',
					 '6'=>'Pending',
					 '7'=>'Pending',
					 '8'=>'Pending',
					 '9'=>$status_master_arr[9],//'Pre-Approval successful',
					 '10'=>$status_master_arr[10],//'On Hold',
					 '11'=>$status_master_arr[11],//'PY Interview unsuccessful',
					 '12'=>$status_master_arr[12],//'PY Interview successful',
					 '13'=>$status_master_arr[13],//'Ready for timetable',
					 '14'=>$status_master_arr[14],//'Removed from timetable',
					 '15'=>$status_master_arr[15],//'Suspended',
					 '16'=>$status_master_arr[16],//'Dismissed',
					 '17'=>$status_master_arr[17],//'Resigned',
					 '18'=>$status_master_arr[18],//'Contract not renewed by staff',
					 '19'=>$status_master_arr[19]//'Contract not renewed by ELSD'
					 );
		}		
		else if($type == "newemployee")			 
		{
			$ret = array('3'=>$status_master_arr[3],//'AHR rejects employee (missing documents)',
						 '5'=>$status_master_arr[5],//'Pending 1',
						 '6'=>$status_master_arr[6],//'Pending 2',
						 '7'=>$status_master_arr[7],//'Pending 3',
						 '8'=>$status_master_arr[8],//'Pending 4',
						 '9'=>$status_master_arr[9],//'Pre-Approval successful',
						 '10'=>$status_master_arr[10]//'On Hold'
						);
		}
		else if($type == "interview")			 
		{
			$ret = array('11'=>$status_master_arr[11],//'PY Interview unsuccessful',
						 '12'=>$status_master_arr[12]//'PY Interview successful'
						);
		}
		else if($type == "activestaff")			 
		{
			$ret = array('13'=>$status_master_arr[13],//'Ready for timetable',
						 '14'=>$status_master_arr[14],//'Removed from timetable',
						 '15'=>$status_master_arr[15]//'Suspended'
						);
		}
		else if($type == "inactivestaff")			 
		{
			$ret = array('2'=>$status_master_arr[2],//'New employee deleted by AHR',
						 '4'=>$status_master_arr[4],//'AHR rejects employee (does not meet requirements)',
						 '16'=>$status_master_arr[16],//'Dismissed',
						 '17'=>$status_master_arr[17],//'Resigned',
						 '18'=>$status_master_arr[18],//'Contract not renewed by staff',
						 '19'=>$status_master_arr[19]//'Contract not renewed by ELSD'
						);
		}
		else if($type == "otherstaff")			 
		{
			$ret = array('20'=>$status_master_arr[20]//'Other'
						);
		}
		
		return $ret;
}

function get_interview_outcome()
{
	return array(''=>'Select Interview Outcome','1'=> 'Approved','2'=>'Rejected','3'=>'Pending');
}

function get_interview_type()
{
	return array(''=>'Select Interview Type','1'=> 'Face to face','2'=>'Skype','3'=>'Phone');
}

function get_department_list() {
		$ci =& get_instance();
		$ci->db->select('*');
		$ci->db->from('department');
		$ci->db->order_by('department_name', 'ASC');	
		$query = $ci->db->get();
		$student_data = $query->result_array();
		$student_arr = array();
		$student_arr[0] = '--Select--';
		foreach ($student_data as $student_datas){
			$student_arr[$student_datas['id']] = $student_datas['department_name'];
		}
		return $student_arr;
	}

	
function get_jobtitle_list() {
		$ci =& get_instance();
		$ci->db->select('*');
		$ci->db->from('job_title');
		$ci->db->order_by('job_title', 'ASC');	
		$query = $ci->db->get();
		$student_data = $query->result_array();
		$student_arr = array();
		$student_arr[0] = '--Select--';
		foreach ($student_data as $student_datas){
			$student_arr[$student_datas['job_title_id']] = $student_datas['job_title'];
		}
		return $student_arr;
	}

function get_original_start_year_list() {
		$arr[""] = '--Select--';
		$arr["2007-08"] = '2007-08';
		$arr["2008-09"] = '2008-09';
		$arr["2009-10"] = '2009-10';
		$arr["2010-11"] = '2010-11';
		$arr["2011-12"] = '2011-12';
		$arr["2012-13"] = '2012-13';
		$arr["2013-14"] = '2013-14';
		$arr["2014-15"] = '2014-15';
		return $arr;
	}
	 
function get_name_title_list() {
		$arr[""] = '--Select--';
		$arr["Mr"] = 'Mr';
		$arr["Mrs"] = 'Mrs';
		$arr["Ms"] = 'Ms';
		$arr["Dr"] = 'Dr';
		return $arr;
	}
	
function list_dashboard_page(){
	return array('Text'=>'Text','Normal'=>'Normal','Advance'=>'Advance');
}

function make_dp_date($date = ''){
	if($date == '' || $date == '0000-00-00'){
		return '';
	}
	return date('D, d M Y',strtotime($date));
}

function make_db_date($date = ''){
	if($date == ''){
		return '';
	}
	return date('Y-m-d',strtotime($date));
}

function get_line_manager_list() {
	$ci =& get_instance();
	$ci->db->select('*,CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) AS staff_name',FALSE);
	$ci->db->from('users');
	$ci->db->join('user_profile','users.user_id = user_profile.user_id','left');
	$ci->db->where('user_profile.is_line_manager',1);
	$ci->db->order_by('first_name', 'ASC');	
	$query = $ci->db->get();
	$student_data = $query->result_array();
	$student_arr = array();
	$student_arr[0] = '--Select--';
	foreach ($student_data as $_student_data){
		$student_arr[$_student_data['user_id']] = $_student_data['staff_name'];
	}
	return $student_arr;
}

function get_interviewer_list() {
	$ci =& get_instance();
	$ci->db->select('*,CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) AS staff_name',FALSE);
	$ci->db->from('users');
	$ci->db->join('user_profile','users.user_id = user_profile.user_id','left');
	$ci->db->where('user_profile.interviewer',1);
	$ci->db->order_by('first_name', 'ASC');	
	$query = $ci->db->get();
	$student_data = $query->result_array();
	$student_arr = array();
	$student_arr[0] = '--Select--';
	foreach ($student_data as $_student_data){
		$student_arr[$_student_data['user_id']] = $_student_data['staff_name'];
	}
	return $student_arr;
}

function get_campus_user_list() {
	$ci =& get_instance();
	$ci->db->select('*,CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) AS staff_name',FALSE);
	$ci->db->from('users');
	$ci->db->where_not_in('users.user_roll_id',array('1','3'));
	$ci->db->order_by('first_name', 'ASC');
	if(($ci->session->userdata('campus_id') > 0 || $ci->session->userdata('campus') != ""))
	{
		$ci->db->where('users.campus_id',$ci->session->userdata('campus_id'));
		$ci->db->or_where('users.campus_id',0);	
	}
	$query = $ci->db->get();
	$student_data = $query->result_array();
	$student_arr = array();
	$student_arr[0] = '--Select--';
	foreach ($student_data as $_student_data){
		$student_arr[$_student_data['user_id']] = $_student_data['staff_name'].' - '.$_student_data['elsd_id'];
	}
	return $student_arr;
}
/* End of file general_function_helper.php */
/* Location: ./application/helpers/general_function_helper.php */ 