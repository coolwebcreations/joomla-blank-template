<?php defined( '_JEXEC' ) or die;

// variables
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$menu = $app->getMenu();
$active = $app->getMenu()->getActive();
$params = $app->getParams();
$pageclass = $params->get( 'pageclass_sfx' );
$tpath = $this->baseurl . '/templates/' . $this->template;

// remove generator tag
$this->setGenerator( null );

// template js
$doc->addScript( $tpath.'/js/logic.js' );

//add critical css support (aka "above the fold" css)
$critical = JPATH_THEMES . '/' . $this->template . '/css/critical.css';
$criticalcss = @file_get_contents( $critical ); //dont throw error if no file

if ( ! empty($criticalcss) ) {
    $doc->addStyleDeclaration($criticalcss);
}
$doc->addStylesheet('some/stylesheet.css');

//move scripts and css to the end of body to keep it from blocking
$scripts = '';
foreach( $doc->_styleSheets as $sheet => $settings ) {
    $media = false == $settings['media'] ? '' : ' media="' . $settings['media'] . '"';
    $scripts .= '<link ' . $media . $type . 'rel="stylesheet" href="' . $sheet . '">';
    unset( $doc->_styleSheets[$sheet] );
}

foreach( $doc->_scripts as $script => $settings) {
    $async = false == $settings['async'] ? '' : ' async';
    $defer = false == $settings['defer'] ? '' : ' defer';
    $scripts .= '<script' . $async . $defer . ' type="text/javascript" src="' . $script . '"></script>' . "\n";
    unset( $doc->_scripts[$script] );
}