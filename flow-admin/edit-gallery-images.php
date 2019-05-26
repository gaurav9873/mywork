<?php
include_once 'header.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions();

$gallery_id = $customObj->DecryptClientId($_GET['gallery_id']);
$get_row = $obj->get_row_by_id('op2mro9899_galleries', 'id', intval($gallery_id));

$id = $get_row[0]['id'];
$gallery_name = $get_row[0]['gallery_name'];
$category_id = $get_row[0]['category_id'];


$all_images = $obj->get_row_by_id('op2mro9899_gallery_images', 'gid', intval($gallery_id), 'image_order');

$cat = $obj->getGalleryCategory();

//Gallery Path And Folder
$upload_path = './uploads/gallery/'.date('Y').'/'.date('m');
$images = array('full' => array('max_width' => 1000, 'max_height' => 1000),
				'medium' => array('max_width' => 600, 'max_height' => 600),
				'thumbnail' => array('max_width' => 82, 'max_height' => 83),);

if(isset($_POST['submit'])){
	$galleryName = $_POST['gallery_name'];
	$cat_id = intval($_POST['cat_id']);
	
	$img_array = isset($_FILES['gallery_img']['name']) ? $_FILES['gallery_img']['name'] : '';
	$tmp_name = isset($_FILES['gallery_img']['tmp_name']) ? $_FILES['gallery_img']['tmp_name'] : '';
	
	$row_arr = array('gallery_name' => $galleryName, 'category_id' => $cat_id);
	$array = array('cat_id' => $cat_id);
	$update_img_stmt = $obj->update_row('op2mro9899_gallery_images', $array, "WHERE gid = '$gallery_id'");
	$update_stmt = $obj->update_row('op2mro9899_galleries', $row_arr,"WHERE id = '$gallery_id'");
	if($update_stmt){
		
		foreach($images as $key => $val){
			$path = $upload_path."/".$key;
			if($key!='full')
			$destination_path[$key] =  array('path'=>$upload_path.'/'.$key.'/','heigth'=>$images[$key]['max_height'],'width'=>$images[$key]['max_width']);
			if(!is_dir($path)){
				mkdir($path, 0777, true);
			}
		}


		foreach($img_array as $k => $img_name){
			$source = $upload_path.'/'.'full/';
			$fileNameWithExtension = time().rand(290,7689).'.'.@end(explode('.',$img_name));
			$img_array = array('full_path' => $source.$fileNameWithExtension, 'medium_path' => $destination_path['medium']['path'].$fileNameWithExtension, 
			'thumbnail_path' => $destination_path['thumbnail']['path'].$fileNameWithExtension, 'cat_id' => $cat_id, 'gid' => $gallery_id);
			$file_parts = pathinfo($source.$fileNameWithExtension);
			if($file_parts['extension']){
				$obj->insert_records('op2mro9899_gallery_images', $img_array);
			}
			$createimagestatus = move_uploaded_file($tmp_name[$k],$source.$fileNameWithExtension);
			if($createimagestatus){
				foreach($destination_path as $key=>$value){
					$customObj->make_thumb($source.$fileNameWithExtension,$value['path'].$fileNameWithExtension,$value['width'],$value['heigth']);
				}
			}
		}
		//header("location:edit-gallery-images.php?gallery_id=".$_REQUEST['gallery_id']."&msg=success");
		header("location:gallery-list.php");
	}
}
?>
<style>
 .imgClass {
    display: inline-block;
    width:80px;
    text-align:center;
    margin-top:10px;
    margin-right:10px;
}
.imgClass img{border-radius:5px;}
</style>
<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/normalize.css">
<script>
$(document).ready(function(){
	$( ".sortable" ).sortable();
	
	$(document).on('click','#save-reorder',function(evt){
		evt.preventDefault();
		var list = new Array();
		$('.sortable').find('.imgClass').each(function(){
		var id=$(this).attr('data-ids');    
		list.push(id);
		});
		var data=JSON.stringify(list);
		$.ajax({
			url:'action/gallery.php',
			type:'POST',
			data: {action:'imageorder',data:data},
			cache:false,
			beforeSend:function(){
				$(".loader").show();
			},
			complete:function(){
				$(".loader").hide();
			},
			success:function(resp){
				if(resp=='order saved'){
					$(".alert-success").show();
				}
			}
		});

	});
	
	$("#gallery_img").change(function (e) {
		var numFiles = $("input:file")[0].files.length;
		var numFilespre = $(".delete").length;
		//alert(numFilespre); return false;
		var numFilesTotal = numFiles + numFilespre;
		//console.log(numFilesTotal);
		if(numFilesTotal > 30){
			alert('you have cross the limit');
			$("#gallery_img").val('');
	    }else{
			
			var files = e.originalEvent.target.files;
			for (var i=0, len=files.length; i<len; i++){
				var n = files[i].name,
				    s = files[i].size,
				    t = files[i].type;
				if (s > 7340032) {
				  alert('Please deselect this file: "' + n + '," it\'s larger than the maximum filesize allowed. Sorry!');
				  $("#gallery_img").val('');
				}
			}
			
			 if (typeof (FileReader) != "undefined") {
				var dvPreview = $("#dvPreviewinner");
				dvPreview.html("");
				var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|)$/;
				$($(this)[0].files).each(function () {
					var file = $(this);
					if (regex.test(file[0].name.toLowerCase())) {
						var reader = new FileReader();
						reader.onload = function (e) {
							var img = $("<img />");
							img.attr("style", "height:100px;width: 100px;margin:5px 5px 0 0; border-radius:5px;");
							img.attr("src", e.target.result);
							dvPreview.append(img);
						}
						reader.readAsDataURL(file[0]);
					} else {
						alert(file[0].name + " is not a valid image file.");
						dvPreview.html("");
						return false;
					}
				});
			} else {
				alert("This browser does not support HTML5 FileReader.");
			}
		 
	    }
     });
     
     $(".delete").on('click', function(){
		 var imgid = $(this).data('id');
		 var loader = $(this).siblings(".ajloader");
		 
		if(imgid!=''){
			$.ajax({
				url:'action/gallery.php',
				type:'post',
				data:{action:'delete_image', image_id:imgid},
				cache:false,
				beforeSend:function(){
					$(loader).show();
				},
				complete:function(){
					$(loader).hide();
				},
				success:function(responce){
					window.location.href='';
				}
			});
		}
		 
	 });
	
});

</script>

<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i> Add Gallery Image</h2>
            </div>
            <div class="box-content">
                <form name="frm" id="frm" action="" role="form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Gallery Name</label>
                        <input class="form-control" id="gallery_name" name="gallery_name" value="<?php echo $gallery_name; ?>" placeholder="Enter name" type="text">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Select Category</label>
                        <select class="form-control" name="cat_id">
							<option value="">Select category</option>
							<?php
								foreach($cat as $value){
									$id = $value->id;
									$category_name = $value->category_name;
									$sel = (($id == $category_id) ? 'selected' : '');
									echo '<option '.$sel.' value="'.$id.'">'.$category_name.'</option>';
								}
							?>
						</select>
                    </div>
                    <div class="form-group">
						<div id='imageloadstatus' style='display:none'><img src="img/loader.gif" alt="Uploading...."/></div>
						<div id='imageloadbutton'>
                        <label for="exampleInputFile">Image</label>
                        <input id="gallery_img" name="gallery_img[]" multiple type="file">
                        <p class="help-block"><strong>Upload image size 600x600</strong></p>
                        <button class="btn btn-default" id="save-reorder">Save Order</button><br />
                        <img src="img/ajax-loaders/ajax-loader-1.gif" class="loader" title="Please wait" style="display:none;">
						<div id="dvPreview" class="sortable">
							<?php
								foreach($all_images as $img_path){
									$img_id = $img_path['id'];
									$thumbnail_path = $img_path['thumbnail_path'];
									echo '<div class="imgClass" data-ids="'.$img_id.'"><img src="'.$thumbnail_path.'" width="80" height="80">
									<a href="javascript:void(0);" class="delete" data-id="'.$customObj->EncryptClientId($img_id).'"><span class="label-default label label-danger">Delete</span></a>
									<img class="ajloader" style="display:none;" src="img/ajax-loaders/ajax-loader-1.gif" title="img/ajax-loaders/ajax-loader-1.gif">
									</div>';
								}
							?>
							
                    </div><br /><br />
                    <div class="checkbox">
                        <div id="dvPreviewinner"></div>
                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
            </div>
             </form>
        </div>
    </div>
    <!--/span-->
<div class="alert alert-success" style="display:none;">
	<button type="button" class="close" data-dismiss="alert">×</button>
	Order saved successfully.
</div>
</div>

<?php include_once 'footer.php';?>
