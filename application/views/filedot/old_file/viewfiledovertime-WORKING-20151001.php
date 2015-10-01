<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>Overtime - # <?php echo $ot_details->UserlogID; ?></strong>
    </div>
</div>
<div class="modal-body">
    <?php echo form_open($action = site_url('filedot/updateot'), $attributes = array('id' => 'filedot', 
    'name' => 'filedot', 'role' => 'form')); ?>        
        <?php echo form_hidden(array('userlogid' => $ot_details->UserlogID,
            'othours_oldvalue' => format_overtime($ot_details->{$ot_type_column}))); ?>
        <h5>Type</h5>
        <select id="ottype" class="form-control">
        <?php foreach ($ottypes as $key => $value): ?>
            <?php if ($value == $current_ot_type['type']): ?>
                <option value="<?php echo $key; ?>" selected><?php echo $value; ?></option>
            <?php else : ?>
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php endif; ?>
        <?php endforeach; ?>        
        </select>
        <h5>Number of Hours</h5>
        <?php echo form_input(array(
            'name' => 'othours',
            'id' => 'othours',
            'class' => 'form-control',
            'value' => format_overtime($ot_details->{$ot_type_column})
        )); ?>
        <h5>Overtime Reason</h5>
        <?php echo form_textarea(array(
            'name' => 'otreason',
            'id' => 'otreason',
            'class' => 'form-control',
            'value' => $ot_details->OTReason
        )); ?> 
        <div class="modal-footer-nobg text-right">    
            <?php echo form_button(array(
                'class' => 'btn btn-default',
                'data-dismiss' => 'modal',
                'content' => 'Close'
            )); ?>
            <?php echo form_button(array(
                'class' => 'btn btn-primary',
                'id' => 'btnsaveot',
                'content' => 'Approve'
            )); ?>
            <?php echo form_button(array(
                'class' => 'btn btn-danger',
                'id' => 'btndecline',
                'content' => 'Decline'
            )); ?>
        </div>
    <?php echo form_close(); ?>
</div>