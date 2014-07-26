<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->model('profile_model');
    }

    public function index() {
        // set content data
        $this->load->model('profile_model');
        $content_data = $this->profile_model->get_profile();
        $content_data['course_detail'] = $this->profile_model->get_teacher_class_info();
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        $this->template->title('profile');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('profile', $content_data);
    }

    /**
     *
     * update_account: update member info
     *
     */

    public function update_account() {
        // form input validation
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('first_name', 'full name', 'trim|required|max_length[40]|min_length[2]');
        $this->form_validation->set_rules('email', 'email', 'trim|max_length[255]|is_valid_email|is_existing_unique_field[users.email]');
        $this->form_validation->set_rules('password', 'password', 'trim|required|is_member_password');
        
        if (!$this->form_validation->run())
        {
            if (form_error('first_name')) {
                $this->session->set_flashdata('message', form_error('first_name'));
            }elseif (form_error('email')) {
                $this->session->set_flashdata('message', form_error('email'));
            }elseif (form_error('password')) {
                $this->session->set_flashdata('message', form_error('password'));
            }
			// redirect to profile on fail with error
            redirect('profile');
            exit();
        }
        
        
        $this->profile_model->set_profile($this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('email'),$this->input->post('address1'),$this->input->post('city'),$this->input->post('state'),$this->input->post('zip'),$this->input->post('birth_date'),$this->input->post('language_known'),$this->input->post('work_phone'));
        $this->session->set_flashdata('message', $this->lang->line('account_updated'));
        redirect('profile');
        exit();
    }

    /**
     *
     * update_password: change member password
     *
     *
     */

    public function update_password() {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('current_password', 'current password', 'trim|required|max_length[64]|is_member_password');
        $this->form_validation->set_rules('new_password', 'new password', 'trim|required|max_length[64]|min_length[4]|matches[new_password_again]');
        $this->form_validation->set_rules('new_password_again', 'new password again', 'trim|required|max_length[64]|min_length[4]');

        if (!$this->form_validation->run())
        {
            if (form_error('current_password')) {
                $this->session->set_flashdata('pwd_message', form_error('current_password'));
            }elseif (form_error('new_password')) {
                $this->session->set_flashdata('pwd_message', form_error('new_password'));
            }elseif (form_error('new_password_again')) {
                $this->session->set_flashdata('pwd_message', form_error('new_password_again'));
            }
            
            redirect('profile');
            exit();
        }

        if ($this->profile_model->set_password($this->input->post('new_password'))) {
            $this->session->set_flashdata('pwd_message', $this->lang->line('profile_success'));
        }
        redirect('profile');
    }

    public function upload_profile_pic(){
    	
    	if($_FILES['uploadpic']['error'] == 0){
    		$curr_dir = str_replace("\\","/",getcwd()).'/';
    		//upload and update the file
    		$config['upload_path'] = $curr_dir.'images/profile_picture/original/';
    		$config['allowed_types'] = 'gif|jpg|png';
    		$config['overwrite'] = false;
    		$config['remove_spaces'] = true;
    		//$config['max_size']	= '100';// in KB
    	
    		//load upload library
    		$this->load->library('upload', $config);
    		$this->load->library('image_lib');
    		if ( ! $this->upload->do_upload('uploadpic')){
    			$this->session->set_flashdata('message', $this->upload->display_errors('<p class="error">', '</p>'));
    		}
    		else
    		{
    			$data1 = array('upload_data' => $this->upload->data());
    			$image= $data1['upload_data']['file_name'];
    			
    			$configBig = array();
				
				$configBig['image_library'] = 'gd2';
				$configBig['source_image']    = $curr_dir.'images/profile_picture/original/'.$image;;
				$configBig['new_image'] = $curr_dir.'images/profile_picture/thumb7575/'.$image;;
				$configBig['maintain_ratio'] = TRUE;
				$configBig['width']     = 75;
				$configBig['height']    = 75;

				$this->image_lib->initialize($configBig); 

				if ( ! $this->image_lib->resize())
				{
					echo $this->image_lib->display_errors();
				}
				$this->image_lib->clear();
				unset($configBig);
				
				$configBig = array();
				
				$configBig['image_library'] = 'gd2';
				$configBig['source_image']    = $curr_dir.'images/profile_picture/original/'.$image;;
				$configBig['new_image'] = $curr_dir.'images/profile_picture/thumb150150/'.$image;;
				$configBig['maintain_ratio'] = TRUE;
				$configBig['width']     = 150;
				$configBig['height']    = 150;

				$this->image_lib->initialize($configBig); 

				if ( ! $this->image_lib->resize())
				{
					echo $this->image_lib->display_errors();
				}
				$this->image_lib->clear();
				unset($configBig);
				
				$user_id = $this->session->userdata('user_id');
				$data = array('profile_picture' => $image);
				$this->db->where('user_id', $user_id);
				$this->db->update('users', $data);
				 
				if($this->db->affected_rows() == 1) {
					echo "1";
				}
    			
    		}
    	}
    	 
    }
}

/* End of file profile.php */
