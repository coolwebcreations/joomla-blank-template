<?php defined( '_JEXEC' ) or die;

//defaults
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$doc->setHtml5( true );

$menu = $app->getMenu();
$active = $app->getMenu()->getActive();
$params = $app->getParams();
$pageclass = $params->get( 'pageclass_sfx' );
$tpath = $this->baseurl . '/templates/' . $this->template;

$templateCSS = $tpath . '/css/template.min.css';

if ( isset( $devmode ) ) {
  $templateCSS = $tpath . '/css/template.css.php';
}

// remove generator tag
$this->setGenerator( null );

//add critical css support (aka "above the fold" css)
$critical = JPATH_THEMES . '/' . $this->template . '/css/critical.css';
$criticalcss = @file_get_contents( $critical ); //dont throw error if no file

if ( ! empty( $criticalcss ) ) {
    $doc->addStyleDeclaration( $criticalcss );
}

switch ( $framework ){
   case 'bootstrap':
       $direction = 'ltr';
       JHtmlBootstrap::framework();
       JHtmlBootstrap::loadCSS( true, $direction );
       break;
   
   case 'bootstrap-current':
       JHtml::_( 'jquery.framework' );
       $doc->addStyleSheet( $tpath . '/css/bootstrap.min.css' );
       $doc->addScript( $tpath.'/js/bootstrap.min.js' );
       break;
   
   case 'skeleton':
   case 'skeleton-framework':
       $doc->addStyleSheet( $tpath . '/css/skeleton.min.css' );
       break;
   
   case 'materialize':
       JHtml::_( 'jquery.framework' );
       $doc->addStyleSheet( '//fonts.googleapis.com/icon?family=Material+Icons' );
       $doc->addStyleSheet( $tpath . '/css/materialize.min.css' );
       $doc->addScript( $tpath . '/js/materialize.min.js' );
       break;
   
   default:   
       break;
}

if ( isset ( $fontAwesome ) ){
  $doc->addStyleSheet( $tpath . '/css/fontawesome.min.css' );
}

// template js
$doc->addScript( $tpath . '/js/logic.js' );
$doc->addStyleSheet( $templateCSS );

//move scripts and css to the end of body to keep it from blocking
$scripts = '';
foreach( $doc->_styleSheets as $sheet => $settings ) {
    $media = ( false == $settings['media'] ) ? '' : ' media="' . $settings['media'] . '"';
    $scripts .= '<link ' . $media . 'rel="stylesheet" href="' . $sheet . '">';
    unset( $doc->_styleSheets[$sheet] );
}

foreach( $doc->_scripts as $script => $settings) {
    $async = ( false == $settings['async'] ) ? '' : ' async';
    $defer = ( false == $settings['defer'] ) ? '' : ' defer';
    $scripts .= '<script' . $async . $defer . ' type="text/javascript" src="' . $script . '"></script>' . "\n";
    unset( $doc->_scripts[$script] );
}

$scripts .= '<script type="text/javascript">' . implode( ' ' , $doc->_script ) . '</script>';
$doc->_script = array();

//output buffering
ob_start();

function minimize( $body = '') {
  $replace = array(
    //remove tabs before and after HTML tags
    '/\>[^\S ]+/s'   => '>',
    '/[^\S ]+\</s'   => '<',
    //shorten multiple whitespace sequences; keep new-line characters because they matter in JS!!!
    '/([\t ])+/s'  => ' ',
    //remove leading and trailing spaces
    '/^([\t ])+/m' => '',
    '/([\t ])+$/m' => '',
    // remove JS line comments (simple only); do NOT remove lines containing URL (e.g. 'src="http://server.com/"')!!!
    '~//[a-zA-Z0-9 ]+$~m' => '',
    //remove empty lines (sequence of line-end and white-space characters)
    '/[\r\n]+([\t ]?[\r\n]+)+/s'  => "\n",
    //remove empty lines (between HTML tags); cannot remove just any line-end characters because in inline JS they can matter!
    '/\>[\r\n\t ]+\</s'    => '><',
    //remove "empty" lines containing only JS's block end character; join with next line (e.g. "}\n}\n</script>" --> "}}</script>"
    '/}[\r\n\t ]+/s'  => '}',
    '/}[\r\n\t ]+,[\r\n\t ]+/s'  => '},',
    //remove new-line after JS's function or condition start; join with next line
    '/\)[\r\n\t ]?{[\r\n\t ]+/s'  => '){',
    '/,[\r\n\t ]?{[\r\n\t ]+/s'  => ',{',
    //remove new-line after JS's line end (only most obvious and safe cases)
    '/\),[\r\n\t ]+/s'  => '),',
    //remove quotes from HTML attributes that does not contain spaces; keep quotes around URLs!
    //'~([\r\n\t ])?([a-zA-Z0-9]+)="([a-zA-Z0-9_/\\-]+)"([\r\n\t ])?~s' => '$1$2=$3$4', //$1 and $4 insert first white-space character found before/after attribute
  );
  return preg_replace( array_keys( $replace ) , array_values( $replace ) , $body );
}
