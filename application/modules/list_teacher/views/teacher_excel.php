<?php
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=export.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
if(isset($arrTeacher) && count($arrTeacher) > 0)
{
?>
<table border="1">
  <tr>
    <td align="center"><?php echo $this->lang->line('elsid'); ?></td>
    <td align="center"><?php echo $this->lang->line('teacher_p_full_name'); ?></td>
    <td align="center"><?php echo $this->lang->line('campus'); ?></td>
    <td align="center"><?php echo $this->lang->line('teacher_p_username'); ?></td>
	<td align="center"><?php echo $this->lang->line('teacher_p_email_add'); ?></td>
  </tr>
  <?php 
	  	if($arrTeacher){
			foreach ($arrTeacher as $key => $row){
		?>
  <tr>
    <td><?php echo $row["elsd_id"]; ?></td>
    <td><?php echo $row["first_name"]; ?></td>
    <td><?php echo $row["campus"]; ?></td>
    <td align="right"><?php echo $row["username"]; ?></td>
	<td align="right"><?php echo $row["email"]; ?></td>
  </tr>
  <?php
				}
				
			} 
			?>
</table>
<?php
}
else 
{
	echo "Data not found";
}
?>
</body>
</html>
