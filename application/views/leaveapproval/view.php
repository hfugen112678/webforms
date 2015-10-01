<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>Leave - # <?php echo $leave->LeaveID; ?></strong>
    </div>
</div>
<div class="modal-body">
    <div id="leave-credits" class="row">
        <div class="col-xs-12">
            <h5>Employee Name: <?php echo $employee->FirstName; ?> <?php echo $employee->LastName; ?></h5>
        </div>
    </div>
    <hr />
    <div id="leave-credits" class="row">
        <div class="col-xs-12">            
            <div class="pull-left">
                <i class="ace-icon fa fa-calendar green"></i>
                Leave Credits:
            </div>
            <ul class="list-inline">
                <li>VL: <strong><?php echo $leavecredits->Vacation; ?></strong></li> 
                <li>SL: <strong><?php echo $leavecredits->Sick; ?></strong></li> 
                <li>EL: <strong><?php echo $leavecredits->Emergency; ?></strong></li> 
                <li>ML: <strong><?php echo $leavecredits->Maternity; ?></strong></li> 
                <li>PL: <strong><?php echo $leavecredits->Paternity; ?></strong></li> 
            </ul>            
        </div>
    </div>   
    <hr />
    <?php echo form_open($action = site_url('leaveapproval/update'), $attributes = array('id' => 'modal-approval' , 
    'name' => 'modal-approval', 'role' => 'form')); ?>
        <?php echo form_hidden(array('leaveid' => $leave->LeaveID, 
            'employeenumber' => $leave->EmployeeNumber, 
            'leavecount' => $leave->LeaveCount)); ?>
        <h6>From:</h6>
        <?php echo form_input(array(
            'name' => 'leavefrom',
            'id' => 'leavefrom',
            'class' => 'form-control',
        ), convert_date($leave->LeaveFrom, "Y-m-d"), 'required'); ?>
        <h6>To:</h6>
        <?php echo form_input(array(
            'name' => 'leaveto',
            'id' => 'leaveto',
            'class' => 'form-control',
        ), convert_date($leave->LeaveTo, "Y-m-d"), 'required'); ?>
        <h6>Type:</h6>
        <?php echo form_dropdown('leavetypes', $leavetypes, $leavedescription[trim($leave->Type)], 'class="form-control" id="leavetypes"'); ?>
        <h6>Pay:</h6>
        <?php echo form_dropdown('leavepaylabel', $leavepaylabels, $leave->WithPay, 'class="form-control" id="leavepaylabel"'); ?>
        <h6>Reason:</h6>
        <?php echo form_textarea(array(
            'name' => 'leavereason',
            'id' => 'leavereason',
            'rows' => '7',
            'class' => 'form-control'
        ), $leave->Reason, 'required'); ?>
        <?php if (trim($leave->Type) == 'SL'): ?>
            With medical certificate: <?php echo $medcert; ?>
        <?php endif; ?>
        <div class="modal-footer-nobg text-right">
            <?php echo form_button(array(
                'class' => 'btn btn-default',
                'data-dismiss' => 'modal',
                'content' => 'Close'
            )); ?>
            <?php echo form_button(array(
                'class' => 'btn btn-primary',
                'id' => 'btnapprove',
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