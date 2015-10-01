<style>
    .datepicker{z-index:1151 !important;}
</style>
<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>Transpo Allowance - # <?php echo $transpo->ID; ?></strong>
    </div>
</div>
<div class="modal-body">
    <?php echo form_open($action = site_url('transportation/updatetranspo'), $attributes = array('id' => 'transpoapproval' , 
    'name' => 'transpoapproval', 'role' => 'form')); ?>
        <?php echo form_hidden('id', $transpo->ID); ?>
        <h4>From:</h4>
        <?php echo form_input(array(
            'id' => 'transpofrom',
            'name' => 'transpofrom',
            'class' => 'form-control date-picker',
            'required' => 'required'
        ), convert_date($transpo->Datefrom, "Y-m-d")); ?>
        <h4>To:</h4>
        <?php echo form_input(array(
            'id' => 'transpoto',
            'name' => 'transpoto',
            'class' => 'form-control date-picker',
            'required' => 'required'
        ), convert_date($transpo->Dateto, "Y-m-d")); ?>
        <h4>Remarks</h4>
        <?php echo form_textarea(array(
            'name' => 'remarks',
            'id' => 'remarks',
            'class' => 'form-control',
            'rows' => '7'
        ), $transpo->Remarks, 'required'); ?>
        <div class="modal-footer-nobg text-right">
            <?php echo form_button(array(
                'class' => 'btn btn-default',
                'data-dismiss' => 'modal',
                'content' => 'Close'
            )); ?>
            <?php if ($transpo->Status == 0): ?>
                <?php echo form_submit(array(
                    'class' => 'btn btn-primary',
                    'id' => 'btnupdatetranspo',
                    'name' => 'btnupdatetranspo',
                    'value' => 'Update'
                )); ?>
            <?php endif; ?>
        </div>
    <?php echo form_close(); ?>
</div>    
<script type="text/javascript">
    $(document).ready(function(){
        $('#transpofrom').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
        
        $('#transpoto').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });
</script>