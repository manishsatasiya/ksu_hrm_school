<?php 
$arrIcon = array("home" => "icon-custom-home",
				"my_profile" => "fa fa-user",
				"school" => "fa fa-shield",
				"student" => "fa fa-group",
				"teacher" => "fa fa-th-large",
				"course" => "fa fa-file-text",
				"attendance" => "fa fa-pencil-square-o",
				"grades" => "fa fa-plus-circle",
				"role" => "fa fa-users",
				"privilege" => "fa fa-thumbs-up",
				"logs" => "fa fa-columns");

$arrMenu = get_rolewise_priviledge();
$controller_name = "";
$controller_name = $this->router->fetch_class(); 
?>
<!-- BEGIN MENU -->
<div class="page-sidebar" id="main-menu"> 
	<div class="page-sidebar-wrapper" id="main-menu-wrapper">
		<!-- BEGIN MINI-PROFILE -->
		<div class="user-info-wrapper">	
			<div class="profile-wrapper">
				<img src="assets/img/profiles/avatar.jpg" alt="" data-src="assets/img/profiles/avatar.jpg" data-src-retina="assets/img/profiles/avatar2x.jpg" width="69" height="69" />
			</div>
			<div class="user-info">
				<div class="greeting"><?php echo $this->lang->line('welcome'); ?></div>
				<div class="username"><?php echo $this->session->userdata('username');  ?> </div>
			</div>
		</div>
		<!-- END MINI-PROFILE -->
		<!-- BEGIN SIDEBAR MENU -->	
		<ul id="nav1">
		<?php 
			if($arrMenu){
				foreach ($arrMenu as $key => $val){
					$iconclass = "";
					
					if(array_key_exists($key,$arrIcon))
						$iconclass = $arrIcon[$key];
						
					$current = '';
					foreach ($arrMenu[$key]['sub_menu'] as $key1 => $val1){
						foreach ($val1 as $key2 => $val2){
							if($controller_name == $key2){
								$current = 'class="active"';
							}
						}
					}
			?>
					<li <?php echo $current; ?>>
						<a href="<?php if($arrMenu[$key]['parent_id'] == '0'){ print site_url($arrMenu[$key]['controller']); }else{ print '#'; } ?>">
							<i class="<?php echo $iconclass;?>"></i>
							<span class="title"><?php print $this->lang->line($key);?></span>
							<?php 
							if(isset($arrMenu[$key]['sub_menu']) && $arrMenu[$key]['parent_id'] != '0'){ ?>
							<span class="arrow"></span>
							<?php
							} ?>
						</a>
						<?php 
						if(isset($arrMenu[$key]['sub_menu']) && $arrMenu[$key]['parent_id'] != '0'){ ?>
							<ul class="sub-menu">
								<?php
								foreach ($arrMenu[$key]['sub_menu'] as $key1 => $val1){ 
									foreach ($val1 as $key2 => $val2){ ?>
										<li><a href="<?php print site_url($key2); ?>"><?php print $this->lang->line($val2);?></a></li>
										<?php
									} 
								} ?>
							</ul>
						<?php
						} ?>
					</li>
		<?php
				}
			} 
			?>
		</ul>
		<div class="side-bar-widgets">
        	<p class="menu-title"></p>
      	</div>
		<div class="clearfix"></div>
		<!-- END SIDEBAR MENU -->
		
	</div>	
</div>

<!-- BEGIN SCROLL UP HOVER -->
<a href="#" class="scrollup">Scroll</a>
<!-- END SCROLL UP HOVER -->
<!-- END MENU -->
<!-- BEGIN SIDEBAR FOOTER WIDGET -->
<div class="footer-widget">		
	<div class="pull-right">	
		<a href="<?php print site_url('auth/logout'); ?>" title="<?php echo $this->lang->line('logout'); ?>"><i class="fa fa-power-off"></i></a>
	</div>
</div>
<!-- END SIDEBAR FOOTER WIDGET -->