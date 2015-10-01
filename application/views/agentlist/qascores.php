<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>Quality Assurance Scores</strong>
    </div>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-8">
            <strong>Employee Number: <span id="empno-qascores"><?php echo $employee->EmployeeNumber; ?></span></strong>
        </div>
    </div>    
    <div class="row">
        <div class="col-md-8">
            <strong>Employee Name: <?php echo $employee->FirstName . ' ' . $employee->LastName; ?></strong>
        </div>
    </div>
    <div class="hr hr-18 dotted hr-double"></div>
    <div class="row" id="audit-list">
        <div class="col-xs-12">
            <?php echo $auditlist; ?>
        </div>
    </div>
    <div class="hr hr-18 dotted hr-double"></div>
</div>
<!-- include necessary javascripts for the data table and form submission -->
<script src="<?php echo base_url($this->config->item('js_directory') . 'jquery.dataTables.min.js'); ?>"></script>    
<script src="<?php echo base_url($this->config->item('js_directory') . 'dataTables.bootstrap.min.js'); ?>"></script>    
<script src="<?php echo base_url($this->config->item('js_directory') . 'initdttable.js'); ?>"></script>    
<script src="<?php echo base_url($this->config->item('js_directory') . 'agentlist.js'); ?>"></script>