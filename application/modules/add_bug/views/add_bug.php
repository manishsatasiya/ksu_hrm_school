<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
print form_open_multipart('add_bug/add', array('id' => 'add_bug_form')) ."\r\n";
$this->load->view('generic/flash_error');
?>

<div class="add_bug_form">
  <h2><?php echo $this->lang->line('supp_p_add_heading'); ?></h2>
  <div id="bugimg"></div>
  <div class="grey_box">
    <div class="left">
      <div class="form_label"><?php print form_label($this->lang->line('supp_p_field_ticket_title'), 'reg_bug_title'); ?></div>
      <div class="input_box_thin"><?php print form_input(array('name' => 'bug_title', 'id' => 'reg_bug_title', 'value' => $this->session->flashdata('bug_title'), 'class' => 'input_text qtip_bug_title')); ?><br/>
      </div>
    </div>
    <div class="clear"></div>
  </div>
  <div class="grey_box">
    <div class="left">
      <div class="form_label"><?php print form_label($this->lang->line('supp_p_field_description'), 'reg_description'); ?></div>
      <div class="input_box_thin"><?php print form_textarea(array('name' => 'description', 'id' => 'reg_description', 'value' => $this->session->flashdata('description'), 'class' => 'input_textarea qtip_description', 'style' => 'width:550px;')); ?></div>
      <div id="email_valid"></div>
      <div id="email_taken"></div>
    </div>
    <div class="clear"></div>
  </div>
  <div class="grey_box">
    <div class="left">
    <div class="input_box_thin">
      <label class="file-upload"> <span><strong><?php echo $this->lang->line('supp_p_attch_btn'); ?></strong></span> <?php print form_upload(array('name' => 'attchment[]', 'id' => 'reg_attchment', 'value' => $this->session->flashdata('attchment'), 'class' => 'input_text qtip_attchment', 'multiple'=>'multiple')); ?> </label>
    </div>
    <div id="email_valid"></div>
    <div id="email_taken"></div>
  </div>
  <div class="clear"></div>
</div>
<?php print form_submit(array('name' => 'bug_submit', 'id' => 'bug_submit', 'value' => $this->lang->line('supp_p_submit_btn'), 'class' => 'input_submit')) ."<br />\r\n";
	print form_close() ."\r\n";
	?>
</div>
