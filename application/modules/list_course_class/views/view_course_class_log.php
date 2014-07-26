<div id="containers" style="height:100%;">
  <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
  <h4>Course Class Log Report</h4>
  <table width="100%" class="table no-more-tables log-table">
    <tr>
      <th width="150">Updated By</th>
      <th width="65">Date</th>
      <th width="400" class="in-table"><table width="100%">
          <th width="30%">Field Name</th>
            <th width="35%">Old Value</th>
            <th width="35%">New Value</th>
        </table></th>
      <th>Reason</th>
    </tr>
    <?php 
if(!empty($course_class_log_data)) {
	foreach($course_class_log_data as $data1) { ?>
    <tr>
      <td><?php echo addslashes($data1["cname"]); ?></td>
      <td><?php echo addslashes($data1["change_date"]); ?></td>
      <td class="in-table"><?php 
			$arrPredefinedField["course_id"] = "course_title";
			$arrPredefinedField["course_id_new"] = "course_title_new";
			$arrPredefinedField["primary_teacher_id"] = "pname";			
			$arrPredefinedField["primary_teacher_id_new"] = "pname_new";			
			$arrPredefinedField["secondary_teacher_id"] = "sname";			
			$arrPredefinedField["secondary_teacher_id_new"] = "sname_new";			
			$arrPredefinedField["class_room_id"] = "class_room_title";			
			$arrPredefinedField["class_room_id_new"] = "class_room_title_new";			
			$arrPredefinedField["section_id"] = "section_title";			
			$arrPredefinedField["section_id_new"] = "section_title_new";			
			$arrPredefinedField["shift"] = "shift";			
			$arrPredefinedField["shift_new"] = "shift_new";
			
			$arrPredefinedTitle["course_id"] = "Course";
			$arrPredefinedTitle["primary_teacher_id"] = "Pri. Teacher";			
			$arrPredefinedTitle["secondary_teacher_id"] = "Sec. Teacher";			
			$arrPredefinedTitle["class_room_id"] = "Class Room";			
			$arrPredefinedTitle["section_id"] = "Section";			
			$arrPredefinedTitle["shift"] = "Shift";			
			
			$chn_fld = $data1["change_field"];
			$chn_fldArr = explode(',',$chn_fld);
			if(count($chn_fldArr) > 0){
				echo '<table border="0" width="100%"  cellspacing="0"  cellpadding="0" class="table no-more-tables">';
				$cnt = 1;
			foreach($chn_fldArr as $field_data) {
				$keyf = $arrPredefinedField[$field_data];
				$keyfn = $arrPredefinedField[$field_data."_new"];
				if($cnt<count($chn_fldArr)) { 
					echo '<tr><td  width="30%">'.$arrPredefinedTitle[$field_data].'</td><td  width="35%">'.$data1[$keyf].'</td><td  width="35%">'.$data1[$keyfn].'</td></tr>';
				}else{
					echo '<tr><td  width="30%">'.$arrPredefinedTitle[$field_data].'</td><td  width="35%">'.$data1[$keyf].'</td><td  width="35%">'.$data1[$keyfn].'</td></tr>';
				}
				$cnt++;
			}
				echo '</table>';
				
			}?>
      </td>
      <td width=\"200px\"><?php echo addslashes(str_replace("'"," ",str_replace("\n"," ",str_replace("\r\n"," ",$data1["reason"])))) ?></td>
    </tr>
    <?php
	}
}else{
	echo '<tr><th colspan="3">No date found</th></tr>';
}	
?>
  </table>
</div>
