<?php
/**
 * @version		$Id: default.php 2012-06-20 vinaora $
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

?>

<!-- BEGIN: Vinaora Cu3ox Slideshow >> http://vinaora.com/ -->
<div id="cu3ox$C3O_SUFFIX$" style="width:$CU3OX_WIDTH$px;height:$CU3OX_HEIGHT$px;margin:0 auto;text-align:center">
	<script language="JavaScript" type="text/javascript">
		var cu3oxId = ("cu3ox" + Math.random()).replace(".","");
		document.write('<div id ="' + cu3oxId + '" style="text-align:center;"><img src="data/images$C3O_SUFFIX$/$FirstImage$"/></div>');
		if (swfobject.getFlashPlayerVersion().major)
			swfobject.createSWF(
			  {data:"engine/cu3ox.swf", width:"100%", height:"100%" },
			  {FlashVars:"images=data/images$C3O_SUFFIX$&cfgsuffix=$C3O_SUFFIX$",menu:true, allowFullScreen:false, allowScriptAccess:'sameDomain', wmode:"transparent", bgcolor:'#FFFFFF', 
			   devicefont:false, scale:'noscale', loop:true, play:true, quality:'high'}, cu3oxId);
	</script>
	<noscript>
		<!--[if !IE]> -->
		<object type="application/x-shockwave-flash" data="engine/cu3ox.swf" width="100%" height="100%"  align="middle">
		<!-- <![endif]-->
		<!--[if IE]>
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0"
			width="100%" height="100%"  align="middle">
			<param name="movie" value="engine/cu3ox.swf" />
		<!-->
			<param name="FlashVars" value="images=data/images$C3O_SUFFIX$&cfgsuffix=$C3O_SUFFIX$" />
			<param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="false" />
			<param name="quality" value="high"/><param name="scale" value="noscale"/>
			<param name="wmode" value="transparent" />	
			<param name="bgcolor" value="#ffffff" />	
			<img src="data/images$C3O_SUFFIX$/$FirstImage$"/>
		</object>
		<!-- <![endif]-->		
	</noscript>
</div>
<!-- END: Vinaora Cu3ox Slideshow >> http://vinaora.com/ -->