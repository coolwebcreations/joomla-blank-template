<?php defined( '_JEXEC' ) or die;

// variables
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$menu = $app->getMenu();
$active = $app->getMenu()->getActive();
$params = $app->getParams();
$pageclass = $params->get( 'pageclass_sfx' );
$tpath = $this->baseurl . '/templates/' . $this->template;

// generator tag
$this->setGenerator( null );

// template js
$doc->addScript( $tpath.'/js/logic.js' );


//add critical css
$critical = $tpath . 'css/critical.css';
if ( file_exists( $critical ) ) {
    $criticalcss = file_get_contents( $critical );
    if ( ! empty( $critical ) ){
        $doc->addStyleDeclaration($criticalcss);
    }
}
