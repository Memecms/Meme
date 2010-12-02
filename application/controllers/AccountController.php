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
 * @copyright   	Copyright (C) 2009 - 2011 Alessio Pigliacelli, Studio Pigliacelli S.a.s. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt http://memecms.com/license/gnu-gpl
 * @version		$Id: IndexController.php 133 2010-01-03 23:56:59Z alex $
 */

class AccountController extends Zend_Controller_Action
{
 
    
    public function init()
    {
		$this->meme = new Admin_Model_MemeStart();
      	
      	
		$this->view->headTitle('Login');
    }
    
    
   	public function preDispatch()
	{

	
	}


    public function indexAction()
    {


 	}
 	
 	public function loginAction()
    {
    
    $loginForm = new Model_LoginForm(array(
            'action' => '/account/login',
            'method' => 'post',));
    
   	$this->view->loginForm = $loginForm;

	$auth = Zend_Auth::getInstance();


		if(Zend_Auth::getInstance()->hasIdentity()) {
			$this->_redirect('/index/hello');
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
					$this->_redirect( $redirect );
				}
			}
		}
		
		
 	}

}