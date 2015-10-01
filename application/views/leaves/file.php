<style>
    .datepicker{z-index:1151 !important;}
</style>
<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>File Leave</strong>
    </div>
</div>
<div class="modal-body">
    <?php echo form_open($action = site_url('leaves/file'), $attributes = array('id' => 'fileleave' , 
    'name' => 'fileleave', 'role' => 'form')); ?>
        <h6>From:</h6>
        <?php echo form_input(array(
            'name' => 'start',
            'id' => 'start',
            'class' => 'form-control date-picker',
            'data-date-format' => 'yyyy-mm-dd',
        ), $start, 'required'); ?>
        <h6>To:</h6>
        <?php echo form_input(array(
            'name' => 'end',
            'id' => 'end',
            'class' => 'form-control date-picker',
            'data-date-format' => 'yyyy-mm-dd',
        ), $end, 'required'); ?>
        <h6>Type:</h6>
        <?php echo form_dropdown('leavetypes', $leavetypes, '', 'class="form-control" id="leavetypes"'); ?>
        <h6>Reason:</h6>
        <?php echo form_textarea(array(
            'name' => 'leavereason',
            'id' => 'leavereason',
            'rows' => '7',
            'class' => 'form-control'
        ), '', 'required'); ?>        
        <div class="modal-footer-nobg text-right">
            <?php echo form_button(array(
                'class' => 'btn btn-default',
                'data-dismiss' => 'modal',
                'content' => 'Close'
            )); ?>
            <?php echo form_submit(array(
                'class' => 'btn btn-primary',
                'id' => 'btnfile',
                'name' => 'btnfile',
                'value' => 'Save changes'
            )); ?>
        </div>
    <?php echo form_close(); ?>
</div>    
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