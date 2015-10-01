<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>Update Credits</strong>
    </div>
</div>
<?php echo form_open($action = site_url('leavecredits/update'), $attributes = array('id' => 'creditview' , 
        'name' => 'creditview', 'role' => 'form')); ?>
    <?php echo form_hidden('employeenumber', $employee->EmployeeNumber); ?>
    <div class="modal-body">
        <h5>Employee Number: <?php echo $employee->EmployeeNumber; ?></h5>
        <h5>Employee Name: <?php echo $employee->FirstName . ' ' . $employee->LastName ; ?></h5>
        <h5>
            Date Hired: <?php echo convert_date($employee->DateHired, 'M d, Y'); ?> 
            Regularization Date: <?php echo convert_date($employee->RegularizationDate, 'M d, Y'); ?>
        </h5>
        <?php echo $empleaves; ?>   
    </div>
    <div class="modal-footer"> 
        <?php echo form_button(array(
            'class' => 'btn btn-default',
            'data-dismiss' => 'modal',
            'content' => 'Close'
        )); ?>        
        <?php echo form_submit(array(
            'name' => 'btnsave',
            'value' => 'Save Changes',
            'class' => 'btn btn-success'
        )); ?>
    </div>
<?php echo form_close(); ?>