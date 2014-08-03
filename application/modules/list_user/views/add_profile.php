<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
              
<div class="row">
  <div class="col-md-12">
    <div class="grid simple transparent">
      <div class="grid-title">
        <h4>Add New <span class="semi-bold">Employee</span></h4>
      </div>
      <div class="grid-body ">
        <div class="row">
        	
          <?php print form_open('list_user/add_profile/', array('id' => 'add_profile','name'=>'add_profile')) ."\r\n"; ?>
            <div id="rootwizard" class="col-md-12">
            	<?php
				if ($this->session->flashdata('message')) {
					print "<br><div class=\"alert alert-error\">". $this->session->flashdata('message') ."</div>";
				}
				?>
              <div class="form-wizard-steps">
                <ul class="wizard-steps">
                  <li class="active" data-target="#step1"> <a href="#tab1" data-toggle="tab"> <span class="step">1</span> <span class="title">Personal</span> </a> </li>
                  <li data-target="#step2" class=""> <a href="#tab2" data-toggle="tab"> <span class="step">2</span> <span class="title">Contact & Job details</span> </a> </li>
                  <li data-target="#step3" class=""> <a href="#tab3" data-toggle="tab"> <span class="step">3</span> <span class="title">Work</span> </a> </li>
                  <li data-target="#step4" class=""> <a href="#tab4" data-toggle="tab"> <span class="step">4</span> <span class="title">Medical</span> </a> </li>
                  <li data-target="#step5" class=""> <a href="#tab5" data-toggle="tab"> <span class="step">5</span> <span class="title">Reference</span> </a> </li>
                  <li data-target="#step6" class=""> <a href="#tab6" data-toggle="tab"> <span class="step">6</span> <span class="title">Verification <br>
                    </span> </a> </li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="tab-content transparent">
                <div class="tab-pane active" id="tab1"> <br>
                  <h4 class="semi-bold">Step 1 - <span class="light">Personal Information</span></h4>
                  <br>
                  <!--row 1 start-->
                  <div class="row form-row">
                    <div class="col-md-4">
                      <?php print form_label('Status', 'status',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('status',$user_profile_status,'','id="status" class="select2 form-control"'); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Gender', 'gender',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('gender',array(''=> 'Select Gender','M'=> 'Male','F'=>'Female'),'','id="gender" class="select2 form-control"'); ?>
                    </div>
                    <div class="col-md-4">
                      <?php //print form_label('ELSD ID', 'elsd_id',array('class'=>'form-label')); ?>
                      <?php //print form_input(array('name' => 'elsd_id', 'id' => 'elsd_id', 'value' => $this->session->flashdata('elsd_id'), 'class' => 'form-control no-boarder','placeholder' => 'ELSD ID')); ?>
                    </div>
                  </div>
                  <!--row 1 end-->
                  <!--row 2 start-->
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Names', 'names',array('class'=>'form-label')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_input(array('name' => 'first_name', 'id' => 'first_name', 'value' => $this->session->flashdata('first_name'), 'class' => 'form-control no-boarder','placeholder' => 'First Name')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_input(array('name' => 'middle_name', 'id' => 'middle_name', 'value' => $this->session->flashdata('middle_name'), 'class' => 'form-control no-boarder','placeholder' => 'Middle Name')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_input(array('name' => 'last_name', 'id' => 'last_name', 'value' => $this->session->flashdata('last_name'), 'class' => 'form-control no-boarder','placeholder' => 'Last Name')); ?>
                    </div>
                  </div>
                  <!--row 2 end-->
                  
                  <!--row 5 start-->
                  <div class="row form-row">
                    <div class="col-md-4">
                      <?php print form_label('Login Email', 'username',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'username', 'id' => 'username', 'value' => $this->session->flashdata('username'), 'class' => 'form-control no-boarder','placeholder' => 'email@example.com')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Password', 'password',array('class'=>'form-label')); ?>
                      <?php print form_password(array('name' => 'password', 'id' => 'password', 'value' => $this->session->flashdata('password'), 'class' => 'form-control','placeholder' => 'Password')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Confirm password', 'password_confirm',array('class'=>'form-label')); ?>
                      <?php print form_password(array('name' => 'password_confirm', 'id' => 'password_confirm', 'value' => $this->session->flashdata('password_confirm'), 'class' => 'form-control','placeholder' => 'Confirm password')); ?>
                    </div>
                  </div>
                  <!--row 5 end-->
                  
                  <!--row 6 start-->
                  <div class="row form-row">
                    <div class="col-md-6">
                      <?php print form_label('Hand Scan ID', 'scanner_id',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'scanner_id', 'id' => 'scanner_id', 'value' => $this->session->flashdata('scanner_id'), 'class' => 'form-control no-boarder','placeholder' => 'Hand Scan ID')); ?>
                    </div>
                    <div class="col-md-6">
                      <?php print form_label('Dob', 'birth_date',array('class'=>'form-label')); ?>
                      <div class="input-append success date col-md-10 col-lg-6 no-padding">
                        <?php print form_input(array('name' => 'birth_date', 'id' => 'birth_date', 'value' => $this->session->flashdata('birth_date'), 'class' => 'form-control ','placeholder' => 'Dob')); ?>
                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> </div>
                    </div>
                  </div>
                  <!--row 6 end-->
                  
                  <!--row 7 start-->
                  <div class="row form-row">
                    <div class="col-md-6">
                      <?php print form_label('Nationality', 'nationality',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('nationality',$nationality_list,'','id="nationality" class="select2 form-control"'); ?>
                    </div>
                    <div class="col-md-6">
                      <?php print form_label('Marital status', 'gender',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('marital_status',array(''=> 'Select Marital status','Married'=> 'Married','Single'=>'Single'),'','id="marital_status" class="select2 form-control"'); ?>
                    </div>
                  </div>
                  <!--row 7 end-->
                </div>
                <div class="tab-pane" id="tab2"> <br>
                  <h4 class="semi-bold">Step 2 - <span class="light">Contact & Job details</span></h4>
                  <br>
                  <!--row 3.1 start-->
                  <div class="row form-row">
                    <div class="col-md-6">
                      <?php print form_label('Mobile Phone', 'cell_phone',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'cell_phone', 'id' => 'cell_phone', 'value' => $this->session->flashdata('cell_phone'), 'class' => 'form-control no-boarder','placeholder' => 'Mobile Phone')); ?>
                    </div>
                    <div class="col-md-6">
                      <?php print form_label('Home Number', 'home_phone',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'home_phone', 'id' => 'home_phone', 'value' => $this->session->flashdata('home_phone'), 'class' => 'form-control no-boarder','placeholder' => 'Home Number')); ?>
                    </div>
                  </div>
                  <!--row 3.1 end-->
                  <!--row 3.2 start-->
                  <div class="row form-row">
                    <div class="col-md-5">
                      <?php print form_label('Work Mobile', 'work_mobile',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'work_mobile', 'id' => 'work_mobile', 'value' => $this->session->flashdata('work_mobile'), 'class' => 'form-control no-boarder','placeholder' => 'Work Mobile')); ?>
                    </div>
                    <div class="col-md-5">
                      <?php print form_label('Work Number', 'work_phone',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'work_phone', 'id' => 'work_phone', 'value' => $this->session->flashdata('work_phone'), 'class' => 'form-control no-boarder','placeholder' => 'Work Number')); ?>
                    </div>
                    <div class="col-md-2">
                      <?php print form_label('Ext', 'work_extention',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'work_extention', 'id' => 'work_extention', 'value' => $this->session->flashdata('work_extention'), 'class' => 'form-control no-boarder','placeholder' => 'Ext')); ?>
                    </div>
                  </div>
                  <!--row 3.2 end-->
                  <!--row 3.3 start-->
                  <div class="row form-row">
                    <div class="col-md-6">
                      <?php print form_label('Personal Email', 'personal_email',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'personal_email', 'id' => 'personal_email', 'value' => $this->session->flashdata('personal_email'), 'class' => 'form-control','placeholder' => 'Personal Email')); ?>
                    </div>
                    <div class="col-md-6">
                      <?php print form_label('Office / Room #', 'office_no',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'office_no', 'id' => 'office_no', 'value' => $this->session->flashdata('office_no'), 'class' => 'form-control','placeholder' => 'Office no')); ?>
                    </div>
                  </div>
                  <!--row 3.3 end-->
                  <div class="row form-row">
                      <div class="col-md-4">
                          <?php print form_label('Job Title', 'job_title',array('class'=>'form-label')); ?>
                          <?php print form_input(array('name' => 'job_title', 'id' => 'job_title', 'value' => $this->session->flashdata('job_title'), 'class' => 'form-control no-boarder','placeholder' => 'Job Title')); ?>
                      </div>
                      <div class="col-md-6">
                          <?php print form_label('Original Joining Date', 'original_start_date',array('class'=>'form-label')); ?>
                          <div class="input-append success date col-md-10 col-lg-6 no-padding">
                            <?php print form_input(array('name' => 'original_start_date', 'id' => 'original_start_date', 'value' => $this->session->flashdata('original_start_date'), 'class' => 'form-control')); ?>
                            <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> </div>
                        </div>
                  </div>
                  <div class="row form-row">
                  	<div class="col-md-6">
                      <?php print form_label('KSU Role', 'user_roll_id',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('user_roll_id',$other_user_roll,'0','id="user_roll_id" class="select2 form-control"'); ?>
                      <span>The employee's actual role within the ELSD programme</span>
                    </div>
                    <div class="col-md-6">
					  <?php print form_label('Department', 'department_id',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('department_id',$department_list,$this->session->flashdata('department_id'),'id="department_id" class="select2 form-control"'); ?>
                  	 </div>
                  </div>
                  
                </div>
                <div class="tab-pane" id="tab3"> <br>  
                  <h4 class="semi-bold">Step 3 - <span class="light">Work</span></h4>
                  <br>
                  <!--row 2.1 start-->
                  <div class="row form-row">
                    
                      <?php /*?><div class="col-md-6">
                      <?php print form_label('System Role', 'system_roll_id',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('system_roll_id',$other_user_roll,'0','id="system_roll_id" class="select2 form-control"'); ?>
                      <span>The employee's role within the HR system</span> </div><?php */?>
                  </div>
                  <!--row 2.1 end-->
                 
                  <!--row 2.2 start-->
                  <div class="row form-row">
                    
                     <?php /*?><div class="col-md-4">
                      <?php print form_label('ECL Access', 'ecl_access',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('ecl_access',array('2'=> 'No','1'=>'Yes'),'','id="ecl_access" class="select2 form-control"'); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Restricted teacher', 'banned_teacher',array('class'=>'form-label')); ?>
                       <?php print form_dropdown('banned_teacher',array('2'=> 'No','1'=>'Yes'),'','id="banned_teacher" class="select2 form-control"'); ?>
                    </div><?php */?>
                  </div>
                  <!--row 2.3 end-->
                  
                  <!--row 2.4 start-->
                  <?php /*?><div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Additional Duties', 'responsibilities',array('class'=>'form-label')); ?>
                      <?php print form_textarea(array('name' => 'responsibilities', 'id' => 'responsibilities', 'value' => $this->session->flashdata('responsibilities'), 'class' => 'form-control no-boarder')); ?>
                    </div>
                  </div><?php */?>
                  <!--row 2.4 end-->
                  <!--row 2.5 start-->
                  <div class="row form-row">
                    <div class="col-md-6">
                      <?php print form_label('Joining Date', 'cy_joining_date',array('class'=>'form-label')); ?>
                      <div class="input-append success date col-md-10 col-lg-6 no-padding">
                        <?php print form_input(array('name' => 'cy_joining_date', 'id' => 'cy_joining_date', 'value' => $this->session->flashdata('cy_joining_date'), 'class' => 'form-control','placeholder' => 'Joining Date')); ?>
                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> </div>
                    </div>
                    
                  </div>
                  <!--row 2.5 end-->
                  
                  <!--row 2.6 start-->
                  <div class="row form-row">
                    <div class="col-md-6">
                      <?php print form_label('Returning Employee', 'returning',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('returning',array(''=>'Select Returning Teacher','1'=> 'Yes','2'=>'No'),'','id="returning" class="select2 form-control"'); ?>
                    </div>
                    <div class="col-md-6">
                      <?php print form_label('Revelant experience', 'teaching_experience',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'teaching_experience', 'id' => 'teaching_experience', 'value' => $this->session->flashdata('teaching_experience'), 'class' => 'form-control no-boarder','placeholder' => 'Revelant experience')); ?>
                      <span>(years)</span> </div>
                  </div>
                  <!--row 2.6 end-->
                 
                  <!--row 2.7 start-->
                  <div class="row form-row">
                    <div class="col-md-4">
                      <?php print form_label('Contractor', 'contractor',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('contractor',array(''=>'Select Contractor','1'=> 'ICEAT','2'=>'EdEx','3'=>'KSU'),'','id="contractor" class="select2 form-control"'); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Campus', 'campus_id',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('campus_id',$campus_list,'','id="campus_id" class="select2 form-control"'); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Line Manager', 'coordinator',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('coordinator',$other_user_list,'','id="coordinator" class="select2 form-control"'); ?>
                      
                    </div>
                  </div>
                  <!--row 2.7 end-->	
                </div>
                <div class="tab-pane" id="tab4"> <br>
                  <h4 class="semi-bold">Step 4 - <span class="light">Medical</span></h4>
                  <br>
                  <!--row 4.1 start-->
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Blood type', 'blood_type',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'blood_type', 'id' => 'blood_type', 'value' => $this->session->flashdata('blood_type'), 'class' => 'form-control','placeholder' => 'Blood type')); ?>
                    </div>
                  </div>
                  <!--row 4.1 end-->
                  <!--row 4.2 start-->
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Medical conditions', 'medical_condition',array('class'=>'form-label')); ?>
                      <?php print form_textarea(array('name' => 'medical_condition', 'id' => 'medical_condition', 'value' => $this->session->flashdata('medical_condition'), 'class' => 'form-control no-boarder')); ?>
                    </div>
                  </div>
                  <!--row 4.2 end-->
                  <!--row 4.3 start-->
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Allergies', 'medical_allergies',array('class'=>'form-label')); ?>
                      <?php print form_textarea(array('name' => 'medical_allergies', 'id' => 'medical_allergies', 'value' => $this->session->flashdata('medical_allergies'), 'class' => 'form-control no-boarder')); ?>
                    </div>
                  </div>
                  <!--row 4.3 end-->
                </div>
                <div class="tab-pane" id="tab5"> <br>
                  <h4 class="semi-bold">Step 5 - <span class="light">Reference</span></h4>
                  <br>
                  <!--row 5.1 start-->
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Referee 1', '',array('class'=>'form-label')); ?>
                    </div>
                  </div>
                  <!--row 5.1 end-->
                  <!--row 5.2 start-->
                  <div class="row form-row">
                    <div class="col-md-4">
                      <?php print form_label('Company Name', 'cv_reference_company_name_1',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'cv_reference_company_name_1', 'id' => 'cv_reference_company_name_1', 'value' => $this->session->flashdata('cv_reference_company_name_1'), 'class' => 'form-control','placeholder' => 'Company Name')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Referee Name', 'cv_reference_name_1',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'cv_reference_name_1', 'id' => 'cv_reference_name_1', 'value' => $this->session->flashdata('cv_reference_name_1'), 'class' => 'form-control','placeholder' => 'Referee Name')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label("Referee's Position", 'cv_reference_position_1',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'cv_reference_position_1', 'id' => 'cv_reference_position_1', 'value' => $this->session->flashdata('cv_reference_position_1'), 'class' => 'form-control','placeholder' => "Referee's Position")); ?>
                    </div>
                  </div>
                  <!--row 5.2 end-->
                  <!--row 5.3 start-->
                  <div class="row form-row">
                    <div class="col-md-4">
                      <?php print form_label('Contact number', 'cv_reference_contact_1',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'cv_reference_contact_number_1', 'id' => 'cv_reference_contact_number_1', 'value' => $this->session->flashdata('cv_reference_contact_number_1'), 'class' => 'form-control','placeholder' => 'Contact number')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Email Address', 'cv_reference_email_1',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'cv_reference_email_1', 'id' => 'cv_reference_email_1', 'value' => $this->session->flashdata('cv_reference_email_1'), 'class' => 'form-control','placeholder' => 'Email Address')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('CV Confirmed', 'cv_confirm_1',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('cv_confirm_1',array(''=>'CV Confirmed','1'=> 'Yes','2'=>'No'),'','id="cv_confirm_1" class="select2 form-control"'); ?>
                    </div>
                  </div>
                  <!--row 5.3 end-->
                 
                  <!--row 5.1b start-->
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Referee 2', '',array('class'=>'form-label')); ?>
                    </div>
                  </div>
                  <!--row 5.1b end-->
                  <!--row 5.2b start-->
                  <div class="row form-row">
                    <div class="col-md-4">
                      <?php print form_label('Company Name', 'cv_reference_company_name_2',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'cv_reference_company_name_2', 'id' => 'cv_reference_company_name_2', 'value' => $this->session->flashdata('cv_reference_company_name_2'), 'class' => 'form-control','placeholder' => 'Company Name')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Referee Name', 'cv_reference_name_1',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'cv_reference_name_2', 'id' => 'cv_reference_name_2', 'value' => $this->session->flashdata('cv_reference_name_2'), 'class' => 'form-control','placeholder' => 'Referee Name')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label("Referee's Position", 'cv_reference_position_2',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'cv_reference_position_2', 'id' => 'cv_reference_position_2', 'value' => $this->session->flashdata('cv_reference_position_2'), 'class' => 'form-control','placeholder' => "Referee's Position")); ?>
                    </div>
                  </div>
                  <!--row 5.2b end-->
                  
                  <!--row 5.3b start-->
                  <div class="row form-row">
                    <div class="col-md-4">
                      <?php print form_label('Contact number', 'cv_reference_contact_2',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'cv_reference_contact_number_2', 'id' => 'cv_reference_contact_number_2', 'value' => $this->session->flashdata('cv_reference_contact_number_2'), 'class' => 'form-control','placeholder' => 'Contact number')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Email Address', 'cv_reference_email_2',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'cv_reference_email_2', 'id' => 'cv_reference_email_2', 'value' => $this->session->flashdata('cv_reference_email_2'), 'class' => 'form-control','placeholder' => 'Email Address')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('CV Confirmed', 'cv_confirm_2',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('cv_confirm_2',array(''=>'CV Confirmed','1'=> 'Yes','2'=>'No'),'','id="cv_confirm_2" class="select2 form-control"'); ?>
                    </div>
                  </div>
                  <!--row 5.3b end-->
                </div>
                <div class="tab-pane" id="tab6"> <br>
                  <h4 class="semi-bold">Step 6 - <span class="light">Verification</span></h4>
                  <br>
                  <!--row 6.1 start-->
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Nationality', 'ver_nationality',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('ver_nationality',array('1'=>'Verified','2'=>'Un verified'),'','id="ver_nationality" class=""');
					        //print form_input(array('name' => 'ver_nationality', 'id' => 'ver_nationality', 'value' => $this->session->flashdata('ver_nationality'), 'class' => 'form-control','placeholder' => 'Nationality')); ?>
                    </div>
                  </div>
                  <!--row 6.1 end-->
                  <!--row 6.2 start-->
                  <div class="row form-row">
                  <?php /*?>
                    <div class="col-md-12">
                      <?php print form_label('Qualifications', 'ver_qualification',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'ver_qualification', 'id' => 'ver_qualification', 'value' => $this->session->flashdata('ver_qualification'), 'class' => 'form-control','placeholder' => 'Qualifications')); ?>
                    </div>
                  <?php */?>
                  	<div class="col-md-4">
					  <?php print form_label('Degree Comments', 'degree_comments',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'degree_comments', 'id' => 'degree_comments', 'value' => $this->session->flashdata('degree_comments'), 'class' => 'form-control','placeholder' => 'Degree Comments')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Experience', 'ver_experience',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('ver_experience',array('1'=>'Verified','2'=>'Un verified'),'','id="ver_experience" class=""');
					  		//print form_input(array('name' => 'ver_experience', 'id' => 'ver_experience', 'value' => $this->session->flashdata('ver_experience'), 'class' => 'form-control','placeholder' => 'Experience')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('References', 'ver_reference',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('ver_reference',array('1'=>'Verified','2'=>'Un verified'),'','id="ver_reference" class=""');
					  		//print form_input(array('name' => 'ver_reference', 'id' => 'ver_reference', 'value' => $this->session->flashdata('ver_reference'), 'class' => 'form-control','placeholder' => 'References')); ?>
                    </div>
                  </div>  
                  <!--row 6.2 end-->
                  
                  <!--row 6.5 start-->
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Interview Details', '',array('class'=>'form-label')); ?>
                    </div>
                    <div class="col-md-6">
                      <?php //print form_input(array('name' => 'interviewee1', 'id' => 'interviewee1', 'value' => $this->session->flashdata('interviewee1'), 'class' => 'form-control','placeholder' => 'Interview 1')); ?>
                      <?php print form_label('Interviewer 1', 'interviewee1',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('interviewee1',$other_user_list,$this->session->flashdata('interviewee1'),'id="interviewee1" class=""'); ?>
                    </div>
                    <div class="col-md-6">
                      <?php //print form_input(array('name' => 'interviewee2', 'id' => 'interviewee2', 'value' => $this->session->flashdata('interviewee2'), 'class' => 'form-control','placeholder' => 'Interview 2')); ?>
                      <?php print form_label('Interviewer 2', 'interviewee2',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('interviewee2',$other_user_list,$this->session->flashdata('interviewee2'),'id="interviewee2" class=""'); ?>
                    </div>
                    <div class="col-md-4">
                      <div class="input-append success date col-md-10 col-lg-6 no-padding">
                        <?php print form_input(array('name' => 'interview_date', 'id' => 'interview_date', 'value' => $this->session->flashdata('interview_date'), 'class' => 'form-control','placeholder' => 'Interview date')); ?>
                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> </div>
                    </div>
                    <div class="col-md-4">
                    	<?php print form_dropdown('interview_outcome',$interview_outcome_list,$this->session->flashdata('interview_outcome'),'id="interview_outcome" class=""'); ?>
                    </div>
                    <div class="col-md-4">
                            <?php print form_dropdown('interview_type',$interview_type_list,$this->session->flashdata('interview_type'),'id="interview_type" class=""'); ?>
                        </div>
                    <div class="col-md-12">
                    	<?php print form_textarea(array('name' => 'interview_notes', 'id' => 'interview_notes', 'value' => $this->session->flashdata('interview_notes'), 'class' => 'form-control no-boarder')); ?>
                    </div>
                  </div>
                  <!--row 6.5 end-->
                  <!--row 6.6 start-->
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('On Timetable', 'on_timetable',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('on_timetable',array(''=>'Select Status','1'=> 'Yes','2'=>'No'),'','id="on_timetable" class="select2 form-control"'); ?>
                    </div>
                  </div>
                  <!--row 6.6 end-->
                </div>
                <ul class=" wizard wizard-actions">
                  <li class="previous first" style="display:none;"><a href="javascript:;" class="btn">&nbsp;&nbsp;First&nbsp;&nbsp;</a></li>
                  <li class="previous"><a href="javascript:;" class="btn">&nbsp;&nbsp;Previous&nbsp;&nbsp;</a></li>
                  <li class="next last" style="display:none;"><a href="javascript:;" class="btn btn-primary">&nbsp;&nbsp;Last&nbsp;&nbsp;</a></li>
                  <li class="next"><a href="javascript:;" class="btn btn-primary">&nbsp;&nbsp;Next&nbsp;&nbsp;</a></li>
                  <li class=""><input type="submit" name="submit" id="submit" value="Save" class="btn btn-success"/></li>
                </ul>
              </div>
            </div>
          <?php
		  		print form_close() ."\r\n";
			?>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- END PAGE -->
