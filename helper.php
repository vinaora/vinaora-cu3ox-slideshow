<?php
/**
 * @version		$Id: helper.php 2012-06-20 vinaora $
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

class modVtCu3oxSlideshowHelper{

	function __construct(){
	}

	public static function &validParams($params){
	
		$param	= intval($params->get('ImageWidth'));
		$param	= (!$param) ? '640' : $param;
		$params->set('ImageWidth', $param);
		
		$param	= intval($params->get('ImageHeight'));
		$param	= (!$param) ? '480' : $param;
		$params->set('ImageHeight', $param);
		
		$param	= intval($params->get('TweenTime', 1200));
		$params->set('TweenTime', $param/1000);
		
		$param	= intval($params->get('TweenDelay', 100));
		$params->set('TweenDelay', $param/1000);
		
		$param	= intval($params->get('ZDistance', 0));
		$params->set('ZDistance', $param);
		
		$params->set('ImageList', self::_getImageSettings($params));
	}
	
	private static function _makeXML($params){
		$xml	= '';

		$path	= JPath::clean( JPATH_BASE.'/media/mod_vt_cu3ox_slideshow/templates/cu3oxXML.xml' );
		
		$xml .= file_get_contents( $path );
		
		// Replace XML variables
		$xml	= preg_replace( "/\\$(\w+)\\$/e", '$params->get("$1")', $xml );
		
		// Make file XML
		JFile::write( $params->get('configPath').DS.'vt_cu3ox_slideshow.xml', $xml);
	}
	
	private static function _getImageSettings( $params ){
		// Create Element - <Cu3ox>
		$node = new SimpleXMLElement('<Cu3ox />');
		
		foreach($images as $position=>$image){

			// Create Element - <Cu3ox>.<Image>
			$nodeL1 =& $node->addChild('Image');
			$nodeL1->addAttribute('Filename', $image);

			// Create Element - <Cu3ox>.<Image>.<Settings>
			$nodeL2 =& $nodeL1->addChild('Settings');
			
				// Create Element - <Cu3ox>.<Image>.<Settings>.<goLink>
				$param = $params->get('goLink');
				$nodeL2->addChild('goLink', self::getParam($param, $position));
			
				// Create Element - <Cu3ox>.<Image>.<Settings>.<rDirection>
				$param = $params->get('rDirectionItems');
				$nodeL2->addChild('rDirection', self::getParam($param, $position));
			
				// Create Element - <Cu3ox>.<Image>.<Settings>.<segments>
				$param = $params->get('segmentsItems');
				$nodeL2->addChild('segments', self::getParam($param, $position));
			
			// Create Element - <Cu3ox>.<Image>.<Text>
			$nodeL2 =& $nodeL1->addChild('Text');
				
				// Create Element - <Cu3ox>.<Image>.<Text>.<headline>
				$param = $params->get('headline');
				$nodeL2->addChild('headline', self::getParam($param, $position));
				
				// Create Element - <Cu3ox>.<Image>.<Text>.<paragraph>
				$param = $params->get('paragraph');
				$nodeL2->addChild('paragraph', self::getParam($param, $position));
		}
		
		$str = $xml->xpath('/Image');
		
		return $str;
	}
}