<div class="page-header">
    <h1>
        Leaves
        <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                File Leave
        </small>
    </h1>
</div><!-- /.page-header -->
<div id="leave-credits" class="row">
    <div class="col-xs-12">
        <div class="alert alert-block alert-success">
            <div class="pull-left">
                <i class="ace-icon fa fa-check green"></i>
                Leave Credits:
            </div>
            <ul class="list-inline">
                <li>VL: <strong><?php echo $leavecredits->Vacation; ?></strong></li> 
                <li>SL: <strong><?php echo $leavecredits->Sick; ?></strong></li> 
                <li>EL: <strong><?php echo $leavecredits->Emergency; ?></strong></li> 
                <li>ML: <strong><?php echo $leavecredits->Maternity; ?></strong></li> 
                <li>PL: <strong><?php echo $leavecredits->Paternity; ?></strong></li> 
            </ul>
        </div>
    </div>
</div> 
<div class="col-sm-8">
    <div class="space"></div>
    <div id="calendar"></div>
</div>
<div class="col-sm-4">
    <div class="widget-box transparent">
        <div class="widget-header">
            <h4>Holidays</h4>
        </div>
        <div class="widget-body">
            <div class="widget-main no-padding">                
                <div id="external-events">
                    <?php if (isset($holidays) && !empty($holidays)): ?>
                        <?php for ($i = 0; $i < count($holidays); $i++) : ?>
                            <?php if ($holidays[$i]->Type == 'Legal Holiday') : ?>
                                <div class="external-event label-danger">
                            <?php else : ?>
                                 <div class="external-event label-yellow">
                            <?php endif; ?>
                                <i class="ace-icon fa fa-flag-o"></i>
                                <abbr title="<?php echo convert_date($holidays[$i]->Date, "M d, Y"); ?>">
                                    <?php echo $holidays[$i]->Name; ?>
                                </abbr>
                            </div>
                        <?php endfor; ?>
                    <?php else : ?>
                        <div class="external-event label-info">
                            <i class="ace-icon fa fa-calendar-times-o"></i>
                            No upcoming holidays.
                        </div>
                    <?php endif; ?>
                    <label>
                        <div class="col-xs-1 label-danger">&nbsp;</div>&nbsp;
                        Legal Holiday
                    </label>
                    <label>
                        <div class="col-xs-1 label-yellow">&nbsp;</div>&nbsp;
                        Special Holiday
                    </label>
                </div>                    
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Modal Pop up for viewing/ filing leaves -->
<div class="modal fade" id="leaveModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            
        </div>
    </div>
</div>
<!-- eof Bootstrap Modal Pop up -->