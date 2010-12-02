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
 * @version		$Id: Comments.php 401 2010-11-18 20:25:30Z alex $
 */


class Form_Comments extends Zend_Form
{
	public function __construct()
	{
		$acl = new Model_Acl();
		$identity = Zend_Auth::getInstance()->getIdentity();
		/*
		 * Check whether they have access to it.
		 */
		if( Zend_Auth::getInstance()->hasIdentity()
		&& $acl->isAllowed( $identity['Role'] ,'comments','add') ) {
			parent::__construct($options);
			$this->setName('Comments');
			$id = new Zend_Form_Element_Hidden('id');
			$name = new Zend_Form_Element_Text('name');
			$name->setLabel('Your Name')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');
			$email = new Zend_Form_Element_Text('email');
			$email->setLabel('Email')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');
			$webpage = new Zend_Form_Element_Text('webpage');
			$webpage->setLabel('Webpage')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');
			$comment = new Zend_Form_Element_Textarea('comment');
			$comment->setLabel('Comments')
				->setRequired(true)
				->setAttrib('rows',7)
				->setAttrib('cols',30)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');
			$submit = new Zend_Form_Element_Submit('submit');
			$submit->setAttrib('id', 'submitbutton');
			$this->addElements( array ($id, $name, $email, $webpage, $comment, $submit));
		}
	}
}