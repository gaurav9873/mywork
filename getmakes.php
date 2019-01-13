<?php
include("includes/conn.php");

$year = $_POST['myear'];
$resy = mysql_query("select * from makes_model where year='".$year."' and parent_id='0'");
while($rowy = mysql_fetch_assoc($resy)){
?>
<option value="<?php echo $rowy['id'];?>"><?php echo $rowy['make_model_text'];?></option>
<?php } ?>