<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta charset="utf-8">
<title><?php print Settings_model::$db_config['site_title']; ?>:<?php print $template['title']; ?></title>
<?php print $template['metadata']; ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<link href="<?php print base_url(); ?>assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?php print base_url(); ?>assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
<!--<link href="<?php print base_url(); ?>assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />-->
<link rel="stylesheet" href="<?php print base_url(); ?>assets/plugins/ios-switch/ios7-switch.css" type="text/css" media="screen">
<link href="<?php print base_url(); ?>assets/plugins/jquery-slider/css/jquery.sidr.light.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?php print base_url(); ?>assets/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?php print base_url(); ?>assets/plugins/jquery-datatable/css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url(); ?>assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?php print base_url(); ?>assets/plugins/jquery-datatable/css/dataTables.colVis.css" rel="stylesheet" type="text/css"/>

<link href="<?php print base_url(); ?>assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url(); ?>assets/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>

<link href="<?php print base_url(); ?>assets/plugins/jquery-notifications/css/messenger.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?php print base_url(); ?>assets/plugins/jquery-notifications/css/messenger-theme-flat.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?php print base_url(); ?>assets/plugins/jquery-notifications/css/location-sel.css" rel="stylesheet" type="text/css" media="screen"/>

<link href="<?php print base_url(); ?>assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url(); ?>assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url(); ?>assets/css/responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php print base_url(); ?>assets/css/custom-icon-set.css" rel="stylesheet" type="text/css"/>

<!--<link rel="stylesheet" href="<?php print base_url(); ?>css/reset.css?t=1" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php print base_url(); ?>css/flexigrid.pack.css?t=1" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php print base_url(); ?>css/themes/<?php print Settings_model::$db_config['default_theme']; ?>/style.css" type="text/css" media="screen" />-->
<link rel="stylesheet" href="<?php print base_url(); ?>js/jquery_datatables_editable/media/jAlert/jquery.alerts.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php print base_url(); ?>js/timepicker/jquery.ui.timepicker.css?v=0.3.1" type="text/css" />
<link rel="stylesheet" href="<?php print base_url(); ?>js/jquery_datatables_editable/media/css/themes/smoothness/jquery-ui-1.7.2.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php print base_url(); ?>css/themes/<?php print Settings_model::$db_config['default_theme']; ?>/custom.css" type="text/css" media="screen" />

<?php 
$controller_name = $this->router->fetch_class(); 
?>
<?php
$this->load->view('generic/js_base_url',array('controller_name'=>$controller_name));
$this->load->view('generic/js_language_files');
?>
<script src="<?php print base_url(); ?>assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script> 
<!--<script type="text/javascript" src="<?php print base_url(); ?>js/jQuery/jquery-1.6.1.min.js"></script>

<script type="text/javascript" src="<?php print base_url(); ?>js/jQuery/jquery-qtip-1.0.0-rc3.min.js"></script>
<script type="text/javascript" src="<?php print base_url(); ?>js/jQuery/jq_functions.js"></script>


<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.form.js"></script>
<script src="<?php print base_url(); ?>js/datepicker/development-bundle/ui/jquery.ui.core.js"></script>
<script src="<?php print base_url(); ?>js/datepicker/development-bundle/ui/jquery.ui.datepicker.js"></script>-->
<!--<style type="text/css" media="screen">
	@import "<?php print base_url(); ?>js/jquery_datatables_editable/media/css/demo_page.css";
	@import "<?php print base_url(); ?>js/jquery_datatables_editable/media/css/demo_table.css";
	@import "<?php print base_url(); ?>js/jquery_datatables_editable/media/css/demo_table_jui.css";
	@import "<?php print base_url(); ?>js/jquery_datatables_editable/media/css/themes/base/jquery-ui.css";
	@import "<?php print base_url(); ?>js/jquery_datatables_editable/media/css/themes/smoothness/jquery-ui-1.7.2.custom.css";
	@import "<?php print base_url(); ?>js/jquery_datatables_editable/media/jAlert/jquery.alerts.css";
	@import "<?php print base_url(); ?>js/jquery_datatables_editable/media/jAlert/jquery.alerts.css";
</style>-->
<?php
	$ci =& get_instance();
	$cotroller = $ci->router->fetch_class();
	$cotroller_methodname = $ci->router->fetch_method();
	if($cotroller == "attendance_report" || $cotroller == "late_attendance" || $cotroller == "teachers_attendance")
	{
	?>
		<!--<script src="<?php print base_url(); ?>js/flexigrid.js"></script>-->
<?php
	}
	else if($cotroller != "profile")
	{
	?>
		<!--<script type="text/javascript" src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/complete.js"></script>-->
<?php
		if($cotroller == "list_grade" || $cotroller == "verify_grade")
		{
		?>
<!--<script src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/jquery.dataTables.js" type="text/javascript"></script>-->
<?
		}
		else
		{
		?>
		<!--<script src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>-->
<?
		}
		?>
<!--<script type="text/javascript" src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/jquery.dataTables.editable.js"></script>
<script src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/jquery.jeditable.js" type="text/javascript"></script>
<script src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/jquery-ui.js" type="text/javascript"></script>
<script src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php print base_url(); ?>js/jquery_datatables_editable/media/jAlert/jquery.alerts.js" type="text/javascript"></script>
<script src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
<script src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>-->
<script type="text/javascript">
$(document).ready(function() {
	$.fn.dataTableExt.oApi.fnStandingRedraw = function(oSettings) {
		if(oSettings.oFeatures.bServerSide === false){
			var before = oSettings._iDisplayStart;
	 
			oSettings.oApi._fnReDraw(oSettings);
	 
			// iDisplayStart has been reset to zero - so lets change it back
			oSettings._iDisplayStart = before;
			oSettings.oApi._fnCalculateEnd(oSettings);
		}
		  
		// draw the 'current' page
		oSettings.oApi._fnDraw(oSettings);
	};
});
</script>
<?php
	}
	?>
<!--<link rel="stylesheet" href="<?php print base_url(); ?>js/colorbox/example1/colorbox.css?n=1" />
<script src="<?php print base_url(); ?>js/colorbox/colorbox/jquery.colorbox.js"></script>-->
</head>
<?php $controller_name = $this->router->fetch_class();  ?>
<body class="<?php if($controller_name == "login" || $controller_name == "forgot_password" || $controller_name == "reset_password"){ echo 'error-body no-top lazy'; } ?>" >
	<?php print $template['partials']['header']; ?>
	<!-- BEGIN CONTENT -->
	<div class="page-container row-fluid">
		<!-- BEGIN SIDEBAR -->
		<?php print $template['partials']['sidebar']; ?>
		<!-- END SIDEBAR --> 
		<!-- BEGIN PAGE CONTAINER-->
		<div class="<?php if($controller_name == "login" || $controller_name == "forgot_password" || $controller_name == "reset_password"){ echo 'container'; }else{	echo 'page-content'; } ?>"> 
			<div class="content">
				<?php print $template['body']; ?>
			</div>
		</div>
		<!-- END PAGE CONTAINER -->
	</div>
	<!-- END CONTENT -->
	
	<!-- BEGIN CORE JS FRAMEWORK-->
    <?php //if(($cotroller != "grade_report") && ($cotroller != "attendance")) { ?>
	<script src="<?php print base_url(); ?>assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script> 
    <?php //} ?>
	<script src="<?php print base_url(); ?>assets/plugins/boostrapv3/js/bootstrap.min.js?ver=1.0" type="text/javascript"></script> 
	<script src="<?php print base_url(); ?>assets/plugins/breakpoints.js" type="text/javascript"></script> 
	<script src="<?php print base_url(); ?>assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
	<script src="<?php print base_url(); ?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="<?php print base_url(); ?>assets/plugins/jquery-slider/jquery.sidr.min.js" type="text/javascript"></script>  
	<script src="<?php print base_url(); ?>assets/plugins/pace/pace.min.js" type="text/javascript"></script>
	<script src="<?php print base_url(); ?>assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
	<script src="<?php print base_url(); ?>assets/plugins/jquery-block-ui/jqueryblockui.js" type="text/javascript"></script> 
    
    <script type="text/javascript" src="<?php print base_url(); ?>js/underscore-min.js"></script>
    <script type="text/javascript" src="<?php print base_url(); ?>js/backbone-min.js"></script>


	<script src="<?php print base_url(); ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
	<!--<script src="<?php print base_url(); ?>assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>-->
	<script src="<?php print base_url(); ?>assets/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
	<script src="<?php print base_url(); ?>assets/plugins/jquery-datatable/js/jquery.dataTables.min.js" type="text/javascript" ></script>
	<script src="<?php print base_url(); ?>assets/plugins/jquery-datatable/extra/js/TableTools.min.js" type="text/javascript" ></script>
	<script src="<?php print base_url(); ?>assets/plugins/datatables-responsive/js/datatables.responsive.js" type="text/javascript" ></script>
	<script src="<?php print base_url(); ?>assets/plugins/jquery-datatable/js/dataTables.colVis.js" type="text/javascript" ></script>
	<script src="<?php print base_url(); ?>assets/plugins/boostrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript" ></script>
	<script src="<?php print base_url(); ?>assets/plugins/datatables-responsive/js/lodash.min.js" type="text/javascript" ></script>
    <script src="<?php print base_url(); ?>assets/plugins/jquery-notifications/js/messenger.min.js" type="text/javascript"></script>
	<script src="<?php print base_url(); ?>assets/plugins/jquery-notifications/js/messenger-theme-future.js" type="text/javascript"></script>

	<!-- END CORE JS FRAMEWORK --> 
    
    <script type="text/javascript" src="<?php print base_url(); ?>assets/plugins/jquery-notifications/js/demo/location-sel.js"></script>
	<script type="text/javascript" src="<?php print base_url(); ?>assets/plugins/jquery-notifications/js/demo/theme-sel.js"></script>
    <script type="text/javascript" src="<?php print base_url(); ?>assets/plugins/jquery-notifications/js/demo/demo.js"></script>
		
	<script src="<?php print base_url(); ?>assets/js/form_elements.js" type="text/javascript"></script>
	<script src="<?php print base_url(); ?>assets/js/datatables.js" type="text/javascript"></script>
	<script src="<?php print base_url(); ?>assets/js/tabs_accordian.js" type="text/javascript"></script>
	
	<!-- BEGIN CORE TEMPLATE JS --> 
	<script src="<?php print base_url(); ?>assets/js/core.js" type="text/javascript"></script> 
	<!-- END CORE TEMPLATE JS --> 
	<script type="text/javascript" src="<?php print base_url(); ?>js/jQuery/jq_functions.js?t=1"></script>
	<script type="text/javascript" src="<?php print base_url(); ?>js/custom.js"></script>
	<script type="text/javascript" src="<?php print base_url(); ?>js/jquery.validate.js"></script>
	<script type="text/javascript" src="<?php print base_url(); ?>js/validation.js?t=6"></script>
	<script type="text/javascript" src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/jquery.dataTables.editable.js"></script>
	<script src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/jquery.jeditable.js" type="text/javascript"></script>
	<script src="<?php print base_url(); ?>js/jquery_datatables_editable/media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
    <script src="<?php print base_url(); ?>js/jquery_datatables_editable/media/jAlert/jquery.alerts.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php print base_url(); ?>js/timepicker/jquery.ui.timepicker.js?v=0.3.1"></script>

	<?php print $template['partials']['footer']; ?>
</body>
</html>
