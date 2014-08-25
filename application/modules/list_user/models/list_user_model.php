<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_user_profile($user_id) {
		$this->db->select('users.*,users.user_id as user_unique_id,user_profile.*,job_title.job_title AS job_title_name,user_verifications.*,countries.nationality as nationality_name,user_roll.user_roll_name,contractors.contractor as contractor_name,school_campus.campus_name,c1.first_name as line_manager,duties.duties,c2.first_name as change_by_name');
    	$this->db->from('users');
		$this->db->join('user_profile', 'user_profile.user_id = users.user_id','left');
		$this->db->join('job_title', 'job_title.job_title_id = user_profile.job_title','left');
    	$this->db->join('user_verifications', 'user_verifications.user_id = users.user_id','left');
		$this->db->join('countries', 'countries.id = user_profile.nationality','left');
		$this->db->join('user_roll', 'user_roll.user_roll_id = users.user_roll_id','left');
		$this->db->join('contractors', 'contractors.id = user_profile.contractor','left');
		$this->db->join('school_campus', 'school_campus.campus_id = users.campus_id','left');
		$this->db->join('users AS c1', 'c1.user_id = users.coordinator','left');
		$this->db->join('duties', 'duties.user_roll_id = users.user_roll_id','left');
		$this->db->join('users AS c2', 'c2.user_id = users.change_by','left');
		$this->db->where('users.user_id',$user_id);
		//$this->db->where('users.user_id',$user_id);
    	$query = $this->db->get();
		//echo $this->db->last_query();
    	
		if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
	
	public function check_user_profile_exist($user_id) {
		$this->db->select('*');
    	$this->db->from('user_profile');
		$this->db->where('user_profile.user_id',$user_id);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
	
	public function get_cv_reference($user_id) {
		$this->db->select('user_cv_reference.*');
    	$this->db->from('user_cv_reference');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
		//echo $this->db->last_query();
    	if($query->num_rows() > 0) {
    		return $query;
    	}
		
		return false;
	}
	
	public function get_quali_certi($id,$type) {
		$this->db->select('user_qualification.*');
    	$this->db->from('user_qualification');
		$this->db->where('type',$type);
		$this->db->where('user_qualification_id',$id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
	
	public function get_emergency_contact_data($id) {
		$this->db->select('emergency_contacts.*');
    	$this->db->from('emergency_contacts');
		$this->db->where('emergency_contact_id',$id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
	
	public function get_emergency_contacts($user_id) {
		$this->db->select('emergency_contacts.*,countries.country as country_name');
    	$this->db->from('emergency_contacts');
		$this->db->join('countries', 'countries.id = emergency_contacts.country','left');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query;
    	}
		
		return false;
	}
	
	public function get_user_quali_certi($user_id,$type) {
		$this->db->select('user_qualification.*,qualifications.qualification');
    	$this->db->from('user_qualification');
		$this->db->join('qualifications', 'qualifications.id = user_qualification.qualification_id','left');
		$this->db->where('user_id',$user_id);
		$this->db->where('user_qualification.type',$type);
		$query = $this->db->get();

    	if($query->num_rows() > 0) {
    		return $query;
    	}
		
		return false;
	}
	
	public function get_user_experience($user_id) {
		$this->db->select('user_workhistory.*');
    	$this->db->from('user_workhistory');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query;
    	}
		
		return false;
	}
	
	public function get_user_workshop($user_id) {
		$this->db->select('workshops.title,workshops.topic,workshops.start_date,CONCAT_WS(" ",users.first_name,users.middle_name,users.middle_name2,users.last_name) as presenter_name,workshop_types.type',false);
    	$this->db->from('workshops');
		$this->db->join('user_workshop', 'user_workshop.workshop_id = workshops.workshop_id','left');
		$this->db->join('users', 'workshops.presenter = users.user_id','left');
		$this->db->join('workshop_types', 'workshop_types.id = workshops.workshop_type_id','left');
		$this->db->where('user_workshop.attendance',1);
		$this->db->where('user_workshop.attendee',$user_id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query;
    	}
		
		return false;
	}
	
	public function get_experience($id) {
		$this->db->select('user_workhistory.*');
    	$this->db->from('user_workhistory');
		$this->db->where('user_workhistory_id',$id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
	public function get_reference($id) {
		$this->db->select('user_cv_reference.*');
    	$this->db->from('user_cv_reference');
		$this->db->where('referance_id',$id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
	
	public function get_user_permossion($user_id) {
		$this->db->select('user_permissions.*');
    	$this->db->from('user_permissions');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		return $query->row();
    	}
		
		return false;
	}
	
	public function get_user_documents($user_id) {
		$this->db->select('profile_certificate.*');
    	$this->db->from('profile_certificate');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
		$arrData = array();
		
    	if($query->num_rows() > 0) {
    		foreach($query->result_array() AS $data)
			{
				$arrData[$data["certificate_type"]][$data["id"]] = $data["certificate_file"];
			}
    	}
		
		return $arrData;
	}
	
	public function delete_user_document($user_id,$certificate_type,$certificate_id = 0) {
        $this->db->where('user_id', $user_id);
        $this->db->where('certificate_type', $certificate_type);
		if($certificate_id > 0){
			$this->db->where('id', $certificate_id);
		}
        $this->db->delete('profile_certificate');
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }
	
	public function update_user_profileid($userid, $table, $table_profile_field='profile_id', $table_where_field='user_id') {
        $data = array($table_profile_field => 'user_profile_id');

        $this->db->where($table_where_field, $userid);
        $this->db->update($table, $data);
		$this->db->join('user_profile', 'user_profile.user_id = '.$table.$table_where_field,'left');
    }
	
	public function get_observations($limit = 0, $offset = 0, $order_by = "username", $sort_order = "asc", $search_data,$campus_id=0,$count = false) {
        $fields = $this->db->list_fields('users');

        if (!empty($search_data)) {
            !empty($search_data['username']) ? $data['users.username'] = $search_data['username'] : "";
            !empty($search_data['first_name']) ? $data['users.first_name'] = $search_data['first_name'] : "";
            !empty($search_data['ca_lead_teacher']) ? $data['ca_lead.first_name'] = $search_data['ca_lead_teacher'] : "";
            !empty($search_data['section_title']) ? $data['course_section.section_title'] = $search_data['section_title'] : "";
            !empty($search_data['elsd_id']) ? $data['users.elsd_id'] = $search_data['elsd_id'] : "";
            !empty($search_data['email']) ? $data['users.email'] = $search_data['email'] : "";
            !empty($search_data['user_roll_name']) ? $data['user_roll_name'] = $search_data['user_roll_name'] : "";
            !empty($search_data['name_suffix']) ? $data['users.name_suffix'] = $search_data['name_suffix'] : "";
            !empty($search_data['address1']) ? $data['users.address1'] = $search_data['address1'] : "";
            !empty($search_data['city']) ? $data['users.city'] = $search_data['city'] : "";
            !empty($search_data['state']) ? $data['users.state'] = $search_data['state'] : "";
            !empty($search_data['zip']) ? $data['users.zip'] = $search_data['zip'] : "";
            !empty($search_data['cell_phone']) ? $data['users.cell_phone'] = $search_data['cell_phone'] : "";
            !empty($search_data['campus']) ? $data['users.campus'] = $search_data['campus'] : "";
        }
        $this->db->select('users.*,course_section.section_title,obs_detail.comment,obs_detail.comment,obs_detail.score1,obs_detail.et_score1,obs_detail.updated_at AS obs_date',FALSE);
        $this->db->from('users');  
        $this->db->join('course_class','users.user_id = course_class.primary_teacher_id','left');  
        $this->db->join('course_section','course_class.section_id = course_section.section_id','left');  
		$this->db->join('obs_detail','users.user_id = obs_detail.user_id','left');  
        
		$this->db->where('users.active','1');       
		if($campus_id > 0) {
			$this->db->where('users.campus_id = '.$campus_id);
		}
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
        
        $this->db->order_by($order_by, $sort_order);
		
		if($count == false)
        	$this->db->limit($limit, $offset);
		
		$this->db->group_by(array("users.user_id"));
        $query = $this->db->get();
		
		if($count == true)
			return $query->num_rows();
			
        if($query->num_rows() > 0) {
            return $query;
        }
    }
	
	public function check_obs_exists($user_id) {
		
		$this->db->select('obs_detail.id');
    	$this->db->from('obs_detail');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
		//echo $this->db->last_query();
    	if($query->num_rows() > 0) {
    		$data = $query->row();
			return $data->id;
    	}
		return false;
	}
	
	public function set_password($password,$user_id) {
        $this->load->helper('password');
        $new_nonce = md5(uniqid(mt_rand(), true));
        $data = array(
               'password' => hash_password($password, $new_nonce),
               'nonce' => $new_nonce
            );

        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);

        if ($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }
	
	public function set_user_verifications($user_id) {
		$query = 'INSERT INTO user_verifications (user_id,
															ver_nationality,
															degree_comments,
															ver_experience,
															ver_reference,
															interviewee1,
															interviewee2,
															interview_date,
															interview_outcome,
															interview_notes,
															interview_type,
															lesson_plan_submitted,
															lesson_plan_suitable,
															lesson_plan_comments,
															writing_sample_submitted,
															writing_sample_suitable,
															writing_sample_comments,
															demo_lesson_recommended,
															updated_at) 
													VALUES ("'.$user_id.'",
															"'.$this->input->post('ver_nationality').'",
															"'.$this->input->post('degree_comments').'",
															"'.$this->input->post('ver_experience').'",
															"'.$this->input->post('ver_reference').'",
															"'.$this->input->post('interviewee1').'",
															"'.$this->input->post('interviewee2').'",
															"'.date('Y-m-d',strtotime($this->input->post('interview_date'))).'",
															"'.$this->input->post('interview_outcome').'",
															"'.$this->input->post('interview_notes').'",
															"'.$this->input->post('interview_type').'",
															"'.$this->input->post('lesson_plan_submitted').'",
															"'.$this->input->post('lesson_plan_suitable').'",
															"'.$this->input->post('lesson_plan_comments').'",
															"'.$this->input->post('writing_sample_submitted').'",
															"'.$this->input->post('writing_sample_suitable').'",
															"'.$this->input->post('writing_sample_comments').'",
															"'.$this->input->post('demo_lesson_recommended').'",
															"'.date('Y-m-d H:i:s').'"
															)
					  ON DUPLICATE KEY UPDATE ver_nationality="'.$this->input->post('ver_nationality').'",
					  						degree_comments="'.$this->input->post('degree_comments').'",
											ver_experience="'.$this->input->post('ver_experience').'",
											ver_reference="'.$this->input->post('ver_reference').'",
											interviewee1="'.$this->input->post('interviewee1').'",
											interviewee2="'.$this->input->post('interviewee2').'",
											interview_date="'.date('Y-m-d',strtotime($this->input->post('interview_date'))).'",
											interview_outcome="'.$this->input->post('interview_outcome').'",
											interview_notes="'.$this->input->post('interview_notes').'",
											interview_type="'.$this->input->post('interview_type').'",
											lesson_plan_submitted="'.$this->input->post('lesson_plan_submitted').'",
											lesson_plan_suitable="'.$this->input->post('lesson_plan_suitable').'",
											lesson_plan_comments="'.$this->input->post('lesson_plan_comments').'",
											writing_sample_submitted="'.$this->input->post('writing_sample_submitted').'",
											writing_sample_suitable="'.$this->input->post('writing_sample_suitable').'",
											writing_sample_comments="'.$this->input->post('writing_sample_comments').'",
											demo_lesson_recommended="'.$this->input->post('demo_lesson_recommended').'",
											updated_at="'.date('Y-m-d H:i:s').'"';
					 
					 $this->db->query($query);
	}
	
	function upload_profile_pic($user_id,$data){
		$this->db->where('user_id', $user_id);
		$this->db->update('users', $data);
		return $this->db->affected_rows();
	}
	
	public function get_existing_privilege($user_roll_id) {
    	$this->db->select('*');
    	$this->db->from('user_privilege');
    	$this->db->where('user_roll_id',$user_roll_id);
    	$query = $this->db->get();
    	$menu_data = $query->result_array();
    	
    	$user_roll_id = array();
    	$i = 0;
    	$action_arr = array();
    	foreach ($menu_data as $menu_datas){
    		$this->db->select('*');
    		$this->db->from('menu_action');
    		$this->db->where('menu_action_id',$menu_datas['menu_action_id']);
    		$querys = $this->db->get();
    		$menuaction = $querys->result_array();
    		foreach ($menuaction as $menuactions){
    			$action_arr[] = $menuactions['menu_id'].'_'.$menuactions['rights'];
    		}
    		$i++;
    	}
    	return ($action_arr);
    }
	
	public function create_single_user_privilege($user_id, $action) {
    	$this->db->where('user_id',$user_id);
    	$delete = $this->db->delete('user_custom_privilege');
		for($i=0;$i<count($action);$i++){
			if($action[$i]){
				$actArr = explode('_', $action[$i]);
				$menu_id = $actArr[0];
				$rights = $actArr[1];
				
				$this->db->select('*');
				$this->db->from('menu_action');
				$this->db->where('menu_id',$menu_id);
				$this->db->where('rights',$rights);
				$query = $this->db->get();
				$menu_action = $query->result_array();
				if($menu_action){
					foreach ($menu_action as $menu_actions){
						
						$data = array(
								'user_id' => $user_id,
								'menu_action_id' => $menu_actions['menu_action_id']
						);
						$this->db->insert('user_custom_privilege', $data);
					}
				}
				
			}
	   	
		}
		return true;
    }
	
	public function get_user_roll($user_id) {
		$this->db->select('user_roll_id');
    	$this->db->from('users');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		$data = $query->row();
			return $data->user_roll_id;
    	}
		
		return false;
	}
	
	public function is_accepted_nationality($id) {
		$this->db->select('accepted');
    	$this->db->from('countries');
		$this->db->where('id',$id);
		$query = $this->db->get();
    	if($query->num_rows() > 0) {
    		$data = $query->row();
			if($data->accepted == 'Yes')
				return true;
			else
				return false;	
    	}
		
		return false;
	}
	
	public function profile_update_log($id,$new_status) {
		
		$user_id = $this->session->userdata('user_id');
		$change_date = date('Y-m-d H:i:s');
		
		$sql = "insert into users_log(`user_id`, `user_roll_id`, `username`, `password`, `section_id`, `section_title_name`, `email`, `student_uni_id`, `track`, `schedule_date`, `academic_status`, `first_name`, `first_name_arabic`, `middle_name`, `last_name`, `name_suffix`, `address1`, `address2`, `city`, `state`, `zip`, `birth_date`, `birth_place`, `gender`, `language_known`, `work_phone`, `home_phone`, `cell_phone`, `last_login_date`, `login_attempts`, `profile_picture`, `nonce`, `elsd_id`, `coordinator`, `ca_lead_teacher`, `campus`, `campus_id`, `created_date`, `updated_date`, `active`, `discontinue`, `discontinue_date`, `discontinue_week_id`,`status_old`,`status_new`,`change_by`, `change_date`)

SELECT `user_id`, `user_roll_id`, `username`, `password`, `section_id`, `section_title_name`, `email`, `student_uni_id`, `track`, `schedule_date`, `academic_status`, `first_name`, `first_name_arabic`, `middle_name`, `last_name`, `name_suffix`, `address1`, `address2`, `city`, `state`, `zip`, `birth_date`, `birth_place`, `gender`, `language_known`, `work_phone`, `home_phone`, `cell_phone`, `last_login_date`, `login_attempts`, `profile_picture`, `nonce`, `elsd_id`, `coordinator`, `ca_lead_teacher`, `campus`, `campus_id`, `created_date`, `updated_date`, `active`, `discontinue`, `discontinue_date`, `discontinue_week_id`,`status`,'$new_status',$user_id,'$change_date' FROM `users` WHERE user_id =$id";
		$this->db->query($sql);
		$last_log_id =  $this->db->insert_id();  
		
		$user_id = $this->session->userdata('user_id');
        $data = array('change_by' => $user_id,'change_date' => date('Y-m-d H:i:s'));
        $this->db->where('user_id', $id);
        $this->db->update('users', $data);
        
        if($this->db->affected_rows() == 1) {
            return $last_log_id;
        }
        return false;
    }
	
	public function get_viewstatuslog($user_unique_id,$limit = 0, $offset = 0, $order_by = "username", $sort_order = "asc", $search_data,$campus_id=0,$count = false) 
	{
		$arrAllStatus = user_profile_status();
		$strQueryAllStatus = "";
		$strQueryAllOldStatus = "";
		$strQueryAllNewStatus = "";
		
		foreach($arrAllStatus AS $key=>$val)
		{
			if($key != "")
			{
				$strQueryAllStatus .= " WHEN $key THEN '$val' ";
			}	
		}
		
		if($strQueryAllStatus != "")
		{
			$strQueryAllOldStatus = " CASE user_status_log.old_status $strQueryAllStatus  ELSE 'N/A' END ";
			$strQueryAllNewStatus = " CASE user_status_log.new_status $strQueryAllStatus  ELSE 'N/A' END ";
		}
		
        $this->db->select('user_status_log.user_id,
						   u1.elsd_id,
						   CONCAT_WS(" ",u1.first_name,u1.middle_name,u1.middle_name2,u1.last_name) AS staff_name,'.
						   $strQueryAllOldStatus.' AS oldstatus,'.
						   $strQueryAllNewStatus.' AS newstatus,
						   CONCAT_WS(" ",c1.first_name,c1.middle_name,c1.middle_name2,c1.last_name) AS updated_by,
						   comment,
						   updated_at'
						  ,FALSE);
        $this->db->from('user_status_log');  
        $this->db->join('users AS u1','u1.user_id = user_status_log.user_id','left');  
        $this->db->join('users AS c1','c1.user_id = user_status_log.change_by','left'); 
        
        $this->db->where('user_status_log.user_id', $user_unique_id);
        $this->db->order_by($order_by, $sort_order);
		
		if($count == false)
        	$this->db->limit($limit, $offset);
		
		$query = $this->db->get();
		
		if($count == true)
			return $query->num_rows();
			
        if($query->num_rows() > 0) {
            return $query;
        }
    }
}

/* End of file list_user_model.php */
