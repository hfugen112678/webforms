<div class="container">
    <?php echo form_open($action = '', $attributes = array('id' => 'login', 
        'name' => 'login',
        'class' => 'form-signin',
        'role' => 'form')); ?>
    <h2 class="form-signin-heading">TREC Pacific - Webforms</h2>
    <?php echo form_label("Employee Number:", "employeenumber", array('class' => 'sr-only')); ?>
    <?php echo form_input(array( 
        'name' => 'employeenumber',
        'id' => 'employeenumber',
        'class' => 'form-control',
        'placeholder' => 'Employee Number'
    ), (isset($employeenumber) ? $employeenumber : ''), 'required autofocus'); ?>
    <?php echo form_label("Password:", "password", array('class' => 'sr-only')); ?>
    <?php echo form_password(array(
        'name' => 'password',
        'id' => 'password',
        'class' => 'form-control',
        'placeholder' => 'Password',
    ), '', 'required'); ?>
    <?php echo form_submit(array(
        'name' => 'btnlogin',
        'value' => 'Log in',
        'class' => 'btn btn-lg btn-primary btn-block'
    )); ?>
    <?php echo form_close(); ?>
    <?php echo validation_errors('<div class="loginerror">', '</div>'); ?>
    <?php if (isset($login_failed)): ?>
        <div class="alert alert-danger loginerror" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <?php echo $login_failed; ?>
        </div>
    <?php endif; ?>    
</div>
<script type="text/javascript">
    $(document).ready(function(){        
        $('form').submit(function(e){
           if ($('#employeenumber').val().trim() == "" || $('#password').val().trim() == "")
           {
               e.preventDefault();
           }
        });
    });
</script>