<div class="page-header">
    <h1>
        Adjustments
        <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Finance Approval
        </small>
    </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-block alert-success">
            <button type="button" class="close" data-dismiss="alert">
                <i class="ace-icon fa fa-times"></i>
            </button>
            <i class="ace-icon fa fa-check green"></i>
            Note: Search results are based on the date the adjustment was filed. 
        </div>
    </div>
</div>
<?php echo form_open($action='/adjustmentfinance',$attributes = array('class' => 'class=form-horizontal', 
    'role' => 'form', 'id' => 'filteradjustments')); ?>
    <div class="form-group col-sm-5 no-padding-left">
        <?php echo form_label('Start:', 'startdate', array(
            'class' => 'col-sm-2 control-label no-padding-left' 
        )); ?>
        <div class="col-sm-9">
            <div class="input-group">
                <?php echo form_input(array(
                    'class' => 'form-control date-picker',
                    'id' => 'start',
                    'name' => 'start',
                    'data-date-format' => 'yyyy-mm-dd',
                ), $start, 'required'); ?>            
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
                    'id' => 'end',
                    'name' => 'end',
                    'data-date-format' => 'yyyy-mm-dd',
                ), $end, 'required'); ?>            
                <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
                </span>
            </div>
        </div>        
    </div>
    <?php echo form_submit('btnfilter', 'Submit', 'class = "btn btn-sm btn-success"'); ?>    
<?php echo form_close(); ?>
<div class="row"></div> <!-- spacer -->
<div class="row">
    <?php echo $adjustments; ?>
</div>
<!-- Bootstrap Modal Pop up -->
<div class="modal fade" id="updateAdjustment" tabindex="-1" role="dialog" aria-labelledby="smallModal" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            
        </div>
    </div>
</div>
<!-- eof Bootstrap Modal Pop up -->