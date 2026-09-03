
<script>
    $( function() {
        $( "#progressbar" ).progressbar({
            var a=document.getElementById('')
              value: 80
        });
    } );

</script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div style="width: 20%">
<div id="progressbar"></div>
</div>
 <script type="text/javascript">
    $(document).ready(function() {
    $('#level-data-report-table').DataTable();
    } );

</script>
<div class="col"> 
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
    <div class="card-body"> 

                                                    <div class="card-body table-responsive">
                                                            <table id="datatable-buttons" class="table align-middle table-nowrap table-check">

        <thead class="thead-light">
            <tr>
                <th>Sr.No</th>
                <th>User Id</th>
                <th>Level 1</th>
                <th>Level 2</th>
                <th>Level 3</th>
                <th>Level 4</th>
                <th>Level 5</th>
                <th>Level 6</th>
                <th>Level 7</th>
                <th>Level 8</th>
                <th>Level 9</th>
                <th>Level 10</th>

            </tr>
        </thead>
        <tbody>
            <?php  $this->plan_model->level_list_summery($this->session->user_id); ?>
        </tbody>
      
    </table>
</div>
</div>
</div>
</div>
</div><!-- end of panel-->
</div><!-- end of main panel-->