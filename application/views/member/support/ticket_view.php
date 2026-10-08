 <div class="col"> 
    <div class="card shadow">   
        <div class="card-header  border-0"> 
                <div class="row align-items-center">
                    <div class="col-12">
                          <h4 class="mb-0"><?php echo $title ?></h4>
                    </div>
            </div>
        </div>
    <div class="card-body"> 

    <h3><?php echo $detail->ticket_title ?></h3>
    <?php if ($detail->status !== "Closed") { ?>
        <a href="<?php echo site_url('ticket/close/' . $detail->id) ?>" class="btn btn-xs btn-danger pull-right">Close
            Ticket</a>
    <?php } ?>
    <hr/>
    <blockquote>
        <?php echo $detail->ticket_detail ?>
    </blockquote>
    <p>
        <?php

        $this->db->select('msg, msg_from')->where('ticket_id', $detail->id)->order_by('id', 'ASC');
        foreach ($this->db->get('ticket_reply')->result() as $data) {
            if ($data->msg_from == $this->session->user_id) {
                $class = "from_ticket";
                $from  = "Me";
            }
            else {
                $class = "to_ticket";
                $from  = "Support Team";
            }
            echo '<fieldset class="' . $class . '"><legend class="ticket_legend">' . $from . ': </legend>' . $data->msg . '</fieldset>';
        }

        ?>
    </p>
    
    <?php echo form_open() ?>
    <input type="hidden" class="form-control form-control-alternative" name="ticket_id" value="<?php echo $detail->id ?>">
    <h3>Reply This Ticket</h3>
    <textarea id="editor" class="form-control form-control-alternative" name="ticket_reply"></textarea>
    <br>
    <button class="btn btn-success">Reply</button>
    <?php echo form_close() ?>

</div>
</div>
</div>


