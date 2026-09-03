    <div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-12"> 
                        <h3 class="mb-0">View Support Ticket of user : <span class="h1 text-danger"><?= $this->db_model->select('userid', 'ticket', array('id' => $detail->id)) ?></span></h3>
                    </div>
                </div>  
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-6" >
                        <h3><?php echo "Subject: ".$detail->ticket_title ?></h3>
                        <h5><?php echo "Message: ".$detail->ticket_detail ?></h5>
                        <p>
                        <?php
                            $this->db->select('msg, msg_from')->where('ticket_id', $detail->id)->order_by('id', 'ASC');
                            foreach ($this->db->get('ticket_reply')->result() as $data) {
                                if ($data->msg_from !== 'Admin') {
                                    $class = "from_ticket";
                                    $from  = "User Reply:";
                                }
                                else {
                                    $class = "to_ticket";
                                    $from  = "Support Team";
                                }
                                echo '<fieldset class="' . $class . '"><legend class="ticket_legend">' . $from . ': </legend>' . $data->msg . '</fieldset>';
                            }
                        ?>
                        </p>
                    </div>
                    <div class="col-4" >
                        <?php echo form_open() ?>
                        <input type="hidden" class="form-control form-control-alternative form-control-alternative-alternative" name="ticket_id" value="<?php echo $detail->id ?>">
                        <textarea id="editor" class="form-control form-control-alternative form-control-alternative-alternative" name="ticket_reply"></textarea>
                    </div>
                    <div class="col-2">
                        <button class="btn btn-success btn-sm">Reply</button>
                        <?php if ($detail->status !== "Closed") { ?>
                        <a href="<?php echo site_url('ticket/close/' . $detail->id) ?>" class="btn btn-sm btn-danger">Close </a>
                        <?php } ?>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>

 