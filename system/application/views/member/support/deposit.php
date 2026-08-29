
<?php echo form_open_multipart() ?>
 <div class="col-12"> 
    <div class="card  shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-11 ">
                          <h4 class="mb-0"><?php echo $title ?></h4>
                    </div>
                <div class="col-1 text-right"> 
                    <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div><hr>
    <div class="card-body"> 
        <div class="row">
            <div class="col-md-6">
                <label> Payment Details</label>
                <p>Bank Name:- <?= config_item('bank_name') ?> </p>
                <p>Name:- <?= config_item('account_name') ?></p>
                <p>Bank Branch:- <?= config_item('bank_branch') ?></p>
                <p>Account No. :- <?= config_item('account_number') ?></p>
                <p>IFSC Code:- <?= config_item('ifsc_Code') ?></p>
            </div>
            <div class="col-md-6 d-flex justify-content-center">
             <a href="#">
                <img src="<?php echo base_url('uploads/qr_code.png') ?>"height="250px" width="250px" justify-content=center>
            </a>    
            </div>
    </div>
</div>
<div class="card-body"> 
        <div class="row">
            <div class="col-sm-6">
                <label>Deposit Amount</label>
                <input type="text" class="form-control form-control-alternative" value="" name="amount"  placeholder="Amount">
            </div>
            <div class="col-sm-6">
                <label>How you made Deposit</label>
                <select class="form-control form-control-alternative" id="type" name="type" required>
                    <option value="" selected disabled>Select Payment Method</option>
                    <option value="IMPS/NEFT">IMPS/NEFT</option>
                    <option value="Online/Upi">Online / Upi</option>
                </select>
            </div>
            <div class="col-sm-6 mt-3">
                <label>Transcation Number</label>
                <input type="text" class="form-control form-control-alternative" value="" name="tnumber" placeholder="Transaction number">
            </div>
             <div class="col-sm-6 mt-3">
                <label>Add Recepit</label>
                <input type="file" class="form-control form-control-alternative" name="recepit">
            </div>
            
            <div class="col-sm-12">
                <br/>
                <button type="submit" class="btn btn-sm btn-primary btn-block">Submit</button>
                <br/><br/>
            </div>
    </div>
</div>
</div>
</div>
</div>
</div>

<?php echo form_close() ?>
