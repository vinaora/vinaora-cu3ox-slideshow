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

$module_id	= $module->id;
$base_url	= rtrim(JURI::base(true),'/');

// Initialize some variables
$params->set('ImageWidth', '640');
$params->set('ImageHeight', '360');
$params->set('Segments', '5');
$params->set('TweenTime', '1200');
$params->set('TweenDelay', '100');
$params->set('TweenType', 'easeInOutBack');
$params->set('ZDistance', '0');
$params->set('Expand', '20');

$params->set('InnerColor', '0x111111');
$params->set('TextBackground', '0x333333');
$params->set('StartBackground', '0xcccccc');

$params->set('NoLogo', '0');
$params->set('ShadowDarkness', '100');
$params->set('TextDistance', '3');
$params->set('AutoPlayDelay', '3');
$params->set('RDirection', 'random');

$params->set('BorderRadius', '16');
$params->set('DescWidth', '610');
$params->set('DescHeight', '60');
$params->set('DescOffset', '290');
$params->set('DescType', 'upDown');

$params->set('LogoText', '');
$params->set('LogoLink', '');

$params->set('NoShadow', '0');
$params->set('ShowControls', 'true');

$params->set('LogoFile', '');
$params->set('AutoLoop', '1');
$params->set('SoundFile', '');






