<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>Adjustment - # <?php echo $empadjustments->ID; ?></strong>
    </div>
</div>
<div class="modal-body">
    <?php echo form_open($action = site_url('adjustmentlead/update'), $attributes = array('id' => 'filedadjustment', 
    'name' => 'filedadjustment', 'role' => 'form')); ?>
        <?php echo form_hidden('id', $empadjustments->ID); ?>
        <h5>Type</h5>
            <?php echo form_dropdown('adjustments', 
            $this->config->item('adjustments'), 
            $empadjustments->Type, 
            'class="form-control" id="adjustments"'); ?>
        <h5>Date</h5>
        <?php echo form_input(array(
            'name' => 'dateoccurred',
            'id' => 'dateoccurred',
            'class' => 'form-control',
            'value' => convert_date($empadjustments->DateOccurred, 'Y-m-d')
        )); ?>
        <h5>Payday</h5>
        <?php echo form_dropdown('paydays', 
                $this->config->item('paydays'), 
                $payday, 
                'class="form-control" id="paydays"'); ?>
        <h5>Remarks</h5>
        <?php echo form_textarea(array(
            'name' => 'remarks',
            'id' => 'remarks',
            'class' => 'form-control',
            'value' => strip_quotes($empadjustments->Remarks)
        )); ?>    
        <div class="modal-footer-nobg text-right">
        <?php echo form_button(array(
            'class' => 'btn btn-default',
            'data-dismiss' => 'modal',
            'content' => 'Close'
        )); ?>            
        <?php if ($empadjustments->Status == 0): ?>
            <?php if ($empadjustments->Type == 0 || $empadjustments->Type == 1): ?>
                <?php if (isset($empadjustments->SecurityNotes) && isset($empadjustments->SecurityID)): ?>
                    <?php echo form_submit(array(
                        'name' => 'btnapproveadj',
                        'value' => 'Approve',
                        'class' => 'btn btn-success'
                    )); ?>
                <?php else: ?>
                    <?php echo form_submit(array(
                        'name' => 'btnapproveadj',
                        'value' => 'Approve',
                        'class' => 'btn btn-success',
                        'disabled' => "disabled"
                    )); ?>
                 <?php endif; ?>
            <?php else: ?>
                <?php echo form_submit(array(
                    'name' => 'btnapproveadj',
                    'value' => 'Approve',
                    'class' => 'btn btn-success'
                )); ?>
            <?php endif; ?>
            <?php echo form_submit(array(
                'name' => 'btndeclineadj',
                'value' => 'Decline',
                'class' => 'btn btn-danger'
            )); ?>
        <?php endif; ?>
        </div>
    <?php echo form_close(); ?>
</div>