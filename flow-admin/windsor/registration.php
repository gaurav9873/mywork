<?php include_once 'header.php'; 

$msg = '';
if(isset($_POST['register'])){
	unset($_POST['register']);
	//print_r($_POST); die;
	
	
	
	//$url = 'http://54.191.172.136:82/florist-admin/flowers/api/login';register
	$url = API_PATH.'register';
	$post_data = $_POST;
	$req = $obj->httpPost($url, $post_data);
	
	if($req->email_status == 'false'){
		$msg = $req->message;
	} else if($req->status == 'true'){
		
		$res = $req->data;
		$_SESSION['user_id'] = $res->user_id;
		$_SESSION['email'] = $res->emailID;
		$_SESSION['first_name'] = $res->fname;
		$_SESSION['last_name'] = $res->lname;
		$_SESSION['key'] = $res->user_key;
		header("location:my-account.php");
		
	}else if($req->status == 'false'){
		$msg = $req->message;
	}else{
	}
}
?>

<script src="validation/jquery.validate.min.js"></script>
<script src="validation/additional-methods.min.js"></script>
<script src="javascripts/user-form.js"></script>

<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">

          <h2>Registration Form</h2>
          <div class="error-message" style="color:red;"><?php echo $msg; ?></div>
          <div class="requiredText"><span>*</span>Required</div>
          
          <div class="billingInformaion">
          <div class="billingform nopart">
            <form name="user-frm" id="user-frm" action="" class="form-horizontal" method="post">
              <h4>* This will be your default Billing Address.</h4>
             
              <div class="billInformation newFormDe">
                <div class="form-group">
                  <label for="Name" class="col-sm-3 control-label">Name <span>*</span></label>
                  <div class="col-sm-3">
                    <select class="form-control require" name="user_prefix" id="user_prefix">
						<option value="">Select prefix</option>
						<option value="Mr">Mr.</option>
						<option value="Ms">Ms.</option>
					</select>
                  </div>
                  <div class="col-sm-3">
                    <input type="text" class="form-control require" name="user_fname" id="user_fname" placeholder="First Name">
                  </div>
                  <div class="col-sm-3">
                    <input type="text" class="form-control require" name="user_lname" id="user_lname" placeholder="Last Name">
                  </div>
                </div>
                <div class="form-group">
                  <label for="PostCode" class="col-sm-3 control-label">Search Address<span>*</span></label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control require" name="post_code" id="post_code" placeholder="Post Code">
                  </div>
                  <div class="col-sm-6">
                    <button type="button" id="searchPin" class="search">Search</button>
                  </div>
                </div>
                <div class="form-group">
                  <label for="HouseNumber" class="col-sm-3 control-label">Select Address</label>
                  <div class="col-sm-9">
                    <select name="address_list" id="address_list" class="findColAddress form-control searchPin require">
                      <option value="">Select Address</option>
                      <option value="Flat 1 Penfold Court Sutton Road,Headington Oxford">Flat 1 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 2 Penfold Court Sutton Road,Headington Oxford">Flat 2 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 3 Penfold Court Sutton Road,Headington Oxford">Flat 3 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 4 Penfold Court Sutton Road,Headington Oxford">Flat 4 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 5 Penfold Court Sutton Road,Headington Oxford">Flat 5 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 6 Penfold Court Sutton Road,Headington Oxford">Flat 6 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 7 Penfold Court Sutton Road,Headington Oxford">Flat 7 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 8 Penfold Court Sutton Road,Headington Oxford">Flat 8 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 9 Penfold Court Sutton Road,Headington Oxford">Flat 9 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 10 Penfold Court Sutton Road,Headington Oxford">Flat 10 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 11 Penfold Court Sutton Road,Headington Oxford">Flat 11 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 12 Penfold Court Sutton Road,Headington Oxford">Flat 12 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 13 Penfold Court Sutton Road,Headington Oxford">Flat 13 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 14 Penfold Court Sutton Road,Headington Oxford">Flat 14 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 15 Penfold Court Sutton Road,Headington Oxford">Flat 15 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 16 Penfold Court Sutton Road,Headington Oxford">Flat 16 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 17 Penfold Court Sutton Road,Headington Oxford">Flat 17 Penfold Court Sutton Road,Headington Oxford</option>
                      <option value="Flat 18 Penfold Court Sutton Road,Headington Oxford">Flat 18 Penfold Court Sutton Road,Headington Oxford</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="HouseNumber" class="col-sm-3 control-label">House Number / Name <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control require" name="user_hnumber" id="user_hnumber" placeholder="House Number / Name">
                  </div>
                </div>
                <div class="form-group">
                  <label for="Address1" class="col-sm-3 control-label">Address1 <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control require" name="primary_address" id="primary_address" placeholder="Address1">
                  </div>
                </div>
                <div class="form-group">
                  <label for="Address2" class="col-sm-3 control-label">Address2</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="secondary_address" id="secondary_address" placeholder="Address2">
                  </div>
                </div>
                <div class="form-group">
                  <label for="City" class="col-sm-3 control-label">City <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control require" name="user_city" id="user_city" placeholder="City">
                  </div>
                </div>
                <div class="form-group">
                  <label for="County" class="col-sm-3 control-label">County <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control require" name="user_county" id="user_county" placeholder="County">
                  </div>
                </div>
                <div class="form-group">
                  <label for="Country" class="col-sm-3 control-label">Country <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control require" name="user_country" id="user_country" placeholder="Country">
                  </div>
                </div>
                <div class="form-group">
                  <label for="Phone" class="col-sm-3 control-label">Phone <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control require" name="user_phone" id="user_phone" placeholder="Phone">
                  </div>
                </div>
                
              </div>
                
              <div class="cardMassages formDee">
                <div class="form-group">
				
                  <label for="Email" class="col-sm-3 control-label">Email ID <span>*</span></label>
                  <div class="col-sm-9">
                  <input type="text" class="form-control user_emailid require" name="user_emailid" id="user_emailid" placeholder="Email">
                  </div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-sm-3 control-label">Password <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="password" class="form-control require" name="user_password" id="user_password" placeholder="Password">
                  </div>
                </div>
                <div class="form-group">
                  <label for="cnfpassword" class="col-sm-3 control-label">Confirm Password <span>*</span></label>
                  <div class="col-sm-9">
                    <input type="password" class="form-control require" name="cnfpassword" id="cnfpassword" placeholder="Confirm Password">
                  </div>
                </div>
                <div class="form-group">
                  <label for="cnfpassword" class="col-sm-3 control-label">&nbsp;</label>
                  <div class="col-sm-9">
					  <div class="wait" style="display:none;">Please wait..</div>
                    <button type="submit" name="register" id="registerCust" class="RegisterBt">Register</button>
                  </div>
                </div>
                
                
              </div>
            </form>
          </div>
        </div>
          <!--<button type="button" id="register"  class="btn btn-col btnDirection">Submit</button>-->
      </div>
    </div>
  </div>
</section>
<?php include_once 'footer.php'; ?>
