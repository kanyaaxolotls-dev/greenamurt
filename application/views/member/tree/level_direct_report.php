<script type="text/javascript">
    $(document).ready(function() {
    $('#level-data-table').DataTable();
    } );
</script>
<div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-9">
                          <h4 class="mb-0"><?php echo $title ?></h4>
                    </div>
                <div class="col-3 text-right"> 
                   <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div> 
<div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">      
            <tr>
                <th>S.N.</th>
                <th>User Id</th>
                 <th>Full Name</th>
                <th>Sponsor</th>
          
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php  $this->plan_model->direct_level_list($this->session->user_id); ?>
        </tbody>
       
    </table>

</div>
</div><!-- end of panel-->
