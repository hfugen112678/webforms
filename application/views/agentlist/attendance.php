<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>Attendance</strong>
    </div>
</div>
<div id="emp-attendance-listing" class="modal-body">
    <div class="row">
        <div class="col-md-8">
            <strong>Employee Name: <?php echo $employee->FirstName . ' ' . $employee->LastName; ?></strong>
        </div>
    </div>
    <div class="hr hr-18 dotted hr-double"></div>
    <?php echo form_open($action = site_url('agentlist/filterattendance'), $attributes = array('id' => 'filterattendance', 
    'name' => 'filterattendance', 'role' => 'form')); ?>
        <?php echo form_hidden('employeenumber', $employeenumber); ?>
        <div class="form-group col-sm-5 no-padding-left">
            <?php echo form_label('Start:', 'startdate', array(
                'class' => 'col-sm-2 control-label no-padding-left' 
            )); ?>
            <div class="col-sm-9">
                <div class="input-group">
                    <?php echo form_input(array(
                        'class' => 'form-control date-picker',
                        'id' => 'startdate',
                        'name' => 'startdate',
                        'data-date-format' => 'yyyy-mm-dd',
                    ), '', 'required'); ?>            
                    <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                    </span>
                </div>
            </div>        
        </div>    
        <div class="form-group col-sm-5 no-padding-left">
            <?php echo form_label('End:', 'enddate', array(
                'class' => 'col-sm-2 control-label no-padding-left' 
            )); ?>
            <div class="col-sm-9">
                <div class="input-group">
                    <?php echo form_input(array(
                        'class' => 'form-control date-picker',
                        'id' => 'enddate',
                        'name' => 'enddate',
                        'data-date-format' => 'yyyy-mm-dd',
                    ), '', 'required'); ?>            
                    <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                    </span>
                </div>
            </div>        
        </div>
        <?php echo form_submit('btnfilter', 'Submit', 'class = "btn btn-sm btn-success"'); ?>    
    <?php echo form_close(); ?>
    <div class="hr hr-18 dotted hr-double"></div>
    <div class="row" id="attendance-list">
        <div class="col-xs-12">
            <?php echo $attendance; ?>
            <div class="space-6"></div>
            <?php echo form_button(array(
            'id' => 'preview-attendance',
            'name' => 'preview-attendance',
            'class' => 'btn btn-primary',
            'content' => 'Printable Version', 
            'data-source' => site_url('agentlist/printtableattendance')
        )); ?>
        </div>
    </div>
    <div class="hr hr-18 dotted hr-double"></div>
</div>
<!-- include necessary javascripts for the data table and form submission -->
<script src="<?php echo base_url($this->config->item('js_directory') . 'bootstrap-datepicker.min.js'); ?>"></script>    
<script src="<?php echo base_url($this->config->item('js_directory') . 'input-datepicker.js'); ?>"></script>    
<script src="<?php echo base_url($this->config->item('js_directory') . 'jquery.dataTables.min.js'); ?>"></script>    
<script src="<?php echo base_url($this->config->item('js_directory') . 'dataTables.bootstrap.min.js'); ?>"></script>    
<script src="<?php echo base_url($this->config->item('js_directory') . 'initdttable.js'); ?>"></script>    
<script src="<?php echo base_url($this->config->item('js_directory') . 'agentlist.js'); ?>"></script>    