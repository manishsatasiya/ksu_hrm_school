<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN MANDATORY STYLE -->
<link href="../css/themes/school/custom.css" rel="stylesheet">
<!-- END  MANDATORY STYLE -->
    
<div id="loginpg" class="login fade-in" data-page="login">    
<div class="container" id="login-block">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4">
                <div class="login-box clearfix animated flipInY">
                    <div class="page-icon animated bounceInDown">
                        <img src="../images/account/user-icon.png" alt="Key icon">
                    </div>
                    <div class="login-logo">
                        <a href="#?login-theme-3">
                            <img src="../images/account/login_logo.png" alt="Company Logo">
                        </a>
                    </div>
                    <hr>
                    <div class="login-form" style="font-family:Arial, Helvetica, sans-serif;">
                        <!-- BEGIN ERROR BOX -->
                        <div class="alert alert-danger hide">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h4>Error!</h4>
                            Your Error Message goes here
                        </div>
                        <!-- END ERROR BOX -->
                        <div class="form-sec">
					<?php print form_open('auth/login/validate', 'id="login_form" class="membership_form"') ."\r\n"; ?>
            <div class="membership_box">
              <?php
                if (Settings_model::$db_config['login_enabled'] == 0)
                { ?>
            <div id="error" class="error_box"><?php print $this->lang->line('login_disabled') ?></div>
            <?php
                }else{
                    $this->load->view('generic/flash_error');
                }
                ?>
            </div>
            <div class="row">
              <?php print form_input(array('name' => 'username', 'placeholder'=> 'Username', 'id' => 'login_username', 'class' => 'input-field form-control user')); ?>
        	</div>
			<div class="row">
		  <?php print form_password(array('name' => 'password', 'placeholder'=> 'Password', 'id' => 'login_password', 'class' => 'input-field form-control password')); ?>
			</div>	
	<div class="row">
	  <?php print form_submit(array('name' => 'submit', 'value' => $this->lang->line('login'), 'id' => 'login_submit', 'class' => 'btn btn-login')); ?>
	</div>
	<div class="row">
	  <div class="col-md-10">
		<?php
			if ($this->session->userdata('login_attempts') > 5) {
				print $this->recaptcha->get_html();
			}
			?>
	  </div>
	</div>
	<?php print form_close() ."\r\n"; ?>
    	<div class="login-links">
            <a href="<?php print base_url(); ?>auth/forgot_password">Forgot password?</a>
        </div>
	
</div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
<script src="../js/custom.js"></script>
</div>	
    
<?php /*?><?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-md-5 col-md-offset-1"></div>
<div class="row login-container column-seperation">  
	<div class="col-md-5 "> <br>
	<?php print form_open('auth/login/validate', 'id="login_form" class="membership_form"') ."\r\n"; ?>
	<div class="membership_box">
	  <?php
		if (Settings_model::$db_config['login_enabled'] == 0)
		{ ?>
	<div id="error" class="error_box"><?php print $this->lang->line('login_disabled') ?></div>
	<?php
		}else{
			$this->load->view('generic/flash_error');
		}
		?>
	</div>
	<div class="row">
	  <div class="form-group col-md-10"> <?php print form_label(ucfirst($this->lang->line('username')) .':', 'login_username'); ?>
		<div class="controls">
		  <div class="input-with-icon  right"> <i class=""></i> <?php print form_input(array('name' => 'username', 'id' => 'login_username', 'class' => 'form-control')); ?> </div>
		</div>
	  </div>
	</div>
	<div class="row">
	  <div class="form-group col-md-10"> <?php print form_label(ucfirst($this->lang->line('password')), 'login_password'); ?> <span class="help"></span>
		<div class="controls">
		  <div class="input-with-icon  right"> <i class=""></i> <?php print form_password(array('name' => 'password', 'id' => 'login_password', 'class' => 'form-control')); ?> </div>
		</div>
	  </div>
	</div>	
	<div class="row">
	  <div class="col-md-10"> <?php print form_submit(array('name' => 'submit', 'value' => $this->lang->line('login'), 'id' => 'login_submit', 'class' => 'btn btn-primary btn-cons pull-right')); ?> </div>
	</div>
	<div class="row">
	  <div class="col-md-10">
		<?php
			if ($this->session->userdata('login_attempts') > 5) {
				print $this->recaptcha->get_html();
			}
			?>
	  </div>
	</div>
	<?php print form_close() ."\r\n"; ?>
	<ul class="membership_link">
	  <li style="line-height:19px;">If you have forgotten your username or password, <br/>
		please go to Room <em style="color:red; font-size:20px">2322</em> and see <u>Yusuf</u> or <u>Hamed</u> for Assistance</li>
	  <li style="line-height:19px;">For the Female Campus (Olaysha) please <br/>
		email <u>Areej Warsi</u> on <em style="color:red; font-size:17px">els.mico1@py.ksu.edu.sa</em></li>
	</ul>
</div>
</div><?php */?>