    <div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0">  
                <div class="row align-items-center">
                    <div class="col-8">  
                          <h3 class="mb-0"><?= $title ?></h3>
                    </div>
                    <div class="col-4 text-right">  
                       <a href="<?= base_url('admin') ?>" class="btn btn-sm btn-primary">Home</a>
                    </div> 
                </div>  
            </div>  
            <form action="<?= base_url('admin/power_leg') ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <div class="card-body row">
                    <div class="form-group col-md-6 col-12">
                        <label for="news_title" class="form-control-label">Userid <span class="text-danger"> * Name : [ <span id="spn_res" style="color: green;">Loading...</span> ]</span></label>
                        <div class="">
                            <input type="text" id="sponsor" oninput="get_user_name('#sponsor', '#spn_res')" name="userid" placeholder="Enter Userid" class="form-control form-control-alternative form-control-alternative-alternative" />
                        </div>
                    </div>
                    <div class="form-group col-md-6 col-12">
                        <label for="news_details" class="form-control-label">Power <span class="text-danger"> * </span></label>
                        <div class="">
                            <input type="text" name="power" placeholder="Enter Power" class="form-control form-control-alternative form-control-alternative-alternative" />
                        </div>
                    </div>
                    <div class="form-group col-md-6 col-12">
                        <label for="news_title" class="form-control-label">Select side <span class="text-danger"> * </span></label>
                        <select id="powerSide" class="form-control" name="powerSide">
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6 col-12">
                        <label for="news_title" class="form-control-label">Select Power distribution <span class="text-danger"> * </span></label>
                        <select id="powerCategory" class="form-control" name="powerCategory">
                            <option value="selfCount">Self Count</option>
                            <option value="selfAndUpline">Self + Upline</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12 col-12">
                        <label for="news_title" class="form-control-label">Power type<span class="text-danger"> * </span></label>
                        <select id="powerCategory" class="form-control" name="powerType">
                            <option value="add">Add</option>
                            <option value="remove">Remove</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-8">
                            <button type="submit" class="btn btn-success">Assign Power</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
    function get_user_name(id, result) {
        var myString = $(id).val();
        // let numbersOnly = myString.replace(/\D/g, "");
        var id =  myString.replace(/\D/g, "");
        $.get("<?php echo site_url('site/get_user_name/') ?>" + id, function (data) {
            $(result).html(data);
        });
    }
    </script>