<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class List_student extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->model('attendance/attendance_model');
		$this->load->model('list_course/courses_model');
		$this->load->model('list_course_class/list_student_class_model');
        $this->load->model('list_teacher_student_model');
		$this->load->model('grade_report/grade_report_model');
		$this->load->helper('general_function');
		$this->load->model('view_attendance_weekly_report/view_attendance_weekly_report_model');
		$this->load->model('get_pdf/get_pdf_model');
    }
    /**
     *
     * index
     *
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     * @param int $offset the offset to be used for selecting data
     *
     */
    public function index($order_by = "users.username", $sort_order = "asc", $search = "all", $offset = 0) {
    	$content_data['section_list'] = get_section();
		$content_data['teacher_list'] = get_teacher_list();
    	$content_data['campus_list'] = get_campus_list();
    	$content_data['discontinue'] = array('Regular'=>'Regular','Withdrawn'=>'Withdrawn','Denied'=>'Denied');
    	$content_data['track'] = get_track();
    	$content_data['buildings'] = get_buildings();
    	
		$content_data['school_campus'] = $this->get_pdf_model->get_campus_pdf();
        if (!is_numeric($offset)) {
            redirect('/list_student');
        }
        $this->load->library('pagination');
		
		$search_data = array();
				
        if ($search == "post") {
            $this->session->unset_userdata(array('s_username' => '', 's_first_name' => '', 's_student_uni_id' => '', 's_email' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('username', 'username', 'trim|max_length[16]');
            $this->form_validation->set_rules('first_name', 'full name', 'trim|max_length[40]');
            $this->form_validation->set_rules('student_uni_id', 'student ID', 'trim|max_length[60]');
            $this->form_validation->set_rules('email', 'email', 'trim|max_length[255]');
            if (empty($_POST['username']) && empty($_POST['first_name']) && empty($_POST['student_uni_id']) && empty($_POST['email'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_student/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('username')) {
                    $this->session->set_flashdata('message', form_error('username'));
                }elseif (form_error('email')) {
                    $this->session->set_flashdata('message', form_error('email'));
                }elseif (form_error('first_name')) {
                    $this->session->set_flashdata('message', form_error('first_name'));
                }elseif (form_error('student_uni_id')) {
                    $this->session->set_flashdata('message', form_error('student_uni_id'));
                }
                redirect('/list_student/');
                exit();
            }
            $search_session = array(
                's_username'  => $this->input->post('username'),
                's_first_name'     => $this->input->post('first_name'),
                's_student_uni_id' => $this->input->post('student_uni_id'),
                's_email' => $this->input->post('email')
            );
            $this->session->set_userdata($search_session);
            $base_url = site_url('list_student/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('username' => $this->input->post('username'), 'first_name' => $this->input->post('first_name'), 'student_uni_id' => $this->input->post('student_uni_id'), 'email' => $this->input->post('email'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_teacher_student_model->count_all_student_search_members($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }
        }elseif($search == "session") {
            $base_url = site_url('list_student/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('username' => $this->session->userdata('s_username'), 'first_name' => $this->session->userdata('s_first_name'), 'student_uni_id' => $this->session->userdata('s_student_uni_id'), 'email' => $this->session->userdata('s_email'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_teacher_student_model->count_all_student_search_members($search_data);
            $content_data['search'] = "session";
        }else{
			$unset_search_session = array('s_username' => '', 's_first_name' => '', 's_student_uni_id' => '', 's_email' => '');
            $this->session->unset_userdata($unset_search_session);
            $content_data['total_rows'] = $config['total_rows'] = $this->list_teacher_student_model->count_all_student_members($search_data);
            $base_url = site_url('list_student/index/'. $order_by .'/'. $sort_order .'/all');
			$content_data['search'] = "all";
        }
        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = $this->list_teacher_student_model->get_student($per_page, $offset, $order_by, $sort_order, $search_data);
        if (empty($data)) {
            //redirect("/private/list_student");
        }else{
            $content_data['members'] = $data;
        }
        $content_data['offset'] = $offset;
        $content_data['order_by'] = $order_by;
        $content_data['sort_order'] = $sort_order;
        // set pagination config data
        $config['uri_segment'] = '7';
        $config['base_url'] = $base_url;
        $config['per_page'] = Settings_model::$db_config['members_per_page'];
        $config['prev_tag_open'] = ''; // removes &nbsp; at beginning of pagination output
        $this->pagination->initialize($config);
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('List Student');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_student', $content_data);
    }
    public function index_json($order_by = "users.username", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('student_uni_id','first_name','student_schedule_date','section_title','campus','users.track','users.buildings','academic_status');
    	$grid_data = get_search_data($aColumns);
    	
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
		
		if($order_by == "username")
			$order_by = "users.username";
		
		if($order_by == "student_uni_id" || $order_by == "")
			$order_by = "users.campus,section_title,users.student_uni_id";
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];

		$this->list_teacher_student_model->set_campus_name();
				
    	$data = $this->list_teacher_student_model->get_student($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],1);
    	$count = $this->list_teacher_student_model->count_all_student_mem($grid_data['search_data'],1);
    
    	/*
    	 * Output
    	*/
    	$output = array(
    			"sEcho" => intval($_GET['sEcho']),
    			"iTotalRecords" => $count,
    			"iTotalDisplayRecords" => $count,
    			"aaData" => array()
    	);
    
    	if($data){
    		foreach($data->result_array() AS $result_row){
    			$row = array();
    			
				$row[] = '<i class="fa fa-plus-circle"><span class="hide">'.$result_row["student_uni_id"].'</span></i>';
				$row[] = $result_row["student_uni_id"];
				$row[] = ucwords(strtolower($result_row["first_name"]));
				$row[] = $result_row["stu_schedule_date"];
    			if($result_row["change_by"] > 0){
    				$log_data = $this->list_teacher_student_model->get_user_log($result_row["user_id"],4);
    				$strTooltip = '<table style=\"font-size:10px\" class="table no-more-tables prop-log-table" >';
					$strTooltip .= '<tr><th><b>Section</b></th><th><b>Update By</b></th><th><b>Date</b></th><th><b>Reason</b></th></tr>';
					foreach($log_data->result_array() as $data1) {
						$strTooltip .= "<tr><td>".addslashes($data1["section_title"])."</td>";
						$strTooltip .= "<td>".addslashes(str_replace("'"," ",$data1["first_name"]))."</td>";						
						$strTooltip .= "<td>".date('d-M-Y',strtotime($data1["change_date"]))."</td>";
						$strTooltip .= '<td width=\"200px\">'.addslashes(str_replace("'"," ",str_replace("\n"," ",str_replace("\r\n"," ",$data1["reason"])))).'</td></tr>';						
					}
					$p= $log_data->result_array();
					if(empty($p)) {
						$strTooltip .= '<tr><td colspan=\"3\">No data available</td></tr>';
					}
    				$strTooltip .= '</table>';
					//$row[] =  "<a onmouseover='javascript:popup(\"".$strTooltip."\",\"350px\");'><font size=\"2\" color=\"red\">".$result_row["section_title"]."</font></a>";
					$row[] =  "<a onmouseover='' id=\"popover\" data-content='".$strTooltip."' data-toggle=\"popover\"><font size=\"2\" color=\"red\">".$result_row["section_title"]."</font></a>";
    			}else{
    				$row[] = $result_row["section_title"];
    			}
    			
    			$row[] = $result_row["campus"];
				$row[] = $result_row["section_track"];
				$row[] = $result_row["section_buildings"];
				$row[] = $result_row["academic_status"];
    			
    			
    			$row[] = $result_row["user_id"];
    			$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
    
    public function add_student(){
    	//Post data
    	$section_id = $this->input->post('section_id');
    	$student_uni_id = $this->input->post('student_uni_id');
    	$first_name = $this->input->post('first_name');
    	$username = $this->input->post('username');
    	$email = $this->input->post('email');
    	$address1 = $this->input->post('address1');
    	$address2 = $this->input->post('address2');
    	$city = $this->input->post('city');
    	$birth_date = $this->input->post('birth_date');
    	$home_phone = $this->input->post('home_phone');
    	$cell_phone = $this->input->post('cell_phone');
    	$discontinue = $this->input->post('discontinue');
    
    	$data = array();
    	$data['user_roll_id'] = '4';
    	$data['section_id'] = $section_id;
    	$data['student_uni_id'] = $student_uni_id;
    	$data['first_name'] = $first_name;
    	$data['username'] = $username;
    	$data['email'] = $email;
    	$data['address1'] = $address1;
    	$data['address2'] = $address2;
    	$data['city'] = $city;
    	$data['birth_date'] = $birth_date;
    	$data['home_phone'] = $home_phone;
    	$data['cell_phone'] = $cell_phone;
    	$data['academic_status'] = $discontinue;
    	$data['created_date'] = date('Y-m-d H:i:s');
    	 
    	//Table name
    	$table = 'users';
    
    	grid_add_data($data,$table);
    }
    
    public function add($id = null){
    	$content_data['teacher_list'] = get_teacher_list();
    	$content_data['section_list'] = get_section();
		$content_data['campus_list'] = get_campus_list();
    	$content_data['discontinue'] = array('Regular'=>'Regular','Withdrawn'=>'Withdrawn','Denied'=>'Denied');
    	$content_data['track'] = get_track();
    	$content_data['buildings'] = get_buildings();
    	$content_data['user_id'] = $id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_teacher_student_model->get_student_data($id);
    	}	
    	
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$nonce = md5(uniqid(mt_rand(), true));
    		$gender = $this->input->post('gender');
			$user_id = $this->input->post('user_id');
			$campus_id = $this->input->post('campus_id');
    		$section_id = $this->input->post('section_id');
    		$student_uni_id = $this->input->post('student_uni_id');
    		$first_name = $this->input->post('first_name');
    		$email = $this->input->post('email');
    		$address1 = $this->input->post('address1');
    		$address2 = $this->input->post('address2');
    		$city = $this->input->post('city');
    		$birth_date = $this->input->post('birth_date');
    		$home_phone = $this->input->post('home_phone');
    		$cell_phone = $this->input->post('cell_phone');
    		$username = $this->input->post('username');
    		$password = $this->input->post('password');
    		$password_confirm = $this->input->post('password_confirm');
    		$discontinue = $this->input->post('discontinue');
    		$first_name_arabic = $this->input->post('first_name_arabic');
    		$student_schedule_date = $this->input->post('student_schedule_date');
    		$track = $this->input->post('track');
    		$buildings = $this->input->post('buildings');
    		$error = "";
    		$error_seperator = "<br>";
			
			$where_campus[] = array("campus_id"=>$campus_id);
			$campus_arr = get_campus_name($where_campus);
			$campus = "";
			
			if(isset($campus_arr["campusname"]))
				$campus = $campus_arr["campusname"];
				
    		if($id){
    			
    			$this->form_validation->set_rules('student_uni_id', 'student ID', 'trim|is_existing_field[users.student_uni_id^users.user_id !=^'.$user_id.']');
    			 
    			if (!$this->form_validation->run()) {
    				if (form_error('student_uni_id')) {
    					$error .= form_error('student_uni_id').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    			$data = array();
    			$data['gender'] = $gender;
    			$data['section_id'] = $section_id;
				$data['campus_id'] = $campus_id;
    			$data['campus'] = $campus;
    			$data['student_uni_id'] = $student_uni_id;
    			$data['first_name'] = $first_name;
    			$data['email'] = $email;
    			$data['address1'] = $address1;
    			$data['address2'] = $address2;
    			$data['city'] = $city;
    			$data['birth_date'] = date('Y-m-d',strtotime($birth_date));
    			$data['home_phone'] = $home_phone;
    			$data['cell_phone'] = $cell_phone;
    			$data['updated_date'] = date('Y-m-d H:i:s');
    			$data['academic_status'] = $discontinue;
    			$data['first_name_arabic'] = $first_name_arabic;
    			$data['track'] = $track;
    			$data['buildings'] = $buildings;
    			$data['student_schedule_date'] = date('Y-m-d',strtotime($student_schedule_date));
    			$table = 'users';
    			$wher_column_name = 'user_id';
    			
    			if($rowdata->section_id."j" != $section_id){
    				$this->list_teacher_student_model->section_update_log($rowdata->user_id);
    			}
    			set_activity_data_log($id,'Update','Student > List student','List student',$table,$wher_column_name,'');
    			grid_data_updates($data,$table,$wher_column_name,$id);
				update_student_moved_attendace($user_id,$section_id);
    			
    		}else{
    			
    			$this->form_validation->set_rules('student_uni_id', 'Student Univercity Id', 'trim|is_existing_unique_field[users.student_uni_id]');
    			
    			if (!$this->form_validation->run()) {
    				if (form_error('student_uni_id')) {
    					$error .= form_error('student_uni_id').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    			
    			$data = array();
    			$data['gender'] = $gender;
    			$data['section_id'] = $section_id;
				$data['campus_id'] = $campus_id;
    			$data['campus'] = $campus;
    			$data['student_uni_id'] = $student_uni_id;
    			$data['first_name'] = $first_name;
    			$data['email'] = $email;
    			$data['address1'] = $address1;
    			$data['address2'] = $address2;
    			$data['city'] = $city;
    			$data['birth_date'] = date('Y-m-d',strtotime($birth_date));
    			$data['home_phone'] = $home_phone;
    			$data['cell_phone'] = $cell_phone;
    			$data['username'] = $username;
    			$data['password'] = hash_password($password, $nonce);
    			$data['user_roll_id'] = '4';
    			$data['active'] = '1';
    			$data['nonce'] = $nonce;
    			$data['created_date'] = date('Y-m-d H:i:s');
    			$data['academic_status'] = $discontinue;
    			$data['first_name_arabic'] = $first_name_arabic;
				$data['track'] = $track;
    			$data['buildings'] = $buildings;
    			$data['student_schedule_date'] = date('Y-m-d',strtotime($student_schedule_date));
    			$table = 'users';
    			$wher_column_name = 'user_id';
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Student > List student','List student',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_student_datatable', $content_data);
    }
    
    public function update_student(){
    	$error = "";
    	$error_seperator = "<br>";
    	$update_weekly_attendance = '';
    	
    	$columnName = $this->input->post('columnName');
    	$value = $this->input->post('value');
    	$id = $this->input->post('id');
    	$id = strip_tags($id);
    	
    	$tablename = 'users';
    	$whrid_column = 'student_uni_id';
    	
		$id = strip_tags($id);
    	
    	if($columnName != '') $_POST[$columnName] = $value;
    	$this->form_validation->set_error_delimiters('', '');
    	if ($columnName == 'username')
    		$this->form_validation->set_rules('username', 'username', 'trim|required|max_length[16]|min_length[6]|is_valid_username|is_existing_field[users.username^users.user_id !=^'.$id.']');
    	if ($columnName == 'email')
    		$this->form_validation->set_rules('email', 'email', 'trim|required|max_length[255]|is_valid_email|is_existing_field[users.email^users.user_id !=^'.$id.']');
    	if ($columnName == 'first_name')
    		$this->form_validation->set_rules('first_name', 'first name', 'trim|required|max_length[40]|min_length[2]');
    	if ($columnName == 'student_uni_id'){
    		$this->form_validation->set_rules('student_uni_id', 'student ID', 'trim|required|is_existing_field[users.student_uni_id^users.user_id !=^'.$id.']');
    	}
    	if ($columnName == 'password'){
    		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
    	}
    	
    	if (!$this->form_validation->run()) {
    		if (form_error('username')) {
    			$error .= form_error('username').$error_seperator;
    		}elseif (form_error('email')) {
    			$error .= form_error('email').$error_seperator;
    		}elseif (form_error('first_name')) {
    			$error .= form_error('first_name').$error_seperator;
    		}elseif (form_error('student_uni_id')) {
    			$error .= form_error('student_uni_id').$error_seperator;
    		}elseif (form_error('password')) {
    			$error .= form_error('password').$error_seperator;
    		}
			
			if($error != "")
			{
				echo $error;
				exit();
			}
    	}
		
		if($columnName == "campus")
			$columnName = "campus_id";
			
		if($columnName == "section_title")	{
			$columnName = "section_id";
		}
		
		if($columnName != "section_id")
		{	
			set_activity_data_log($id,'Update','Student > List student','List student',$tablename,$whrid_column,$user_id='');
			$update_weekly_attendance = grid_update_data($whrid_column,$id,$columnName,$value,$tablename);
    	}
		if($columnName == "campus_id")
		{
			$where_campus[] = array($columnName=>$value);
			$campus_arr = get_campus_name($where_campus);
			$value_campus = "";
			
			if(isset($campus_arr["campusname"]))
				$value_campus = $campus_arr["campusname"];
			
			grid_update_data($whrid_column,$id,"campus",$value_campus,$tablename);
		}	
		
		if($update_weekly_attendance == "update_weekly_attendance")
		{
			echo "success";
		}
	    else if($columnName == "campus_id" || $columnName == "campus")
		{	
			echo "success";
		}	
		
		if($columnName == "section_id")
		{
			$html = '<form name="reason_form" id="reason_form" method="post" action="list_student/add_reason">';
				$html .= '<input type="hidden" name="posted_value" value="'.$value.'"/>';
				$html .= '<input type="hidden" name="posted_columnName" value="'.$columnName.'"/>';
				$html .= '<input type="hidden" name="posted_id" value="'.$id.'"/>';
				$html .= '<input type="hidden" name="tablename" value="'.$tablename.'"/>';
				$html .= '<input type="hidden" name="whrid_column" value="'.$whrid_column.'"/>';
						$html .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
							/*$html .= '<tr>';
								$html .= '<td colspan="2" align="center">';
								$html .= '&nbsp;';
								$html .= '</td>';
							$html .= '</tr>';
							$html .= '<tr>';*/
								$html .= '<td align="right"><strong>Reason : &nbsp;</strong></td>';
								$html .= '<td>';
								$html .= '<textarea rows="7" class="form-control" cols="25" name="reason"></textarea>';
								$html .= '</td>';
							$html .= '</tr>';
							$html .= '<tr>';
								$html .= '<td colspan="2" align="center">';
								$html .= '&nbsp;';
								$html .= '</td>';
							$html .= '</tr>';
							$html .= '<tr>';
								$html .= '<td colspan="2" align="center">';
								$html .= '<input class="btn btn-success" type="submit" name="submit" value="Submit With / Without a reason">';
								$html .= '</td>';
							$html .= '</tr>';
						$html .= '</table>';
						$html .= '</form>';
						@ob_end_clean();
				echo $html;
		}else {
			//echo "success";
		}	
		
    }
	
	public function add_reason()
	{
		ob_start();
		$last_log_id = 0;
		$reason = "";
		$posted_value = '';
		$posted_columnName = 0;
		$posted_id = 0;
		$tablename = '';
		$whrid_column = '';
		
		if(isset($_POST["reason"]))
			$reason = addslashes($_POST["reason"]);
		if(isset($_POST["posted_value"]))
			$posted_value = $_POST["posted_value"];
		if(isset($_POST["posted_columnName"]))
			$posted_columnName = $_POST["posted_columnName"];
		if(isset($_POST["posted_id"]))
			$posted_id = $_POST["posted_id"];
		if(isset($_POST["tablename"]))
			$tablename = $_POST["tablename"];
		if(isset($_POST["whrid_column"]))
			$whrid_column = $_POST["whrid_column"];
		
		if($posted_columnName == "section_id")
		{
			$rowdata_user = $this->list_teacher_student_model->get_student_data_uni_id($posted_id);
			if($rowdata_user->section_id."j" != $posted_value){
				$last_log_id = $this->list_teacher_student_model->section_update_log($rowdata_user->user_id);
			}
			update_student_moved_attendace($rowdata_user->user_id,$posted_value);
		}	
		set_activity_data_log($posted_id,'Update','Student > List student','List student',$tablename,$whrid_column,$user_id='');
		$update_weekly_attendance = grid_update_data($whrid_column,$posted_id,$posted_columnName,$posted_value,$tablename);			
		
		$this->list_teacher_student_model->set_student_log($reason,$last_log_id);
		
		@ob_end_clean();
		redirect('/list_student');
	}

    public function get_listbox($type){
    	$jsondata = '';
    	if($type == 'section'){
    		$section = get_section();
    		$jsondata .= '{';
    		foreach ($section as $key => $val){
    			 
    			 
    			if(end($section) == $val){
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\'';
    			}else{
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\''.',';
    			}
    			 
    		}
    		$jsondata .= '}';
    		echo $jsondata;
    	}
    }
    
    public function action_member($id, $offset, $order_by, $sort_order, $search) {
        if (array_key_exists('update', $_POST)) {
            $this->_update_student($id, $offset, $order_by, $sort_order, $search);
        }else{
            $this->_delete_student($id, $offset, $order_by, $sort_order, $search);
        }
    }
    /**
     *
     * _update_student: update student info from adminpanel
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */
    private function _update_student($id, $offset, $order_by, $sort_order, $search) {
        $this->form_validation->set_error_delimiters('', '');
        if ($this->input->post('username_box') == true) {
            $this->form_validation->set_rules('username', 'username', 'trim|required|max_length[16]|min_length[6]|is_valid_username|is_existing_unique_field[users.username]');
        }
        if ($this->input->post('email_box') == true) {
            $this->form_validation->set_rules('email', 'email', 'trim|required|max_length[255]|is_valid_email|is_existing_unique_field[users.email]');
        }
        $this->form_validation->set_rules('first_name', 'first name', 'trim|required|max_length[40]|min_length[2]');
        $this->form_validation->set_rules('student_uni_id', 'student ID', 'trim|required|is_existing_field[users.student_uni_id^users.student_uni_id !=^'.$this->input->post('student_uni_id').']');
		
        $username = $this->list_teacher_student_model->get_username_by_id($id);
        if ($username == ADMINISTRATOR && $this->input->post('username_box') == true) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noedit'));
            redirect('/list_student');
            exit();
        }
        if (!$this->form_validation->run()) {
            if (form_error('username') && ($this->input->post('username_box') == true)) {
                $this->session->set_flashdata('message', form_error('username'));
            }elseif (form_error('email') && ($this->input->post('email_box') == true)) {
                $this->session->set_flashdata('message', form_error('email'));
            }elseif (form_error('first_name')) {
                $this->session->set_flashdata('message', form_error('first_name'));
            }elseif (form_error('student_uni_id')) {
                $this->session->set_flashdata('message', form_error('student_uni_id'));
            }
            redirect('/list_student/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }
        $this->list_teacher_student_model->update_member($this->input->post('user_id'), $this->input->post('username'), $this->input->post('email'), $this->input->post('first_name'), $this->input->post('middle_name'),$this->input->post('name_suffix'),$this->input->post('address1'),$this->input->post('address2'),$this->input->post('city'),$this->input->post('state'),$this->input->post('zip'),$this->input->post('birth_date'),$this->input->post('birth_place'),$this->input->post('gender'),$this->input->post('language_known'),$this->input->post('work_phone'),$this->input->post('home_phone'),$this->input->post('cell_phone'), $this->input->post('username_box'), $this->input->post('email_box'),$this->input->post('section_id'), $this->input->post('student_uni_id'));
        set_activity_log($this->input->post('user_id'),'update','student','list student');
        $this->session->set_flashdata('message', sprintf($this->lang->line('member_updated'), $this->input->post('username'), $this->input->post('id')));
        redirect('/list_student/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
     /**
     *
     * _delete_student: delete student from adminpanel
     *
     * @param int $id the id of the member to be deleted
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */
    private function _delete_student($id, $offset, $order_by, $sort_order, $search) {
        $username = $this->list_teacher_student_model->get_username_by_id($id);
        if ($username == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noremove'));
        }elseif ($this->list_teacher_student_model->delete_member($id)) {
        	set_activity_log($id,'delete','student','list student');
            $this->session->set_flashdata('message', sprintf($this->lang->line('member_deleted'), $username, $id));
        }
        redirect('/list_student/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
    
    /**
     *
     * toggle_active: (de)activate student from adminpanel
     *
     * @param int $id the id of the member to be deleted
     * @param string $username the username of the member involved
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     * @param bool $active or deactivate?
     *
     */
    public function toggle_active($id, $username, $offset, $order_by, $sort_order, $search, $active) {
        if ($this->list_teacher_student_model->get_username_by_id($id) == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noactivate'));
            redirect('/list_student/index');
            return;
        }elseif ($this->list_teacher_student_model->toggle_active($id, $active)) {
            $active ? $active = $this->lang->line('deactivated') : $active = $this->lang->line('activated');
            set_activity_log($id,$active,'student','list student');
            $this->session->set_flashdata('message', sprintf($this->lang->line('toggle_active'), $username) . $active);
        }
        redirect('/list_student/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
	
	public function view_report($id,$createpdf=false) 
	{
		$section_id = 0;
		$page = isset($_POST['page']) ? $_POST['page'] : 1;
		$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
		$order_by = isset($_POST['sortname']) ? $_POST['sortname'] : 'attendeance_id';
		$sort_order = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
        // set content data
        $offset = (($page-1) * $rp);
		$per_page = $rp;
		
		$search_data = array();
		$search_data["student_uni_id"] = $id;
		
		$data = $this->attendance_model->get_late_attendance_report($per_page, $offset, $order_by, $sort_order, $search_data);
		
		$content_data['attendance'] = array();
		
		if(!empty($data)) 
		{
			$data_attendance_week_activation_time = $this->list_teacher_student_model->get_week_activation_time();
			$arrActivateTime = $data_attendance_week_activation_time->result_array(); 
			$activate_time = "00:00:00";
			
			if(isset($arrActivateTime[0]["activation_time"]))
				$activate_time = $arrActivateTime[0]["activation_time"];
				
			$enable_week = $this->list_teacher_student_model->get_enable_week($activate_time);
			
			$cell_week = array();
			foreach ($enable_week as $enable_wek){
				$cell_week[$enable_wek->week_id] = 0;
			}
				
			$i=1;
			foreach($data->result() as $courseclasses)
			{
				
				$course_title = $courseclasses->course_title;
				$section_title = $courseclasses->section_title;
				$section_id = $courseclasses->section_id;
				$class_room_title = $courseclasses->class_room_title;
				$student_name = $courseclasses->student_name;
				$teacher_name = $courseclasses->teacher_name;
				$sec_teacher_name = $courseclasses->sec_teacher_name;
				$school_year_title = $courseclasses->school_year_title;
				$shift = $courseclasses->shift;
				$campus = $courseclasses->campus;
				$student_uni_id = $courseclasses->student_uni_id;
				$absent_hour = $courseclasses->total_absent_hour;
				$all_weeks = explode(",",$courseclasses->all_weeks);
				$all_hours = explode(",",$courseclasses->all_hours);
				$attendance_perc = 0;
				
				if($absent_hour > 0 && $courseclasses->max_hours > 0)
					$attendance_perc = round((($absent_hour * 100)/$courseclasses->max_hours),2);
				
				$cell = 
				array(
						'Primary Teacher'=>$teacher_name,
						'Secondary Teacher'=>$sec_teacher_name,
						'Classroom'=>$class_room_title,
						'Shift'=>$shift
						/*,
						'Section'=>$section_title,
						'Course'=>$course_title,
						'Campus'=>$campus,*/
					);	
					
				$enable_cell_week = array('Total'=>$absent_hour,'%'=>$attendance_perc);
					
				foreach ($cell_week as $enable_wek=>$enable_wek_val){
					if(in_array($enable_wek,$all_weeks))
					{
						$key = array_search($enable_wek, $all_weeks);
						$enable_cell_week["Wk ".$enable_wek] =  $all_hours[$key];
					}
					else
					{
						$enable_cell_week["Wk ".$enable_wek] =  0;
					}
				}
				
				$content_data['studentname'] = $student_name;
				$content_data['studentuniid'] = $student_uni_id;
				$content_data['attendance']['school_year_title'] = $school_year_title;
				$content_data['attendance']["student_info"] = $cell;
				$content_data['attendance']['weeks'] = $enable_cell_week;
				$i++;
			}
		}
		
		$order_by = "section_title";
		$sort_order = "asc";
		$offset = 0;
		
		$search = array();
		$where[] = array("course_section.section_id"=>$section_id);	
		$data = $this->list_student_class_model->get_course_class(10, $offset, $order_by, $sort_order, $search,$where);
		
		
		$show_total_tab = "Yes";
		$show_grade_range = "Yes";
		
		$query_res = $this->list_teacher_student_model->get_grade_setting();
		$data_check_grade_report = $query_res->result_array();
		
		if(isset($data_check_grade_report[0]["show_total_grade"]))
			$show_total_tab = $data_check_grade_report[0]["show_total_grade"];
		
		if(isset($data_check_grade_report[0]["show_grade_range"]))
			$show_grade_range = $data_check_grade_report[0]["show_grade_range"];
		
		$content_data['show_total_tab'] = $show_total_tab;
		$content_data['show_grade_range'] = $show_grade_range;
		
		$arrGradeRange = array();
		
		$data_grade_range = $this->list_teacher_student_model->get_grade_range();
		
		if(is_array($data_grade_range) && count($data_grade_range))
		{
			foreach($data_grade_range AS $rowrange)
			{
				$arrGradeRange[$rowrange["grade_range_id"]] = array("grade_min_range"=>$rowrange["grade_min_range"],
																    "grade_max_range"=>$rowrange["grade_max_range"],
																    "grade_name"=>$rowrange["grade_name"]
																   );
			}
		}
		
		$content_data['arrGradeRange'] = $arrGradeRange;	
		
		if(!empty($data)) 
		{
			foreach($data->result() as $courseclasses)
			{
				$section_id = $courseclasses->section_id;
				$course_class_id = $courseclasses->course_class_id;
				
				$student_data = $this->list_teacher_student_model->get_student($per_page, $offset, $order_by, $sort_order, $search_data);
				if($student_data) $courseclasses->student = $student_data->result();
				
				$student_grade_data = $this->grade_report_model->get_student_class_grades($section_id);
				$courseclasses->student_grade_data = $student_grade_data;
			}
		}
		
		if(!empty($data))
		{
            $content_data['course_class'] = $data;
			$student_data = $this->list_teacher_student_model->get_student(10, $offset, $order_by, $sort_order, $search_data);
			$content_data['student_data'] = $student_data;
			$content_data['grade_type'] = $this->grade_report_model->get_grade_type();
			$content_data['grade_type_exam'] = $this->grade_report_model->get_grade_type_exam();
		}
		$student_user_id = $this->list_teacher_student_model->get_student_data_uni_id($id);
		$section_log_data = $this->list_teacher_student_model->get_user_log($student_user_id->user_id);
		$student_grade_report_log = $this->grade_report_model->get_grade_report_log($student_user_id->section_id,$student_user_id->student_uni_id,0);
		$content_data['student_grade_report_log'] = $student_grade_report_log;
		
		$content_data['section_log_data'] = $section_log_data->result_array();
		
		if($createpdf === true) {
			return $content_data;
		}else{
        	//$this->template->build('view_student_report', $content_data);
        	$this->template->build('student-info', $content_data);
		}
	}
	public function getstudent_gradereport($section_id,$search_data) {
		$order_by = "student_uni_id";
		$sort_order = "asc";
		$offset = 0;
		
		$search = array();
		$content_data = array();
		$where[] = array("course_section.section_id"=>$section_id);	
		$data = $this->list_student_class_model->get_course_class(10, $offset, $order_by, $sort_order, $search,$where);
		
		if(!empty($data)) 
		{
			foreach($data->result() as $courseclasses)
			{
				$section_id = $courseclasses->section_id;
				$course_class_id = $courseclasses->course_class_id;
				$student_data = $this->list_student_class_model->get_class_wise_student($section_id);
				if($student_data) $courseclasses->student = $student_data->result();
				
				$student_grade_data = $this->grade_report_model->get_student_class_grades($section_id);
				$courseclasses->student_grade_data = $student_grade_data;
			}
		}
		
		if(!empty($data))
		{
            $content_data['course_class'] = $data;
			$student_data = $this->list_teacher_student_model->get_student(10, $offset, $order_by, $sort_order, $search_data);
			$content_data['student_data'] = $student_data;
			$content_data['grade_type'] = $this->grade_report_model->get_grade_type();
			$content_data['grade_type_exam'] = $this->grade_report_model->get_grade_type_exam();
		}
		return $content_data;
    }
	public function delete($id = null){
    	if($id){
			$table = 'users';
			$wher_column_name = 'user_id';
			set_activity_data_log($id,'Delete','Student > List Student','List Student',$table,$wher_column_name,$user_id='');
			
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/list_student/');
        exit();
	}
	
	public function create_report_pdf($id)
	{
		$content_data = $this->view_report($id,true);
		// As PDF creation takes a bit of memory, we're saving the created file in /downloads/pdfreports/
		$pdfFilePath = "downloads\pdfreports\student-report\\".$id.".pdf";
		$pdfFilePath = "downloads/pdfreports/student-report/".$id.".pdf";
		
		$data['page_title'] = $this->lang->line('student_report').' PDF'; // pass data to the view
		ini_set('max_execution_time', 0);
		ini_set('memory_limit','10240M');
		$this->load->library('pdf');
		$pdf = $this->pdf->load();
		$pdf->SetFooter('|{PAGENO}|'.date(DATE_RFC822));
		
		$html = '<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family: arial; font-size: 9pt; color: #000000;">
				<tr>
					<td width="5%" align="left"><img src="'.base_url().'images/logo.png" height="80px"/></td>
					<td width="45%" align="left" valign="middle">
						<h2>'.$this->lang->line('student_report').' : '.$content_data['studentname'].' ('.$content_data['studentuniid'].')</h2><br/>
				     </td>
				</tr>
				</table><br>';
		if($content_data['attendance'])
		{
			$html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-family: arial; font-size: 9pt; color: #000000;">';
			$flag = 1;
			foreach($content_data['attendance'] as $key=>$arrData)
			{
				$flag = 0;	
				if($key == "school_year_title")
				{
					$html .= '<tr><td align="left"><h3>'.$this->lang->line('student_class_info').'</h3></td></tr>';
				}
				if($key == "student_info")
				{
					$html .= '<tr><td><table border="1" width="100%" cellpadding="3" cellspacing="0">
							 <tr>';
							 foreach($arrData AS $key_info=>$val)
							 {
								$html .= '<td width="100">'.$key_info.'</td>';
							 }
					$html .= '</tr>
							 <tr>';
							 foreach($arrData AS $key_info=>$val)
							 {
							 	$html .= '<td>'.$val.'</td>';
							 }
					$html .= '</tr>
							 </table></td></tr>';
				}
				
				if($key == "school_year_title")
				{
					$temp_school_year_title = $arrData;	
				}
						
				if($key == "weeks")
				{
					$html .= '<br>
							<tr><td align="left"><h3>'.$temp_school_year_title.' '.$this->lang->line('attendance').'</h3></td></tr>
							<tr><td>
							<table border="1" width="100%" cellpadding="2" cellspacing="0"><tr>';
								foreach($arrData AS $key_weeks=>$val)
								{
									$html .= '<td width="72" align="center">'.$key_weeks.'</td>';
								}
							$html .= '</tr>
								 <tr>';
								foreach($arrData AS $key_weeks=>$val)
								{
									$html .= '<td>'.$val.'</td>';
								}
							$html .= '</tr>
							</table></td>
							</tr>';
				}		
			}
			
			$html .= '</table>';
		}
		else
		{
			$html .= 'No attendance data found.';
		}
					$html .= '<h3 style="margin-top: 10px;">'.$this->lang->line('grade_report').'</h3>';
					$j = 1;
					
					$tabcnt = 0;
					if (isset($content_data['course_class'])) {
						foreach ($content_data['course_class']->result() as $course_classes):
							$course_class_week = $course_classes->school_week;
							$max_hours = $course_classes->max_hours;
							
							$arrTotals_Tab = array();
							$c = 1;
							foreach($content_data['grade_type'] AS $grade_type_id=>$grade_type_data)
							{
								$html .= '<h4 style="margin-top: 5px; margin-bottom: 0px;">'.$content_data['grade_type'][$c]["grade_type"].'</h4>';
								$html .= '<table cellpadding="0" cellspacing="0" width="100%" height="100%" style="font-family: arial; font-size: 8pt; color: #000000;"><tr><td><table><tr><td>';
								
								if(isset($content_data['grade_type_exam'][$grade_type_id]))
								{
								$arr_grade_exam = $content_data['grade_type_exam'][$grade_type_id];	
								if (isset($course_classes->student)) 
								{
									$iscolspan = 0;
									$colspan = "";
									$rowspan = "";
									
									foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data){
										if($grade_type_exam_data["is_show_percentage"] == "Yes" || $grade_type_exam_data["is_two_marker"] == "Yes")
										{
											$rowspan = 'rowspan="2"';
											if($grade_type_exam_data["is_two_marker"] == "Yes")
												$colspan = 'colspan="4"';
										}
									}
									$html .= '<table border="1" cellpadding="3" cellspacing="0" width="100%" height="100%">
											  <tr>';
											  foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data){
												$colspan = '';
												if($grade_type_exam_data["is_show_percentage"] == "Yes" || $grade_type_exam_data["is_two_marker"] == "Yes")
												{
													$iscolspan = 1;
													$colspan = 'colspan="2"';
													
													if($grade_type_exam_data["is_two_marker"] == "Yes")
													{
														$colspancnt = 4;
														
														if($grade_type_exam_data["is_show_percentage"] == "Yes")
															$colspancnt++;
															
														$colspan = 'colspan="'.$colspancnt.'"';
													}
												}
				
												if($grade_type_data["attendance_type"] == "examwise")
												{
													$html .= '<th align="left" '.$rowspan.' valign="top">'.$this->lang->line('attendance').'</th>';
												}
												$html .= '<th '.$colspan.' valign="top">'.$grade_type_exam_data["exam_type_name"];if($iscolspan == 0){ $html .= '('.$grade_type_exam_data["exam_marks"].')'; } $html .= '</th>';
											}
											if($grade_type_data["attendance_type"] == "common")
											{
												$html .= '<th align="left" '.$rowspan.' valign="top">'.$this->lang->line('attendance').'</th>';
											}
											if($grade_type_data["show_total_marks"] == "Yes")
											{
												$html .= '<th align="left" '.$rowspan.' valign="top" width="110">'.$this->lang->line('total_marks').' ('.$grade_type_data["total_markes"].')</th>';
											}
											if($grade_type_data["show_grade_range"] == "Y")
											{
												$html .= '<th align="left" '.$rowspan.' valign="top">'.$this->lang->line('range').'</th>';
											}
											if($grade_type_data["show_total_per"] == "Yes")
											{
												$html .= '<th align="left" '.$rowspan.' valign="top">'.$this->lang->line('total').' %('.$grade_type_data["total_percentage"].')</th>';
											}
											$html .= '</tr>';
											if($iscolspan == 1)
											{
											$html .= '<tr>';
												foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data){
												if($grade_type_exam_data["is_two_marker"] == "Yes")
												{
													$html .= '<th>'.$this->lang->line('Marks').' 1 </th>';
													$html .= '<th>'.$this->lang->line('Marks').' 2 </th>';
													$html .= '<th width="100">'.$this->lang->line('Marks').' 3 ('.$this->lang->line('optional').') </th>';
													$html .= '<th width="70">'.$this->lang->line('Marks').' 3 ('.$grade_type_exam_data["exam_marks"].') </th>';
													if($grade_type_exam_data["is_show_percentage"] == "Yes")
													{
														$html .= '<th>'.$grade_type_exam_data["exam_percentage"].' % </th>';
													}
												}
												else
												{
												
													$html .= '<th width="100">Marks('.$grade_type_exam_data["exam_marks"].' ) </th>';
													if($grade_type_exam_data["is_show_percentage"] == "Yes")
													{
														$html .= '<th>'.$grade_type_exam_data["exam_percentage"].'% </th>';
													}
												}
												}
											$html .= '</tr>';
											}
		
											$l = 0;
											foreach ($course_classes->student as $student_datas)
											{
												$html .= '<tr>';
												$total_100_percentage = 0;
												$temp_grade_type = $content_data['grade_type'];							
												foreach($temp_grade_type AS $temp_grade_type_id=>$temp_grade_type_data)	
												{
													$temp_total_exam_mark = 0;
													if(isset($content_data['grade_type_exam'][$temp_grade_type_id]))
													{
														$temp_arr_grade_exam = $content_data['grade_type_exam'][$temp_grade_type_id];
														foreach($temp_arr_grade_exam as $temp_grade_type_exam_id=>$temp_grade_type_exam_data)
														{
															$temp_exam_mark = 0;
															if(isset($course_classes->student_grade_data[$course_classes->section_id][$temp_grade_type_exam_id][$student_datas->student_uni_id]) && 
														isset($course_classes->student_grade_data[$course_classes->section_id][$temp_grade_type_exam_id][$student_datas->student_uni_id]["exam_marks"]))
														{
															if($temp_grade_type_exam_data["is_two_marker"] == "Yes")
															{
																$temp_exam_mark_1 = $course_classes->student_grade_data[$course_classes->section_id][$temp_grade_type_exam_id][$student_datas->student_uni_id]["exam_marks"];
																$temp_exam_mark_2 = $course_classes->student_grade_data[$course_classes->section_id][$temp_grade_type_exam_id][$student_datas->student_uni_id]["exam_marks_2"];
																$temp_exam_mark_3 = $course_classes->student_grade_data[$course_classes->section_id][$temp_grade_type_exam_id][$student_datas->student_uni_id]["exam_marks_3"];
																if(abs($temp_exam_mark_1-$temp_exam_mark_2) >= $grade_type_exam_data["two_mark_difference"])
																{
																	if($temp_exam_mark_3 !== "" && $temp_exam_mark_3 !== "3rd")
																	{
																		$arrMarkerVal = array();
																		$arrMarkerVal = array($temp_exam_mark_1,$temp_exam_mark_2,$temp_exam_mark_3);
																		
																		rsort($arrMarkerVal);
																		$temp_exam_mark = ($arrMarkerVal[0]+$arrMarkerVal[1])/2;
																	}
																}
																else
																{
																	if($temp_exam_mark_1 > 0 || $temp_exam_mark_2 > 0)
																		$temp_exam_mark = ($temp_exam_mark_1+$temp_exam_mark_2)/2;
																}
															}
															else
															{
																$temp_exam_mark = $course_classes->student_grade_data[$course_classes->section_id][$temp_grade_type_exam_id][$student_datas->student_uni_id]["exam_marks"];
															}	
														}
															if($temp_exam_mark > 0)
															{
																$temp_exam_mark = round($temp_exam_mark,1);
																$temp_total_exam_mark += $temp_exam_mark;
															}
														}	
														$temp_percentage = round(($temp_total_exam_mark*$temp_grade_type_data["total_percentage"])/$temp_grade_type_data["total_markes"],2);
														$total_100_percentage += $temp_percentage;	
													}	
												}
												
												$total_exam_mark = 0;
												$total_percentage = 0;
												$k = 1;
												$exam_status = "N/A";
												$grade_status_combination = "";
												foreach ($arr_grade_exam as $grade_type_exam_id=>$grade_type_exam_data)
												{
													$grade_entry_combination = 'grade['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
													$grade_examwisestatus_combination = 'grade_status['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_exam_id.']';
													$grade_status_combination = 'grade_status['.$course_classes->section_id."_".$student_datas->student_uni_id."_".$grade_type_id.']';
														
													$absent_hours = "";
													if(isset($course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]) && 
														isset($course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["exam_marks"]))
													{
														$exam_mark = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["exam_marks"];
														$exam_mark_2 = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["exam_marks_2"];
														$exam_mark_3 = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["exam_marks_3"];
														$exam_status = $course_classes->student_grade_data[$course_classes->section_id][$grade_type_exam_id][$student_datas->student_uni_id]["exam_status"];
														$exam_mark = round($exam_mark,1);
														if($exam_mark_2 !== "" && $exam_mark_2 !== NULL)
														{
															$exam_mark_2 = round($exam_mark_2,1);
														}	
														$bgcolortd = "";
														$bgcolortd_status_cheat = "";
														
														if($exam_status == "Cheating")
															$bgcolortd_status_cheat = ' bgcolor="#8AC5FF" ';
															
														if($exam_mark_3 != "")
															$exam_mark_3 = round($exam_mark_3,1);
														else
														{
															$exam_mark_3 = "3rd";	
														}
														if($grade_type_exam_data["is_two_marker"] == "Yes")
														{
															if(abs($exam_mark-$exam_mark_2) >= $grade_type_exam_data["two_mark_difference"] && $exam_mark_2 !== "" && $exam_mark_2 !== NULL)
															{
																if($exam_mark_3 !== "" && $exam_mark_3 !== "3rd")
																{
																	$arrMarkerVal = array();
																	$arrMarkerVal = array($exam_mark,$exam_mark_2,$exam_mark_3);
																	
																	rsort($arrMarkerVal);
																	
																	$total_exam_mark += ($arrMarkerVal[0]+$arrMarkerVal[1])/2;
																	
																	$percentage = ((($arrMarkerVal[0]+$arrMarkerVal[1])/2)*$grade_type_exam_data["exam_percentage"])/$grade_type_exam_data["exam_marks"];	
																}
																else
																{
																	$bgcolortd = ' bgcolor="#FF6666" ';
																	$percentage = "3rd";
																}
															}
															else
															{
																$exam_mark_3 = "";
																if($exam_mark > 0 || $exam_mark_2 > 0)
																	$total_exam_mark += ($exam_mark+$exam_mark_2)/2;
																	
																$percentage = ((($exam_mark+$exam_mark_2)/2)*$grade_type_exam_data["exam_percentage"])/$grade_type_exam_data["exam_marks"];		
															}
														}
														else
														{
															$total_exam_mark += $exam_mark;
															if($grade_type_exam_data["exam_marks"])
																$percentage = ($exam_mark*$grade_type_exam_data["exam_percentage"])/$grade_type_exam_data["exam_marks"];
														}
														
														$total_percentage += $percentage;
														
													if($grade_type_data["attendance_type"] == "examwise")
													{
													$html .= '<td align="center">';
														$arr_exam_status = array("Present"=>"Present","Absent"=>"Absent");
														$html .= $exam_status."&nbsp;";
													$html .= '</td>';
													}
													if($grade_type_exam_data["is_two_marker"] == "Yes")
													{
														$html .= '<td align="center">';
															$html .= $exam_mark."&nbsp;";
														$html .= '</td>';
														$html .= '<td align="center">';
															$html .= $exam_mark_2."&nbsp;";
														$html .= '</td>';
														$html .= '<td align="center">';
															$html .= $exam_mark_3."&nbsp;";
														$html .= '</td>';
														$html .= '<td align="center" '.$bgcolortd.'>';
								
														if($exam_mark_2 !== "" && $exam_mark_2 !== NULL)
														{						
															if(abs($exam_mark-$exam_mark_2) >= $grade_type_exam_data["two_mark_difference"])
															{
																if($exam_mark_3 !== "" && $exam_mark_3 !== "3rd")
																{
																	$arrMarkerVal = array();
																	$arrMarkerVal = array($exam_mark,$exam_mark_2,$exam_mark_3);
																	
																	rsort($arrMarkerVal);
																	
																	$html .=  round(($arrMarkerVal[0]+$arrMarkerVal[1])/2,1);
																}
																else	
																	$html .=  "3rd";	
															}
															else
															{
																if($exam_mark > 0 || $exam_mark_2 > 0)
																	$html .=  round(($exam_mark+$exam_mark_2)/2,1);
															}
														}	
															$html .=  "&nbsp;";
														
														$html .= '</td>';
														if($grade_type_exam_data["is_show_percentage"] == "Yes")
														{
															$html .= '<td align="center">';
																$html .=  round($percentage,2);
															$html .= '</td>';
														}
													}
													else 
													{
														$html .= '<td align="center">';
															$html .= $exam_mark."&nbsp;";
														$html .= '</td>';
														
														if($grade_type_exam_data["is_show_percentage"] == "Yes" && $iscolspan == 1)
														{
														$html .= '<td align="center">';
															$html .= round($percentage,2);
														$html .= '</td>';
														}
													}
															
													}
													else 
													{
														$percentage = 0;
														if($grade_type_data["attendance_type"] == "examwise")
														{
															$html .= '<td align="center">';
																$arr_exam_status = array("Present"=>"Present","Absent"=>"Absent");
																$html .=  $exam_status."&nbsp;";
															$html .= '</td>';
														}
														if($grade_type_exam_data["is_two_marker"] == "Yes")
														{
															$html .= '<td align="center">';
																$html .=  "&nbsp;";
															$html .= '</td>';
															$html .= '<td align="center">';
																$html .= "&nbsp;";
															$html .= '</td>';
															$html .= '<td align="center">';
																$html .=  "&nbsp;";
															$html .= '</td>';
															$html .= '<td align="center" '.$bgcolortd.'>';
															if($exam_mark_2 !== "" && $exam_mark_2 !== NULL)
															{						
																if(abs($exam_mark-$exam_mark_2) >= $grade_type_exam_data["two_mark_difference"])
																{
																	if($exam_mark_3 !== "" && $exam_mark_3 !== "3rd")
																	{
																		$arrMarkerVal = array();
																		$arrMarkerVal = array($exam_mark,$exam_mark_2,$exam_mark_3);
																		rsort($arrMarkerVal);
																		$html .=  round(($arrMarkerVal[0]+$arrMarkerVal[1])/2,1);
																	} else	 {
																		$html .=  "3rd";	
																	}	
																}
																else
																{
																	if($exam_mark > 0 || $exam_mark_2 > 0)
																		$html .=  "&nbsp;";
																}
															}	
																$html .=  "&nbsp;";
															$html .= '</td>';
														}
														else
														{
														$html .= '<td align="center">&nbsp;</td>';
														}
														if($grade_type_exam_data["is_show_percentage"] == "Yes")
														{
														$html .= '<td align="center">';
															$html .=  round($percentage,2);
														$html .= '</td>';
														}
													}
													$k++;
												}
												
												if($grade_type_data["attendance_type"] == "common")
												{
												$html .= '<td align="center">';
													$arr_exam_status = array("Present"=>"Present","Absent"=>"Absent");
													$html .= $exam_status."&nbsp;";
												$html .= '</td>';
												}
												if($grade_type_data["show_total_marks"] == "Yes")
												{
													$html .= '<td>';
														$html .=  round($total_exam_mark,1);
													$html .= '</td>';
												}
												if($grade_type_data["show_grade_range"] == "Y")
												{
													$range_name = "N/A";
													$range_total_marks = round($total_exam_mark,1);
													if(is_array($arrGradeRange) && count($arrGradeRange))
													{
														foreach($arrGradeRange AS $rowrange)
														{
															if($range_total_marks >= $rowrange["grade_min_range"] && $range_total_marks <= $rowrange["grade_max_range"])
																$range_name = $rowrange["grade_name"];		
														}
													}
													$html .= '<td>'.$range_name.'</td>';
												}
												if($grade_type_data["show_total_per"] == "Yes")
												{
												$html .= '<td>'.round(($total_exam_mark*$grade_type_data["total_percentage"])/$grade_type_data["total_markes"],2).'</td>';
												}
											$html .= '</tr>';
											$l++;
											
											$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["student_uni_id"] = $student_datas->student_uni_id;
											$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["first_name"] = $student_datas->first_name;
											$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_marks"] = round($total_exam_mark,1);
											$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_perc"] = round(($total_exam_mark*$grade_type_data["total_percentage"])/$grade_type_data["total_markes"],2);
											$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_100_perc"] = round($total_100_percentage,2);
											
											}
											
									$html .= '</table>';
									$j++;
								}
								else
								{
									$html .= 'No Student found in this class.Please ask to administrator.';
								}
								}
								else
								{
									$html .=  "<table><tr><td><b>No Exam Found</b></td></tr></table>";
								}
								$html .= '</td></tr></table>';
								$html .= '</td></tr></table>';
								$c++;
							}
							
							if($content_data['show_total_tab'] == "Yes")
							{
								$html .= '<h4 style="margin-top: 5px; margin-bottom: 0px;">'.$this->lang->line('totals').'</h4>';
								$html .= '
											<table border="1" cellpadding="2" cellspacing="0" width="100%">
												<tr>';
													$c = 1;
													foreach($content_data['grade_type'] AS $grade_type_id=>$grade_type_data)
													{
														if($grade_type_data["show_total_marks"] == "Yes")
														{
															$html .= '<th align="left" valign="top">'.$grade_type_data["grade_type"].' Marks('.$grade_type_data["total_markes"].')</th>';
														}
														if($grade_type_data["show_grade_range"] == "Y")
														{
															$html .= '<th align="left" valign="top">Range</th>';
														}
														if($grade_type_data["show_total_per"] == "Yes")
														{
															$html .= '<th align="left" valign="top">'.$grade_type_data["grade_type"].' %('.$grade_type_data["total_percentage"].')</th>';
														}
													}
													$html .= '<th align="left" valign="top">Total 100%</th>';
													if($content_data['show_grade_range'] == "Yes")
													{
														$html .= '<th align="left" valign="top">Range</th>';
													}
										$html .= '</tr>';
									$trcnttotal = 1;
									if(isset($course_classes->student))
									{
									foreach ($course_classes->student as $student_datas)
									{
										$trbg = "";
										if($trcnttotal%2 == 0)
										{
											 $trbg = 'style="background-color:#ddd"';
										}
										$trcnttotal++;
										$html .= '<tr '.$trbg.'>';
											$total_100_per = 0;
											foreach($content_data['grade_type'] AS $grade_type_id=>$grade_type_data)
											{
												if(isset($arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]))
												{
													if($grade_type_data["show_total_marks"] == "Yes")
													{
														$html .= '<td>'.$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_marks"].'</td>';					
													}
													if($grade_type_data["show_grade_range"] == "Y")
													{
														$range_name = "N/A";
														$range_total_marks = round($arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_marks"],1);
														if(is_array($arrGradeRange) && count($arrGradeRange))
														{
															foreach($arrGradeRange AS $rowrange)
															{
																if($range_total_marks >= $rowrange["grade_min_range"] && $range_total_marks <= $rowrange["grade_max_range"])
																	$range_name = $rowrange["grade_name"];		
															}
														}
														$html .= '<td>'.$range_name.'</td>';
													}
													if($grade_type_data["show_total_per"] == "Yes")
													{
														$html .= '<td>'.$arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_perc"].'</td>';
													}
													$total_100_per = $arrTotals_Tab[$grade_type_id][$student_datas->student_uni_id]["total_100_perc"];
												}
												else 
												{
													if($grade_type_data["show_total_marks"] == "Yes")
													{
														$html .= '<td>0</td>';
													}
													if($grade_type_data["show_grade_range"] == "Y")
													{
														$html .= '<td>N/A</td>';
													}
													if($grade_type_data["show_total_per"] == "Yes")
													{
														$html .= '<td>0</td>';
													}
												}		
											}
											$html .= '<td>'.$total_100_per.'</td>';
											if($content_data['show_grade_range'] == "Yes")
											{
												$range_name = "N/A";
												$range_total_marks = $total_100_per;
												if(is_array($arrGradeRange) && count($arrGradeRange))
												{
												
													foreach($arrGradeRange AS $rowrange)
													{
														if($range_total_marks >= $rowrange["grade_min_range"] && $range_total_marks <= $rowrange["grade_max_range"])
															$range_name = $rowrange["grade_name"];		
													}
												}
											$html .= '<td>'.$range_name.'</td>';
											}
											$html .= '</tr>';
									}
									}
									$html .= '</table>';
								
							}
							
							$tabcnt++;
						endforeach;
					}
		$pdf->AddPage("L");
		$pdf->WriteHTML($html); // write the HTML into the PDF
		$pdf->Output($pdfFilePath, 'F'); // save to file because we can
		redirect("downloads/pdfreports/student-report/".$id.".pdf");			 
	}
	
	
	public function export_to_excel()
    {
    	ini_set('memory_limit','1024M');
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	
		$where = array();
		$where1 = array();
		$search_data = array();
		$campus_id = 0;
    	if(isset($_POST['campus']))
    		$campus_id =  $_POST['campus'];
		
		$order_by = "campus,section_title";
    	/*
    	 * Paging
    	*/
    	$per_page =  50000;
    	$offset =  0;
		$data = $this->list_teacher_student_model->get_student_export($per_page, $offset, $order_by, "asc", $search_data,0,$campus_id);
    	$arrStudent = array();
    	if($data){
    		foreach($data->result_array() AS $result_row){
    			$arrStudent[] = array("student_uni_id" => $result_row["student_uni_id"],
											"first_name" => $result_row["first_name"],
											"first_name_arabic" => $result_row["first_name_arabic"],
											"stu_schedule_date" => $result_row["stu_schedule_date"],
											"section_title" => $result_row["section_title"],
											"class_room_title" => $result_row["class_room_title"],
											"course_title" => $result_row["course_title"],
											"section_track" => $result_row["section_track"],
											"section_buildings" => $result_row["section_buildings"],
											"shift" => $result_row["courses_shift"],
											"campus" => $result_row["campus"],
											"start_time" => $result_row["start_time"],
											"end_time" => $result_row["end_time"],
											"pname" => $result_row["pname"],
											"sname" => $result_row["sname"],
											"academic_status" => $result_row["academic_status"]
										 );
    		}
    	}
		$content_data["arrStudent"] = $arrStudent;
    	$this->template->build('student_excel', $content_data);
    }
	
}
/* End of file list_student.php */