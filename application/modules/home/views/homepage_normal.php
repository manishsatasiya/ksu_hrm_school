<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script src="<?php print base_url(); ?>assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
<div class="row spacing-bottom 2col">
  <div class="col-md-3 col-sm-6 spacing-bottom-sm spacing-bottom">
    <div class="tiles blue added-margin">
      <div class="tiles-body">
        <div class="controller"> </div>
        <div class="tiles-title"> Total Student </div>
        <div class="heading"> <span class="animate-number" data-value="<?=$student_of_teacher;?>" data-animation-duration="1200">0</span></div>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 spacing-bottom-sm spacing-bottom">
    <div class="tiles green added-margin">
      <div class="tiles-body">
        <div class="controller"> </div>
        <div class="tiles-title"> Course class </div>
        <div class="heading"> <span class="animate-number" data-value="<?=$count_course_class_of_teacher;?>" data-animation-duration="1000">0</span> </div>
        <?php
				if($count_course_class_of_teacher > 0){ ?>
        <div class="description">
          <?php
						foreach($course_class_of_teacher_arr as $course_class){ ?>
          <a href="<?php print base_url(); ?>attendance/index/course_class_id/asc/post/0/0/<?php echo $course_class; ?>"><span class="text-white mini-description "><?php echo $course_class; ?></span></a><br />
          <?php
						}?>
        </div>
        <?php
				} ?>
      </div>
    </div>
  </div>
</div>
