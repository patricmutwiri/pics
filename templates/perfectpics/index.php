<?php
/**
 * @package Helix3 Framework
 * Template Name - Shaper Startup Biz
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2015 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

$doc = JFactory::getDocument();

JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework'); //Force load Bootstrap
unset($doc->_scripts[$this->baseurl . '/media/jui/js/bootstrap.min.js']); // Remove joomla core bootstrap

//Load Helix
$helix3_path = JPATH_PLUGINS.'/system/helix3/core/helix3.php';

if (file_exists($helix3_path)) {
    require_once($helix3_path);
    $this->helix3 = helix3::getInstance();
} else {
    die('Please install and activate helix plugin');
}

//Coming Soon
if($this->helix3->getParam('comingsoon_mode')) header("Location: ".$this->baseUrl."?tmpl=comingsoon");

//Class Classes
$body_classes = '';

if($this->helix3->getParam('sticky_header')) {
    $body_classes .= ' sticky-header';
}

$body_classes .= ($this->helix3->getParam('boxed_layout', 0)) ? ' layout-boxed' : ' layout-fluid';

//Menu Class
$app = JFactory::getApplication();
$menu = $app->getMenu()->getActive();

if(isset($menu)) {
    if(trim($menu->params->get('pageclass_sfx', ''))) {
        $body_classes .= ' page-suffix-' . trim($menu->params->get('pageclass_sfx', ''));
    }
}

//Body Background Image
if($bg_image = $this->helix3->getParam('body_bg_image')) {

    $body_style  = 'background-image: url(' . JURI::base(true ) . '/' . $bg_image . ');';
    $body_style .= 'background-repeat: '. $this->helix3->getParam('body_bg_repeat') .';';
    $body_style .= 'background-size: '. $this->helix3->getParam('body_bg_size') .';';
    $body_style .= 'background-attachment: '. $this->helix3->getParam('body_bg_attachment') .';';
    $body_style .= 'background-position: '. $this->helix3->getParam('body_bg_position') .';';
    $body_style  = 'body.site {' . $body_style . '}';

    $doc->addStyledeclaration( $body_style );
}

//Body Font
$webfonts = array();

if( $this->params->get('enable_body_font') ) {
    $webfonts['body'] = $this->params->get('body_font');
}

//Heading1 Font
if( $this->params->get('enable_h1_font') ) {
    $webfonts['h1'] = $this->params->get('h1_font');
}

//Heading2 Font
if( $this->params->get('enable_h2_font') ) {
    $webfonts['h2'] = $this->params->get('h2_font');
}

//Heading3 Font
if( $this->params->get('enable_h3_font') ) {
    $webfonts['h3'] = $this->params->get('h3_font');
}

//Heading4 Font
if( $this->params->get('enable_h4_font') ) {
    $webfonts['h4'] = $this->params->get('h4_font');
}

//Heading5 Font
if( $this->params->get('enable_h5_font') ) {
    $webfonts['h5'] = $this->params->get('h5_font');
}

//Heading6 Font
if( $this->params->get('enable_h6_font') ) {
    $webfonts['h6'] = $this->params->get('h6_font');
}

//Navigation Font
if( $this->params->get('enable_navigation_font') ) {
    $webfonts['.sp-megamenu-parent'] = $this->params->get('navigation_font');
}

//Custom Font
if( $this->params->get('enable_custom_font') && $this->params->get('custom_font_selectors') ) {
    $webfonts[ $this->params->get('custom_font_selectors') ] = $this->params->get('custom_font');
}

$this->helix3->addGoogleFont($webfonts);

//Custom CSS
if($custom_css = $this->helix3->getParam('custom_css')) {
    $doc->addStyledeclaration( $custom_css );
}

//Custom JS
if($custom_js = $this->helix3->getParam('custom_js')) {
    $doc->addScriptdeclaration( $custom_js );
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    if($favicon = $this->helix3->getParam('favicon')) {
        $doc->addFavicon( JURI::base(true) . '/' .  $favicon);
    } else {
        $doc->addFavicon( $this->helix3->getTemplateUri() . '/images/favicon.ico' );
    }
    ?>

    <jdoc:include type="head" />

    <?php

    $this->helix3->addCSS('bootstrap.min.css, font-awesome.min.css') // CSS Files
        ->addJS('bootstrap.min.js, jquery.sticky.js, main.js, customJS.js, custom.js') // JS Files
        ->lessInit()->setLessVariables(array(
            'preset'=>$this->helix3->Preset(),
            'bg_color'=> $this->helix3->PresetParam('_bg'),
            'text_color'=> $this->helix3->PresetParam('_text'),
            'major_color'=> $this->helix3->PresetParam('_major')
            ))
        ->addCSS('custom.css')
        ->addCSS('formats.css')
        ->addLess('legacy/bootstrap', 'legacy')
        ->addLess('master', 'template');

        //RTL
        if($this->direction=='rtl') {
            $this->helix3->addCSS('bootstrap-rtl.min.css')
            ->addLess('rtl', 'rtl');
        }

        $this->helix3->addLess('presets',  'presets/'.$this->helix3->Preset(), array('class'=>'preset'));

        //Before Head
        if($before_head = $this->helix3->getParam('before_head')) {
            echo $before_head . "\n";
        }
    ?>
</head>
<body class="<?php echo $this->helix3->bodyClass( $body_classes ); ?>">
    <div class="body-innerwrapper">
        <?php $this->helix3->generatelayout(); ?>

        <div class="offcanvas-menu">
            <a href="#" class="close-offcanvas"><i class="fa fa-remove"></i></a>
            <div class="offcanvas-inner">
                <?php if ($this->helix3->countModules('offcanvas')) { ?>
                    <jdoc:include type="modules" name="offcanvas" style="sp_xhtml" />
                <?php } else { ?>
                    <p class="alert alert-warning"><?php echo JText::_('HELIX_NO_MODULE_OFFCANVAS'); ?></p>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php

    if($this->params->get('compress_css')) {
        $this->helix3->compressCSS();
    }

    if($this->params->get('compress_js')) {
        $this->helix3->compressJS( $this->params->get('exclude_js') );
    }

    if($before_body = $this->helix3->getParam('before_body')) {
        echo $before_body . "\n";
    }

    ?>
    <jdoc:include type="modules" name="debug" />

<!--div overlay--->
<div id="overlayBody">
	<div id="contentHolder">
  	<p>Thank you for choosing to download the software. Please fill in you details so that we can keep you up to date with our product, including updates and useful hints and tips. In downloading this software, you agree to the terms and conditions of this service. We respect your privacy and your details will never be passed to third parties</p>
    <span>Name</span><span class="errorName"></span>
    <input type="text" name="nameField" id="nameField" placeholder="Please enter your name" />
    <span>Email Address</span><span class="errorEmail"></span>
    <input type="text" name="emailField" id="emailField" placeholder="Please enter your Email address" />
    <div class="submitbtn">
    	<table border="0" cellspacing="0" cellpadding="0">
      	<tr>
        	<td class="textHere">Submit Details</td>
          <td class="loading"></td>
          <td class="success"></td>
        </tr>
      </table>
     </div>
     <div class="downloadBar">
     	<div class="windowsDownload"><a href="perfectpicssofware/PerfectPics3.2.0PERFECTPICS.exe" title="Download the Windows Version">Download</a></div>
     	<div class="macDownload"><a href="macsoftware/PerfectPics3.2.0PERFECTPICS.dmg" title="Download the Mac Version">Download</a></div>
     	<div class="clr"></div>
     </div>
  </div>
  <div class="closeBar"><span>Close X</span></div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="downloadform">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Download PerfectPics Software</h4>
      </div>
      <div class="modal-body">
        <p>Thank you for choosing to download the software. Please fill in you details so that we can keep you up to date with our product, including updates and useful hints and tips. In downloading this software, you agree to the terms and conditions of this service. We respect your privacy and your details will never be passed to third parties</p>

        <form class="form-validate form-horizontal" action="http://localhost/perfectpics_v21/register.php" method="post">
          <div class="form-group">
    				<label id="nameField-lbl" for="nameField" class="col-md-2" title="<strong>Name</strong><br />Your name.">Name<span class="star">&nbsp;*</span></label>
    					<div class="col-sm-10 col-md-10">
    					<input type="text" name="nameField" id="nameField" value="shadow" class="required form-control" size="30" required="required" aria-required="true" placeholder="Please enter your Name">
    				</div>
    			</div>
          <div class="clearfix">  </div>
          <div class="form-group">
    				<label id="emailField-lbl" for="nameField" class="col-md-2" title="<strong>Email</strong><br />Your email.">Email<span class="star">&nbsp;*</span></label>
    					<div class="col-sm-10 col-md-10">
    					<input type="email" name="emailField" id="emailField" value="mbuluma@gbc.co.ke" class="required form-control" size="30" required="required" aria-required="true" placeholder="Please enter your Email address">
    				</div>
    			</div>
         <div class="downloadBar">
         	<div class="windowsDownload"><a href="perfectpicssofware/PerfectPics3.2.0PERFECTPICS.exe" title="Download the Windows Version">Download</a></div>
         	<div class="macDownload"><a href="macsoftware/PerfectPics3.2.0PERFECTPICS.dmg" title="Download the Mac Version">Download</a></div>
         	<div class="clr"></div>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" id="form_send_btn" class="btn btn-primary">Submit</button>
      </div>

    </form>
    </div>
  </div>
</div>
<!-- newsletter subscription -->
<?php if ($this->helix3->countModules('subscribe')) { ?>
<div id="TableData" style="display: inline-block;width: 100%;" class="hidden-xs">
  <button type="button" role="button" class="expander"></button>

  <div class="email-signup__content">
      <div class="email-signup__group">
  <h5 id="email-signup__form-heading" class="email-signup__heading">
    Join &amp; save 20%
  </h5>
  <p id="email-signup__form-copy" class="email-signup__copy">
    <label for="email_signup__address">Join PerfectPics's newsletter for exclusive offers and 20% off your first order!</label>
  </p>
</div>
<fieldset class="email-signup__group">
  <div class="email-signup__field-group">
    <jdoc:include type="modules" name="subscribe" style="sp_xhtml" />
  </div>
</fieldset>
</div>
</div>
<?php } ?>
<!-- newsletter subscription -->
</body>
</html>
