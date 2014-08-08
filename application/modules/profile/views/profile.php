<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="page-title">
  <h3><?php print $this->lang->line('profile_page_heading'); ?></h3>
  <div class="pull-right"> <img src="<?php echo $profile_picture; ?>" width="63" height="63" id="previewimg"/><br />
    <a href="javascript:void(0)" class="button" onclick="changepic();"><?php print $this->lang->line('pro_p_change_pic'); ?></a> </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-body no-border">
        <?php
        if ($this->session->flashdata('message')) {
            print "<br><div class=\"alert alert-error\">". $this->session->flashdata('message') ."</div>";
        }
        ?>
    <?php
	print form_open('profile/upload_profile_pic', array('id' => 'uploadpic_form')) ."\r\n";
		print form_upload(array('name' => 'uploadpic', 'id' => 'uploadpic', 'value' => '', 'onchange'=>'previewUploadImg(this)', 'style'=>'display:none;'));
	print form_close(); ?>
    <?php
	print form_open('profile/update_account', array('id' => 'profile_form','class' => 'form-no-horizontal-spacing')) ."\r\n";
	if($this->session->userdata('role_id') == 3){
	?>
        <table border="0" cellpadding="5" cellspacing="0" width="100%" style="" id="profile_form">
          <tr>
            <td><h2><?php print $this->lang->line('profile_course_heading'); ?></h2></td>
          </tr>
          <tr>
            <td><table border="1" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                  <th><?php print $this->lang->line('pro_p_coursename'); ?></th>
                  <th><?php print $this->lang->line('pro_p_section'); ?></th>
                  <th><?php print $this->lang->line('pro_p_classroom'); ?></th>
                  <th><?php print $this->lang->line('pro_p_shift'); ?></th>
                  <th><?php print $this->lang->line('pro_p_reg_student'); ?></th>
                </tr>
                <?php
					if($course_detail){
					foreach ($course_detail as $row){ 
					?>
                <tr>
                  <td align="center"><?php echo $row['course_name']?></td>
                  <td align="center"><?php echo $row['section']?></td>
                  <td align="center"><?php echo $row['class_room']?></td>
                  <td align="center"><?php echo $row['shift']?></td>
                  <td align="center"><?php echo $row['no_of_student']?></td>
                </tr>
                <?php
					}
					}else{ 
					?>
                <tr>
                  <td colspan="5" align="center"><?php print $this->lang->line('no_data_found'); ?></td>
                </tr>
                <?php
					} 
					?>
              </table></td>
          </tr>
        </table>
        <?php
	} 
	?>
        <div class="row column-seperation">
          <div class="col-md-6">
            <h4></h4>
            <div class="row form-row">
              <div class="col-md-12"> <?php print form_label($this->lang->line('pro_p_firstname'), 'profile_first_name'); ?> <?php print form_input(array('name' => 'first_name', 'id' => 'profile_first_name', 'value' => $first_name, 'class' => 'form-control','style'=>'')); ?> 
			  </div>
            </div>
            <div class="row form-row">
              <div class="col-md-12"> <?php print form_label($this->lang->line('pro_p_address1'), 'profile_first_name'); ?> <?php print form_input(array('name' => 'address1', 'id' => '', 'value' => $address1, 'class' => 'form-control','style'=>'')); ?> </div>
            </div>
            <div class="row form-row">
              <div class="col-md-6"> <?php print form_label($this->lang->line('pro_p_city'), 'profile_first_name'); ?> <?php print form_input(array('name' => 'city', 'id' => '', 'value' => $city, 'class' => 'form-control','style'=>'')); ?> </div>
              <div class="col-md-6"> <?php print form_label($this->lang->line('pro_p_state'), 'profile_first_name'); ?> <?php print form_input(array('name' => 'state', 'id' => '', 'value' => $state, 'class' => 'form-control','style'=>'')); ?> </div>
            </div>
            <div class="row form-row">
              <div class="col-md-8"> <?php print form_label($this->lang->line('pro_p_zip'), 'profile_first_name'); ?> <?php print form_input(array('name' => 'zip', 'id' => '', 'value' => $zip, 'class' => 'form-control','style'=>'')); ?> </div>
              <div class="col-md-4"> </div>
            </div>
          </div>
          <div class="col-md-6">
            <h4></h4>
            <div class="row form-row">					
              <div class="col-md-6"> <?php print form_label($this->lang->line('pro_p_birthdate'), 'birth_date'); ?> 
			  	<div class="input-append success date col-md-10 col-lg-6 no-padding">
                   <?php print form_input(array('name' => 'birth_date', 'id' => 'show_dp', 'value' => make_dp_date($birth_date), 'class' => 'form-control','style'=>'')); ?> 
                    <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> </div>
				</div>
				
              <div class="col-md-6"> <?php print form_label($this->lang->line('pro_p_workphone'), 'profile_first_name'); ?> <?php print form_input(array('name' => 'work_phone', 'id' => '', 'value' => $work_phone, 'class' => 'form-control','style'=>'')); ?> </div>
            </div>
            <div class="row form-row">
              <div class="col-md-12"> <?php print form_label($this->lang->line('pro_p_languange'), 'profile_first_name'); ?> <?php print form_input(array('name' => 'language_known', 'id' => '', 'value' => $language_known, 'class' => 'form-control','style'=>'')); ?> </div>
            </div>
            <div class="row form-row">
              <div class="col-md-6"> <?php print form_label($this->lang->line('change_email'), 'profile_email'); ?> <?php print form_input(array('name' => 'email', 'id' => 'profile_email', 'class' => 'form-control','style'=>'')); ?> <br/>
                <?php print $this->lang->line('current_email') .": <strong>". $email; ?>
                <div id="email_valid"></div>
                <div id="email_taken"></div>
              </div>
              <div class="col-md-6"> <?php print $this->lang->line('password_required_for_changes'); ?> <?php print form_password(array('name' => 'password', 'id' => 'profile_password', 'class' => 'form-control','style'=>'')); ?> </div>
            </div>
          </div>
        </div>
        <div class="form-actions">
          <div class="pull-right"> <?php print form_submit(array('name' => 'submit', 'value' => $this->lang->line('update_profile'), 'id' => 'profile_submit', 'class' => 'btn btn-danger btn-cons', 'style'=>'')); ?> </div>
        </div>
        <?php print form_close(); ?> </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php print  $this->lang->line('edit_password'); ?></h4>
      </div>
      <div class="grid-body no-border"> 
	  	<?php print form_open('profile/update_password', array('id' => 'profile_pwd_form', 'class' => 'membership_form')) ."\r\n"; ?>
		<?php
		if ($this->session->flashdata('pwd_message')) {
			print "<div class=\"alert alert-error\">". $this->session->flashdata('pwd_message') ."</div>";
		}
		?>
        <div class="form-group"> <?php print form_label($this->lang->line('current_password'), 'form-label'); ?>
          <div class="input-with-icon  right"> <i class=""></i> <?php print form_password(array('name' => 'current_password', 'id' => 'current_password', 'class' => 'form-control')); ?> </div>
        </div>
        <div class="form-group"> <?php print form_label($this->lang->line('new_password'), 'profile_new_password'); ?>
          <div class="input-with-icon  right"> <i class=""></i> <?php print form_password(array('name' => 'new_password', 'id' => 'profile_new_password', 'class' => 'form-control')); ?> </div>
        </div>
        <div class="form-group"> <?php print form_label($this->lang->line('new_password_again'), 'new_password_again'); ?>
          <div class="input-with-icon  right"> <i class=""></i> <?php print form_password(array('name' => 'new_password_again', 'id' => 'new_password_again', 'class' => 'form-control')); ?> </div>
        </div>
        <div class="form-actions"> <?php print form_hidden('email', $email); ?>
          <div class="pull-right"> <?php print form_submit(array('name' => 'submit', 'value' => $this->lang->line('update_password'), 'id' => 'profile_pwd_submit', 'class' => 'btn btn-success btn-cons')); ?> </div>
        </div>
        <?php print form_close() ."\r\n"; ?> 
	  </div>
    </div>
  </div>
</div>
