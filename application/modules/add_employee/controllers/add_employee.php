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
				$user_data = array(
							'status'       => $this->input->post('status'),
							'first_name'       => $this->input->post('first_name'),
							'middle_name'       => $this->input->post('middle_name'),
							'last_name'       => $this->input->post('last_name'),
							'gender'       => $this->input->post('gender'),
							'email'       => $email,
							'birth_date'       => date('Y-m-d',strtotime($this->input->post('birth_date'))),
							'cell_phone'       => $this->input->post('cell_phone'),
							'language_known'       => $this->input->post('language_known'),
							'updated_date' => date('Y-m-d H:i:s')
						);
				grid_data_updates($user_data,'users', 'user_id',$user_id);		
				$profile_data = array(
							'nationality'       => $this->input->post('nationality'),
							'marital_status'       => $this->input->post('marital_status'),
							'expected_arrival_date'       => date('Y-m-d',strtotime($this->input->post('expected_arrival_date')))
						);						
				grid_data_updates($profile_data,'user_profile', 'user_id',$user_id);
				
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
							'last_name'       => $this->input->post('last_name'),
							'gender'       => $this->input->post('gender'),
							'elsd_id'       => $elsd_id,
							'email'       => $email,
							'username'       => $username,
							'birth_date'       => date('Y-m-d',strtotime($this->input->post('birth_date'))),
							'cell_phone'       => $this->input->post('cell_phone'),
							'language_known'       => $this->input->post('language_known'),
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
							'expected_arrival_date'       => date('Y-m-d',strtotime($this->input->post('expected_arrival_date')))
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
							$start_date = date('Y-m-d',strtotime($experience['start_date'][$i]));
							$end_date = date('Y-m-d',strtotime($experience['end_date'][$i]));
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
							$date = date('Y-m-d',strtotime($certificates['date'][$i]));
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
							$date = date('Y-m-d',strtotime($qualifications['date'][$i]));
							$created_at = date('Y-m-d H:i:s');
							
							$user_qualification = array(
								'user_id'       => $user_id,
								'type'       => 'qualification',
								'qualification_id'       => $qualification_id,
								'date'       => $date,
								'subject_related'       => $subject_related,
								'subject'       => $subject,
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
		
		$user_data = array();
		if($user_id > 0){
			$user_data = $this->add_employee_model->get_employee_data($user_id);
		}	
    	$content_data = array();
		$content_data['user_id'] = $user_id;
		$content_data['user_data'] = $user_data;
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