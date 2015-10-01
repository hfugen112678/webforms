<style>
    .datepicker{z-index:1151 !important;}
</style>
<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>Transpo Allowance - # <?php echo $transpo->ID; ?></strong>
    </div>
</div>
<div class="modal-body">
    <?php echo form_open($action = site_url('filedtranspo/update'), $attributes = array('id' => 'transpoapproval' , 
    'name' => 'transpoapproval', 'role' => 'form')); ?>
        <?php echo form_hidden('id', $transpo->ID); ?>
        <h6>Name: <?php echo $employee->FirstName; ?> <?php echo $employee->LastName; ?></h6>
        <h6>From:</h6>
        <?php echo form_input(array(
            'id' => 'transpofrom',
            'name' => 'transpofrom',
            'class' => 'form-control',
            'required' => 'required',
            'disabled' => 'disabled'
        ), convert_date($transpo->Datefrom, "Y-m-d")); ?>
        <h6>To:</h6>
        <?php echo form_input(array(
            'id' => 'transpoto',
            'name' => 'transpoto',
            'class' => 'form-control',
            'required' => 'required',
            'disabled' => 'disabled'
        ), convert_date($transpo->Dateto, "Y-m-d")); ?>
        <h6>Remarks:</h6>
        <?php echo form_textarea(array(
            'name' => 'remarks',
            'id' => 'remarks',
            'class' => 'form-control',
            'rows' => '7'
        ), $transpo->Remarks, 'required readonly'); ?>        
        <div class="modal-footer-nobg text-right">
            <?php echo form_button(array(
                'class' => 'btn btn-default',
                'data-dismiss' => 'modal',
                'content' => 'Close'
            )); ?>
            <?php if ($transpo->Status == 0): ?>
                <?php echo form_submit(array(
                    'class' => 'btn btn-primary',
                    'id' => 'btnapprovetranspo',
                    'name' => 'btnapprovetranspo',
                    'value' => 'Approve'
                )); ?>
                <?php echo form_submit(array(
                    'class' => 'btn btn-danger',
                    'id' => 'btndenytranspo',
                    'name' => 'btndenytranspo',
                    'value' => 'Decline'
                )); ?>
            <?php endif; ?>
        </div>
    <?php echo form_close(); ?>
</div>