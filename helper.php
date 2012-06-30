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
	
		// Check the Dimension of your images
		$param	= (int) $params->get('ImageWidth');
		$param	= ($param) ? $param : '640';
		$params->set('ImageWidth', $param);
		
		$param	= (int) $params->get('ImageHeight');
		$param	= ($param) ? $param : '480';
		$params->set('ImageHeight', $param);
		
		// Check the Number of Segments
		$param	= (int) $params->get('Segments');
		if( !$param ){
			$param = (int) $params->get('SegmentsMax', '10');
			$params->set('Segments', rand(1,$param));
			$params->set('SegmentsDefault', '80');
		}else{
			$params->set('SegmentsDefault', $param);
		}
		
		// Check the Duration and Delay Time of Transitions
		$param	= trim($params->get('TweenTime', '1.2'));
		$param	= str_replace(',', '.', $param);
		$params->set('TweenTime', $param);
		
		$param	= (int) $params->get('TweenDelay', '0.1');
		$param	= str_replace(',', '.', $param);
		$params->set('TweenDelay', $param);
		
		// Check the Transition Type
		$TweenType	= "linear,"
					. "easeInQuad,easeOutQuad,easeInOutQuad,easeOutInQuad,"
					. "easeInCubic,easeOutCubic,easeInoutCubic,easeOutInCubic,"
					. "easeInQuart,easeOutQuart,easeInOutQuart,easeOutInQuart,"
					. "easeInQuint,easeOutQuint,easeInOutQuint,easeOutInQuint,"
					. "easeInSine,easeOutSine,easeInOutSine,easeOutInSine,"
					. "easeInExpo,easeOutExpo,easeInOutExpo,easeOutInExpo,"
					. "easeInCirc,easeOutCirc,easeInOutCirc,easeOutInCirc,"
					. "easeInElastic,easeOutElastic,easeInOutElastic,easeOutInElastic,"
					. "easeInBack,easeOutBack,easeInOutBack,easeOutInBack,"
					. "easeInBounce,easeOutBounce,easeInOutBounce,easeOutInBounce";

		$TweenType	= explode(",", $TweenType);
		$param	= $params->get('TweenType');
		$param	= ( $param='random' ) ? $TweenType[array_rand($TweenType, 1)] : $param;
		$params->set('TweenType', $param);
		
		// Check the Distance, Expand
		$param	= (int) $params->get('ZDistance', '0');
		$params->set('ZDistance', $param);
		
		$param	= (int) $params->get('Expand', '20');
		$params->set('ZDistance', $param);
		
		// Check Color Format. Ensure that the prefix is '0x'
		$param	= $params->get('InnerColor');
		$param	= '0x'.ltrim($param, '#');
		$params->set('InnerColor', $param);
		
		$param	= $params->get('TextBackground');
		$param	= '0x'.ltrim($param, '#');
		$params->set('TextBackground', $param);
		
		$param	= $params->get('StartBackground');
		$param	= '0x'.ltrim($param, '#');
		$params->set('StartBackground', $param);
		
		// Check the Logo File, Logo Text and Logo Link
		$param	= $params->get('LogoFile');
		$param	= $param ? '/images/'.$param : '/media/mod_vt_cu3ox_slideshow/images/logo.jpg';
		$param	= rtrim(JURI::base(true), '/') . $param;
		$params->set('LogoFile', $param);
		
		// Remove http(s) if exist in Logo's Link
		$param	= trim($params->get('LogoLink'));
		$param	= preg_replace('/^(?i)(https?):\/\//', '', $param);
		$params->set('LogoLink', $param);
		
		$param	= trim($params->get('HeadFontSize'));
		$param	= rtrim($param, 'px').'px';
		$params->set('HeadFontSize', $param);
		
		$param	= trim($params->get('ParaFontSize'));
		$param	= rtrim($param, 'px').'px';
		$params->set('ParaFontSize', $param);
		
		$param	= self::_getImageSettings($params);
		$params->set('ImageList', $param);
		
		return $params;
	}
	
	/*
	 * Make the XML, CSS, SWF files for Cu3ox Slideshow
	 */
	public static function makeFiles( $params ){
		
		$buffer = '<!DOCTYPE html><title></title>';
		
		// Make directory /media/mod_vt_cu3ox_slideshow/[module_id]/ if not exist
		$path	= JPATH_BASE.'/media/mod_vt_cu3ox_slideshow/'.$params->get('ID');
		$path	= JPath::clean($path);
		if( !is_dir($path) ){
			JFolder::create($path);
			JFile::write($path.DS.'index.html', $buffer);
			
			$path = $path.DS.'engine';
			if( !is_dir($path) ){
				JFolder::create($path);
				JFile::write($path.DS.'index.html', $buffer);
			}
		}
		
		$EnginePath	= JPATH_BASE.'/media/mod_vt_cu3ox_slideshow/'.$params->get('ID').'/engine';
		$EnginePath	= JPath::clean($EnginePath);
		$params->set('EnginePath', $EnginePath);
		
		$EngineURL	= JURI::base(true).'/media/mod_vt_cu3ox_slideshow/'.$params->get('ID').'/engine';
		$EngineURL	= JPath::clean($EngineURL, '/');
		$params->set('EngineURL', $EngineURL);
		
		$cache_time	= (int) $params->get('cache_time', '900');
		$log		= $EnginePath.DS.$params->get('lastedit').'.log';
		
		// Check the file log is exist or not. If exists then check the created time
		if( !is_file($log) || ( (int) JFile::read($log) + $cache_time < time()) ){
		
			self::_makeXML($params);
			self::_makeCSS($params);
			self::_makeSWF($params);
			
			$buffer = time();
			JFile::write($EnginePath.DS.$params->get('lastedit').'.log', $buffer);
		}
	}
	
	/*
	 * Make the Cu3ox SWF file in the directory /media/mod_vt_cu3ox_slideshow/[id]/engine/
	 */
	private static function _makeSWF( $params ){
		$src = JPATH_BASE.'/media/mod_vt_cu3ox_slideshow/templates/cu3ox.swf';
		$src = JPath::clean($src);
		JFile::copy($src, $params->get('EnginePath').DS.'vt_cu3ox_slideshow.swf');
	}

	/*
	 * Make the Config XML file in the directory /media/mod_vt_cu3ox_slideshow/[id]/engine/
	 */	
	private static function _makeXML( $params ){

		$path	= JPATH_BASE.'/media/mod_vt_cu3ox_slideshow/templates/cu3oxXML.xml';
		$path	= JPath::clean( $path );
		$str	= file_get_contents( $path );
		
		// Replace XML variables
		$str	= preg_replace( "/\\$(\w+)\\$/e", '$params->get("$1")', $str );
		
		$node	= new SimpleXMLElement($str);
		
		// Make file XML
		$path	= $params->get('EnginePath').DS.'vt_cu3ox_slideshowXML.xml';
		JFile::write( $path, $node->asXML());
	}
	
	/*
	 * Make the Main CSS file in the directory /media/mod_vt_cu3ox_slideshow/[id]/engine/
	 */
	private static function _makeCSS( $params ){
		
		$path	= JPATH_BASE.'/media/mod_vt_cu3ox_slideshow/templates/cu3oxCSS.css';
		$path	= JPath::clean( $path );
		$str	= file_get_contents( $path );
		
		// Replace CSS variables
		$str	= preg_replace( "/\\$(\w+)\\$/e", '$params->get("$1")', $str );
		
		// Make file CSS
		$path	= $params->get('EnginePath').DS.'vt_cu3ox_slideshowCSS.css';
		JFile::write( $path, $str);
	}
	
	/*
	 * Get the Image List Settings
	 */
	private static function _getImageSettings( $params ){
		$str = '';
		
		// Create Element - <Cu3ox>
		$node = new SimpleXMLElement('<Cu3ox />');
		
		// Get all images relative paths
		$images = self::getItems($params, false);
		
		if(empty($images) || !count($images)) return $str;

		$params->set('FirstImage', $images[0]);
		
		foreach($images as $position=>$image){

			// Create Element - <Cu3ox>.<Image>
			$nodeL1 =& $node->addChild('Image');
			$nodeL1->addAttribute('Filename', $image);

			// Create Element - <Cu3ox>.<Image>.<Settings>
			$nodeL2 =& $nodeL1->addChild('Settings');
			
			// Create Element - <Cu3ox>.<Image>.<Settings>.<goLink>
			$param	= $params->get('item_url');
			$param	= self::getParam($param, $position+1, "\n");
			$param	= trim($param);
			$nodeL3 =& $nodeL2->addChild('goLink', $param);
			$nodeL3->addAttribute('target', $params->get('item_target'));
		
			// Create Element - <Cu3ox>.<Image>.<Settings>.<rDirection>
			$param	= $params->get('item_rdirection');
			$param	= self::getParam($param, $position+1, "\n");
			$param	= strtolower(trim($param));
			$param	= ( in_array($param, array("left,right,up,down,random")) ) ? $param : $params->get('RDirection');
			$param	= ($param != 'random') ? $param : '';
			$nodeL3 =& $nodeL2->addChild('rDirection', $param);
		
			// Create Element - <Cu3ox>.<Image>.<Settings>.<segments>
			$param	= $params->get('item_segments');
			$param	= self::getParam($param, $position+1, "\n");
			$param	= (int) $param;
			$param	= ($param) ? $param : $params->get('SegmentsDefault');
			$param	= ($param) ? $param : rand(1, $params->get('SegmentsMax'));
			$nodeL3 =& $nodeL2->addChild('segments', $param);
			
			// Create Element - <Cu3ox>.<Image>.<Text>
			$nodeL2 =& $nodeL1->addChild('Text');
				
			// Create Element - <Cu3ox>.<Image>.<Text>.<headline>
			$param	= $params->get('item_title');
			$param	= self::getParam($param, $position+1, "\n");
			$nodeL3 =& $nodeL2->addChild('headline', $param);
			
			// Create Element - <Cu3ox>.<Image>.<Text>.<paragraph>
			$param	= $params->get('item_description');
			$param	= self::getParam($param, $position+1, "\n");
			$nodeL3 =& $nodeL2->addChild('paragraph', $param);
			
			$str .= $nodeL1->asXML();
		}
		
		return $str;
	}

	/*
	 * Get the Paths of Items
	 */
	public static function getItems( $params, $absolute=true ){

		$param	= $params->get('item_path');
		$param	= str_replace(array("\r\n","\r"), "\n", $param);
		$param	= explode("\n", $param);

		// Get Paths from invidual paths
		foreach($param as $key=>$value){
			$param[$key] = self::validPath($value, $absolute);
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
				$param[$key] = self::validPath($value, $absolute);
			}
		}

		// Reset keys
		$param = array_values($param);
		return $param;
	}

	/*
	 * Get the Valid Path of Item
	 */
	public static function validPath( $path, $absolute=true ){
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
		$path = $absolute ? JPath::clean(JURI::base(true)."/".$path, "/") : JPath::clean($path, '/');
		
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
	public static function addSWFObject($source='local', $version='2.2'){

		switch($source){

			case 'local':
				JHtml::script("media/mod_vt_cu3ox_slideshow/js/swfobject/$version/swfobject.js");
				break;

			case 'google':
				JHtml::script("http://ajax.googleapis.com/ajax/libs/swfobject/$version/swfobject.js");
				break;

			default:
				return false;
		}
		return true;

	}
	
}