<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php if (isset($title)): ?>
        <title><?php echo $title; ?></title>
    <?php else: ?>
        <title>Untitled</title>
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo base_url($this->config->item('css_directory') . 'bootstrap.min.css') ; ?>">
    <link rel="stylesheet" href="<?php echo base_url($this->config->item('css_directory') . 'googlefonts.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url($this->config->item('css_directory') . 'font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url($this->config->item('css_directory') . 'ace.min.css'); ?>">
    <?php if (isset($stylesheet)  && count($stylesheet) !== 0 ): ?>
        <?php for ( $css = 0; $css < count($stylesheet); $css++ ): ?>
            <?php if(!empty($stylesheet[$css])): ?>
                <link rel="stylesheet" href="<?php echo base_url($this->config->item('css_directory') . $stylesheet[$css]) ;?>.css">
            <?php endif; ?>
        <?php endfor; ?>    
    <?php endif; ?> 
    <!--[if lte IE 9]>
        <link rel="stylesheet" href="<?php echo base_url('ace-part2.min.css'); ?>" class="ace-main-stylesheet" />
    <![endif]-->

    <!--[if lte IE 9]>
        <link rel="stylesheet" href="<?php echo base_url('ace-ie.min.css'); ?>" />
    <![endif]-->
    
    <script type="text/javascript" src="<?php echo base_url($this->config->item('js_directory') . 'ace-extra.min.js'); ?>"></script>
    
    <?php if (isset($javascript) && count($javascript) !== 0): ?>
        <?php for ($js = 0; $js < count($javascript); $js++): ?>
            <?php if(!empty($javascript[$js])): ?>
                <script type="text/javascript" src="<?php echo base_url($this->config->item('js_directory') . $javascript[$js]); ?>.js"></script>
            <?php endif; ?>
        <?php endfor; ?>
    <?php endif; ?>
                
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo base_url($this->config->item('js_directory') . 'html5shiv.min.js'); ?>"></script>
      <script src="<?php echo base_url($this->config->item('js_directory') . 'respond.min.js'); ?>"></script>
    <![endif]-->
  </head>
  <body class="no-skin">
    <?php $this->load->view('masterpage/acenavbar'); ?>
      <div id="main-container" class="main-container">
          <script type="text/javascript">
            try{ace.settings.check('main-container' , 'fixed')}catch(e){}
          </script>
          <?php $this->load->view('masterpage/acesidebar'); ?>
          <?php if (isset($tpl) && $tpl != ""): ?>
            <div class="main-content">
                <div class="main-content-inner">
                    <div class="page-content">
                        <?php if (isset($template_data) && !empty($template_data)): ?>
                            <?php $this->load->view($tpl, $template_data); ?>
                        <?php else : ?>
                            <?php $this->load->view($tpl); ?>
                        <?php endif; ?>
                    </div>
                </div><!-- /.main-content-inner -->
            </div>
            <!-- /.main-content -->
          <?php endif; ?>          
          <?php $this->load->view('masterpage/acefooter'); ?>
      </div>
      <!-- /.main-container -->      
  </body>
</html>