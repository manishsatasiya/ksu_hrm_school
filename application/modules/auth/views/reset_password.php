<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="lockscreen-wrapper animated  flipInX">
<div class="row ">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-4 col-xs-offset-2">
    	<?php
		if ($this->session->flashdata('message')) {
		?>
    	<div class="alert alert-success ">
		<?php
		$this->load->view('generic/flash_error');
		?>
		</div>
		<?php
		}
		?>
		<p class="membership_link"><a href="<?php print base_url(); ?>" class="btn btn-primary"><?php print $this->lang->line('login'); ?></a></p>
    </div>
</div>
</div>