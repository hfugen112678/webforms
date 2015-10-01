<div class="modal-header no-padding">
    <div class="table-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
        </button>
        <strong>Transpo Allowance - # <?php echo $transpoallowance->ID; ?></strong>
    </div>
</div>
<div class="modal-body">
    <?php echo form_open($action='transpohrapproval/update',$attributes = array('class' => 'class=form-horizontal', 
    'role' => 'form', 'id' => 'updatetranspo')); ?>
        <?php echo form_hidden(array('employeenumber' => $employee->EmployeeNumber, 
            'transpoid' => $transpoallowance->ID)); ?>
        <div class="row">
            <div class="col-md-12">
                <?php echo form_label('Employee Number: ' . $employee->EmployeeNumber); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?php echo form_label('First Name: ' . $employee->FirstName); ?>
            </div>
            <div class="col-md-6">
                <?php echo form_label('Last Name: ' . $employee->LastName); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php echo form_label('Last Updated: ' . convert_date($transpoallowance->AuditDate, "M d, Y h:i A")); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">                
                <?php echo form_checkbox(array(
                    'name' => 'approveall',
                    'id' => 'approveall',
                    'value' => 1,)); ?>&nbsp;<strong>Approve All</strong>
            </div>
        </div>
        <div class="spacer-sm-15"></div>
            <div id="coverage-list">
                <?php echo $coverage; ?>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_button(array(
                'class' => 'btn btn-default',
                'data-dismiss' => 'modal',
                'content' => 'Close'
            )); ?>
        </div>    
    <?php echo form_close(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#approveall").click(function(){
            if ($('.chkapprove').length !== 0) {
                if (this.checked) {
                    $('.chkapprove').each(function(){
                        this.checked = true; 
                        $(this).updateapprovedtranspo(this.checked, this.value);
                    });
                } else {
                    $('.chkapprove').each(function(){
                        this.checked = false; 
                        $(this).updateapprovedtranspo(this.checked, this.value);
                    });
                }               
            } else {
               alert('No records found.');
            }
        });
        
        $('.chkapprove').click(function(){
           $(this).updateapprovedtranspo(this.checked, this.value);           
        });
    });
</script>