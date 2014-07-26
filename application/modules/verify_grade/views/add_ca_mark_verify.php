<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js"></script>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open('verify_grade/add_ca_mark/'.$rowdata->grade_type_exam_id.'/'.$rowdata->student_uni_id, array('action'=>'post','id' => 'add_cagrade_form_datatable','name'=>'formmain')) ."\r\n";
print form_hidden('studentUniID', $rowdata->student_uni_id);
print form_hidden('exam_type_id',$rowdata->grade_type_exam_id);
$arr_exam_status = array(""=>"Please Select","Present"=>"Present","Absent"=>"Absent","Cheating"=>"Cheating","Makeup"=>"Makeup","IELTS"=>"IELTS");
?>

<div class="modal-header">
  <h2>Add Grade Marks</h2>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
  <br />
</div>
<div class="modal-body">
<?php $this->load->view('generic/flash_error'); ?>
<div class="containerfdfdf"></div>
<div class="row form-row">
  <div class="col-md-6">
    <div class="form-group">
    <div class="form_label">Marks 1</div>
    <div class="input_box_thin"><?php print form_input(array('name' => 'exam_marks', 'id' => 'reg_exam_marks', 'value' => ($rowdata)?$rowdata->exam_marks:$this->session->flashdata('exam_marks'), 'class' => 'input_text1 qtip_exam_marks')); ?></div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
    <div class="form_label">Marks 2</div>
    <div class="input_box_thin"><?php print form_input(array('name' => 'exam_marks_2', 'id' => 'reg_exam_marks_2', 'value' => ($rowdata)?$rowdata->exam_marks_2:$this->session->flashdata('exam_marks_2'), 'class' => 'input_text1 qtip_exam_marks_2')); ?></div>
    </div>
  </div>
  <div style="clear:both"></div>
</div>
<div class="row form-row">
  <div class="col-md-6">
    <div class="form-group">
    <div class="form_label">Marks 3(Optional)</div>
    <div class="input_box_thin"><?php print form_input(array('name' => 'exam_marks_3', 'id' => 'reg_exam_marks_3', 'value' => ($rowdata)?$rowdata->exam_marks_3:$this->session->flashdata('exam_marks_3'), 'class' => 'input_text1 qtip_exam_marks_3')); ?></div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
    <div class="form_label">Attendance</div>
    <div class="input_box_thin"><?php print form_dropdown('exam_status',$arr_exam_status,($rowdata)?$rowdata->exam_status:$this->session->flashdata('exam_status'),'id="reg_exam_status" class="input_text1 qtip_exam_status"'); ?></div>
    </div>
  </div>
  <div style="clear:both"></div>
</div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<input type="submit" name="student_submit" id="student_submit" value="Save" class="btn btn-primary"/>
</div>
<?php
print form_close() ."\r\n";
?>
