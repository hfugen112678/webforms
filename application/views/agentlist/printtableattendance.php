<div class="page-content" style="min-height: 488px;">
    <div  id="printable-area" >
        <div class="row">
            <div class="col-md-8">
                <strong>Employee Number: <?php echo $employee->EmployeeNumber; ?></strong>
                <div class="space-6"></div>
                <strong>Employee Name: <?php echo $employee->FirstName . ' ' . $employee->LastName; ?></strong>
            </div>
        </div>
        <div class="space-6"></div>
        <div class="row" id="attendance-list">
            <div class="col-xs-12">
                <?php echo $attendance; ?>
            </div>
        </div>
    </div>
    <p>
        <?php echo form_button(array(
            'id' => 'print-emp-attendance',
            'name' => 'print-emp-attendance',
            'class' => 'btn btn-primary print',
            'content' => '<i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i> Print', 
            'rel' => 'printable-area'
        )); ?>
    </p>
</div>