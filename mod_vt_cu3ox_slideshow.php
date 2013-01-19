<?php
/**
 * @package		VINAORA CU3OX SLIDESHOW
 * @subpackage	mod_vt_cu3ox_slideshow
 * @copyright	Copyright (C) 2012-2013 VINAORA. All rights reserved.
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
require_once dirname(__FILE__) . '/helper.php';

// Add SWFObject library. Check SWFObject loaded or not.
$app =& JFactory::getApplication();
$sobjsource		= $params->get('swfobject_source', 'local');
$sobjversion	= $params->get('swfobject_version', 'latest');

if(!isset($app->swfobject) || $app->swfobject === false) {
	modVtCu3oxSlideshowHelper::addSWFObject( $sobjsource, $sobjversion );
	$app->swfobject = true;
}

$module_id	= $module->id;
$base_url	= rtrim(JURI::base(true), '/');

modVtCu3oxSlideshowHelper::makeFiles( $params, $module_id );

$FirstImage 	= modVtCu3oxSlideshowHelper::getFirstItem( $params );
$swf			= $params->get('EngineURL') . '/vt_cu3ox_slideshow.swf';

$ImageWidth		= $params->get('ImageWidth');
$ImageHeight	= $params->get('ImageHeight');

$FirstImage		= "<img src=\"$base_url/$FirstImage\" alt=\"Vinaora Cu3ox Slideshow\" width=\"$ImageWidth\" height=\"$ImageHeight\"/>";

if($params->get('NoShadow')){
	$PanelWidth		= $ImageWidth;
	$PanelHeight	= $ImageHeight;
}else{
	$PanelWidth		= (int) $ImageWidth + 2*$params->get('MarginHoz', '70');
	$PanelHeight	= (int) $ImageHeight + 2*$params->get('MarginVer', '70');
}

// Todo: Add SWFObject param
// Todo: Add z-index param

require JModuleHelper::getLayoutPath('mod_vt_cu3ox_slideshow', $params->get('layout', 'default'));
