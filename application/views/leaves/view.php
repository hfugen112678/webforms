<style>
    .datepicker{z-index:1151 !important;}
</style>
<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>Leave - # <?php echo $leave->LeaveID; ?></strong>
    </div>
</div>
<div class="modal-body">
    <?php echo form_open($action = site_url('leaves/update'), $attributes = array('id' => 'updateleave' , 
    'name' => 'updateleave', 'role' => 'form')); ?>
        <?php echo form_hidden(array('leaveid' => $leave->LeaveID, 
            'employeenumber' => $leave->EmployeeNumber)); ?>
        <h6>From:</h6>
        <?php echo form_input(array(
            'name' => 'leavefrom',
            'id' => 'leavefrom',
            'data-date-format' => 'yyyy-mm-dd',
            'class' => 'form-control date-picker',
        ), convert_date($leave->LeaveFrom, "Y-m-d"), 'required'); ?>
        <h6>To:</h6>
        <?php echo form_input(array(
            'name' => 'leaveto',
            'id' => 'leaveto',
            'data-date-format' => 'yyyy-mm-dd',
            'class' => 'form-control date-picker',
        ), convert_date($leave->LeaveTo, "Y-m-d"), 'required'); ?>
        <h6>Type:</h6>
        <?php echo form_dropdown('leavetypes', $leavetypes, $leavedescription[trim($leave->Type)], 'class="form-control" id="leavetypes"'); ?>
        <h6>Reason:</h6>
        <?php echo form_textarea(array(
            'name' => 'leavereason',
            'id' => 'leavereason',
            'rows' => '7',
            'class' => 'form-control'
        ), $leave->Reason, 'required'); ?>        
        <h6>Status: <strong><em><?php echo leave_status($leave); ?></em></strong></h6>
        <?php if (trim($leave->Type) == 'SL'): ?>
            With medical certificate: <?php echo $medcert; ?>
        <?php endif; ?>
        <div class="modal-footer-nobg text-right">
            <?php echo form_button(array(
                'class' => 'btn btn-default',
                'data-dismiss' => 'modal',
                'content' => 'Close'
            )); ?>
            <?php if (leave_status($leave) == "Pending"): ?>
            <?php echo form_submit(array(
                'class' => 'btn btn-primary',
                'id' => 'btnupdateleave',
                'name' => 'btnupdateleave',
                'value' => 'Save changes'
            )); ?>
        <?php endif; ?>
        </div>
    <?php echo form_close(); ?>
</div>    
<?php if (leave_status($leave) == "Pending"): ?>
    <script type="text/javascript">
        $(document).ready(function(){
            //datepicker plugin
            //link
            $('.date-picker').datepicker({
                    autoclose: true,
                    todayHighlight: true
            })
            //show datepicker when clicking on the icon
            .next().on(ace.click_event, function(){
                    $(this).prev().focus();
            });

            //or change it into a date range picker
            $('.input-daterange').datepicker({autoclose:true});
        });
    </script>
<?php endif; ?>