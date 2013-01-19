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

jimport('joomla.form.formfield');

class JFormFieldLastEdit extends JFormField {

	protected $type = 'LastEdit';

	public function getInput() {
		return '<input id="'.$this->id.'" name="'.$this->name.'" value="'.time().'" type="hidden" />';
	}

	public function getLabel(){
	}
}
