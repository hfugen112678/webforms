<div class="page-header">
    <h1>
        Agents
        <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Quality Assurance Score
        </small>
    </h1>
</div><!-- /.page-header -->
<div class="col-sm-offset-1 col-sm-10">
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
    <p>
        <?php echo form_button(array(
            'id' => 'print-score',
            'name' => 'print-score',
            'class' => 'btn btn-primary',
            'content' => 'Printable Version', 
            'data-source' => site_url('agentlist/printtableempscores')
        )); ?>
    </p>
</div>
<!-- Bootstrap Modal Pop up -->
<div class="modal fade" id="print-emp-scores" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
        </div>
    </div>
</div>
<!-- eof Bootstrap Modal Pop up -->