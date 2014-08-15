<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add_employee extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
		
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->model('list_course/courses_model');
        $this->load->model('list_student/list_teacher_student_model');
		$this->load->model('list_user/list_user_model');
		$this->load->model('add_employee_model');
		$this->load->helper('general_function');
		$this->load->model('add_privilege/privilege_model');
		
    }
	
    public function index($user_id=0) {
    	
		if(isset($_POST['submit']) && $this->input->post('submit') == 'Save') {		
			
			$error = "";
			$error_seperator = "<br>";
			$nonce = md5(uniqid(mt_rand(), true));
			
			$user_id = $this->input->post('user_id');
			$first_name = $this->input->post('first_name');
			$email = $this->input->post('email');
			$username = $this->input->post('email');
			
			if($user_id > 0) {
				
				$this->list_user_model->profile_update_log($user_id,$this->input->post('status'));
				$user_data = array(
							/*'status'       => $this->input->post('status'),*/
							'first_name'       => $this->input->post('first_name'),
							'middle_name'       => $this->input->post('middle_name'),
							'middle_name2'       => $this->input->post('middle_name2'),
							'last_name'       => $this->input->post('last_name'),
							'gender'       => $this->input->post('gender'),
							/*'email'       => $email,*/
							'birth_date'       => make_db_date($this->input->post('birth_date')),
							'cell_phone'       => $this->input->post('cell_phone'),
							'language_known'       => $this->input->post('language_known'),
							'personal_email'       => $this->input->post('personal_email'),
							'updated_date' => date('Y-m-d H:i:s')
						);
				grid_data_updates($user_data,'users', 'user_id',$user_id);		
				$profile_data = array(
							'nationality'       => $this->input->post('nationality'),
							'marital_status'       => $this->input->post('marital_status'),
							'expected_arrival_date'       => make_db_date($this->input->post('expected_arrival_date')),
							'first_day_at_py'       => make_db_date($this->input->post('first_day_at_py')),
							'visa_type'       => $this->input->post('visa_type'),
							'third_party_ver_company'       => $this->input->post('third_party_ver_company'),
							'third_party_ver_date_requested'       => make_db_date($this->input->post('third_party_ver_date_requested')),
							'third_party_ver_date_completed'       => make_db_date($this->input->post('third_party_ver_date_completed')),							
							'skype_id'       => $this->input->post('skype_id'),
							'worked_at_ksu_before'       => $this->input->post('worked_at_ksu_before'),
							'worked_ksu_job_detail'       => $this->input->post('worked_ksu_job_detail'),
							'worked_ksu_start_date'       => make_db_date($this->input->post('worked_ksu_start_date')),
							'worked_ksu_end_date'       => make_db_date($this->input->post('worked_ksu_end_date'))
						);						
				grid_data_updates($profile_data,'user_profile', 'user_id',$user_id);
				
				grid_delete('user_cv_reference','user_id',$user_id);
				$cv_reference = $this->input->post('cv_reference');
				$cv_reference_count = count($cv_reference['company_name']);
				if($cv_reference_count > 1){
					for($i=0;$i < $cv_reference_count -1;$i++){
						$company_name = $cv_reference['company_name'][$i];
						$name = $cv_reference['name'][$i];
						$email = $cv_reference['email'][$i];
						
						$user_cv_reference = array(
							'user_id'       => $user_id,
							'company_name'       => $company_name,
							'name'       => $name,
							'email'       => $email
						);
						grid_add_data($user_cv_reference,'user_cv_reference');
					}
				}
				
				grid_delete('user_workhistory','user_id',$user_id);
				$experience = $this->input->post('experience');
				$experience_count = count($experience['company']);
				if($experience_count > 1){
					for($i=0;$i < $experience_count -1;$i++){
						$company = $experience['company'][$i];
						$position = $experience['position'][$i];
						$start_date = make_db_date($experience['start_date'][$i]);
						$end_date = make_db_date($experience['end_date'][$i]);
						$departure_reason = $experience['departure_reason'][$i];
						$created_at = date('Y-m-d H:i:s');
						
						$user_workhistory = array(
							'user_id'       => $user_id,
							'company'       => $company,
							'position'       => $position,
							'start_date'       => $start_date,
							'end_date'       => $end_date,
							'departure_reason'       => $departure_reason,
							'created_at'       => $created_at
						);
						grid_add_data($user_workhistory,'user_workhistory');
					}
				}
				
				grid_delete('user_qualification','user_id',$user_id);
				$certificates = $this->input->post('certificates');
				$certificates_count = count($certificates['certificate_id']);
				if($certificates_count > 1){
					for($i=0;$i < $certificates_count -1;$i++){
						$certificate_id = $certificates['certificate_id'][$i];
						$date = make_db_date($certificates['date'][$i]);
						$created_at = date('Y-m-d H:i:s');
						
						$user_certificate = array(
							'user_id'       => $user_id,
							'type'       => 'certificate',
							'qualification_id'       => $certificate_id,
							'date'       => $date,
							'created_at'       => $created_at
						);
						grid_add_data($user_certificate,'user_qualification');
					}
				}
				
				$qualifications = $this->input->post('qualifications');
				$qualifications_count = count($qualifications['qualification_id']);
				if($qualifications_count > 1){
					for($i=0;$i < $qualifications_count -1;$i++){
						$qualification_id = $qualifications['qualification_id'][$i];
						$subject_related = $qualifications['subject_related'][$i];
						$subject = $qualifications['subject'][$i];
						$date = make_db_date($qualifications['date'][$i]);
						$institute = $qualifications['institute'][$i];
						$graduation_year = $qualifications['graduation_year'][$i];
						$created_at = date('Y-m-d H:i:s');
						
						$user_qualification = array(
							'user_id'       => $user_id,
							'type'       => 'qualification',
							'qualification_id'       => $qualification_id,
							'date'       => $date,
							'subject_related'       => $subject_related,
							'subject'       => $subject,
							'institute'       => $institute,
							'graduation_year'       => $graduation_year,
							'created_at'       => $created_at
						);
						grid_add_data($user_qualification,'user_qualification');
					}
				}
				
				
				
				if(isset($_POST['delete_document']) && count($_POST['delete_document']) > 0){
					foreach($_POST['delete_document'] as $certificate_type=>$certificate_ids){
						if(count($certificate_ids) > 0){
							foreach($certificate_ids as $certificate_id){
								$this->list_user_model->delete_user_document($user_id,$certificate_type,$certificate_id);
							}
						}
					}
				}

				$arrCertificateType = getCertificateType();
				$curr_dir = str_replace("\\","/",getcwd()).'/';
				//upload and update the file
				$config['upload_path'] = $curr_dir.'uploads/'.$user_id.'/';
				
				$config['allowed_types'] = 'jpg|jpeg|pdf|png|xlsx|doc|zip|docx|csv';
				
				$config['overwrite'] = true;
				$config['remove_spaces'] = true;
				$config['max_size']	= '2048';// in KB
			
				//load upload library
				$this->load->library('upload', $config);
				
				$dir_exist = true; // flag for checking the directory exist or not
				if(!is_dir($curr_dir.'uploads/'.$user_id))
				{
					mkdir($curr_dir.'uploads/'.$user_id, 0777, true);
					$dir_exist = true; // dir not exist
				}
				$data = array();
				$errors = "";
				if($dir_exist)
				{
					foreach($_FILES as $field => $files)
					{
						if(count($files['name']) >0)
						{
							$file_names = array();
							foreach($files['name'] as $file_name) {
								$file_names[] = $field.'_'.$file_name;
							}
							$config['file_name'] = $file_names;
							$this->upload->initialize($config);
							if($this->upload->do_multi_upload($field))
							{
								$data[$field] = $this->upload->get_multi_upload_data();
							}else{
								$errors .= str_replace("_"," ",$field).': '.$this->upload->display_errors()."<br>";
							}
							
						}
					}
				}
				
				if(is_array($data) && count($data) > 0)
				{
					$table = 'profile_certificate';
							
					foreach($data AS $certificate_type=>$arrFiles)
					{
						if(isset($arrCertificateType[$certificate_type]) && count($arrFiles) > 0)
						{
							foreach($arrFiles as $file_data) {
								$certificate_file = 'uploads/'.$user_id.'/'.$file_data["file_name"];
								
								$data_document['user_id'] = $user_id;
								$data_document['certificate_type'] = $arrCertificateType[$certificate_type];
								$data_document['certificate_file'] = $certificate_file;
								
								//$this->list_user_model->delete_user_document($user_id,$certificate_type);
								grid_add_data($data_document,$table);
							}
						}
					}
				}
				
				if($this->input->post('status') == 3) {
					//$this->load->module('email_template');
					//$this->email_template->send_email('pankaj.bhakhar9@gmail.com',array());
					$email_html = 'Hi '.$first_name.','."\r\n\r\n";
					$email_html .= 'You are rejected in interview.'."\r\n\r\n";
					$email_html .= 'Thank you';
					$this->load->helper('send_email');
					$this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
					$this->email->from(Settings_model::$db_config['admin_email_address'], $_SERVER['HTTP_HOST']);
					$this->email->to($email);
					$this->email->subject('Interview notification - KSU');
					$this->email->message($email_html);
					$this->email->send();
				}	
			}else {
				$elsd_id = generateElsdId($this->input->post('gender'));
				$this->form_validation->set_rules('first_name', 'first name', 'trim|required|max_length[40]|min_length[2]');
				//$this->form_validation->set_rules('username', 'username', 'trim|required|max_length[255]|is_existing_unique_field[users.username]');
				
				if (!$this->form_validation->run()) {
					if (form_error('first_name')) {
						$this->session->set_flashdata('message', form_error('first_name'));
					}/*elseif (form_error('username')) {
						$this->session->set_flashdata('message', form_error('username'));
					}*/
					redirect('add_employee');
					//exit();
				}else {
					
					$user_data = array(
							'status'       => $this->input->post('status'),
							'first_name'       => $this->input->post('first_name'),
							'middle_name'       => $this->input->post('middle_name'),
							'middle_name2'       => $this->input->post('middle_name2'),
							'last_name'       => $this->input->post('last_name'),
							'gender'       => $this->input->post('gender'),
							'elsd_id'       => $elsd_id,
							'email'       => $email,
							'username'       => $username,
							'birth_date'       => make_db_date($this->input->post('birth_date')),
							'cell_phone'       => $this->input->post('cell_phone'),
							'language_known'       => $this->input->post('language_known'),
							'personal_email'       => $this->input->post('personal_email'),
							'created_by'       => $this->session->userdata('user_id'),
							'created_date' => date('Y-m-d H:i:s')
						);
					$user_id = grid_add_data($user_data,'users');
					
					$password = 'user_'.$user_id;
					$hash_password = hash_password($password, $nonce);
					$password_data = array('password' => $hash_password);
					
					grid_data_updates($password_data,'users', 'user_id',$user_id);
					
					$profile_data = array(
							'user_id'       => $user_id,
							'contractor'       => $this->session->userdata('contractor'),
							'nationality'       => $this->input->post('nationality'),
							'marital_status'       => $this->input->post('marital_status'),
							'expected_arrival_date'       => make_db_date($this->input->post('expected_arrival_date')),
							'first_day_at_py'       => make_db_date($this->input->post('first_day_at_py')),
							'visa_type'       => $this->input->post('visa_type'),
							'third_party_ver_company'       => $this->input->post('third_party_ver_company'),
							'third_party_ver_date_requested'       => make_db_date($this->input->post('third_party_ver_date_requested')),
							'third_party_ver_date_completed'       => make_db_date($this->input->post('third_party_ver_date_completed')),							
							'skype_id'       => $this->input->post('skype_id'),
							'worked_at_ksu_before'       => $this->input->post('worked_at_ksu_before'),
							'worked_ksu_job_detail'       => $this->input->post('worked_ksu_job_detail'),
							'worked_ksu_start_date'       => make_db_date($this->input->post('worked_ksu_start_date')),
							'worked_ksu_end_date'       => make_db_date($this->input->post('worked_ksu_end_date'))
						);
					$profile_id = grid_add_data($profile_data,'user_profile');
					
					$cv_reference = $this->input->post('cv_reference');
					$cv_reference_count = count($cv_reference['company_name']);
					if($cv_reference_count > 1){
						for($i=0;$i < $cv_reference_count -1;$i++){
							$company_name = $cv_reference['company_name'][$i];
							$name = $cv_reference['name'][$i];
							$email = $cv_reference['email'][$i];
							
							$user_cv_reference = array(
								'user_id'       => $user_id,
								'profile_id'       => $profile_id,
								'company_name'       => $company_name,
								'name'       => $name,
								'email'       => $email
							);
							grid_add_data($user_cv_reference,'user_cv_reference');
						}
					}
					
					$experience = $this->input->post('experience');
					$experience_count = count($experience['company']);
					if($experience_count > 1){
						for($i=0;$i < $experience_count -1;$i++){
							$company = $experience['company'][$i];
							$position = $experience['position'][$i];
							$start_date = make_db_date($experience['start_date'][$i]);
							$end_date = make_db_date($experience['end_date'][$i]);
							$departure_reason = $experience['departure_reason'][$i];
							$created_at = date('Y-m-d H:i:s');
							
							$user_workhistory = array(
								'user_id'       => $user_id,
								'company'       => $company,
								'position'       => $position,
								'start_date'       => $start_date,
								'end_date'       => $end_date,
								'departure_reason'       => $departure_reason,
								'created_at'       => $created_at
							);
							grid_add_data($user_workhistory,'user_workhistory');
						}
					}
					
					$certificates = $this->input->post('certificates');
					$certificates_count = count($certificates['certificate_id']);
					if($certificates_count > 1){
						for($i=0;$i < $certificates_count -1;$i++){
							$certificate_id = $certificates['certificate_id'][$i];
							$date = make_db_date($certificates['date'][$i]);
							$created_at = date('Y-m-d H:i:s');
							
							$user_certificate = array(
								'user_id'       => $user_id,
								'type'       => 'certificate',
								'qualification_id'       => $certificate_id,
								'date'       => $date,
								'created_at'       => $created_at
							);
							grid_add_data($user_certificate,'user_qualification');
						}
					}
					
					$qualifications = $this->input->post('qualifications');
					$qualifications_count = count($qualifications['qualification_id']);
					if($qualifications_count > 1){
						for($i=0;$i < $qualifications_count -1;$i++){
							$qualification_id = $qualifications['qualification_id'][$i];
							$subject_related = $qualifications['subject_related'][$i];
							$subject = $qualifications['subject'][$i];
							$date = make_db_date($qualifications['date'][$i]);
							$institute = $qualifications['institute'][$i];
							$graduation_year = $qualifications['graduation_year'][$i];
							$created_at = date('Y-m-d H:i:s');
							
							$user_qualification = array(
								'user_id'       => $user_id,
								'type'       => 'qualification',
								'qualification_id'       => $qualification_id,
								'date'       => $date,
								'subject_related'       => $subject_related,
								'subject'       => $subject,
								'institute'       => $institute,
								'graduation_year'       => $graduation_year,
								'created_at'       => $created_at
							);
							grid_add_data($user_qualification,'user_qualification');
						}
					}
					
					$arrCertificateType = getCertificateType();
					$curr_dir = str_replace("\\","/",getcwd()).'/';
					//upload and update the file
					$config['upload_path'] = $curr_dir.'uploads/'.$user_id.'/';
					
					$config['allowed_types'] = 'jpg|jpeg|pdf|png|xlsx|doc|zip|docx|csv';
					
					$config['overwrite'] = true;
					$config['remove_spaces'] = true;
					$config['max_size']	= '2048';// in KB
				
					//load upload library
					$this->load->library('upload', $config);
					
					$dir_exist = true; // flag for checking the directory exist or not
					if(!is_dir($curr_dir.'uploads/'.$user_id))
					{
						mkdir($curr_dir.'uploads/'.$user_id, 0777, true);
						$dir_exist = true; // dir not exist
					}
					$data = array();
					$errors = "";
					if($dir_exist)
					{
						foreach($_FILES as $field => $files)
						{
							if(count($files['name']) >0)
							{
								$file_names = array();
								foreach($files['name'] as $file_name) {
									$file_names[] = $field.'_'.$file_name;
								}
								$config['file_name'] = $file_names;
								$this->upload->initialize($config);
								if($this->upload->do_multi_upload($field))
								{
									$data[$field] = $this->upload->get_multi_upload_data();
								}else{
									$errors .= str_replace("_"," ",$field).': '.$this->upload->display_errors()."<br>";
								}
								
							}
						}
					}
					
					if(is_array($data) && count($data) > 0)
					{
						$table = 'profile_certificate';
								
						foreach($data AS $certificate_type=>$arrFiles)
						{
							if(isset($arrCertificateType[$certificate_type]) && count($arrFiles) > 0)
							{
								foreach($arrFiles as $file_data) {
									$certificate_file = 'uploads/'.$user_id.'/'.$file_data["file_name"];
									
									$data_document['user_id'] = $user_id;
									$data_document['certificate_type'] = $arrCertificateType[$certificate_type];
									$data_document['certificate_file'] = $certificate_file;
									
									$this->list_user_model->delete_user_document($user_id,$certificate_type);
									grid_add_data($data_document,$table);
								}
							}
						}
					}
								
					//$this->session->set_flashdata('message', $errors);
					//redirect('add_employee');
					redirect('/company_employee');
				}						
			}
			
			redirect('/company_employee');
		}
		
		$user_data = $user_documents = array();
		if($user_id > 0){
			$user_data = $this->add_employee_model->get_employee_data($user_id);

			$cv_reference_data = $this->list_user_model->get_cv_reference($user_id);
			$cv_reference = array();
			if($cv_reference_data){
				$i = 1;
				foreach($cv_reference_data->result_array() as $_cv_reference_data){
					$row = array();
					$row['referance_id'] = $_cv_reference_data['referance_id'];
					$row['company_name'] = $_cv_reference_data['company_name'];
					$row['name'] = $_cv_reference_data['name'];
					$row['position'] = $_cv_reference_data['position'];
					$row['contact_number'] = $_cv_reference_data['contact_number'];
					$row['email'] = $_cv_reference_data['email'];
					$row['cv_confirm'] = $_cv_reference_data['cv_confirm'];
					
					$cv_reference[] = $row;
				}
			}
			
			$user_experience_data = $this->list_user_model->get_user_experience($user_id);
			$user_experience = array();
			$user_experience_count = 0;
			if($user_experience_data){
				foreach($user_experience_data->result_array() as $_user_experience_data){
					$start_date = date(strtotime($_user_experience_data['start_date']));
					$end_date = date(strtotime($_user_experience_data['end_date']));
					$interval = $end_date-$start_date;
					$months = floor($interval / 86400 / 30 );
					$years = 0;
					if($months > 0){
						$years = $months/12;
					}
					$user_experience_count = $user_experience_count + $years;
					$row = array();
					$row['user_workhistory_id'] = $_user_experience_data['user_workhistory_id'];
					$row['company'] = $_user_experience_data['company'];
					$row['position'] = $_user_experience_data['position'];
					$row['start_date'] = date("d M Y",strtotime($_user_experience_data['start_date']));
					$row['end_date'] = date("d M Y",strtotime($_user_experience_data['end_date']));
					$row['departure_reason'] = $_user_experience_data['departure_reason'];
					
					$user_experience[] = $row;
				}
			}
			
			$user_certificate_data = $this->list_user_model->get_user_quali_certi($user_id,'certificate');
			$user_certificate = array();
			if($user_certificate_data){
				foreach($user_certificate_data->result_array() as $_user_certificate_data){
					$row = array();
					$row['user_qualification_id'] = $_user_certificate_data['user_qualification_id'];
					$row['qualification_id'] = $_user_certificate_data['qualification_id'];
					$row['date'] = date("d M Y",strtotime($_user_certificate_data['date']));
					
					$user_certificate[] = $row;
				}
			}
			
			$user_qualification_data = $this->list_user_model->get_user_quali_certi($user_id,'qualification');
			$user_qualification = array();
			if($user_qualification_data){
				foreach($user_qualification_data->result_array() as $_user_qualification_data){
					$row = array();
					$row['user_qualification_id'] = $_user_qualification_data['user_qualification_id'];
					$row['subject'] = $_user_qualification_data['subject'];
					$row['qualification_id'] = $_user_qualification_data['qualification_id'];
					$row['date'] = date("d M Y",strtotime($_user_qualification_data['date']));
					$row['accredited'] = $_user_qualification_data['accredited'];
					$row['in_class'] = $_user_qualification_data['in_class'];
					$row['subject_related'] = $_user_qualification_data['subject_related'];
					$row['institute'] = $_user_qualification_data['institute'];
					$row['graduation_year'] = $_user_qualification_data['graduation_year'];
					$user_qualification[] = $row;
				}
			}
			
			$user_data = (object) array_merge((array)$user_data,array('user_qualification'=>$user_qualification,'cv_reference'=>$cv_reference),array('user_certificate'=>$user_certificate),array('user_experience'=>$user_experience));
			$user_documents = $this->list_user_model->get_user_documents($user_id);
		}	
    	$content_data = array();
		$content_data['user_id'] = $user_id;
		$content_data['user_data'] = $user_data;
		$content_data['user_documents'] = $user_documents;
		$content_data['user_profile_status'] = user_profile_status();
		$content_data['other_user_roll'] = get_other_user_roll();
		$content_data['other_user_list'] = get_other_user_list();
		$content_data['nationality_list'] = get_nationality_list();
		$content_data['campus_list'] = get_campus_list(1);
		$content_data['certificate_list'] = get_certificate_list();
		$content_data['qualifications_list'] = get_qualifications_list();
		
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Add User');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('add_employee', $content_data);
    }
		
}

/* End of file list_user.php */