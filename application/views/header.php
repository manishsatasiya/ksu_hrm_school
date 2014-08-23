<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- BEGIN HEADER -->

<div class="header navbar navbar-inverse"> 

	<!-- BEGIN TOP NAVIGATION BAR -->

	<div class="navbar-inner">

		<!-- BEGIN NAVIGATION HEADER -->

		<div class="header-seperation"> 

			<!-- BEGIN MOBILE HEADER --> 

			<ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">	

				<li class="dropdown">

					<a id="main-menu-toggle" href="#main-menu" class="">

						<div class="iconset top-menu-toggle-white"></div>

					</a>

				</li>		 

			</ul>

			<!-- END MOBILE HEADER -->

			<!-- BEGIN LOGO -->	

			<!--<a href="#">

				<img src="<?php print base_url() ?>assets/img/logo.png" class="logo" alt="" data-src="<?php print base_url() ?>assets/img/logo.png" width="" height=""/>

			</a>-->

			<!-- END LOGO --> 

			<!-- BEGIN LOGO NAV BUTTONS -->

			<ul class="nav pull-right notifcation-center">	

				<li class="dropdown" id="header_task_bar">

					<a href="<?php print base_url() ?>" class="dropdown-toggle active" data-toggle="">

						<div class="iconset top-home"></div>

					</a>

				</li>

				<!-- BEGIN MOBILE CHAT TOGGLER -->

				<li class="dropdown" id="portrait-chat-toggler" style="display:none">

					<a href="#sidr" class="chat-menu-toggle">

						<div class="iconset top-chat-white"></div>

					</a>

				</li>

				<!-- END MOBILE CHAT TOGGLER -->				        

			</ul>

			<!-- END LOGO NAV BUTTONS -->

		</div>

		<!-- END NAVIGATION HEADER -->

		<!-- BEGIN CONTENT HEADER -->

		<div class="header-quick-nav"> 

			<!-- BEGIN HEADER LEFT SIDE SECTION -->

			<div class="pull-left"> 

				<!-- BEGIN SLIM NAVIGATION TOGGLE -->

				<ul class="nav quick-section">

					<li class="quicklinks">

						<a href="#" class="" id="layout-condensed-toggle">

							<div class="iconset top-menu-toggle-dark"></div>

						</a>

					</li>

				</ul>

				<!-- END SLIM NAVIGATION TOGGLE -->								

			</div>

			<!-- END HEADER LEFT SIDE SECTION -->

			<!-- BEGIN HEADER RIGHT SIDE SECTION -->

			<div class="pull-right"> 

				<div class="chat-toggler">	

					<!-- BEGIN NOTIFICATION CENTER -->

					<!--<a href="#" class="dropdown-toggle" id="my-task-list" data-placement="bottom" data-content="" data-toggle="dropdown" data-original-title="">-->

						<div class="user-details"> 

							<div class="username">Welcome, <?php echo $this->session->userdata('first_name');  ?> &nbsp; &nbsp;</div>						

						</div> 

						<!--<div class="iconset top-down-arrow"></div>-->

					<!--</a>-->

					<!--<div id="notification-list" style="display:none">

						<div style="width:130px">

							<a href="<?php print site_url('auth/logout'); ?>"><i class="fa fa-power-off"></i>&nbsp;&nbsp;<?php echo $this->lang->line('logout'); ?></a>	

						</div>				

					</div>-->

					<!-- END NOTIFICATION CENTER -->

					<!-- BEGIN PROFILE PICTURE -->

					<div class="profile-pic"> 

                    	<?php 

						$profile_picture = get_profile_pic();

						$profile_picture_75 = $profile_picture[75];

						?>

						<img src="<?php print $profile_picture_75; ?>" alt="" data-src="<?php print $profile_picture_75; ?>" data-src-retina="" width="35" height="35" /> 

					</div>   

					<!-- END PROFILE PICTURE -->     			

				</div>

                <ul class="nav quick-section ">

			<li class="quicklinks"> 

				<a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">						

					<div class="iconset top-settings-dark "></div> 	

				</a>

				<ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">

                	<li><a href="<?php print site_url('list_user/edit_profile/'.$this->session->userdata('user_id')); ?>">My Profile</a></li>

                     <li><a href="<?php print site_url('my_attendance'); ?>">My Attendance</a></li>
					 <li><a href="<?php print site_url('my_inductions'); ?>">My Inductions</a></li>
                   <li><a href="<?php print site_url('schedule'); ?>">My Schedule</a></li>
   
                     <li class="divider"></li>

                  <li><a href="<?php print site_url('auth/logout'); ?>"><i class="fa fa-power-off"></i>&nbsp;&nbsp;<?php echo $this->lang->line('logout'); ?></a>	</li>

               </ul>

			</li> 

		</ul>

				

			</div>

			<!-- END HEADER RIGHT SIDE SECTION -->

		</div> 

		<!-- END CONTENT HEADER -->

	</div>

	<!-- END TOP NAVIGATION BAR --> 

</div>

<!-- END HEADER -->