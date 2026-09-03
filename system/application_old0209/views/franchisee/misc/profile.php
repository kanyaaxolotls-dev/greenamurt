<div class="container-fluid card bg-secondary">
    <h3 class="text-success mt-3">My Franchisee ID : <?php echo $this->session->fran_id ?></h3>
    <?php echo form_open() ?>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" value="<?php echo set_value('name', $result->name) ?>" name="name" id="name" placeholder="Enter Name">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="Phone">Phone</label>
                <input type="text" class="form-control" value="<?php echo set_value('phone', $result->phone) ?>" name="phone" id="phone" placeholder="Enter phone" readonly>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="email">Email ID</label>
                <input type="email" class="form-control" value="<?php echo set_value('email', $result->email) ?>" name="email" id="email" placeholder="Enter Email" readonly>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="adhar">Adhar Card</label>
                <input type="text" class="form-control" value="<?php echo set_value('adhar', $result->adhar) ?>" name="adhar" id="adhar" placeholder="Enter adhar" >
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="pan">Pan Card</label>
                <input type="text" class="form-control" value="<?php echo set_value('pan', $result->pan) ?>" name="pan" id="pan" placeholder="Enter pan" >
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="pan">GST Number</label>
                <input type="text" class="form-control" value="<?php echo set_value('gstin', $result->gstin) ?>" name="gstin" id="gstin" placeholder="Gst number" >
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="state">State</label>
                <input type="text" class="form-control" value="<?php echo set_value('state', $result->state) ?>" name="state" id="state"  placeholder="Enter state">
            </div>
        </div>  
        <div class="col-sm-6">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" value="<?php echo set_value('city', $result->district) ?>" name="city" id="city"  placeholder="Enter city">
            </div>
        </div>  
        <div class="col-sm-6">
            <div class="form-group">
                <label for="pincode">Pincode</label>
                <input type="text" class="form-control" value="<?php echo set_value('pincode', $result->pincode) ?>" name="pincode" id="pincode" placeholder="Enter pincode">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" value="<?php echo set_value('address', $result->address) ?>" name="address" id="address"  placeholder="Enter address">
            </div>
        </div>  
        <div class="col-sm-6">
            <div class="form-group">
                <label for="address">Bank Name</label>
                <input type="text" class="form-control" value="<?php echo set_value('bank_name', $result->bank_name) ?>" name="bank_name" id="bank_name"  placeholder="Enter Bank Name">
            </div>
        </div>  
        <div class="col-sm-6">
            <div class="form-group">
                <label for="address">Bank IFSC</label>
                <input type="text" class="form-control" value="<?php echo set_value('bank_ifsc', $result->bank_ifsc) ?>" name="bank_ifsc" id="bank_ifsc"  placeholder="Enter Bank IFSC">
            </div>
        </div>  
        <div class="col-sm-6">
            <div class="form-group">
                <label for="address">Bank Account Number</label>
                <input type="text" class="form-control" value="<?php echo set_value('acc_number', $result->acc_number) ?>" name="acc_number" id="acc_number"  placeholder="Enter Account Number">
            </div>
        </div>  
        <div class="col-sm-6">
            <div class="form-group">
                <label for="address">Bank Branch</label>
                <input type="text" class="form-control" value="<?php echo set_value('bank_branch', $result->bank_branch) ?>" name="bank_branch" id="bank_branch"  placeholder="Enter Bank branch">
            </div>
        </div>  
        <div class="col-sm-12">
            <div class="form-group">
                <label for="newpass">New Password</label>
                <input type="password" class="form-control" value="<?php echo set_value('newpass') ?>" name="newpass" id="newpass" placeholder="Enter Password">
            </div>
        </div>
        <div class="col-sm-6 my-3">
            <input type="submit" class="btn btn-success" value="Update" onclick="this.value='Updating..'">
        </div>
    </div>
    <?php echo form_close() ?>
</div>
