<style>
    .datepicker{z-index:1151 !important;}
</style>
<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>Adjustment - # </strong>
    </div>
</div>
<div class="modal-body">
    <?php echo form_open($action = site_url('adjustments/update'), $attributes = array('id' => 'filedadjustment', 
    'name' => 'filedadjustment', 'role' => 'form')); ?>
        <?php echo form_hidden('id', $empadjustments->ID); ?>
        <h5>Type</h5>
            <?php echo form_dropdown('adjustments', 
            $this->config->item('adjustments'), 
            $empadjustments->Type, 
            'class="form-control" id="adjustments"'); ?>
        <h5>Date</h5>
        <?php echo form_input(array(
            'name' => 'dateoccurred',
            'id' => 'dateoccurred',
            'class' => 'form-control date-picker',
            'data-date-format' => 'yyyy-mm-dd',
            'value' => convert_date($empadjustments->DateOccurred, 'Y-m-d')
        )); ?>
        <h5>Payday</h5>
        <?php echo form_dropdown('paydays', 
                $this->config->item('paydays'), 
                $payday, 
                'class="form-control" id="paydays"'); ?>
        <h5>Amount</h5>
        <?php echo form_input(array(
            'name' => 'amount',
            'id' => 'amount',
            'class' => 'form-control'
        ), sprintf('%01.2f', $empadjustments->AdjustmentAmt), 'readonly'); ?>
        <h5>Taxable Amount</h5> 
        <?php echo form_input(array(
            'name' => 'taxableamount',
            'id' => 'taxableamount',
            'class' => 'form-control'
        ), sprintf('%01.2f', $empadjustments->TaxableAmt), 'readonly'); ?> 				
        <h5>Remarks</h5>
        <?php echo form_textarea(array(
            'name' => 'remarks',
            'id' => 'remarks',
            'class' => 'form-control',
            'value' => strip_quotes($empadjustments->Remarks)
        )); ?>    
        <div class="modal-footer-nobg text-right">
            <?php echo form_button(array(
                'class' => 'btn btn-default',
                'data-dismiss' => 'modal',
                'content' => 'Close'
            )); ?>
            <?php if ($empadjustments->Status == 0): ?>
                <?php echo form_submit(array(
                    'name' => 'btnsaveadj',
                    'value' => 'Save changes',
                    'class' => 'btn btn-primary'
                )); ?>
            <?php endif; ?>
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