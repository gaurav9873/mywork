<? 
require('includes/application_top.php');
checklogin();
$id=$_GET["id"];
$table=$_REQUEST['table'];

$page=$_REQUEST['page'];
$deletequery=mysql_query("delete from $table  where id='$id'");
?>
<script>

window.location.href="<?=$page;?>?msg=deleted";
</script>

 