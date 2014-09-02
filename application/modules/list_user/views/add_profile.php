<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
              
<div class="row">
  <div class="col-md-12">
    <div class="grid simple transparent">
      <div class="grid-title">
        <h4>Add New <span class="semi-bold">Employee</span></h4>
      </div>
      <div class="grid-body ">
        <div class="row">
        	
          <?php print form_open('list_user/add_profile', array('id' => 'add_profile','name'=>'add_profile')) ."\r\n"; ?>
            <div id="rootwizard" class="col-md-12">
            	<?php
				if ($this->session->flashdata('message')) {
					print "<br><div class=\"alert alert-error\">". $this->session->flashdata('message') ."</div>";
				}
				?>
              <div class="form-wizard-steps">
                <ul class="wizard-steps">
                  <li class="active" data-target="#step1"> <a href="#tab1" data-toggle="tab"> <span class="step">1</span> <span class="title">Personal & Contact Details</span> </a> </li>
                  <li data-target="#step2" class=""> <a href="#tab2" data-toggle="tab"> <span class="step">2</span> <span class="title">Job Details</span> </a> </li>
                  <li data-target="#step3" class=""> <a href="#tab3" data-toggle="tab"> <span class="step">3</span> <span class="title">Medical</span> </a> </li>
                  <li data-target="#step4" class=""> <a href="#tab4" data-toggle="tab"> <span class="step">4</span> <span class="title">Interview Details<br>
                    </span> </a> </li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="tab-content transparent">
                <div class="tab-pane active" id="tab1"> <br>
                  <h4 class="semi-bold">Step 1 - <span class="light">Personal & Contact Details</span></h4>
                  <br>
                  <!--row 1 start-->
                  <div class="row form-row">
                    <div class="col-md-4">
                      <?php 
					  $user_status = '';
					  if($user_other == 'other'){ ?>
						  <script>
						  $(document).ready(function(){
							$('#status').prop('readonly', true);
						  });
						  </script>
					  <?php 
					  	$user_status = 20;
					  } ?>
                      <?php print form_label('Status', 'status',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('status',$user_profile_status,$user_status,'id="status" class="select2 form-control"'); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Gender', 'gender',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('gender',array(''=> 'Select Gender','M'=> 'Male','F'=>'Female'),'','id="gender" class="select2 form-control"'); ?>
                    </div>
				    <div class="col-md-4"> <?php print form_label('Name Title', 'title',array('class'=>'form-label')); ?> 
				    <?php print form_dropdown('title',$name_title_list,$this->session->flashdata('title'),'id="title" class="select2 form-control"'); ?>
				    </div>
                  </div>
                  <!--row 1 end-->
                  <!--row 2 start-->
                  <div class="row form-row">
                    <div class="col-md-4">
                    <?php print form_label('First Name', 'names',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'first_name', 'id' => 'first_name', 'value' => $this->session->flashdata('first_name'), 'class' => 'form-control no-boarder','placeholder' => 'First Name')); ?>
                    </div>
                    <div class="col-md-4">
                    <?php print form_label('Middle Name 1', 'names',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'middle_name', 'id' => 'middle_name', 'value' => $this->session->flashdata('middle_name'), 'class' => 'form-control no-boarder','placeholder' => 'Middle Name')); ?>
                    </div>
                    <div class="col-md-4">
                    <?php print form_label('Middle Name 2', 'names',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'middle_name2', 'id' => 'middle_name2', 'value' => $this->session->flashdata('middle_name2'), 'class' => 'form-control no-boarder','placeholder' => 'Middle Name 2')); ?>
                    </div>
                  </div>
                  <!--row 2 end-->
                  
                  <!--row 5 start-->
                  <div class="row form-row">
                  <div class="col-md-4">
                  <?php print form_label('Last Name', 'names',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'last_name', 'id' => 'last_name', 'value' => $this->session->flashdata('last_name'), 'class' => 'form-control no-boarder','placeholder' => 'Last Name')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Nationality', 'nationality',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('nationality',$nationality_list,'','id="nationality" class="select2 form-control"'); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Marital status', 'gender',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('marital_status',array(''=> 'Select Marital status','Married'=> 'Married','Single'=>'Single'),'','id="marital_status" class="select2 form-control"'); ?>
                    </div>
                    </div>
                    <div class="row form-row">
                    <div class="col-md-4">
                      <?php print form_label('User Name', 'username',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'username', 'id' => 'username', 'value' => $this->session->flashdata('username'), 'class' => 'form-control no-boarder','placeholder' => 'email@example.com or other name')); ?>
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
                  <div class="row form-row">
					 <div class="col-md-4">
                      <?php print form_label('Dob', 'birth_date',array('class'=>'form-label')); ?>
                      <div class="input-append success date col-md-10 col-lg-10 no-padding">
                        <?php print form_input(array('name' => 'birth_date', 'id' => 'show_dp', 'value' => $this->session->flashdata('birth_date'), 'class' => 'form-control ','placeholder' => 'Dob')); ?>
                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> </div>
                    </div>
                     <div class="col-md-4">
                      <?php print form_label('Personal Email', 'personal_email',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'personal_email', 'id' => 'personal_email', 'value' => $this->session->flashdata('personal_email'), 'class' => 'form-control','placeholder' => 'Personal Email')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Mobile Phone', 'cell_phone',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'cell_phone', 'id' => 'cell_phone', 'value' => $this->session->flashdata('cell_phone'), 'class' => 'form-control no-boarder','placeholder' => 'Mobile Phone')); ?>
                    </div>                    
                  </div>
				  <div class="row form-row">
				  <div class="col-md-4">
					  <?php print form_label('Skype ID', 'skype_id',array('class'=>'form-label')); ?>
					  <?php print form_input(array('name' => 'skype_id', 'id' => 'skype_id', 'value' => $this->session->flashdata('skype_id'), 'class' => 'form-control')); ?>
				   </div>
				  </div> 
                  <!--row 5 end-->
                </div>
                <div class="tab-pane" id="tab2"> <br>
                  <h4 class="semi-bold">Step 2 - <span class="light">Job details</span></h4>
                  <br>
                   <!--row 2.1 start-->
                  <div class="row form-row">
                    <div class="col-md-3">
                      <?php print form_label('Contractor', 'contractor',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('contractor',array(''=>'Select Contractor','1'=> 'ICEAT','2'=>'EdEx','3'=>'KSU'),'','id="contractor" class="select2 form-control"'); ?>
                    </div>
                    <div class="col-md-3"> 
                      <?php print form_label('Campus Privilages', 'campus_privilages_id',array('class'=>'form-label')); ?> 
                      <?php
					  print form_multiselect('campus_privilages[]',$campus_list,"",'id="multi" class="select2 form-control" placeholder="Select Campus"'); 
					  ?> 
                    </div>
                    <div class="col-md-3">
                      <?php print form_label('Campus Location', 'campus_id',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('campus_id',$campus_list,'','id="campus_id" class="select2 form-control"'); ?>
                    </div>
                    <div class="col-md-3">
                      <?php print form_label('Line Manager', 'coordinator',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('coordinator',$other_user_list,'','id="coordinator" class="select2 form-control"'); ?>
                    </div>
                  </div>
                  <!--row 2.1 end-->
                  <!--row 2.2 Start Department,Revelant experience,Returning Employee-->
                  	<div class="row form-row">
                    <div class="col-md-4">
					  <?php print form_label('Department', 'department_id',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('department_id',$department_list,$this->session->flashdata('department_id'),'id="department_id" class="select2 form-control"'); ?>
                  	 </div>
                    <div class="col-md-4">
                      <?php print form_label('Revelant experience', 'teaching_experience',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'teaching_experience', 'id' => 'teaching_experience', 'value' => $this->session->flashdata('teaching_experience'), 'class' => 'form-control no-boarder','placeholder' => 'Revelant experience')); ?>
                      <span>(years)</span> 
					</div>
                    <div class="col-md-4">
                      <?php print form_label('Returning Employee', 'returning',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('returning',array(''=>'Select Returning Teacher','1'=> 'Yes','2'=>'No'),'','id="returning" class="select2 form-control"'); ?>
                    </div>
                  </div>
                  <!--row 2.2 end-->
                  <!--row 2.3 Start KSU Role,Job Title,Scan ID-->
                  	<div class="row form-row">
                  	<div class="col-md-4">
                      <?php print form_label('ELSD System Role', 'user_roll_id',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('user_roll_id',$other_user_roll,'0','id="user_roll_id" class="select2 form-control"'); ?>
                    </div>
                    <div class="col-md-4">
                          <?php print form_label('Job Title', 'job_title',array('class'=>'form-label')); ?>
                          <?php print form_dropdown('job_title',$jobtitle_list,$this->session->flashdata('job_title'),'id="job_title" class="select2 form-control"'); ?>
                      </div>  
                    <div class="col-md-4">
						  <?php print form_label('Scan ID', 'scanner_id',array('class'=>'form-label')); ?>
						  <?php print form_input(array('name' => 'scanner_id', 'id' => 'scanner_id', 'value' => $this->session->flashdata('scanner_id'), 'class' => 'form-control no-boarder','placeholder' => 'Hand Scan ID')); ?>
						</div>                      
                  </div>
                  <!--row 2.3 end-->                  
                   <!--row 2.4 Start  Office / Room #,Work Number,Ext-->
                   <div class="row form-row">
                   <div class="col-md-4">
                      <?php print form_label('Office / Room #', 'office_no',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'office_no', 'id' => 'office_no', 'value' => $this->session->flashdata('office_no'), 'class' => 'form-control','placeholder' => 'Office no')); ?>
                    </div>
                   	<div class="col-md-4">
                      <?php print form_label('Work Number', 'work_phone',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'work_phone', 'id' => 'work_phone', 'value' => $this->session->flashdata('work_phone'), 'class' => 'form-control no-boarder','placeholder' => 'Work Number')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_label('Ext', 'work_extention',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'work_extention', 'id' => 'work_extention', 'value' => $this->session->flashdata('work_extention'), 'class' => 'form-control no-boarder','placeholder' => 'Ext')); ?>
                    </div>
                    </div>
                   <!--row 2.4 end-->                   
                   <!--row 2.5 Start   Original Joining Date at KSU,Joining Date for academic year -->
                   <div class="row form-row">
                   		<div class="col-md-4">
                          <?php print form_label('Original Joining Date at KSU', 'original_start_date',array('class'=>'form-label')); ?>
                          <div class="input-append success date col-md-10 col-lg-6 no-padding">
                            <?php print form_input(array('name' => 'original_start_date', 'id' => 'show_dp', 'value' => $this->session->flashdata('original_start_date'), 'class' => 'form-control')); ?>
                            <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> </div>
                        </div>
						<div class="col-md-4"> <?php print form_label('Original Year Joined', 'original_start_year',array('class'=>'form-label')); ?>
							<div class="input-append success col-md-10 col-lg-10 no-padding"> 
							<?php print form_dropdown('original_start_year',$original_start_year_list,$this->session->flashdata('original_start_year'),'id="original_start_year" class="select2 form-control"'); ?>
							</div>
						</div>
                        <div class="col-md-4">
                      <?php print form_label('Joining Date for academic year', 'cy_joining_date',array('class'=>'form-label')); ?>
                      <div class="input-append success date col-md-10 col-lg-6 no-padding">
                        <?php print form_input(array('name' => 'cy_joining_date', 'id' => 'show_dp', 'value' => $this->session->flashdata('cy_joining_date'), 'class' => 'form-control','placeholder' => 'Joining Date')); ?>
                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> </div>
                   	  </div>
                    </div>
                   <!--row 2.5 end-->
                    <!--row 2.6 Start  Other Responsibilities -->
                    	<div class="row form-row other-responsiblty">
					  <div class="col-md-12"> 
					  	<?php print form_label('Other Responsibilities', 'other_responsibilities',array('class'=>'form-label')); ?>
                        </div>
                        <div class="col-md-4"> 
                        	<?php print form_checkbox(array('name' => 'mentor', 'id' => 'mentor', 'value' => '1')); ?>
                            <?php print form_label('Mentor', 'mentor'); ?>
                        </div>
                        <div class="col-md-4"> 
                        	<?php print form_checkbox(array('name' => 'lesson_observer', 'id' => 'lesson_observer', 'value' => '1')); ?>
                            <?php print form_label('Lesson Observer', 'lesson_observer'); ?>
                        </div>
                        <div class="col-md-4"> 
                        	<?php print form_checkbox(array('name' => 'buzz_observer', 'id' => 'buzz_observer', 'value' => '1')); ?>
                            <?php print form_label('Buzz Observer', 'buzz_observer'); ?>
                        </div>
                        <div class="col-md-4"> 
                        	<?php print form_checkbox(array('name' => 'spot_checker', 'id' => 'spot_checker', 'value' => '1')); ?>
                            <?php print form_label('Spot Checker', 'spot_checker'); ?>
                        </div>
                        <div class="col-md-4"> 
                        	<?php print form_checkbox(array('name' => 'is_line_manager', 'id' => 'is_line_manager', 'value' => '1')); ?>
							<?php print form_label('Line Manager', 'is_line_manager'); ?>
                        </div>
                        <div class="col-md-4"> 
                        	<?php print form_checkbox(array('name' => 'interviewer', 'id' => 'interviewer', 'value' => '1')); ?>
                            <?php print form_label('Interviewer', 'interviewer'); ?>
                        </div>
					</div>
                    <!--row 2.6 end-->
                    <!--row 2.7 Start   Worked at KSU before,Worked at KSU start date,Worked at KSU end date -->
                    	<div class="row form-row">
                          <div class="col-md-4">
                          <?php print form_label('Worked at KSU before', 'worked_at_ksu_before',array('class'=>'form-label')); ?>
                          <?php print form_dropdown('worked_at_ksu_before',array('Yes'=>'Yes','No'=>'No'),$this->session->flashdata('worked_at_ksu_before'),'id="worked_at_ksu_before" class="select2 form-control" onchange="change_worked_at_ksu_before();"'); ?>
                          </div>
                          <div id="worked_at_ksu_date">
                          <div class="col-md-4">
                          <?php print form_label('Worked at KSU start date', 'worked_ksu_start_date',array('class'=>'form-label')); ?>
                          <div class="input-append success date col-md-10 col-lg-6 no-padding">
                            <?php print form_input(array('name' => 'worked_ksu_start_date', 'id' => 'show_dp', 'value' => $this->session->flashdata('worked_ksu_start_date'), 'class' => 'form-control')); ?>
                            <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> </div>
                        </div>
                        <div class="col-md-4">
                          <?php print form_label('Worked at KSU end date', 'worked_ksu_end_date',array('class'=>'form-label')); ?>
                          <div class="input-append success date col-md-10 col-lg-6 no-padding">
                            <?php print form_input(array('name' => 'worked_ksu_end_date', 'id' => 'show_dp', 'value' => $this->session->flashdata('worked_ksu_end_date'), 'class' => 'form-control')); ?>
                            <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> </div>
                        </div>
                        </div>
                        </div>
                    <!--row 2.7 end-->
                    
                    <!--row 2.8 Start Details of previous KSU experience -->                  
                      <div class="row form-row">
                      	<div class="col-md-12">
                          <?php print form_label('Details of previous KSU experience', 'worked_ksu_job_detail',array('class'=>'form-label')); ?>
                          <?php print form_input(array('name' => 'worked_ksu_job_detail', 'id' => 'worked_ksu_job_detail', 'value' => $this->session->flashdata('worked_ksu_job_detail'), 'class' => 'form-control')); ?>
                        </div>
                       </div>
                       <!--row 2.7 end-->				  
				</div>
                <div class="tab-pane" id="tab3"> <br>
                  <h4 class="semi-bold">Step 3 - <span class="light">Medical</span></h4>
                  <br>
                  <!--row 4.1 start-->
                  <div class="row form-row">
                    <div class="col-md-6">
                      <?php print form_label('Blood type', 'blood_type',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'blood_type', 'id' => 'blood_type', 'value' => $this->session->flashdata('blood_type'), 'class' => 'form-control','placeholder' => 'Blood type')); ?>
                    </div>
                  </div>
                  <!--row 4.1 end-->
                  <!--row 4.2 start-->
                  <div class="row form-row">
                    <div class="col-md-6">
                      <?php print form_label('Medical conditions', 'medical_condition',array('class'=>'form-label')); ?>
                      <?php print form_textarea(array('name' => 'medical_condition', 'id' => 'medical_condition', 'value' => $this->session->flashdata('medical_condition'), 'class' => 'form-control no-boarder')); ?>
                    </div>
                    <div class="col-md-6">
                      <?php print form_label('Allergies', 'medical_allergies',array('class'=>'form-label')); ?>
                      <?php print form_textarea(array('name' => 'medical_allergies', 'id' => 'medical_allergies', 'value' => $this->session->flashdata('medical_allergies'), 'class' => 'form-control no-boarder')); ?>
                    </div>
                  </div>
                  <!--row 4.2 end-->
                </div>
                <div class="tab-pane" id="tab4"> <br>
                  <h4 class="semi-bold">Step 4 - <span class="light">Interview Details</span></h4>
                  <br>
                  <div class="row form-row">
					  <div class="col-md-6"> <?php print form_label('a. Lesson plan submitted', 'lesson_plan_submitted',array('class'=>'form-label')); ?> <?php print form_dropdown('lesson_plan_submitted',array(''=>'Select','Yes'=> 'Yes','No'=>'No'),$this->session->flashdata('lesson_plan_submitted'),'id="lesson_plan_submitted" class=""'); ?> </div>
					  <div class="col-md-6"> <?php print form_label('d. Writing sample submitted', 'writing_sample_submitted',array('class'=>'form-label')); ?> <?php print form_dropdown('writing_sample_submitted',array(''=>'Select','Yes'=> 'Yes','No'=>'No'),$this->session->flashdata('writing_sample_submitted'),'id="writing_sample_submitted" class=""'); ?> </div>
				  </div>
				  <div class="row form-row">
					  <div class="col-md-6"> <?php print form_label('b. Lesson plan suitable','lesson_plan_suitable',array('class'=>'form-label')); ?> <?php print form_dropdown('lesson_plan_suitable',array(''=>'Select','Yes'=> 'Yes','No'=>'No'),$this->session->flashdata('lesson_plan_suitable'),'id="lesson_plan_suitable" class=""'); ?> </div>
					  <div class="col-md-6"> <?php print form_label('e. Writing sample suitable', 'writing_sample_suitable',array('class'=>'form-label')); ?> <?php print form_dropdown('writing_sample_suitable',array(''=>'Select','Yes'=> 'Yes','No'=>'No'),$this->session->flashdata('writing_sample_suitable'),'id="writing_sample_suitable" class=""'); ?> </div>
				  </div>
				  <div class="row form-row">
					  <div class="col-md-6"> <?php print form_label('c. Lesson plan comments', 'lesson_plan_comments',array('class'=>'form-label')); ?> <?php print form_textarea(array('name' => 'lesson_plan_comments', 'id' => 'lesson_plan_comments', 'value' => $this->session->flashdata('lesson_plan_comments'), 'class' => 'form-control ')); ?> </div>
					  <div class="col-md-6"> <?php print form_label('f. Writing sample comments', 'writing_sample_comments',array('class'=>'form-label')); ?> <?php print form_textarea(array('name' => 'writing_sample_comments', 'id' => 'writing_sample_comments', 'value' => $this->session->flashdata('writing_sample_comments'), 'class' => 'form-control ')); ?> </div>
				  </div>
				  <div class="row form-row">
					  <div class="col-md-6"> <?php print form_label('g. Demo lesson recommended', 'demo_lesson_recommended',array('class'=>'form-label')); ?> <?php print form_dropdown('demo_lesson_recommended',array(''=>'Select','Yes'=> 'Yes','No'=>'No'),$this->session->flashdata('demo_lesson_recommended'),'id="demo_lesson_recommended" class=""'); ?> </div>
				  </div>
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
                      <div class="input-append success date col-md-10 col-lg-10 no-padding">
                        <?php print form_input(array('name' => 'interview_date', 'id' => 'show_dp', 'value' => $this->session->flashdata('interview_date'), 'class' => 'form-control','placeholder' => 'Interview date')); ?>
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
