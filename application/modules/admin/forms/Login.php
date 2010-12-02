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
 * @version		$Id: Login.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_Form_Login extends Zend_Form
{
    public function init()
    {
    
        $this->setMethod('post');

        $username = $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                'Alnum',
                array('StringLength', false, array(3, 20)),
            ),
            'required'   => true,
            'label'      => 'Username:',
        ));

        $password = $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                'Alnum',
                array('StringLength', false, array(6, 20)),
            ),
            'required'   => true,
            'label'      => 'Password:',
        ));


		$remember = $this->addElement('checkbox', 'remember', array(
					'label'          => 'Remember me', 
        			'checked'        => true,         ));

        
        
        $submit = new Zend_Form_Element_Submit('login');
        $submit->setValue('Login');
        
        $this->addElements(array($submit));



 		$this->setElementDecorators(array(
            'ViewHelper',
            array('Errors', array('tag' => 'td', 'placement' => 'APPEND')),
            
            array(array('data' => 'HtmlTag'),  array('tag' =>'td', 'class'=> 'element')),
            array('Label', array('tag' => 'td')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
        ));
        
        
        $submit->setDecorators(array('ViewHelper',
            array(array('data' => 'HtmlTag'),  array('tag' =>'td', 'class'=> 'element')),
            array(array('emptyrow' => 'HtmlTag'),  array('tag' =>'td', 'class'=> 'element', 'placement' => 'PREPEND')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
            ));


        // We want to display a 'failed authentication' message if necessary;
        // we'll do that with the form 'description', so we need to add that
        // decorator.
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table')),
            'Form'
        ));
    }
}