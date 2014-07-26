<div id="error">
<?php
if ($this->session->flashdata('message')) {
?>
<div class="error_box"><?php print $this->session->flashdata('message'); ?></div>
<?php
}
?>
</div>
