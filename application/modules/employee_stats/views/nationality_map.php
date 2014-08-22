<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="<?php print base_url(); ?>assets/plugins/jquery-craftmap/css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php print base_url(); ?>assets/plugins/jquery-craftmap/js/jquery.latest.min.js"></script>
<link href="<?php print base_url(); ?>assets/plugins/jquery-craftmap/css/demo.css" rel="stylesheet" />
<script src="<?php print base_url(); ?>assets/plugins/jquery-craftmap/js/craftmap.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){	
	$('.demo2').craftmap({
		fullscreen: true,
		image: {
			width: 1700,
			height: 898
		}
	});	
});
</script>
</head>
<body>
<div class="demo2" style="width:800px;height:400px;"> <img src="<?php print base_url(); ?>assets/plugins/jquery-craftmap/dotted.jpg" class="imgMap" />
<?php 
if($nationality) {
	foreach($nationality->result_array() as $_nationality){
		if(!in_array($_nationality['nationality'],$country_to_display))
			continue;
?>
    <div class="marker" id="<?php echo str_replace(' ','',$_nationality['nationality']) ?>" data-coords="<?php echo $_nationality['coords'] ?>">
        <h3><?php echo $_nationality['nationality'] ?></h3>
        <div class="widget-stats">
          <div class="wrapperstats"> <span class="item-title">Male</span> <span class="item-count animate-number semi-bold" data-value="<?php echo ($_nationality['male_count']=='')?'0':$_nationality['male_count'] ?>" data-animation-duration="700"><?php echo ($_nationality['male_count']=='')?'0':$_nationality['male_count'] ?></span> </div>
        </div>
        <div class="widget-stats">
          <div class="wrapperstats"> <span class="item-title">Female</span> <span class="item-count animate-number semi-bold" data-value="<?php echo ($_nationality['female_count']=='')?'0':$_nationality['female_count'] ?>" data-animation-duration="700"><?php echo ($_nationality['female_count']=='')?'0':$_nationality['female_count'] ?></span> </div>
        </div>
        <div class="widget-stats ">
          <div class="wrapperstats last"> <span class="item-title">Total</span> <span class="item-count animate-number semi-bold" data-value="<?php echo $_nationality['total'] ?>" data-animation-duration="700"><?php echo $_nationality['total'] ?></span> </div>
        </div>
      </div>
<?php
	}
} ?>
</div>
<div class="controls">
	<?php 
	if($nationality) {
		foreach($nationality->result_array() as $_nationality){
			if(!in_array($_nationality['nationality'],$country_to_display))
			continue;
	?>
    	<a href="#" rel="<?php echo str_replace(' ','',$_nationality['nationality']) ?>"><?php echo $_nationality['nationality']; ?></a> 
		<?php
        }
    } ?>
</div>
</body>
</html>
