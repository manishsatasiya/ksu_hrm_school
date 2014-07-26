<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
              
<div class="row">
  <div class="col-md-12">
    <div class="grid simple ">
      <div class="grid-title"><h4><?php if($user_id > 0){echo 'Edit';}else{ echo 'Add New';} ?><span class="semi-bold"> Employee</span></h4></div>
      <div class="grid-body ">
        
        	
          <?php print form_open_multipart('add_employee', array('id' => 'add_employee','name'=>'add_employee')) ."\r\n"; ?>
            
            	<?php
				if ($this->session->flashdata('message')) {
					print "<br><div class=\"alert alert-error\">". $this->session->flashdata('message') ."</div>";
				}
				?>
              
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Status', 'status',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('status',$user_profile_status,($user_data)?$user_data->status:$this->session->flashdata('status'),'id="status" class="select2 form-control"'); ?>
                    </div>
                  </div>
                  <?php 
				  if($this->session->userdata('contractor') > 0){ ?>
					  <script>
                      $(document).ready(function(){
                        $('#status').prop('readonly', true);
                      });
                      </script>
                  <?php } ?>
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Names', 'names',array('class'=>'form-label')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_input(array('name' => 'first_name', 'id' => 'first_name', 'value' => ($user_data)?$user_data->first_name:$this->session->flashdata('first_name'), 'class' => 'form-control ','placeholder' => 'First Name')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_input(array('name' => 'middle_name', 'id' => 'middle_name', 'value' => ($user_data)?$user_data->middle_name:$this->session->flashdata('middle_name'), 'class' => 'form-control ','placeholder' => 'Middle Name')); ?>
                    </div>
                    <div class="col-md-4">
                      <?php print form_input(array('name' => 'last_name', 'id' => 'last_name', 'value' => ($user_data)?$user_data->last_name:$this->session->flashdata('last_name'), 'class' => 'form-control ','placeholder' => 'Last Name')); ?>
                    </div>
                  </div>
                  
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Gender', 'gender',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('gender',array(''=> 'Select Gender','M'=> 'Male','F'=>'Female'),($user_data)?$user_data->gender:$this->session->flashdata('gender'),'id="gender" class="select2 form-control"'); ?>
                    </div>
                  </div>
                  
                  <!--row 5 start-->
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Email', 'email',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'email', 'id' => 'email', 'value' => ($user_data)?$user_data->email:$this->session->flashdata('email'), 'class' => 'form-control ','placeholder' => 'email@example.com')); ?>
                    </div>
                  </div>
                  
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Dob', 'birth_date',array('class'=>'form-label')); ?>
                      <div class="input-append success date col-md-10 col-lg-6 no-padding">
                        <?php print form_input(array('name' => 'birth_date', 'id' => 'date', 'value' => ($user_data)?date('m/d/Y',strtotime($user_data->birth_date)):$this->session->flashdata('birth_date'), 'class' => 'form-control ','placeholder' => 'Dob')); ?>
                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> </div>
                    </div>
                  </div>
                  
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Nationality', 'nationality',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('nationality',$nationality_list,($user_data)?$user_data->nationality:$this->session->flashdata('nationality'),'id="nationality" class=" form-control"'); ?>
                    </div>
                  </div>
                  
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Marital status', 'marital_status',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('marital_status',array(''=> 'Select Marital status','Married'=> 'Married','Single'=>'Single'),($user_data)?$user_data->marital_status:$this->session->flashdata('marital_status'),'id="marital_status" class="select2 form-control"'); ?>
                    </div>
                  </div>
                  
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Languages', 'language_known',array('class'=>'form-label')); ?>
                      <?php print form_dropdown('language_known',array('English'=> 'English','Arabic'=>'Arabic','Arabic & English'=> 'Arabic & English'),($user_data)?$user_data->language_known:$this->session->flashdata('language_known'),'id="language_known" class=""'); ?>
                    </div>
                  </div>
                  
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Mobile Phone', 'cell_phone',array('class'=>'form-label')); ?>
                      <?php print form_input(array('name' => 'cell_phone', 'id' => 'cell_phone', 'value' => ($user_data)?$user_data->cell_phone:$this->session->flashdata('cell_phone'), 'class' => 'form-control ','placeholder' => 'Mobile Phone')); ?>
                    </div>
                  </div>
                  
                  <div class="row form-row">
                    <div class="col-md-12">
                      <?php print form_label('Expected arrival date', 'expected_arrival_date',array('class'=>'form-label')); ?>
                      <div class="input-append success date col-md-10 col-lg-6 no-padding">
                      <?php print form_input(array('name' => 'expected_arrival_date', 'id' => 'date', 'value' => ($user_data)?$user_data->expected_arrival_date:$this->session->flashdata('expected_arrival_date'), 'class' => 'form-control ','placeholder' => 'Expected arrival date')); ?>
                      <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> </div>
                    </div>
                  </div>
                  
                  <?php
				  if($user_id == 0){ ?>
                  <h4 class="semi-bold">Reference <input type="button" id="add_reference_div" class="btn btn-primary" value="Add" /></h4>
                  <ol id="references"></ol>
                  <div class="hide" id="reference_main_sample">
                       <li>
                       	  <div class="row form-row">
                            <div class="col-md-3">
                              <label class="form-label">Company Name</label>
                              <input type="text" name="cv_reference[company_name][]" class="form-control"  />
                            </div>
                            <div class="col-md-3">
                              <label class="form-label">Referee Name</label>
                              <input type="text" name="cv_reference[name][]" class="form-control"  />
                            </div>
                            <div class="col-md-3">
                              <label class="form-label">Email Address</label>
                              <input type="text" name="cv_reference[email][]" class="form-control"  />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <input type="button" id="" class="btn btn-danger " onclick="javasctipt:deleteMulBox(this);" value="Delete" />
                            </div>
                          </div>
                      </li>
                  </div>
                  
                  <h4 class="semi-bold">Employment History <input type="button" id="add_experience_div" class="btn btn-primary" value="Add" /></h4>
                  <ol id="experiences"></ol>
                  <div class="hide" id="experience_main_sample">
                       <li>
                       		<div class="row form-row">
                                <div class="col-md-1 pull-right">
                                    <input type="button" id="" class="btn btn-danger " onclick="javasctipt:deleteMulBox(this);" value="Delete" />
                                </div>
                       		</div>
                            <div class="row form-row">
                                <div class="col-md-6">
                                    <div class="form_label2"><?php print form_label('Company', 'company'); ?></div>
                                    <div class="input_box_thin"><?php print form_input(array('name' => 'experience[company][]', 'id' => 'company', 'value' => '', 'class' => 'form-control')); ?></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form_label2"><?php print form_label('Position', 'position'); ?></div>
                                    <div class="input_box_thin"><?php print form_input(array('name' => 'experience[position][]', 'id' => 'position', 'value' => '', 'class' => 'form-control')); ?></div>
                                </div>	
                                <div class="clear"></div>
                            </div>
                            
                            <div class="row form-row">
                                <div class="col-md-6">
                                    <div class="form_label2"><?php print form_label('Start date', 'start_date'); ?></div>
                                    <div class="input-append success date col-md-10 no-padding">	
                                        <?php print form_input(array('name' => 'experience[start_date][]', 'id' => 'date', 'value' => '', 'class' => 'form-control')); ?>
                                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form_label2"><?php print form_label('End Date', 'end_date'); ?></div>
                                    <div class="input-append success date col-md-10 no-padding">	
                                        <?php print form_input(array('name' => 'experience[end_date][]', 'id' => 'date', 'value' => '', 'class' => 'form-control')); ?>
                                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="row form-row">
                                <div class="col-md-12">
                                    <div class="form_label2"><?php print form_label('Reason for Leaving', 'departure_reason'); ?></div>
                                    <div class="input_box_thin"><?php print form_input(array('name' => 'experience[departure_reason][]', 'id' => 'departure_reason', 'value' => '', 'class' => 'form-control')); ?></div>
                                </div>
                            </div>
                       </li>
                  </div>
                  
                  <h4 class="semi-bold">Certificates <input type="button" class="btn btn-primary" id="add_certificate_div" value="Add" /></h4>
                  <ol id="certificates"></ol>
                  <div class="hide" id="certificate_main_sample">
                       <li>
                       	  <div class="row form-row">
                            <div class="col-md-4">
                                <div class="form_label2"><?php print form_label('Certificate', 'certificate_id'); ?></div>
                                <div class="input_box_thin"><?php  
                                    print form_dropdown('certificates[certificate_id][]',$certificate_list,'0','id="certificate_id" class=""'); 
                                ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_label2"><?php print form_label('Date', 'date');?></div>
                                <div class="input-append success date col-md-10 no-padding">	
                                    <?php print form_input(array('name' => 'certificates[date][]', 'id' => 'date', 'value' => '', 'class' => 'form-control')); ?>
                                    <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <input type="button" id="" class="btn btn-danger " onclick="javasctipt:deleteMulBox(this);" value="Delete" />
                            </div>
                          </div>
                      </li>
                  </div>
                  
                  <h4 class="semi-bold">Qualification <input type="button" class="btn btn-primary" id="add_qualification_div" value="Add" /></h4>
                  <ol id="qualifications"></ol>
                  <div class="hide" id="qualification_main_sample">
                  		<li>
                       		<div class="row form-row">
                                <div class="col-md-1 pull-right">
                                    <input type="button" id="" class="btn btn-danger " onclick="javasctipt:deleteMulBox(this);" value="Delete" />
                                </div>
                       		</div>
                            
                            <div class="row form-row">
                                <div class="col-md-6">
                                    <div class="form_label2"><?php print form_label('Qualification', 'qualification_id'); ?></div>
                                    <div class="input_box_thin"><?php  
                                        print form_dropdown('qualifications[qualification_id][]',$qualifications_list,'0','id="qualification_id" class="formselect"'); 
                                    ?></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form_label2"><?php print form_label('English Related', 'subject_related'); ?></div>
                                    <div class="input_box_thin"><?php print form_dropdown('qualifications[subject_related][]',array(''=>'Select','0'=>'No','1'=>'Yes'),'','id="subject_related" class="formselect"'); ?></div>
                                </div>	
                                <div class="clear"></div>
                            </div>
                            
                            <div class="row form-row">
                                <div class="col-md-6">
                                    <div class="form_label2"><?php print form_label('Subject', 'subject'); ?></div>
                                    <div class="input_box_thin"><?php print form_input(array('name' => 'qualifications[subject][]', 'id' => 'subject', 'value' => '', 'class' => 'form-control')); ?></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form_label2"><?php print form_label('Date', 'date'); ?></div>
                                    <div class="input-append success date col-md-10 no-padding">	
                                        <?php print form_input(array('name' => 'qualifications[date][]', 'id' => 'date', 'value' => '', 'class' => 'form-control')); ?>
                                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </li>    	
                  </div>
                  
                  <h4 class="semi-bold">Documents</h4>
                  
                  <div class="row form-row">
                  		<div class="col-md-6">
                    		<label>Photo</label>
                            <input type="file" name="photo[]" id="photo" class="form-control input-sm" accept="image/jpg|image/jpeg|image/png|application/pdf" multiple>
                        </div>
                        <div class="col-md-6">
                        	<label>Passport</label>
                            <input type="file" name="passport[]" id="passport" class="form-control input-sm" accept="image/jpg|image/jpeg|image/png|application/pdf" multiple>
                        </div>
                        <div class="col-md-6">
                        	<label>CV</label>
                            <input type="file" name="cv[]" id="cv" class="form-control input-sm" accept="image/jpg|image/jpeg|image/png|application/pdf" multiple>
                        </div>
                        <div class="col-md-6">
                    		<label>Degree Certificate</label>
                            <input type="file" name="Degree_Certificate[]" id="Degree_Certificate" class="form-control input-sm" accept="image/jpg|image/jpeg|image/png|application/pdf" multiple>
                        </div>
                        <div class="col-md-6">
                        	<label>Master Certificate</label>
                            <input type="file" name="Master_Certificate[]" id="Master_Certificate" class="form-control input-sm" accept="image/jpg|image/jpeg|image/png|application/pdf" multiple>
                        </div>
                        <div class="col-md-6">
                        	<label>Phd Certificate</label>
                            <input type="file" name="Phd_Certificate[]" id="Phd_Certificate" class="form-control input-sm" accept="image/jpg|image/jpeg|image/png|application/pdf" multiple>
                        </div>
                        <div class="col-md-6">
                    		<label>Teaching Certificate</label>
                            <input type="file" name="Teaching_Certificate[]" id="Teaching_Certificate" class="form-control input-sm" accept="image/jpg|image/jpeg|image/png|application/pdf" multiple>
                        </div>
                        <div class="col-md-6">
                        	<label>Other Certificate</label>
                            <input type="file" name="Other_Certificate[]" id="Other_Certificate" class="form-control input-sm" accept="image/jpg|image/jpeg|image/png|application/pdf" multiple>
                        </div>
                        <div class="col-md-6">
                        	<label>Interview evaluation form</label>
                            <input type="file" name="interview_evaluation_form[]" id="interview_evaluation_form" class="form-control input-sm" accept="image/jpg|image/jpeg|image/png|application/pdf" multiple>
                        </div>
                  </div>
                  
                  <?php
				  } ?>
                  <div class="form-actions">
                  		<div class="pull-right">
                        	<?php print form_hidden('user_id', $user_id); ?>
                        	<input type="submit" name="submit" id="submit" value="Save" class="btn btn-success"/>
                        </div>
                  </div>
             
          <?php
		  		print form_close() ."\r\n";
			?>

        
      </div>
    </div>
  </div>
</div>
<!-- END PAGE -->
