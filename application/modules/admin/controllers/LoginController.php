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
 * @version		$Id: LoginController.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_LoginController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        

    }



	public function indexAction()
	{
	
		 $this->_helper->layout->disableLayout();
		/*
		 * Creating $loginForm object of class Form_Login
		 */
		$loginForm = new Admin_Form_Login();
		/*
		 * Trying to redirect to the page from which it came
		 */
		$redirect = $this->getRequest()->getParam('redirect', '/admin/index');
		$loginForm->setAttrib('redirect', $redirect );
		/*
		 * Get the Zend_Auth instance
		 */
		$auth = Zend_Auth::getInstance();
		/*
		 * Check whether it has any identity , else check whether the login form is submitted
		 */
		if(Zend_Auth::getInstance()->hasIdentity()) {
			$this->_redirect('/admin/index');
		} else if ($this->getRequest()->isPost()) {
			if ( $loginForm->isValid($this->getRequest()->getPost()) ) {
				/*
				 * Get the username
				 */
				$username = $this->getRequest()->getPost('username');
				/*
				 * Get password
				 */
				$pwd = $this->getRequest()->getPost('password');
				
				
				$remember =  $this->getRequest()->getPost('remember');

				/*
				 * Create object $authAdapter of class Model_AuthAdapter
				 */
				$authAdapter = new Admin_Model_AuthAdapter($username, $pwd);
				/*
				 * Try to authenticate and check whether its valid
				 */
				$result = $auth->authenticate($authAdapter);
				if(!$result->isValid()) {
					switch ($result->getCode()) {
						case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
							$this->view->error = '<p class=\'denied\'>ACCESS DENIED</p>';
					}
				} else {
					/*
					 * If its valid redirect it . Now it will not work ;) . 
					 * Have not implemented the redirect to the page from where it came.
					 */
					 
					 
					if($remember == '1')
					{
    					Zend_Session::rememberUntil(3600 * 24 * 31 *2);
    				}
					 
					 
					$this->_redirect( $redirect );
				}
			}
		}
		/*
		 * Assign the form elements to view
		 */
		$this->view->loginForm = $loginForm;
	}

	public function logoutAction()
	{
		/*
		 * Logout and clear session
		 */
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		$this->_redirect('/');
	}
	
	public function registerAction()
	{
		/*
		 * Register for new account
		 */
		$register = new Form_Registration();
		if(Zend_Auth::getInstance()->hasIdentity()) {
			$this->_redirect('/index/hello');
		} else if ($this->getRequest()->isPost()) {
			if ( $register->isValid($this->getRequest()->getPost()) ) {
				
			}	
		}	
		$this->view->register = $register;
	}
	
	public function forgotPasswordAction()
	{
		/*
		 * User submits for new password 
		 */
		$forgotPassword = new Form_ForgotPassword();
		if(Zend_Auth::getInstance()->hasIdentity()) {
			$this->_redirect('/index/hello');
		} else if ($this->getRequest()->isPost()) {
			if ( $forgotPassword->isValid($this->getRequest()->getPost()) ) {
				
			}	
		}	
		$this->view->forgotPassword = $forgotPassword;
	}


}