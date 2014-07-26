<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- BEGIN PASSWORD BOX -->
<div id="loginpg" class="login fade-in" data-page="login">
<div class="container" id="login-block">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4">
            <div class="login-box clearfix animated flipInY">
                <div class="page-icon animated bounceInDown">
                    <img src="../images/account/login-questionmark-icon.png" alt="Questionmark icon" />
                </div>
                <div class="login-logo">
                    <a href="#">
                        <img src="../images/account/login_logo.png" alt="Company Logo">
                    </a>
                </div>
                <hr />
                <div class="login-form">
                    <div class="membership_box">
                    <?php
                    $this->load->view('generic/flash_error');
                    ?>
                    </div>
                    <!-- BEGIN ERROR BOX -->
                    <div class="alert alert-danger hide">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4>Error!</h4>
                        Your Error Message goes here
                    </div>
                    <!-- END ERROR BOX -->
                   
                    <?php print form_open('auth/forgot_password/send_password', array('id' => 'password_form', 'class' => 'membership_form')) ."\r\n"; ?>	
                        <p>Enter your email address below and we'll send a special reset password link to your inbox.</p>
                        <?php print form_input(array('name' => 'email', 'id' => 'email', 'class' => 'input-field', 'placeholder' => $this->lang->line('your_email'))); ?>
                        <?php print form_submit(array('name' => 'forgot_password_submit', 'value' => $this->lang->line('send_password'), 'id' => 'forgot_password_submit', 'class' => 'btn btn-login btn-reset')); ?>
                    <?php print form_close() ."\r\n"; ?>
                    <div class="login-links">
                        <a href="<?php print base_url(); ?>">Already have an account?  <strong><?php print $this->lang->line('login'); ?></strong></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- END PASSWORD BOX -->

<?php /*?><div class="centerbox_top">
</div>
<?php print form_open('forgot_password/send_password', array('id' => 'password_form', 'class' => 'membership_form')) ."\r\n";
?>
<div class="membership_box">
<?php
$this->load->view('generic/flash_error');
?>
</div><h2><?php print $this->lang->line('forgot_password'); ?></h2>
<div class="form_label"><?php print form_label($this->lang->line('your_email') .':', 'email'); ?></div>
<div class="input_box"><?php print form_input(array('name' => 'email', 'id' => 'email', 'class' => 'membership_text_field')); ?></div>
<div><?php print $this->recaptcha->get_html(); ?></div>
<div><?php print form_submit(array('name' => 'forgot_password_submit', 'value' => $this->lang->line('send_password'), 'id' => 'forgot_password_submit', 'class' => 'membership_submit')); ?></div>
<?php print form_close() ."\r\n";
?>
<div class="centerbox_bot">
</div>
<ul class="membership_link">
    <li><a href="<?php print base_url(); ?>"><?php print $this->lang->line('login'); ?></a></li>
    <li><a href="<?php print base_url(); ?>forgot_username"><?php print $this->lang->line('forgot_username'); ?></a></li>
</ul>
<?php */?>