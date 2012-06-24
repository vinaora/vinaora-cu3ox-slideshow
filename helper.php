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

	public static function &validParams( $params ){
	
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
	
	public static function makeFiles($params){
	
		$buffer = '<!DOCTYPE html><title></title>';
		
		// Make directory /cache/mod_vt_cu3ox_slideshow/ if not exist
		$path = JPATH_CACHE.DS.'mod_vt_cu3ox_slideshow';
		if( !is_dir($path) ){
			JFolder::create($path);
			JFile::write($path.DS.'index.html', $buffer);
		}
		
		// Make directory /cache/mod_vt_cu3ox_slideshow/[module_id]/ if not exist
		$path = $path.DS.$params->get('ID');
		if( !is_dir($path) ){
			JFolder::create($path);
			JFile::write($path.DS.'index.html', $buffer);
			
			$path = $path.DS.'engine';
			JFolder::create($path);
			JFile::write($path.DS.'index.html', $buffer);
			
			// JFile::write($path.DS.$params->get('lastedit').'.log', time());
		}
		
		self::_makeXML($params);
		self::_makeCSS($params);
		self::_makeSWF($params);
	}
	
	/*
	 * Make the Cu3ox SWF file in the directory /cache/mod_vt_cu3ox_slideshow/[id]/engine/
	 */
	private static function _makeSWF( $params ){
		$src = JPATH_BASE.'/media/mod_vt_cu3ox_slideshow/templates/cu3ox.swf';
		JFile::copy($src, $params->get('EnginePath').DS.'vt_cu3ox_slideshow.swf');
	}

	/*
	 * Make the Config XML file in the directory /cache/mod_vt_cu3ox_slideshow/[id]/engine/
	 */	
	private static function _makeXML( $params ){

		$path	= JPath::clean( JPATH_BASE.'/media/mod_vt_cu3ox_slideshow/templates/cu3oxXML.xml' );
		$str	= file_get_contents( $path );
		
		// Replace XML variables
		$str	= preg_replace( "/\\$(\w+)\\$/e", '$params->get("$1")', $str );
		
		// Make file XML
		$path	= $params->get('EnginePath').DS.'vt_cu3ox_slideshowXML.xml';
		JFile::write( $path, $str);
	}
	
	/*
	 * Make the Main CSS file in the directory /cache/mod_vt_cu3ox_slideshow/[id]/engine/
	 */
	private static function _makeCSS( $params ){
		
		$path	= JPath::clean( JPATH_BASE.'/media/mod_vt_cu3ox_slideshow/templates/cu3oxCSS.css' );
		$str	= file_get_contents( $path );
		
		// Replace CSS variables
		$str	= preg_replace( "/\\$(\w+)\\$/e", '$params->get("$1")', $str );
		
		// Make file CSS
		$path	= $params->get('EnginePath').DS.'vt_cu3ox_slideshowCSS.css';
		JFile::write( $path, $str);
	}
	
	private static function _getImageSettings( $params ){
		// Create Element - <Cu3ox>
		$node = new SimpleXMLElement('<Cu3ox />');
		
		$images = self::getItems($params);
		
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

	/*
	 * Get the Paths of Items
	 */
	public static function getItems( $params ){

		$param	= $params->get('item_path');
		$param	= str_replace(array("\r\n","\r"), "\n", $param);
		$param	= explode("\n", $param);

		// Get Paths from invidual paths
		foreach($param as $key=>$value){
			$param[$key] = self::validPath($value);
		}
		// Remove empty element
		$param = array_filter($param);
		// Get Paths from directory
		if (empty($param)){
			$param	= $params->get('item_dir');
			if ($param == "-1") return null;

			$filter		= '([^\s]+(\.(?i)(jpg|png|gif|bmp))$)';
			$exclude	= array('index.html', '.svn', 'CVS', '.DS_Store', '__MACOSX', '.htaccess');
			$excludefilter = array();

			$param	= JFolder::files(JPATH_BASE.DS.'images'.DS.$param, $filter, true, true, $exclude, $excludefilter);
			foreach($param as $key=>$value){
				$value = substr($value, strlen(JPATH_BASE.DS) - strlen($value));
				$param[$key] = self::validPath($value);
			}
		}

		// Reset keys
		$param = array_values($param);
		return $param;
	}

	/*
	 * Get the Valid Path of Item
	 */
	public static function validPath( $path ){
		$path = trim($path);

		// Check file type is image or not
		if( !preg_match('/[^\s]+(\.(?i)(jpg|png|gif|bmp))$/', $path) ) return '';

		// The path includes http(s) or not
		if( preg_match('/^(?i)(https?):\/\//', $path) ){
			$base = JURI::base(false);
			if (substr($path, 0, strlen($base)) == $base){
				$path = substr($path, strlen($base) - strlen($path));
			}
			else return $path;
		}

		$path = JPath::clean($path, DS);
		$path = ltrim($path, DS);
		if (!is_file(JPATH_BASE.DS.$path)) return '';

		// Convert it to url path
		$path = JPath::clean(JURI::base(true)."/".$path, "/");
		
		return $path;
	}
	
	/*
	 * Get a Parameter in a Parameters String which are separated by a specify symbol (default: vertical bar '|').
	 * Example: Parameters = "value1 | value2 | value3". Return "value2" if positon = 2
	 */
	public static function getParam($param, $position=1, $separator='|'){

		$position = intval($position);

		// Not found the separator in string
		if( strpos($param, $separator) === false ){
			if ( $position == 1 ) return $param;
		}
		// Found the separator in string
		else{
			$param = ($separator = "\n") ? str_replace(array("\r\n","\r"), "\n", $param) : $param;
			$items = explode($separator, $param);
			if ( ($position > 0) && ($position < count($items)+1) ) return $items[$position-1];
		}

		return '';
	}

	/*
	 * Add SWFObject Library to <head> tag
	 */
	public static function addjQuery($source='local', $version='latest'){
		$source = strtolower(trim($source));
		$version = trim($version);

		switch($source){
			case 'local':
				JHtml::script("media/mod_vt_nice_slideshow/js/jquery/$version/jquery.min.js");
				break;
			case 'google':
				JHtml::script("https://ajax.googleapis.com/ajax/libs/jquery/$version/jquery.min.js");
				break;
			default:
				return false;
		}
		return true;
	}
	
}