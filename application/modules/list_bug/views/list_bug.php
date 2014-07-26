<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?php print base_url(); ?>js/accordion/development-bundle/themes/base/jquery.ui.all.css">
<script src="<?php print base_url(); ?>js/accordion/development-bundle/ui/jquery.ui.core.js"></script>
<script src="<?php print base_url(); ?>js/accordion/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="<?php print base_url(); ?>js/accordion/development-bundle/ui/jquery.ui.accordion.js"></script>
<link rel="stylesheet" href="<?php print base_url(); ?>js/colorbox/example1/colorbox.css" />
<script src="<?php print base_url(); ?>js/colorbox/colorbox/jquery.colorbox.js"></script>
<div>
<h2><?php echo $this->lang->line('supp_p_list_heading'); ?></h2>
<div id="admin">
  <?php $this->load->view('generic/flash_error'); ?>
  <div class="pagination">
    <?php
        print $this->pagination->create_links();
        ?>
  </div>
  <ul>
    <li class="t_grid_title"><a href="<?php print base_url() ."list_bug/index/bug_title/". ($order_by == "bug_title" ? ($sort_order == "asc" ? "desc" : "asc" ) : "asc") ."/". $search ."/0"; ?>" <?php print ($order_by == "bug_title" ? ($sort_order == "asc" ? "class=\"asc\"" : "class=\"desc\"" ) : ""); ?>><?php echo $this->lang->line('supp_p_list_hd_ticket_title'); ?></a></li>
    <li class="t_grid_title"><a href="<?php print base_url() ."list_bug/index/status/". ($order_by == "status" ? ($sort_order == "asc" ? "desc" : "asc" ) : "asc") ."/". $search ."/0"; ?>" <?php print ($order_by == "status" ? ($sort_order == "asc" ? "class=\"asc\"" : "class=\"desc\"" ) : ""); ?>><?php echo $this->lang->line('supp_p_list_hd_status'); ?></a></li>
    <li class="t_grid_title"><a href="<?php print base_url() ."list_bug/index/first_name/". ($order_by == "first_name" ? ($sort_order == "asc" ? "desc" : "asc" ) : "asc") ."/". $search ."/0"; ?>" <?php print ($order_by == "first_name" ? ($sort_order == "asc" ? "class=\"asc\"" : "class=\"desc\"" ) : ""); ?>><?php echo $this->lang->line('supp_p_list_hd_user'); ?></a></li>
    <li class="t_grid_title"><b><?php echo $this->lang->line('supp_p_list_hd_cmm'); ?></b></li>
    <li class="t_grid_title"><b><?php echo $this->lang->line('supp_p_list_hd_update'); ?></b></li>
    <li class="t_grid_title"><b><?php echo $this->lang->line('supp_p_list_hd_attach'); ?></b></li>
  </ul>
  <?php
	
    $i = 1;
    if (isset($bug)) {
    foreach ($bug->result() as $bugs):
    
    ?>
  <div class="clear">
    <?php 
    print form_open('list_bug/action_bug/'. $bugs->system_bug_id .'/'. $offset .'/'. $order_by .'/'. $sort_order .'/'. $search, array('id' => 'edit_school_form'.$i, 'class' => 'edit_school_form')) ."\r\n";
    ?>
    <ul>
      <li class="t_grid_title">
        <?php if($bugs->bug_title){echo $bugs->bug_title;}else{echo '&nbsp;';} ?>
      </li>
      <li class="t_grid_title">
        <?php if($bugs->status){echo $bugs->status;}else{echo '&nbsp;';} ?>
      </li>
      <li class="t_grid_title">
        <?php if($bugs->first_name){echo $bugs->first_name;}else{echo '&nbsp;';} ?>
      </li>
      <li class="t_grid_title"> <a class='ajax' href="<?php print base_url(); ?>list_bug/show_comment/<?php echo $bugs->system_bug_id;?>">
        <div class="comment_icon"> <?php echo $bugs->totcomment; ?> </div>
        </a> </li>
      <li class="t_grid_title"> <a href="<?php print site_url('list_bug/toggle_active/'. $bugs->system_bug_id ."/". $bugs->bug_title ."/". $offset .'/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $bugs->status) ?>" title="(de)activate account" >
        <?php if($bugs->status == 'open'){ ?>
        Close Ticket
        <?php }if($bugs->status == 'close'){ ?>
        Open Ticket
        <?php } ?>
        </a> </li>
      <li class="t_grid_title">
        <?php
				if($bugs->image_path){ 
				?>
        <a href="<?php print site_url('list_bug/downlaod_attachment/'. $bugs->image_path) ?>" title="Download Attachement" > Download </a>
        <?php
				}else{
					echo 'None';
				} 
				?>
      </li>
    </ul>
    <?php
    
    print form_hidden('system_bug_id', $bugs->system_bug_id);
    print form_close() ."\r\n";
    echo '</div>';
    $i++;
    endforeach;
    }
    ?>
  </div>
  <div class="clearboth"></div>
  <div class="pagination">
    <?php
    print $this->pagination->create_links();
    ?>
  </div>
</div>
<div class="clearboth"></div>
