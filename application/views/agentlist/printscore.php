<div class="page-content">
    <div  id="printable-area" >
        <h4 class="header blue bolder smaller">Employee</h4>
        <strong><span id="emp-no"><?php echo $employee->EmployeeNumber; ?></span></strong> - 
        <strong><?php echo $employee->FirstName . ' ' . $employee->LastName; ?></strong>
        <h4 class="header blue bolder smaller">Audit Details</h4>
        <strong>Audit ID: <span id="audit-id"><?php echo $auditid; ?></span></strong>
        <div class="space-6"></div>
        <strong>Date of Call: <?php echo convert_date($details->calldate, 'M d, Y H:i a'); ?></strong>
        <div class="space-6"></div>
        <?php echo $empscore; ?>
        <div class="space-6"></div>
        <p><strong>QA Notes:</strong></p>
        <?php echo $details->qa_note; ?>
        <div class="space-6"></div>
    </div>
    <p>
        <?php echo form_button(array(
            'id' => 'print-emp-score',
            'name' => 'print-emp-score',
            'class' => 'btn btn-primary print',
            'content' => '<i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i> Print', 
            'rel' => 'printable-area'
        )); ?>
    </p>
</div>