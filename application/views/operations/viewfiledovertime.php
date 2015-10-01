<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>Overtime - # <?php echo $ot_details->UserlogID; ?></strong>
    </div>
</div>
<div class="modal-body">
    <?php echo form_open($action = site_url('operations/updateot'), $attributes = array('id' => 'filedot', 
    'name' => 'filedot', 'role' => 'form')); ?>        
    <?php echo form_hidden(array('userlogid' => $ot_details->UserlogID, 
        'overtime-oldval' => format_ot($ot_details->Overtime),
        'otrd-oldval' => format_ot($ot_details->OTRD),
        'otsh-oldval' => format_ot($ot_details->OTSH),
        'otshr-oldval' => format_ot($ot_details->OTSHR),
        'otlh-oldval' => format_ot($ot_details->OTLH),
        'otlhr-oldval' => format_ot($ot_details->OTLHR),)); ?>
    <div class="widget-box">
        <div class="widget-body">
            <div class="widget-main">
                <?php if (isset($ot_details->Overtime) && intval($ot_details->Overtime) > 0): ?>
                <div>
                    <label>Regular Overtime</label>
                    <?php echo form_input(array(
                        'name' => 'overtime',
                        'id' => 'overtime',
                        'class' => 'form-control filed-ot',
                        'value' => iif(isset($ot_details->ApprovedOT) && intval($ot_details->ApprovedOT) > 0, $ot_details->ApprovedOT, format_ot($ot_details->Overtime))
                    )); ?>
                </div>                
                <hr />
                <?php endif; ?>
                <?php if (isset($ot_details->OTRD) && intval($ot_details->OTRD) > 0): ?>
                <div>
                    <label>OT Restday</label>
                    <?php echo form_input(array(
                        'name' => 'otrd',
                        'id' => 'otrd',
                        'class' => 'form-control filed-ot',
                        'value' => iif(isset($ot_details->ApprovedOTRD) && intval($ot_details->ApprovedOTRD) > 0, $ot_details->ApprovedOTRD, format_ot($ot_details->OTRD))
                    )); ?>
                </div>                
                <hr />
                <?php endif; ?>
                <?php if (isset($ot_details->OTSH) && intval($ot_details->OTSH) > 0): ?>
                <div>
                    <label>OT Special Holiday</label>
                    <?php echo form_input(array(
                        'name' => 'otsh',
                        'id' => 'otsh',
                        'class' => 'form-control filed-ot',
                        'value' => iif(isset($ot_details->ApprovedOTSH) && intval($ot_details->ApprovedOTSH) > 0, $ot_details->ApprovedOTSH, format_ot($ot_details->OTSH))
                    )); ?>
                </div>                
                <hr />
                <?php endif; ?>
                <?php if (isset($ot_details->OTSHR) && intval($ot_details->OTSHR) > 0): ?>
                <div>
                    <label>OT Special Holiday Restday</label>
                    <?php echo form_input(array(
                        'name' => 'otshr',
                        'id' => 'otshr',
                        'class' => 'form-control filed-ot',
                        'value' => iif(isset($ot_details->ApprovedOTSHR) && intval($ot_details->ApprovedOTSHR) > 0, $ot_details->ApprovedOTSHR, format_ot($ot_details->OTSHR))
                    )); ?>
                </div>                
                <hr />
                <?php endif; ?>
                <?php if (isset($ot_details->OTLH) && intval($ot_details->OTLH) > 0): ?>
                <div>
                    <label>OT Legal Holiday</label>
                    <?php echo form_input(array(
                        'name' => 'otlh',
                        'id' => 'otlh',
                        'class' => 'form-control filed-ot',
                        'value' => iif(isset($ot_details->ApprovedOTLH) && intval($ot_details->ApprovedOTLH) > 0, $ot_details->ApprovedOTLH, format_ot($ot_details->OTLH))
                    )); ?>
                </div>                
                <hr />
                <?php endif; ?>
                <?php if (isset($ot_details->OTLHR) && intval($ot_details->OTLHR) > 0): ?>
                <div>
                    <label>OT Legal Holiday Restday</label>
                    <?php echo form_input(array(
                        'name' => 'otlhr',
                        'id' => 'otlhr',
                        'class' => 'form-control filed-ot',
                        'value' => iif(isset($ot_details->ApprovedOTLHR) && intval($ot_details->ApprovedOTLHR) > 0, $ot_details->ApprovedOTLHR, format_ot($ot_details->OTLHR))
                    )); ?>
                </div>                
                <hr />
                <?php endif; ?>
                <div>
                    <label>Remarks</label>
                    <?php echo form_textarea(array(
                        'name' => 'otreason',
                        'id' => 'otreason',
                        'class' => 'autosize-transition form-control',
                        'value' => $ot_details->OTReason,
                        'rows' => 5
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer-nobg text-right">
        <?php echo form_button(array(
            'class' => 'btn btn-default',
            'data-dismiss' => 'modal',            
            'content' => 'Close'
        )); ?>
        <?php if ($approved == 'N' && substr($ot_details->OTReason, 0, 8) != 'Declined'): ?>
            <?php echo form_submit(array(
                'class' => 'btn btn-primary',
                'name' => 'btnsaveot',
                'id' => 'btnsaveot',
                'value' => 'Save changes'
            )); ?>
        <?php endif; ?>
    </div>
    <?php echo form_close(); ?>
</div>