<?php
/**
 * Meme CMS
 *
 * LICENSE
 *
 * This source file is subject to the GPL license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://memecms.com/license/gnu-gpl
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@memecms.com so we can send you a copy immediately.
 *
 * @version		MEME
 * @package		MemeCMS
 * @copyright	Copyright (C) 2009 - 2012 Alessio Pigliacelli, Studio Pigliacelli S.a.s. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt http://memecms.com/license/gnu-gpl
 * @version		$Id: Post.php 401 2010-11-18 20:25:30Z alex $
 */


class Form_Post extends Zend_Form
{
	public function __construct()
	{
		parent::__construct($options);
		$this->setName('Posts');
		$id = new Zend_Form_Element_Hidden('id');
		$title = new Zend_Form_Element_Text('Title');
		$title->setLabel('Title')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');
		$allowedTags = array(
						'a' => array('href', 'title'),
						'strong',
						'img' => array('src', 'alt'),
						'ul',
						'ol',
						'li',
						'em',
						'u',
						'p',
						'strike');
		$description = new Zend_Form_Element_Textarea('Description');
		$description->setLabel('Description')
				->setRequired(true)
				->setAttrib('rows',20)
				->setAttrib('cols',50)
				->addFilter('StringTrim')
/*				->addFilter('StripTags', $allowedTags)
 * 				Don't know why its not working ?
 */
				->addValidator('NotEmpty');
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$this->addElements( array( $id, $title, $description, $submit ));
	}
}