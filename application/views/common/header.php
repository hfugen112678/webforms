<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php if (isset($title)): ?>
<title><?php echo $title; ?></title>
<?php else: ?>
    <title>Untitled</title>
<?php endif; ?>
<?php if (isset($stylesheet)  && count($stylesheet) !== 0 ): ?>
    <?php for ( $css = 0; $css < count($stylesheet); $css++ ): ?>
        <?php if(!empty($stylesheet[$css])): ?>
            <link href="<?php echo base_url($this->config->item('css_directory') . $stylesheet[$css]) ;?>.css" rel="stylesheet" type="text/css" media="all" />
        <?php endif; ?>
    <?php endfor; ?>    
<?php endif; ?>        
<?php if (isset($print_css)  && count($print_css) !== 0 ): ?>
    <?php for ( $p = 0; $p < count($print_css); $p++ ): ?>
        <?php if(!empty($print_css[$p])): ?>
            <link href="<?php echo base_url($this->config->item('css_directory') . $stylesheet[$p]) ;?>.css" rel="stylesheet" type="text/css" media="print" />
        <?php endif; ?>
    <?php endfor; ?>    
<?php endif; ?>
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
<?php if (isset($bodyclass)): ?>
    <body class="<?php echo $bodyclass; ?>">
<?php else : ?>
    <body>
<?php endif; ?>