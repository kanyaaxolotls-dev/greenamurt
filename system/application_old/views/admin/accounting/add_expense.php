  <div class="panel-body">
                               
                                        <?php echo $this->session->flashdata('other_flash') ?>
                                        <?php echo form_open('admin/add_expense') ?>
                                        <label class="list-font">Expense Title</label>
                                        <input type="text" required name="ename"
                                               class="form-control">
                                       <label class="list-font">Amount</label>
                                        <input type="text" required
                                               name="eamount"
                                               class="form-control">
                                        <label class="list-font">Expense Detail</label>
                                        <input type="text" name="edetail" 
                                               class="form-control">
                                        <label class="list-font">Expense Date</label>
                                        <input type="text" required readonly
                                               name="edate"
                                               class="form-control datepicker"><br>
                                        <button type="submit"
                                                class="btn btn-success btn-block  glyphicon glyphicon-plus-sign"></button>
                                        <?php echo form_close() ?>
                                  
                                </div>