<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_course_class extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('list_course_class_model');
		$this->load->helper('general_function');
		$this->load->model('list_student/list_teacher_student_model');
		$this->load->model('list_course/courses_model');
		$this->output->enable_profiler(FALSE);
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

    public function index($order_by = "course_class.course_class_id", $sort_order = "asc", $search = "all", $offset = 0) {
		
        $content_data['classroom'] = $this->courses_model->get_class_room(1000,0, "class_room_title", "asc", array(),1);
        $content_data['section'] = $this->courses_model->get_section(1000,0, "section_id", "asc", array(),1);
		$content_data['school_year_list'] = get_school_year_title();
		$content_data['school_list'] = get_school_list();
		$content_data['course_subject'] = get_course_subject();		
		$content_data['course'] = get_course();		
		$content_data['teacher_list'] = get_teacher_list();		
		$content_data['course_category'] = get_course_category();	
		$content_data['shift'] = array('AM'=>'AM','PM'=>'PM');	
        if (!is_numeric($offset)) {
            redirect('/list_course_class');
        }

        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_class_room_title' => '', 's_section_title' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('class_room_title', 'Class Room', 'trim');
            $this->form_validation->set_rules('section_title', 'Section', 'trim');
            

            if (empty($_POST['class_room_title']) && empty($_POST['section_title'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_course_class/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('class_room_title')) {
                    $this->session->set_flashdata('message', form_error('class_room_title'));
                }elseif (form_error('section_title')) {
                    $this->session->set_flashdata('message', form_error('section_title'));
                }
                redirect('/list_course_class/');
                exit();
            }

            $search_session = array(
                's_class_room_title'  => $this->input->post('class_room_title'),
                's_section_title'     => $this->input->post('section_title')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('list_course_class/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('class_room_title' => $this->input->post('class_room_title'), 'section_title' => $this->input->post('section_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_course_class_model->count_all_search_course_class($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }


        }elseif($search == "session") {
            $base_url = site_url('list_course_class/index/'. $order_by .'/'. $sort_order .'/session');
             $search_data = array('class_room_title' => $this->input->post('class_room_title'), 'section_title' => $this->input->post('section_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_course_class_model->count_all_search_course_class($search_data);
            $content_data['search'] = "session";

        }else{
            $unset_search_session = array('s_section_title' => '', 's_class_room_title' => '');
            $this->session->unset_userdata($unset_search_session);
            $content_data['total_rows'] = $config['total_rows'] = $this->list_course_class_model->count_all_course_class();
            $base_url = site_url('list_course_class/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }

        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        
        $data = $this->list_course_class_model->get_course_class($per_page, $offset, $order_by, $sort_order, $search_data);
        if (empty($data)) {
            //redirect("/list_course_class");
        }else{
            $content_data['course_class'] = $data;
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
        
        $this->template->title('List Course Class');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_course_class', $content_data);
    }
    
    public function index_json($order_by = "course_class.course_class_id", $sort_order = "asc", $search = "all", $offset = 0){
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('course_class_id','course_title','first_staff_name','second_staff_name','class_room_title','section_title','shift','campus_name','course_section.track','course_section.buildings','student_cnt');
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
    	
		if($order_by == "")
			$order_by = "course_title,class_room_title,section_title";
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    	
    	$data = $this->list_course_class_model->get_course_class($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_course_class_model->count_all_search_course_class_grid($grid_data['search_data']);
    
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
    			
				$log_data = $this->list_teacher_student_model->get_course_class_log($result_row["course_class_id"],4);
				$row[] = '<i class="fa fa-plus-circle"><span class="hide">'.$result_row["course_class_id"].'</span></i>';
				/*if($log_data->result_array()) {
					$row[] = '<a href="list_course_class/view_log_report/'.$result_row["course_class_id"].'" data-target="#myModal" data-toggle="modal" style="color:red;">'.$result_row["course_class_id"].'</a>';  
				}else{*/ 			
    				$row[] = $result_row["course_class_id"];  
				//}  
    							
				if($log_data->result_array()) {
					$strTooltip = '<table style=\"font-size:10px\" class="table no-more-tables prop-log-table" >';
					$strTooltip .= '<tr><th width=\"200\">Updated By</th><th width=\"100\">Date</th><th width=\"600\" class="in-table"><table width=\"100%\"><th width=\"30%\">Field Name</th><th width=\"35%\">Old Value</th><th width=\"35%\">New Value</th></table></th><th width=\"200\">Reason</th></tr>';
					foreach($log_data->result_array() as $data1) {
						
						$section_id_clr = "";
						$primary_teacher_id_clr = "";
						$secondary_teacher_id_clr = "";
						$class_room_id_clr = "";
						$course_id_clr = "";
						$shift_clr = "";

						$chn_fld = $data1["change_field"];
						
						if($chn_fld == "section_id")
							$section_id_clr = 'bgcolor=\"#FF0000\"';
						else if($chn_fld == "primary_teacher_id")
							$primary_teacher_id_clr = 'bgcolor=\"#FF0000\"';
						else if($chn_fld == "secondary_teacher_id")
							$secondary_teacher_id_clr = 'bgcolor=\"#FF0000\"';
						else if($chn_fld == "class_room_id")
							$class_room_id_clr = 'bgcolor=\"#FF0000\"';
						else if($chn_fld == "course_id")
							$course_id_clr = 'bgcolor=\"#FF0000\"';
						else if($chn_fld == "shift")
							$shift_clr = 'bgcolor=\"#FF0000\"';
						
						$strTooltip .= "<tr data-sync-height=\"true\">";
						$strTooltip .= "<td>".addslashes(str_replace("'"," ",$data1["cname"]))."</td>";						
						$strTooltip .= "<td>".date('d-M-Y',strtotime($data1["change_date"]))."</td>";
						$strTooltip .= "<td class=\"in-table\">";
							$arrPredefinedField["course_id"] = "course_title";
							$arrPredefinedField["course_id_new"] = "course_title_new";
							$arrPredefinedField["primary_teacher_id"] = "pname";			
							$arrPredefinedField["primary_teacher_id_new"] = "pname_new";			
							$arrPredefinedField["secondary_teacher_id"] = "sname";			
							$arrPredefinedField["secondary_teacher_id_new"] = "sname_new";			
							$arrPredefinedField["class_room_id"] = "class_room_title";			
							$arrPredefinedField["class_room_id_new"] = "class_room_title_new";			
							$arrPredefinedField["section_id"] = "section_title";			
							$arrPredefinedField["section_id_new"] = "section_title_new";			
							$arrPredefinedField["shift"] = "shift";			
							$arrPredefinedField["shift_new"] = "shift_new";
							
							$arrPredefinedTitle["course_id"] = "Course";
							$arrPredefinedTitle["primary_teacher_id"] = "Pri. Teacher";			
							$arrPredefinedTitle["secondary_teacher_id"] = "Sec. Teacher";			
							$arrPredefinedTitle["class_room_id"] = "Class Room";			
							$arrPredefinedTitle["section_id"] = "Section";			
							$arrPredefinedTitle["shift"] = "Shift";			
							
							$chn_fld = $data1["change_field"];
							$chn_fldArr = explode(',',$chn_fld);
							if(count($chn_fldArr) > 0){
								$strTooltip .=  '<table border=\"0\" width=\"100%\" class="table no-more-tables prop-log-table">';
								$cnt = 1;
							foreach($chn_fldArr as $field_data) {
								$keyf = $arrPredefinedField[$field_data];
								$keyfn = $arrPredefinedField[$field_data."_new"];
								if($cnt<count($chn_fldArr)) { 
									$strTooltip .=  '<tr><td  width=\"30%\">'.$arrPredefinedTitle[$field_data].'</td><td  width=\"35%\">'.$data1[$keyf].'</td><td  width=\"35%\">'.$data1[$keyfn].'</td></tr>';
								}else{
									$strTooltip .=  '<tr><td  width=\"30%\">'.$arrPredefinedTitle[$field_data].'</td><td  width=\"35%\">'.$data1[$keyf].'</td><td  width=\"35%\">'.$data1[$keyfn].'</td></tr>';
								}
								$cnt++;
							}
								$strTooltip .=  '</table>';
								
							}
						$strTooltip .= "</td>";
						$strTooltip .= '<td width=\"300px\">'.addslashes(str_replace("'"," ",str_replace("\n"," ",str_replace("\r\n"," ",$data1["reason"])))).'</td></tr>';						
					}
					$strTooltip .= '</table>';
					//$row[] =  "<a onmouseover='javascript:popup(\"".$strTooltip."\",\"750px\");'><font size=\"2\" color=\"red\">".$result_row["course_title"]."</font></a>";
					$row[] =  "<a onmouseover='' id=\"popover\" data-content='".$strTooltip."' data-toggle=\"popover\"><font size=\"2\" color=\"red\">".$result_row["course_title"]."</font></a>";
				}else {
					$row[] = $result_row["course_title"];
				}
				$row[] = $result_row["first_staff_name"];
    			$row[] = $result_row["second_staff_name"];
    			$row[] = $result_row["class_room_title"];
    			$row[] = $result_row["section_title"];
    			$row[] = $result_row["courses_shift"];
    			$row[] = $result_row["campus_name"];
				$row[] = $result_row["track"];
				$row[] = $result_row["buildings"];
    			$row[] = $result_row["student_cnt"];
    			$row[] = $result_row["course_class_id"];
    			$output['aaData'][] = $row;
    		}
    	}
    	 
    	echo json_encode( $output );
    }
	
	public function view_log_report($id) 
	{
		$course_class_log_data = $this->list_teacher_student_model->get_course_class_log($id);
		$content_data['course_class_log_data'] = $course_class_log_data->result_array();
        $this->template->build('view_course_class_log', $content_data);
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
		
    	if(isset($_POST['search_gradetype']))
    		$content_data["search_gradetype"] = $_POST['search_gradetype'];
		
		$order_by = "course_title,class_room_title,section_title";
    	/*
    	 * Paging
    	*/
    	$per_page =  50000;
    	$offset =  0;
		
		$data = $this->list_course_class_model->get_course_class($per_page, $offset, $order_by, "asc", $search_data);
    	$arrCourseClass = array();
    	if($data){
    		foreach($data->result_array() AS $result_row){
    			$arrCourseClass[] = array("course_title" => $result_row["course_title"],
											"first_name" => $result_row["first_name"],
											"second_name" => $result_row["second_name"],
											"class_room_title" => $result_row["class_room_title"],
											"section_title" => $result_row["section_title"],
											"shift" => $result_row["courses_shift"]
										 );	
    		}
    	}
		$content_data["arrCourseClass"] = $arrCourseClass;
    	$this->template->build('course_class_excel', $content_data);
    }
	
    public function add($id = null){
    	$this->load->model('courses_model');
		$content_data['school_campus'] = get_campus();
        $content_data['classroom'] = $this->courses_model->get_class_room(1000,0, "class_room_title", "asc", array(),1);
        $content_data['section'] = $this->courses_model->get_section(1000,0, "section_title", "asc", array(),1);
		$content_data['school_year_list'] = get_school_year_title();
		$content_data['school_list'] = get_school_list();
		$content_data['course_subject'] = get_course_subject();		
		$content_data['course'] = get_course();		
		$content_data['teacher_list'] = get_teacher_list();		
		$content_data['course_category'] = get_course_category();	
		$content_data['shift'] = array('AM'=>'AM','PM'=>'PM');	
    	$content_data['id'] = $id;
    	$rowdata = array();
    	
    	if($id){
    		$rowdata = $this->list_course_class_model->get_course_class_data($id);
    
    	}
    	
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		
    		$course_id = $this->input->post('course_id');
    		$school_year_id = $this->input->post('school_year_id');
    		$school_id = $this->input->post('school_id');
    		$primary_teacher_id = $this->input->post('primary_teacher_id');
    		$secondary_teacher_id = $this->input->post('secondary_teacher_id');
    		$class_room_id = $this->input->post('class_room_id');
    		$section_id = $this->input->post('section_id');
    		$shift = $this->input->post('shift');
    		$camps_id = $this->input->post('camps_id');
			
    		$error = "";
    		$error_seperator = "<br>";
    		if($id){
    			 
    			$this->form_validation->set_rules('course_id', 'Course Name', 'trim|required|chk_combox_value');
        		$this->form_validation->set_rules('school_year_id', 'School year', 'trim|required|chk_combox_value');
        		$this->form_validation->set_rules('primary_teacher_id', 'Primary Teacher Name', 'trim|required||chk_combox_value');
				$this->form_validation->set_rules('class_room_id', 'Class Room', 'trim|required');
				$this->form_validation->set_rules('section_id', 'Section', 'trim|required|is_existing_field[course_class.section_id^course_class.course_class_id !=^'.$id.']');
    
    			if (!$this->form_validation->run()) {
    				if (form_error('course_id')) {
    					$error .= form_error('course_id').$error_seperator;
    				}elseif (form_error('school_year_id')) {
    					$error .= form_error('school_year_id').$error_seperator;
    				}elseif (form_error('primary_teacher_id')) {
    					$error .= form_error('primary_teacher_id').$error_seperator;
    				}elseif (form_error('class_room_id')) {
    					$error .= form_error('class_room_id').$error_seperator;
    				}elseif (form_error('section_id')) {
    					$error .= form_error('section_id').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    			$data = array();
    			$data['course_id'] = $course_id;
    			$data['school_year_id'] = $school_year_id;
    			$data['school_id'] = $school_id;
    			$data['primary_teacher_id'] = $primary_teacher_id;
    			$data['secondary_teacher_id'] = $secondary_teacher_id;
    			$data['class_room_id'] = $class_room_id;
    			$data['section_id'] = $section_id;
    			$data['shift'] = $shift;
    			$data['camps_id'] = $camps_id;
				
    			$table = 'course_class';
    			$wher_column_name = 'course_class_id';
    			set_activity_data_log($id,'Update','Course > List Course Class','List Course Class',$table,$wher_column_name,$user_id='');
				
				$changed_value = "";
				$changed_field = "";
				
    	   		if($rowdata->section_id."j" != $section_id){
					$changed_field .= "section_id,";
					$changed_value .= $section_id.",";
					$this->list_teacher_student_model->teacher_section_update_log($rowdata->primary_teacher_id,$rowdata->section_id);
					$this->list_teacher_student_model->teacher_section_update_log($rowdata->secondary_teacher_id,$rowdata->section_id);
				}
				if($rowdata->primary_teacher_id != $primary_teacher_id){
					$changed_field .= "primary_teacher_id,";
					$changed_value .= $primary_teacher_id.",";
				}
				if($rowdata->secondary_teacher_id != $secondary_teacher_id){
					$changed_field .= "secondary_teacher_id,";
					$changed_value .= $secondary_teacher_id.",";
				}
				if($rowdata->class_room_id != $class_room_id){
					$changed_field .= "class_room_id,";
					$changed_value .= $class_room_id.",";
				}
				if($rowdata->course_id != $course_id){
					$changed_field .= "course_id,";
					$changed_value .= $course_id.",";
				}
				if($rowdata->shift != $shift){
					$changed_field .= "shift,";
					$changed_value .= $shift.",";
				}
				
				$changed_field = trim(trim($changed_field),",");
				$changed_value = trim(trim($changed_value),",");
				
				if($changed_field != "" && $changed_value != ""){
					$last_log_id = $this->list_teacher_student_model->teacher_course_class_log($id,$changed_value,$changed_field);
				}
				
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    			
    			$data2 = array(
    					'teacher_id'	=> $primary_teacher_id);
    			
				$this->list_course_class_model->update_attendance_report($id,$data2);
				
    			$data3 = array( 
    					'course_id'    			=> $course_id,
    					'school_year_id'    	=> $school_year_id,
    					'school_id'				=> $school_id,
    					'primary_teacher_id'	=> $primary_teacher_id,
    					'secondary_teacher_id'	=> $secondary_teacher_id,
    					'class_room_id'			=> $class_room_id,
    					'section_id'			=> $section_id,
    					'shift'					=> $shift);
    			
				$this->list_course_class_model->update_late_attendance($id,$data3);
    			
    		}else{
    			 
    			$this->form_validation->set_rules('course_id', 'Course Name', 'trim|required|chk_combox_value');
        		$this->form_validation->set_rules('school_year_id', 'School year title', 'trim|required|chk_combox_value');
				$this->form_validation->set_rules('primary_teacher_id', 'Primary Teacher Name', 'trim|required||chk_combox_value');
				$this->form_validation->set_rules('class_room_id', 'Class Room', 'trim|required');
				$this->form_validation->set_rules('section_id', 'Section', 'trim|required|is_existing_unique_field[course_class.section_id]');
    			 
    			if (!$this->form_validation->run()) {
    				if (form_error('course_id')) {
    					$error .= form_error('course_id').$error_seperator;
    				}elseif (form_error('school_year_id')) {
    					$error .= form_error('school_year_id').$error_seperator;
    				}elseif (form_error('primary_teacher_id')) {
    					$error .= form_error('primary_teacher_id').$error_seperator;
    				}elseif (form_error('class_room_id')) {
    					$error .= form_error('class_room_id').$error_seperator;
    				}elseif (form_error('section_id')) {
    					$error .= form_error('section_id').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    			 
    			$data = array();
    			$data['course_id'] = $course_id;
    			$data['school_year_id'] = $school_year_id;
    			$data['school_id'] = $school_id;
    			$data['primary_teacher_id'] = $primary_teacher_id;
    			$data['secondary_teacher_id'] = $secondary_teacher_id;
    			$data['class_room_id'] = $class_room_id;
    			$data['section_id'] = $section_id;
    			$data['shift'] = $shift;
    			$data['camps_id'] = $camps_id;
				
    			$table = 'course_class';
    			$wher_column_name = 'course_class_id';
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Course > List Course Class','List Course Class',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_course_class_datatable', $content_data);
    }
    
    public function add_course_class(){
    	//Post data
    	$section_id = $this->input->post('section_id');
    	$school_year_id = $this->input->post('school_year_id');
    	$course_id = $this->input->post('course_id');
    	$primary_teacher_id = $this->input->post('primary_teacher_id');
    	$secondary_teacher_id = $this->input->post('secondary_teacher_id');
    	$class_room_id = $this->input->post('class_room_id');
    	$shift = $this->input->post('shift');
    	
    
    	$data = array();
    	$data['section_id'] = $section_id;
    	$data['school_year_id'] = $school_year_id;
    	$data['course_id'] = $course_id;
    	$data['primary_teacher_id'] = $primary_teacher_id;
    	$data['secondary_teacher_id'] = $secondary_teacher_id;
    	$data['class_room_id'] = $class_room_id;
    	$data['shift'] = $shift;
    	
    	
    	//Table name
    	$table = 'course_class';
    
    	grid_add_data($data,$table);
    }
    
    public function update_course_class(){
    	
    	$error = "";
    	$error_seperator = "<br>";
    	
    	$value = $this->input->post('value');
    	$columnName = $this->input->post('columnName');
    	$id = strip_tags($this->input->post('id'));

    	if($id){
    		$rowdata = $this->list_course_class_model->get_course_class_data($id);
    	}
    	
    	$tablename = 'course_class';
    	$whrid_column = 'course_class_id';
    	
    	if($columnName != '') $_POST[$columnName] = $value;
    	$this->form_validation->set_error_delimiters('', '');
    	if($columnName == 'campus_name')
			$columnName = 'camps_id';
			
    	if ($columnName == 'course_id')
    		$this->form_validation->set_rules('course_id', 'Course Name', 'trim|required|chk_combox_value');
    	if ($columnName == 'school_year_id')
    		$this->form_validation->set_rules('school_year_id', 'School year', 'trim|required|chk_combox_value');
    	if ($columnName == 'primary_teacher_id')
    		$this->form_validation->set_rules('primary_teacher_id', 'Primary Teacher Name', 'trim|required|chk_combox_value');
    	if ($columnName == 'class_room_id')
    		$this->form_validation->set_rules('class_room_id', 'Class Room', 'trim|required|chk_combox_value');
    	if ($columnName == 'section_id')
    		$this->form_validation->set_rules('section_id', 'Section', 'trim|required|is_existing_field[course_class.section_id^course_class.course_class_id !=^'.$id.']');
    	 
    	if (!$this->form_validation->run()) {
    		if (form_error('course_id')) {
    			$error .= form_error('course_id').$error_seperator;
    		}elseif (form_error('school_year_id')) {
    			$error .= form_error('school_year_id').$error_seperator;
    		}elseif (form_error('primary_teacher_id')) {
    			$error .= form_error('primary_teacher_id').$error_seperator;
    		}elseif (form_error('class_room_id')) {
    			$error .= form_error('class_room_id').$error_seperator;
    		}elseif (form_error('section_id')) {
    			$error .= form_error('section_id').$error_seperator;
    		}
			
			if($error != "")
			{
				echo $error;
				exit();
			}
    	}
		$last_log_id = 0;
		$p_log_id = 0;
		$s_log_id = 0;
   		
		if($rowdata->course_id != $value && $columnName == 'course_id'){
			$last_log_id = $this->list_teacher_student_model->teacher_course_class_log($id,$value,'course_id');
		}
		else if($rowdata->shift != $value && $columnName == 'shift'){
			$last_log_id = $this->list_teacher_student_model->teacher_course_class_log($id,$value,'shift');
		}
		
    	set_activity_data_log($id,'Update','Course > List Course Class','List Course Class',$tablename,$whrid_column,$user_id='');
		if($last_log_id > 0 || $columnName == 'camps_id')
    		grid_update_data($whrid_column,$id,$columnName,$value,$tablename);
		
    	if(($rowdata->section_id."j" != $value && $columnName == 'section_id') ||
    	   ($rowdata->primary_teacher_id != $value && $columnName == 'primary_teacher_id') ||
		   ($rowdata->secondary_teacher_id != $value && $columnName == 'secondary_teacher_id') || 
    	   ($rowdata->class_room_id."j" != $value && $columnName == 'class_room_id'))	
    	{
			$html = '<form name="reason_form" id="reason_form" method="post" action="list_course_class/add_reason">';
					$html .= '<input type="hidden" name="posted_value" value="'.$value.'"/>';
					$html .= '<input type="hidden" name="posted_columnName" value="'.$columnName.'"/>';
					$html .= '<input type="hidden" name="posted_id" value="'.$id.'"/>';
					$html .= '<input type="hidden" name="tablename" value="'.$tablename.'"/>';
					$html .= '<input type="hidden" name="whrid_column" value="'.$whrid_column.'"/>';
							$html .= '<table cellpadding="0" cellspacing="0" border="0">';
								$html .= '<tr>';
									$html .= '<td colspan="2" align="center">';
									$html .= '&nbsp;';
									$html .= '</td>';
								$html .= '</tr>';
								$html .= '<tr>';
									$html .= '<td align="right"><strong>Reason : </strong></td>';
									$html .= '<td>';
									$html .= '<textarea rows="7" cols="25" name="reason"></textarea>';
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
    	}else{
			if(ob_get_contents() != 'success')
				echo 'success';
		}
    }
	
	public function add_reason()
	{
		ob_start();
		$p_log_id = 0;
		$s_log_id = 0;
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
			
		//////
		if($posted_id){
    		$rowdata = $this->list_course_class_model->get_course_class_data($posted_id);
    	}
		if($rowdata->section_id."j" != $posted_value && $posted_columnName == 'section_id'){
   			$last_log_id = $this->list_teacher_student_model->teacher_course_class_log($posted_id,$posted_value,'section_id');
			$p_log_id = $this->list_teacher_student_model->teacher_section_update_log($rowdata->primary_teacher_id,$rowdata->section_id);
			$s_log_id = $this->list_teacher_student_model->teacher_section_update_log($rowdata->secondary_teacher_id,$rowdata->section_id);
		}
		else if($rowdata->primary_teacher_id != $posted_value && $posted_columnName == 'primary_teacher_id'){
			$last_log_id = $this->list_teacher_student_model->teacher_course_class_log($posted_id,$posted_value,'primary_teacher_id');
		}
		else if($rowdata->secondary_teacher_id != $posted_value && $posted_columnName == 'secondary_teacher_id'){
			$last_log_id = $this->list_teacher_student_model->teacher_course_class_log($posted_id,$posted_value,'secondary_teacher_id');
		}
		else if($rowdata->class_room_id != $posted_value && $posted_columnName == 'class_room_id'){
			$last_log_id = $this->list_teacher_student_model->teacher_course_class_log($posted_id,$posted_value,'class_room_id');
		}	
		grid_update_data($whrid_column,$posted_id,$posted_columnName,$posted_value,$tablename);
		///////////////////	
		
		$this->list_course_class_model->update_course_class_log($reason,$last_log_id);
		
		$this->list_course_class_model->update_users_log($reason,$p_log_id);
		
		$this->list_course_class_model->update_users_log($reason,$s_log_id);
		
		@ob_end_clean();
		redirect('/list_course_class');
	}

    public function action_course_class($id, $offset, $order_by, $sort_order, $search) {
        if (array_key_exists('update', $_POST)) {
        	
            $this->_update_course_class($id, $offset, $order_by, $sort_order, $search);
        }else{
        	
            $this->_delete_course_class($id, $offset, $order_by, $sort_order, $search);
        }
    }

    /**
     *
     * _update_course_class: update course class info from course
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _update_course_class($id, $offset, $order_by, $sort_order, $search) {
        $this->form_validation->set_error_delimiters('', '');             
        
		$this->form_validation->set_rules('course_id', 'Course Name', 'trim|required|chk_combox_value');
        $this->form_validation->set_rules('school_year_id', 'School year', 'trim|required|chk_combox_value');
        $this->form_validation->set_rules('primary_teacher_id', 'Primary Teacher Name', 'trim|required|chk_combox_value');
		$this->form_validation->set_rules('class_room_id', 'Class Room', 'trim|required|chk_combox_value');
		$this->form_validation->set_rules('section_id', 'Section', 'trim|required|is_existing_field[course_class.section_id^course_class.course_class_id !=^'.$this->input->post('course_class_id').']');

        $username = $this->list_course_class_model->get_classroom_by_id($id);
        
        if (!$this->form_validation->run()) {
            if (form_error('course_id')) {
                $this->session->set_flashdata('message', form_error('course_id'));
            }elseif (form_error('school_year_id')) {
                $this->session->set_flashdata('message', form_error('school_year_id'));
            }elseif (form_error('primary_teacher_id')) {
                $this->session->set_flashdata('message', form_error('primary_teacher_id'));
            }elseif (form_error('class_room_id')) {
                $this->session->set_flashdata('message', form_error('class_room_id'));
            }elseif (form_error('section_id')) {
                $this->session->set_flashdata('message', form_error('section_id'));
            }
            redirect('/list_course_class/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }

        $this->list_course_class_model->update_course_class($this->input->post('course_class_id'),$this->input->post('course_id'),$this->input->post('category_id'),$this->input->post('school_year_id'),$this->input->post('school_id'), $this->input->post('primary_teacher_id'), $this->input->post('secondary_teacher_id'), $this->input->post('class_room_id'), $this->input->post('section_id'), $this->input->post('shift'), $this->input->post('total_seats'), $this->input->post('registered_student'), $this->input->post('credits'), $this->input->post('restricted_hours'));
        set_activity_log($this->input->post('course_class_id'),'update','course','list course class');
        $this->session->set_flashdata('message', sprintf($this->lang->line('course_class_updated'), $this->input->post('class_room_id'), $this->input->post('course_class_id')));
        redirect('/list_course_class/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

     /**
     *
     * _delete_course_class: delete member from school
     *
     * @param int $id the id of the member to be deleted
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _delete_course_class($id, $offset, $order_by, $sort_order, $search) {
        $username = $this->list_course_class_model->get_classroom_by_id($id);
        if ($username == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noremove'));
        }elseif ($this->list_course_class_model->delete_course_class($id)) {
        	set_activity_log($id,'delete','course','list course class');
            $this->session->set_flashdata('message', sprintf($this->lang->line('course_class_deleted'), $username, $id));
        }
        redirect('/list_course_class/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

    

    /**
     *
     * toggle_active: (de)activate member from school
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
        if ($this->list_course_class_model->get_classroom_by_id($id) == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noactivate'));
            redirect('/list_course_class/index');
            return;
        }elseif ($this->list_course_class_model->toggle_active($id, $active)) {
            $active ? $active = $this->lang->line('deactivated') : $active = $this->lang->line('activated');
            $this->session->set_flashdata('message', sprintf($this->lang->line('toggle_active'), $username) . $active);
        }
        redirect('/list_course_class/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
    
    public function get_listbox($type,$campus_id=0){
    	$this->load->model('courses_model');
    	$jsondata = '';
		if($type == 'track'){
    		$school_year_list = get_track();
    		$jsondata .= '{';
    		foreach ($school_year_list as $key => $val){
    			
    			
    			if(end($school_year_list) == $val){
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\'';
    			}else{
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\''.',';
    			}
    			
    		}
    		$jsondata .= '}';
    		echo $jsondata;
    	}
		if($type == 'buildings'){
    		$school_year_list = get_buildings();
    		$jsondata .= '{';
    		foreach ($school_year_list as $key => $val){
    			
    			
    			if(end($school_year_list) == $val){
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\'';
    			}else{
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\''.',';
    			}
    			
    		}
    		$jsondata .= '}';
    		echo $jsondata;
    	}
    	if($type == 'school_year'){
    		$school_year_list = get_school_year_title();
    		$jsondata .= '{';
    		foreach ($school_year_list as $key => $val){
    			
    			
    			if(end($school_year_list) == $val){
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\'';
    			}else{
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\''.',';
    			}
    			
    		}
    		$jsondata .= '}';
    		echo $jsondata;
    	}
    	
    	if($type == 'course_class'){
    		$whr = array();
    		$course = get_course($whr,$campus_id);
    		
    		if($campus_id > 0)
    		{
    			foreach ($course as $key => $val){
	    				$jsondata .= '<option value=\''.$key.'\'>'.$val.'</option>';
	    		}
    		}
    		else 
    		{
                    $cnt=1;
	    		$jsondata .= '{';
	    		foreach ($course as $key => $val){
	    			 
	    			 
	    			if(count($course) == $cnt){
	    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\'';
	    			}else{
	    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\''.',';
	    			}
	    			 $cnt++;
	    		}
	    		$jsondata .= '}';
    		}
    		
    		
    		
    		echo $jsondata;
    	}
    	
    	if($type == 'course_category'){
    		$course_category = get_course_category();
    		$jsondata .= '{';
    		foreach ($course_category as $key => $val){
    	
    	
    			if(end($course_category) == $val){
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\'';
    			}else{
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\''.',';
    			}
    	
    		}
    		$jsondata .= '}';
    		echo $jsondata;
    	}
    	
    	
    	if($type == 'primary_teacher'){
    		$teacher_list = get_teacher_list($campus_id);
                asort($teacher_list);
            if($campus_id > 0)
    		{
    			foreach ($teacher_list as $key => $val){
	    				$jsondata .= '<option value=\''.$key.'\'>'.$val.'</option>';
	    		}
    		}
    		else 
    		{    
	    		$jsondata .= '{';
	    		foreach ($teacher_list as $key => $val){
	    			 
	    			 
	    			if(end($teacher_list) == $val){
	    				$jsondata .= '"'.addslashes($key).'"'.':'.'"'.addslashes($val).'"';
	    			}else{
	    				$jsondata .= '"'.addslashes($key).'"'.':'.'"'.addslashes($val).'"'.',';
	    			}
	    			 
	    		}
	    		$jsondata .= '}';
    		}
    		echo $jsondata;
    	}
    	
		if($type == 'ca_lead_teacher'){
    		$ca_lead_teacher_list = get_ca_lead_teacher_list();
                asort($ca_lead_teacher_list);
    		$jsondata .= '{';
    		foreach ($ca_lead_teacher_list as $key => $val){
    			 
    			 
    			if(end($ca_lead_teacher_list) == $val){
    				$jsondata .= '"'.addslashes($key).'"'.':'.'"'.addslashes($val).'"';
    			}else{
    				$jsondata .= '"'.addslashes($key).'"'.':'.'"'.addslashes($val).'"'.',';
    			}
    			 
    		}
    		$jsondata .= '}';
    		echo $jsondata;
    	}
		
    	if($type == 'secondary_teacher'){
    		$teacher_list = get_teacher_list($campus_id);
    		
    		if($campus_id > 0)
    		{
    			foreach ($teacher_list as $key => $val){
	    				$jsondata .= '<option value=\''.$key.'\'>'.$val.'</option>';
	    		}
    		}
    		else 
    		{    
	    		$jsondata .= '{';
	    		foreach ($teacher_list as $key => $val){
	    	
	    	
	    			if(end($teacher_list) == $val){
	    				$jsondata .= '"'.addslashes($key).'"'.':'.'"'.addslashes($val).'"';
	    			}else{
	    				$jsondata .= '"'.addslashes($key).'"'.':'.'"'.addslashes($val).'"'.',';
	    			}
	    	
	    		}
	    		$jsondata .= '}';
    		}
    		echo $jsondata;
    	}
    	
    	
    	if($type == 'class_room'){
    		$classroom = $this->courses_model->get_class_room(1000,0, "class_room_title", "asc", array(),1,$campus_id);
    		
    		if($campus_id > 0)
    		{
    			foreach ($classroom as $key => $val){
	    				$jsondata .= '<option value=\''.$key.'\'>'.$val.'</option>';
	    		}
    		}
    		else 
    		{
	    		$jsondata .= '{';
	    		foreach ($classroom as $key => $val){
	    			 
	    			if(end($classroom) == $val){
	    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\'';
	    			}else{
	    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\''.',';
	    			}
	    			 
	    		}
	    		$jsondata .= '}';
    		}
    		echo $jsondata;
    	}
    	
    	
    	if($type == 'section'){
    		$section = $this->courses_model->get_section(1000,0, "section_title", "asc", array(),1,$campus_id);
    		
    		if($campus_id > 0)
    		{
    			foreach ($section as $key => $val){
	    				$jsondata .= '<option value=\''.$key.'\'>'.$val.'</option>';
	    		}
    		}
    		else 
    		{
	    		$jsondata .= '{';
	    		foreach ($section as $key => $val){
	    	
	    	
	    			if(end($section) == $val){
	    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\'';
	    			}else{
	    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\''.',';
	    			}
	    	
	    		}
	    		$jsondata .= '}';
    		}
    		echo $jsondata;
    	}
    	
    	if($type == 'shift'){
    		$shift = array('AM'=>'AM','PM'=>'PM');
    		$jsondata .= '{';
    		foreach ($shift as $key => $val){
    			 
    			 
    			if(end($shift) == $val){
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\'';
    			}else{
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\''.',';
    			}
    			 
    		}
    		$jsondata .= '}';
    		echo $jsondata;
    	}
		
		if($type == 'campus'){
    		$campus = get_campus();
    		$jsondata .= '{';
    		foreach ($campus as $key => $val){
    			 
    			 
    			if(end($campus) == $val){
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\'';
    			}else{
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\''.',';
    			}
    			 
    		}
    		$jsondata .= '}';
    		echo $jsondata;
    	}
    }
	
	public function delete($id = null){
		if($id){
			set_activity_log($id,'Delete','course','list course class');
			$this->list_course_class_model->delete_course_class($id);
    	}
		redirect('/list_course_class/');
        exit();
	}
}

/* End of file list_course_class.php */
