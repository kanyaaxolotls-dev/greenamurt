<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-6" style="margin: 0 auto;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Give Manual Quiz Access</h3>
                </div>
                <div class="card-body">
                    <?php echo form_open('admin/process_manual_quiz'); ?>
                        <div class="form-group">
                            <label class="form-control-label">Enter Member User ID</label>
                            <input type="number" id="userid_input" name="userid" class="form-control" placeholder="e.g. 1001" autocomplete="off" required>
                            <!-- Name Display Area -->
                            <div id="name_display" class="mt-2" style="height: 20px;"></div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" id="submit_btn" class="btn btn-primary">Grant Access Now</button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AJAX SCRIPT -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('#userid_input').on('keyup change', function() {
        var userid = $(this).val();
        
        if (userid.length > 0) {
            $.ajax({
                url: "<?php echo site_url('admin/get_user_name_ajax'); ?>",
                method: "POST",
                data: {userid: userid},
                success: function(data) {
                    $('#name_display').html(data);
                    
                    // Disable submit button if user is not found
                    if (data.includes("not found")) {
                        $('#submit_btn').attr('disabled', true);
                    } else {
                        $('#submit_btn').attr('disabled', false);
                    }
                }
            });
        } else {
            $('#name_display').html('');
        }
    });
});
</script>