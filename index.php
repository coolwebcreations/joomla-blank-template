<?php defined( '_JEXEC' ) or die;

// settings
$devmode = true; //set devmod = false to load static css instead of less
$framework = 'none'; //you may chose between bootstrap, bootstrap-current, skeleton and materialize

include_once JPATH_THEMES . '/' . $this->template . '/logic.php';
?><!DOCTYPE html>

<html lang="<?php echo $this->language; ?>">

<head>
  <jdoc:include type="head" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="apple-touch-icon-precomposed" href="<?php echo $tpath; ?>/images/apple-touch-icon-57x57-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $tpath; ?>/images/apple-touch-icon-72x72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $tpath; ?>/images/apple-touch-icon-114x114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $tpath; ?>/images/apple-touch-icon-144x144-precomposed.png">
</head>
  
<body class="<?php echo ( $menu->getActive() == $menu->getDefault() ? 'front' : 'site' ) . ' ' . $active->alias . ' ' . $pageclass; ?>">
  <!--Your code here-->
  <?php echo $scripts; ?>
</body>

</html>

<?php

echo minimize( ob_get_clean() );
