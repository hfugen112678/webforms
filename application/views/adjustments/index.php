<div class="page-header">
    <h1>
        Adjustments
        <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                File Adjustment
        </small>
    </h1>
</div><!-- /.page-header -->
<?php echo form_open($action='adjustments/file',$attributes = array('class' => 'form-horizontal', 
    'role' => 'form', 'id' => 'fileadjustment')); ?> 
    <div class="form-group">
        <?php echo form_label('Type:', 'startdate', array(
            'class' => 'col-sm-3 control-label no-padding-left' 
        )); ?>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo form_dropdown('adjustments', $this->config->item('adjustments'), '', 'class="form-control" id="adjustments"'); ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?php echo form_label('Date:', 'dateofadjustment', array(
            'class' => 'col-sm-3 control-label no-padding-left' 
        )); ?>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo form_input(array(
                    'class' => 'form-control date-picker',
                    'id' => 'dateofadjustment',
                    'name' => 'dateofadjustment',
                    'data-date-format' => 'yyyy-mm-dd',
                ), '', 'required'); ?>            
                <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                </span>
            </div>
        </div>      
    </div>
    <div class="form-group">
        <?php echo form_label('Payday:', 'paydays', array(
            'class' => 'col-sm-3 control-label no-padding-left' 
        )); ?>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo form_dropdown('paydays', $this->config->item('paydays'), '', 'class="form-control" id="paydays"'); ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?php echo form_label('Remarks:', 'remarks', array(
            'class' => 'col-sm-3 control-label no-padding-left' 
        )); ?>
        <div class="col-sm-9">
            <?php echo form_textarea(array(
                'cols' => 55, 
                'rows' => 4, 
                'id' => 'remarks',
                'name' => 'remarks')); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-3 control-label no-padding-left"></div>
        <div class="col-sm-9">
            <?php echo form_submit('btnfileadjustment', 'Submit', 'class = "btn btn-sm btn-success"'); ?>
            
        </div>
    </div>    
<?php echo form_close(); ?>
<?php echo $adjustment; ?>
<!-- Bootstrap Modal Pop up -->
<div class="modal fade" id="updateAdjustment" tabindex="-1" role="dialog" aria-labelledby="smallModal" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            
        </div>
    </div>
</div>
<!-- eof Bootstrap Modal Pop up -->