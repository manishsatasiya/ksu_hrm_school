<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Createpdf extends Private_Controller {
    public function __construct()
    {
        parent::__construct();		
		$this->load->model('list_course_class/list_student_class_model');
		$this->load->model('list_school_year/list_school_year_model');
		$this->load->model('get_pdf_model');
    }
    
    public function index($campus_id="",$campus_name="general")
    {
		$section = '';
		$filename = $campus_name;
		$week = "";
		$am_start = "";
		$am_end = "";
		$pm_start = "";
		$pm_end = "";
		$day1 = "";
		$day2 = "";
		$day3 = "";
		$day4 = "";
		$day5 = "";
		$date_title = "";
		$export_type = "normal";
		$am_start_time = '';
		$am_time = '';
		$pm_start_time = '';
		$pm_time = '';
		$querytime = $this->get_pdf_model->get_enable_school_time();
		if($querytime){
			foreach ($querytime->result() as $times){
				$am_start_time = $times->am_start_time;
				$am_time = $times->am_time;
				$pm_start_time = $times->pm_start_time;
				$pm_time = $times->pm_time;
			}
		}
		
		if($this->session->userdata('role_id') == '3')
		{
			$arrActivateTime = $this->get_pdf_model->get_activation_time();
			$activate_time = "00:00:00";
			
			if(isset($arrActivateTime[0]["activation_time"]))
				$activate_time = $arrActivateTime[0]["activation_time"];
				
			$enable_week = $this->get_pdf_model->get_enableweek($activate_time);
			
			$no_of_day = 5;
			$pdfdata = $this->list_school_year_model->get_attendance_pdf_settings();
			if($pdfdata){
				$week = $enable_week[0]["week_id"];
				$am_start = $am_start_time;
				$am_end = $am_time;
				$pm_start = $pm_start_time;
				$pm_end = $pm_time;
				$arrLastDate = explode("-",$enable_week[0]["last_date"]);
				
				if($enable_week[0]["no_of_day"] > 0)
					$no_of_day = $enable_week[0]["no_of_day"];
					
				$pdfdata->day1  = date('Y-m-d',mktime(0, 0, 0, $arrLastDate[1]  , $arrLastDate[2]-4, $arrLastDate[0]));
				$pdfdata->day2  = date('Y-m-d',mktime(0, 0, 0, $arrLastDate[1]  , $arrLastDate[2]-3, $arrLastDate[0]));
				$pdfdata->day3  = date('Y-m-d',mktime(0, 0, 0, $arrLastDate[1]  , $arrLastDate[2]-2, $arrLastDate[0]));
				$pdfdata->day4  = date('Y-m-d',mktime(0, 0, 0, $arrLastDate[1]  , $arrLastDate[2]-1, $arrLastDate[0]));
				$pdfdata->day5  = date('Y-m-d',mktime(0, 0, 0, $arrLastDate[1]  , $arrLastDate[2], $arrLastDate[0]));
				
				if($enable_week[0]["no_of_day"] >= 5)
					$day1 = date('D d M',strtotime($pdfdata->day1));
				if($enable_week[0]["no_of_day"] >= 4)	
					$day2 = date('D d M',strtotime($pdfdata->day2));
				if($enable_week[0]["no_of_day"] >= 3)	
					$day3 = date('D d M',strtotime($pdfdata->day3));
				if($enable_week[0]["no_of_day"] >= 2)	
					$day4 = date('D d M',strtotime($pdfdata->day4));
				if($enable_week[0]["no_of_day"] >= 1)	
					$day5 = date('D d M',strtotime($pdfdata->day5));
					
				$date_title = date('F jS',strtotime($enable_week[0]["last_date"]));
				$export_type = $pdfdata->export_type;
			}
		}
		else
		{
			$section = '';
			$filename = $campus_name;
			$week = $_POST["week"];
			
			$am_start = $am_start_time;
			$am_end = $am_time;
			$pm_start = $pm_start_time;
			$pm_end = $pm_time;
			
			if(isset($_POST["section"])){
			$section = (int)$_POST["section"];
			}
			$day1 = date('D d M',strtotime($_POST["day1"]));
			$day2 = date('D d M',strtotime($_POST["day2"]));
			$day3 = date('D d M',strtotime($_POST["day3"]));
			$day4 = date('D d M',strtotime($_POST["day4"]));
			$day5 = date('D d M',strtotime($_POST["day5"]));
			
			$date_title = date('F jS',strtotime($_POST["day6"]));
			$export_type = $_POST["export_type"];
		}
				
		$export_type_color = "#fffff";
		$export_type_color_day = "#FFCCCC";
		$export_type_color_day_val = "#ffffff";
		
		if($export_type == "black")
		{
			$export_type_color = "#ffffff";
			$export_type_color_day = "#ffffff";
			$export_type_color_day_val = "#ffffff";
		}
		
		if($campus_name == "")
			$campus_name = "general";
			
		// As PDF creation takes a bit of memory, we're saving the created file in /downloads/pdfreports/
		$pdfFilePath = "downloads\pdfreports\\".$filename.".pdf";
		$pdfFilePath = "downloads/pdfreports/".$filename.".pdf";
		$data['page_title'] = 'Attendance PDF Sheet'; // pass data to the view
		 
		ini_set('max_execution_time', 0);
		ini_set('memory_limit','10240M');
		
		$searcharray = array();
		if($campus_id != "")
			$searcharray[] = array("p1.campus_id"=>$campus_id);
		
		$search_data = array();
		if($section != ''){
			$searcharray[] = array('course_section.section_title' => $section);
		}
		
		if($this->session->userdata('role_id') == '3')
		{
			$searcharray[] = array('primary_teacher_id'=>$this->session->userdata('user_id'));
		}
		$data = $this->list_student_class_model->get_course_class(0, 0, $order_by="LENGTH(section_title),section_title", $sort_order="asc",$search_data, $searcharray);
		
		if(!empty($data)) 
		{
			$this->load->library('pdf');
			$pdf = $this->pdf->load();
			$pdf->SetFooter('|{PAGENO}|'.date(DATE_RFC822));
			
			foreach($data->result() as $courseclasses)
			{
				$section_id = $courseclasses->section_id;
				$course_class_id = $courseclasses->course_class_id;
				$student_data = $this->list_student_class_model->get_class_wise_student($section_id);
				if($student_data) 
				{
					if($export_type == "black")
					{
						$html = '<table width="100%" style="border-bottom: 1px solid #999; vertical-align: bottom; font-family: arial; font-size: 9pt; color: #999;"><tr>
								<td width="5%" align="left"><img src="'.base_url().'images/logo.png" height="80px"/></td>
								<td width="45%" align="left">
									<b>Primary Teacher\'s Name: </b>'.$courseclasses->first_name.'<br/><br/>
								<b>Secondary Teacher\'s Name: </b>'.$courseclasses->sec_name.'<br/>
								</td>
								<td width="50%" align="right">
									<b>AM Shift Submission (Thursday) : '.$am_start.' - '.$am_end.'</b><br><br>
									<b>PM Shift Submission (Thursday) : '.$pm_start.' - '.$pm_end.'</b><br> 
								</td>
								</tr></table>
								<table cellpadding="5" cellspacing="0" style="font-family:arial;" width="85%">
									<tr><td bgcolor="#BBBBBB" width="5%">Section:</td>
										<td>'.$courseclasses->section_title.'</td>
										<td bgcolor="#BBBBBB" width="9%">Classrom #:</td>
										<td width="8%">'.$courseclasses->class_room_title.'</td>
										<td bgcolor="#BBBBBB" width="5%">Course:</td>
										<td width="8%">'.$courseclasses->course_title.'</td>
										<td bgcolor="#BBBBBB" width="5%">Shift:</td>
										<td>'.$courseclasses->shift.'</td>
										<td bgcolor="#BBBBBB" width="5%">Campus:</td>
										<td>'.$courseclasses->campus.'</td>
										<td bgcolor="#BBBBBB" width="5%">Building:</td>
										<td>'.$courseclasses->buildings.'</td>
									</tr>
									<tr>
										<td colspan="12" style="padding-left:40%" ><br><span style="font-family:arial;float:left"><b>KEY:P =</b>
								<span style="font-family:arial;font-size:10px;">Present (0-6 min.s)</span>, <b>L =</b><span style="font-size:11px;"> Late (7-10 min.s)</span>, <b>A =</b><span style="font-size:11px;">Absent (10+ min.s)</span>, <b>Note: </b><span style="font-size:11px;">2 L\'s= 1 A</span></span></td>
									</tr>
								</table>
								
									<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom: 0px solid #999; vertical-align: bottom; font-family:arial; font-size: 9pt; color: #000000;">
									
									<tr>
										<th colspan="3" bgcolor="#777" style="color:#fff; font-size:13px;border-bottom:1px solid #999; border-top:1px solid #999;border-left:1px solid #999; border-right:1px solid #999;">'.$date_title.' Attendance Register - Week ( '.$week.' )</th>
										<th rowspan="2" bgcolor="#b1db7b" width="3%" style="border-bottom:1px solid #999; border-top:1px solid #999;border-left:1px solid #999; border-right:1px solid #999;">Total<br>Hrs</th>
										<th colspan="4" bgcolor="'.$export_type_color.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
										<th colspan="4" bgcolor="'.$export_type_color.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
										<th colspan="4" bgcolor="'.$export_type_color.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
										<th colspan="4" bgcolor="'.$export_type_color.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
										<th colspan="4" bgcolor="'.$export_type_color.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
										<th rowspan="2" bgcolor="#888" style="color:#fff; font-size:14px; text-transform:uppercaseborder-bottom:1px; border-top:1px;border-left:1px; border-right:1px">Student\'s<br>Signatures</th>
									</tr>
									<tr>
										<th bgcolor="#BBBBBB" style="border-bottom:1px solid #999999; border-top:1px solid #999999;border-left:1px solid #999999; border-right:1px solid #999999;">#</th>
										<th bgcolor="#BBBBBB" style="border-bottom:1px solid #999999; border-top:1px solid #999999;border-left:1px solid #999999; border-right:1px solid #999999;">Uni ID</th>
										<th bgcolor="#BBBBBB" style="border-bottom:1px solid #999999; border-top:1px solid #999999;border-left:1px solid #999999; border-right:1px solid #999999;">Student Name</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
	<th bgcolor="'.$export_type_color_day.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none">&nbsp;</th>
									</tr>';
							$cnt_no = 1;		
							foreach($student_data->result() AS $studata)
							{
								$row_color = "";
								$export_type_color_day_val = "";
								if($cnt_no%2 == 0)
								{
									$row_color = 'bgcolor="#dddddd"';
									$export_type_color_day_val = "#dddddd";
								}	
								$html .= '<tr '.$row_color.'>
										<td width="1%" style="border-bottom:1px solid #999999; border-top:1px solid #999999;border-left:1px solid #999999; border-right:1px solid #999999;">'.$cnt_no.'</td>
										<td width="6%" style="border-bottom:1px solid #999999; border-top:1px solid #999999;border-left:1px solid #999999; border-right:1px solid #999999;">'.$studata->student_uni_id.'</td>
										<td style="border-bottom:1px solid #999999; border-top:1px solid #999999;border-left:1px solid #999999; border-right:1px solid #999999;">'.$studata->first_name.'</td>
										<td style="border-bottom:1px solid #999999; border-top:1px solid #999999;border-left:1px solid #999999; border-right:1px solid #999999;">&nbsp;</td>
										<td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td>
										<td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td>
										<td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td>
										<td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td>
										<td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td><td bgcolor="'.$export_type_color_day_val.'" style="border-bottom:none; border-top:none;border-left:none; border-right:none"></td>
										
										<td style="border-bottom:1px solid #999999; border-top:1px solid #999999;border-left:1px solid #999999; border-right:1px solid #999999;">&nbsp;</td>
									</tr>';
									$cnt_no++;
							}	
							$html .= '</table>
									<br/>
									<br/>
								<span>
									<span style="float:left;"><span style="float:left;">Primary Teacher\'s Signature :</span>____________________________</span>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<span style="float:left;"><span style="float:left;">Secondary Teacher\'s Signature:</span>____________________________</span>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<span style="float:right;"><span style="float:left;">Admin Assistant\'s Signature:</span>____________________________</span>
								</span>	
								';
					}
					else						
					{
												$html = '<table width="100%" style="border-bottom: 1px solid #999999; vertical-align: bottom; font-family: arial; font-size: 9pt; color: #000;"><tr>
								<td width="5%" align="left"><img src="'.base_url().'images/logo.png" height="80px"/></td>
								<td width="45%" align="left">
									<b>Primary Teacher\'s Name: </b>'.$courseclasses->first_name.'<br/><br/>
								<b>Secondary Teacher\'s Name: </b>'.$courseclasses->sec_name.'<br/>
								</td>
								<td width="50%" align="right">
									<b>AM Shift Submission (Thursday) : '.$am_start.' - '.$am_end.'</b><br><br>
									<b>PM Shift Submission (Thursday) : '.$pm_start.' - '.$pm_end.'</b><br> 
								</td>
								</tr></table>
								<table cellpadding="5" cellspacing="0" style="font-family:arial;" width="85%">
									<tr><td bgcolor="#BBBBBB" width="5%">Section:</td>
										<td>'.$courseclasses->section_title.'</td>
										<td bgcolor="#BBBBBB" width="9%">Classrom #:</td>
										<td width="8%">'.$courseclasses->class_room_title.'</td>
										<td bgcolor="#BBBBBB" width="5%">Course:</td>
										<td width="8%">'.$courseclasses->course_title.'</td>
										<td bgcolor="#BBBBBB" width="5%">Shift:</td>
										<td>'.$courseclasses->shift.'</td>
										<td bgcolor="#BBBBBB" width="5%">Campus:</td>
										<td>'.$courseclasses->campus.'</td>
										<td bgcolor="#BBBBBB" width="5%">Building:</td>
										<td>'.$courseclasses->buildings.'</td>
									</tr>
									<tr>
										<td colspan="12" style="padding-left:40%" ><br><span style="font-family:arial;float:left"><b>KEY:P =</b>
								<span style="font-family:arial;font-size:10px;">Present (0-6 min.s)</span>, <b>L =</b><span style="font-size:11px;"> Late (7-10 min.s)</span>, <b>A =</b><span style="font-size:11px;">Absent (10+ min.s)</span>, <b>Note: </b><span style="font-size:11px;">2 L\'s= 1 A</span></span></td>
									</tr>
								</table>
								
									<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-bottom: 0px solid #999999; vertical-align: bottom; font-family:arial; font-size: 9pt; color: #000000;">
									
									<tr>
										<th colspan="3" bgcolor="#888" style="color:#333; font-size:13px;">'.$date_title.' Attendance Register - Week ( '.$week.' )</th>';
										if(trim($day1) !== ""){
											$html .='<th colspan="4" bgcolor="'.$export_type_color.'">'.$day1.'</th>';
										}	
										if(trim($day2) !== ""){
											$html .='<th colspan="4" bgcolor="'.$export_type_color.'">'.$day2.'</th>';
										}
										if(trim($day3) !== ""){	
											$html .='<th colspan="4" bgcolor="'.$export_type_color.'">'.$day3.'</th>';
										}
										if(trim($day4) !== ""){	
											$html .='<th colspan="4" bgcolor="'.$export_type_color.'">'.$day4.'</th>';
										}
										if(trim($day5) !== ""){	
											$html .='<th colspan="4" bgcolor="'.$export_type_color.'">'.$day5.'</th>';
										}
									$html .= '<th rowspan="2" bgcolor="#b1db7b" width="3%">Total<br>Hrs</th>
	<th rowspan="2" width="8px" style="border-bottom:none"></th>
										<th rowspan="2" bgcolor="#888" style="color:#333; font-size:14px; text-transform:uppercase">Students <br />Signatures</th>
									</tr>
									<tr>
										<th bgcolor="#BBBBBB">#</th>
										<th bgcolor="#BBBBBB">Uni ID</th>
										<th bgcolor="#BBBBBB">Student Name</th>';
										if(trim($day1) !== ""){
											$html .='<th bgcolor="'.$export_type_color_day.'">1<sup>st</sup></th>
													 <th bgcolor="'.$export_type_color_day.'">2<sup>nd</sup></th> 	
													 <th bgcolor="'.$export_type_color_day.'">3<sup>rd</sup></th>
													 <th bgcolor="'.$export_type_color_day.'">4<sup>th</sup></th>';
										}
									
										if(trim($day2) !== ""){
											$html .='<th bgcolor="'.$export_type_color_day.'">1<sup>st</sup></th>
													<th bgcolor="'.$export_type_color_day.'">2<sup>nd</sup></th> 	
													<th bgcolor="'.$export_type_color_day.'">3<sup>rd</sup></th>
													<th bgcolor="'.$export_type_color_day.'">4<sup>th</sup></th>';
										}
												
												
										if(trim($day3) !== ""){
											$html .='<th bgcolor="'.$export_type_color_day.'">1<sup>st</sup></th>
													<th bgcolor="'.$export_type_color_day.'">2<sup>nd</sup></th> 	
													<th bgcolor="'.$export_type_color_day.'">3<sup>rd</sup></th>
													<th bgcolor="'.$export_type_color_day.'">4<sup>th</sup></th>';
										}
												
										if(trim($day4) !== ""){
											$html .='<th bgcolor="'.$export_type_color_day.'">1<sup>st</sup></th>
													<th bgcolor="'.$export_type_color_day.'">2<sup>nd</sup></th> 	
													<th bgcolor="'.$export_type_color_day.'">3<sup>rd</sup></th>
													<th bgcolor="'.$export_type_color_day.'">4<sup>th</sup></th>';
										}
									
										if(trim($day5) !== ""){
											$html .='<th bgcolor="'.$export_type_color_day.'">1<sup>st</sup></th>
													<th bgcolor="'.$export_type_color_day.'">2<sup>nd</sup></th> 	
													<th bgcolor="'.$export_type_color_day.'">3<sup>rd</sup></th>
													<th bgcolor="'.$export_type_color_day.'">4<sup>th</sup></th>';
										}
									$html .='</tr>';
							$cnt_no = 1;		
							foreach($student_data->result() AS $studata)
							{
								$row_color = "";
								$export_type_color_day_val = "";
								if($cnt_no%2 == 0)
								{
									$row_color = 'bgcolor="#878787"';
									$export_type_color_day_val = "#878787";
								}
								$html .= '<tr '.$row_color.'>
										<td width="1%">'.$cnt_no.'</td>
										<td width="6%">'.$studata->student_uni_id.'</td>
										<td>'.$studata->first_name.'</td>';
								if(trim($day1) !== ""){
									$html .='
										<td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td>';
								}
								if(trim($day2) !== ""){
									$html .='
										<td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td>';
								}
								if(trim($day3) !== ""){
									$html .='
										<td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td>';
								}
								if(trim($day4) !== ""){
									$html .='	
										<td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td>';
								}
								if(trim($day5) !== ""){
									$html .='
										<td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td><td width="2%" bgcolor="'.$export_type_color_day_val.'"></td>';
								}
										
								$html .='<td></td>
	<td width="8px" style="border-top:none; border-bottom:none"></td>
										<td></td>
									</tr>';
									$cnt_no++;
							}	
							$html .= '</table>
									<br/>
									<br/>
								<span>
									<span style="float:left;"><span style="float:left;">Primary Teacher\'s Signature :</span>____________________________</span>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<span style="float:left;"><span style="float:left;">Secondary Teacher\'s Signature:</span>____________________________</span>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<span style="float:right;"><span style="float:left;">Admin Assistant\'s Signature:</span>____________________________</span>
								</span>	
								';
					}
					//echo $html;exit;
					$pdf->AddPage("L");
					$pdf->WriteHTML($html); // write the HTML into the PDF
				}
			}
		$pdf->Output($pdfFilePath, 'F'); // save to file because we can
		redirect("downloads/pdfreports/".$filename.".pdf");
		}else {
			echo 'No data found.';
		}
		//ob_end_clean();
    }
}
?>
