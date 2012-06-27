<?php
/**
 * @version		$Id: mod_vt_cu3ox_slideshow.php 2012-06-20 vinaora $
 * @package		VINAORA CU3OX SLIDESHOW
 * @subpackage	mod_vt_cu3ox_slideshow
 * @copyright	Copyright (C) 2012 VINAORA. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @website		http://vinaora.com
 * @twitter		http://twitter.com/vinaora
 * @facebook	http://facebook.com/vinaora
 * @google+		https://plus.google.com/111142324019789502653
 */

// no direct access
defined('_JEXEC') or die;

// Require the base helper class only once
require_once dirname(__FILE__).DS.'helper.php';

// Add SWFObject library. Check SWFObject loaded or not.
$app = JFactory::getApplication();
$sobjsource		= $params->get('swfobject_source', 'local');
$sobjversion	= $params->get('swfobject_version', '2.2');

if($app->get('swfobject') == false) {
	modVtCu3oxSlideshowHelper::addSWFObject( $sobjsource, $sobjversion );
	$app->set('swfobject', true);
}

$module_id	= $module->id;
$params->set('ID', $module->id);

$base_url	= rtrim(JURI::base(true), '/');

modVtCu3oxSlideshowHelper::validParams( $params );
modVtCu3oxSlideshowHelper::makeFiles( $params );

$FirstImage 	= $params->get('FirstImage');
$swf			= $params->get('EngineURL').'/vt_cu3ox_slideshow.swf';
if($params->get('NoShadow') == '1'){
	$PanelWidth		= $params->get('ImageWidth');
	$PanelHeight	= $params->get('ImageHeight');
}else{
	$PanelWidth		= (int) $params->get('ImageWidth') + 2*$params->get('MarginHoz', '80');;
	$PanelHeight	= (int) $params->get('ImageHeight') + 2*$params->get('MarginVer', '80');
}

require JModuleHelper::getLayoutPath('mod_vt_cu3ox_slideshow', $params->get('layout', 'default'));
