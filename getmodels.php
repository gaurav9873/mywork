<?php
include("includes/conn.php");

$yersval = $_POST['yersval'];
$makval = $_POST['makval'];

$resy = mysql_query("select * from makes_model where year='".$yersval."' and parent_id='".$makval."'");
while($rowy = mysql_fetch_assoc($resy)){
?>
<option value="<?php echo $rowy['id'];?>"><?php echo $rowy['make_model_text'];?></option>
<?php } ?>