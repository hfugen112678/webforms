<div class="page-header">
    <h1>
        Transportation
        <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                File
        </small>
    </h1>
</div><!-- /.page-header -->
<?php echo form_open($action='transportation/filetranspo',$attributes = array('class' => 'form-horizontal', 
    'role' => 'form', 'id' => 'filetranspo')); ?>
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
                ), @is_variable_set($startdate), 'required'); ?>            
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
                ), @is_variable_set($enddate), 'required'); ?>            
                <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
                </span>
            </div>
        </div>        
    </div>
    <div class="form-group col-sm-5 no-padding-left">
        <?php echo form_label('Remarks:', 'remarks', array(
            'class' => 'col-sm-2 control-label no-padding-left' 
        )); ?>
        <div class="col-sm-9">
            <?php echo form_textarea(array(
                'cols' => 55, 
                'rows' => 4, 
                'id' => 'remarks',
                'name' => 'remarks')); ?>
        </div>
    </div>
    <div class="form-group col-md-8 col-xs-push-1 no-padding-left">
        <?php echo form_submit('btnfiletranspo', 'Submit', 'class = "btn btn-sm btn-success"'); ?>
    </div>
<?php echo form_close(); ?>
<div class="row"></div> <!-- spacer -->
<div class="row">
    <?php echo is_variable_set($transpo); ?>
</div>
<!-- Bootstrap Modal Pop up -->
<div class="modal fade" id="updateTranspo" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
        </div>
    </div>
</div>
<!-- eof Bootstrap Modal Pop up -->