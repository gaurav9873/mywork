<?php include_once 'header.php'; ?>
<section>
	<div class="container-fluid"> 
        <div class="row">    		
            <div class="col-lg-12">
                <form>                
                <h2>Checkout Process</h2>	
                 <div class="reviewDetail formStructure">
                    <h4>Billing Infomation</h4>  
                    <div class="requiredText"><span>*</span>Required</div> 
                      <div class="form-group">
                        <label for="title"><span>*</span>Title :</label>
                        <input type="text" class="form-control" id="title">
                      </div>
                      <div class="form-group">
                        <label for="f-name"><span>*</span>First Name :</label>
                        <input type="text" class="form-control" id="f-name">
                      </div>
                      <div class="form-group">
                        <label for="l-name"><span>*</span>Last Name :</label>
                        <input type="text" class="form-control" id="l-name">
                      </div>
                      <div class="form-group">
                        <label for="Address1"><span>*</span>Address Line1 :</label>
                        <input type="text" class="form-control" id="Address1">
                      </div>
                      <div class="form-group">
                        <label for="Address2">Address Line2 :</label>
                        <input type="text" class="form-control" id="Address2">
                      </div>
                      <div class="form-group">
                        <label for="city"><span>*</span>City :</label>
                        <input type="text" class="form-control" id="city">
                      </div>
                      <div class="form-group">
                        <label for="country"><span>*</span>Country :</label>
                        <input type="text" class="form-control" id="country">
                      </div>
                      <div class="form-group">
                        <label for="county"><span>*</span>County :</label>
                        <input type="text" class="form-control" id="county">
                      </div> 
                      <div class="form-group">
                        <label for="postcode"><span>*</span>Postcode :</label>
                        <input type="text" class="form-control" id="postcode">
                      </div> 
                      <div class="form-group">
                        <label for="phone"><span>*</span>Phone :</label>
                        <input type="text" class="form-control" id="phone">
                      </div> 
                      <div class="form-group">
                        <label for="mobile">Mobile :</label>
                        <input type="text" class="form-control" id="mobile">
                      </div> 
                      <div class="form-group">
                        <label for="fax">Fax :</label>
                        <input type="text" class="form-control" id="fax">
                      </div> 
                      <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="text" class="form-control" id="email">
                      </div> 
                      <div class="form-group">
                        <label for="promotionalcode">Promotional code :</label>
                        <input type="text" class="form-control" id="promotionalcode">
                      </div> 
                      <p>Please ensure the Billing Address is correct. To redeem promotional offers, enter promotional code in a corresponding box and click on proceed button.</p>
                  </div>
                  </form> 
                 	<button type="submit" class="btn btn-col btnDirection" onclick="window.location.href='login.php'">Proceed</button>
                 
            </div>
    	</div>
    </div>
</section>

<?php include_once 'footer.php'; ?>
