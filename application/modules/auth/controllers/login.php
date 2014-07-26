<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('recaptcha');
        $this->lang->load('recaptcha');
        $this->load->helper('general_function');
    }

    function index() {
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        $this->template->title('login');
        $this->template->set_partial('header', 'membership/header');
        $this->template->set_partial('footer', 'membership/footer');
		$this->template->set_partial('sidebar', 'membership/sidebar');
        $this->template->build('login');
    }

    /**
     *
     * validate: validate login after input fields have met requirements
     *
     */
    public function validate() {
        if (Settings_model::$db_config['disable_all'] == 1 && $this->input->post('username') != ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('site_disabled'));
            redirect('/auth/login');
            exit();
        }
        // form input validation
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('username', 'username', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('password', 'password', 'trim|required|max_length[64]');
        if ($this->session->userdata('login_attempts') > 5) {
            $this->form_validation->set_rules('recaptcha_response_field', 'captcha response field', 'required|check_captcha');
        }

        if (!$this->form_validation->run())
        {
            if (form_error('username')) {
                $this->session->set_flashdata('message', form_error('username'));
            }elseif (form_error('password')) {
                $this->session->set_flashdata('message', form_error('password'));
            }elseif (form_error('recaptcha_response_field')) {
                $this->session->set_flashdata('message', $this->lang->line('check_captcha'));
            }
            redirect('/auth/login');
            exit();
        }

        // database validation
        $this->load->model('login_model');
        $data = $this->login_model->validate_login($this->input->post('username'), $this->input->post('password'));
        
        if($data == "banned") {
            $this->session->set_flashdata('message', $this->lang->line('access_denied'));
            redirect('/auth/login');
        }elseif (is_array($data)) {
            if($data['active'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('activate_account'));
                redirect('/auth/login');
            }else{
                $this->load->helper('cookie');
                if ($this->input->post('remember_me') && !get_cookie('unique_token')) {
                    setcookie("unique_token", $data['nonce'], time() + Settings_model::$db_config['cookie_expires'], '/', $_SERVER['SERVER_NAME'], false, false);
                }
                set_activity_log($data['user_id'],'login','login','Do login',$data['user_id']);
                // set session data
                $this->session->set_userdata('logged_in', true);
                $this->session->set_userdata('user_id', $data['user_id']);
                $this->session->set_userdata('username', $data['username']);
                $this->session->set_userdata('first_name', $data['first_name']);
                $this->session->set_userdata('role_id', $data['user_roll_id']);
                $this->session->set_userdata('campus', $data['campus']);
                $this->session->set_userdata('campus_id', $data['campus_id']);
				$this->session->set_userdata('user_privilege_per_page', '');
				$this->session->set_userdata('contractor', $data['contractor']);
				$calead = $this->login_model->getcaleadteacehr($data['user_id']);
				
                $this->session->set_userdata('ca_lead_teacher', $calead);
                // reset login attempts to 0
                $this->login_model->reset_login_attempts($data['username']);
                $this->session->unset_userdata('login_attempts');
                redirect(Settings_model::$db_config['home_page']);
            }
        }else{
            $this->session->set_flashdata('message', $this->lang->line('login_incorrect'));
            $this->session->set_userdata('login_attempts', $data);
            redirect('/auth/login');
        }
    }


}

/* End of file login.php */