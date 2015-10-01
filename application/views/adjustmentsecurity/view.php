<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>Security Approval - # <?php echo $empadjustments->ID; ?></strong>
    </div>
</div>
<div class="modal-body">
    <?php echo form_open($action = site_url('adjustmentsecurity/update'), $attributes = array('id' => 'filedadjustment', 
    'name' => 'filedadjustment', 'role' => 'form')); ?>
        <?php echo form_hidden('disputeid', $empadjustments->ID); ?>
        <div class="modal-body">
            <h5>Employee Information</h5>
            <h5>Name: <?php echo $employee->FirstName . ' ' . $employee->LastName; ?></h5>
            <h5>Type: <?php echo $types[$empadjustments->Type]; ?></h5>
            <h5>Date of <?php echo $types[$empadjustments->Type]; ?>: <?php echo convert_date($empadjustments->DateOccurred, 'M d, Y'); ?></h5>        
            <h5>Security Notes</h5> 
            <p>
                Please state the date and time of the Log In/ Log Out.
            </p>
            <?php echo form_textarea(array(
                'name' => 'remarks',
                'id' => 'remarks',
                'class' => 'form-control',
                'value' => $empadjustments->SecurityNotes,
                'rows' => 4, 
                'required' => 'required'
            )); ?>
        </div>   
        <div class="modal-footer-nobg text-right">
            <?php echo form_button(array(
                'class' => 'btn btn-default',
                'data-dismiss' => 'modal',
                'content' => 'Close'
            )); ?>
            <?php if ($empadjustments->Status == 0): ?>
                <?php echo form_submit(array(
                    'name' => 'btnsaveadj',
                    'value' => 'Save changes',
                    'class' => 'btn btn-primary'
                )); ?>
            <?php endif; ?>
        </div>
    <?php echo form_close(); ?>
</div>