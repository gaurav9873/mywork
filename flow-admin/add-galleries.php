<?php
include_once 'header.php';

$obj = new ConnectDb();
$customObj = new CustomFunctions();

$cat = $obj->getGalleryCategory();


$upload_path = './uploads/gallery/'.date('Y').'/'.date('m');

$images = array('full' => array('max_width' => 1000, 'max_height' => 1000),
				'medium' => array('max_width' => 600, 'max_height' => 600),
				'thumbnail' => array('max_width' => 82, 'max_height' => 83),);

if(isset($_POST['submit'])){
	$gallery_name = $_POST['gallery_name'];
	$cat_id = intval($_POST['cat_id']);
	$created_ip = $customObj->get_client_ip();
	$date = date("Y-m-d H:i:s");
	
	$img_array = isset($_FILES['gallery_img']['name']) ? $_FILES['gallery_img']['name'] : '';
	$tmp_name = isset($_FILES['gallery_img']['tmp_name']) ? $_FILES['gallery_img']['tmp_name'] : '';
	
	
	$aray = array('gallery_name' => $gallery_name, 'category_id' => $cat_id, 'created_date' => $date, 'created_ip' => $created_ip);
	$ins_stmt = $obj->insert_records('op2mro9899_galleries', $aray);
	if($ins_stmt){
		if(!empty($img_array)){
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
				'thumbnail_path' => $destination_path['thumbnail']['path'].$fileNameWithExtension, 'cat_id' => $cat_id, 'gid' => $ins_stmt);
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
		}
		header("location:add-galleries.php?msg=success");
		
	}
		
}
?>


<script>
	$(document).ready(function(){

		$("#gallery_img").on('change', function (e) {
			var numFiles = $("input:file")[0].files.length;

			if(numFiles > 15){
				alert('You have exceeded the limit');
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
					var dvPreview = $("#dvPreview");
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
                        <input class="form-control" id="gallery_name" name="gallery_name" placeholder="Enter name" type="text">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Select Category</label>
                        <select class="form-control" name="cat_id" name="cat_id">
							<option value="">Select category</option>
							<?php
								foreach($cat as $value){
									$id = $value->id;
									$category_name = $value->category_name;
									echo '<option value="'.$id.'">'.$category_name.'</option>';
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
                       
                    </div>
                    <div class="checkbox">
                        <div id='dvPreview'></div>
                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->

</div>

<?php include_once 'footer.php' ?>
