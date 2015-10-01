<div class="page-header">
    <h1>
        Agents
        <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Offense Details
        </small>
    </h1>
</div><!-- /.page-header -->
<div class="col-sm-offset-1 col-sm-10">
    <h4 class="header blue bolder smaller">Employee</h4>
    <p>
        <strong>Name of Employee: <?php echo $offense->fname; ?></strong>
    </p>
    <p>
        <strong>Team/group: <?php echo $offense->campaign; ?></strong>
    </p>
    <h4 class="header blue bolder smaller">Details</h4>
    <p>
        <strong>Date of offense: <?php echo convert_date($offense->OffenseDate, 'M d, Y'); ?></strong>
    </p>
    <p>
        <strong>Violation Code: <?php echo $offense->OffenseCode; ?></strong>
    </p>
    <p>
        <strong>Details of the offense committed:</strong>
    </p>
    <p>
         <?php echo $offense->OffenseDescription; ?>
    </p>
    <p>
        <strong>Points incurred: <?php echo $offense->Points; ?> pts.</strong>
    </p>
    <p>
        <strong>Penalty: <?php echo $offense->Definition; ?></strong>
    </p>
</div>