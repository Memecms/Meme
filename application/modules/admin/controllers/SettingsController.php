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
 * @version		$Id: SettingsController.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_SettingsController extends Zend_Controller_Action
{
	
    public function init()
    {
        /* Initialize action controller here */
        
        
        $this->view->identity = Zend_Auth::getInstance()->getIdentity();
        //Login verificated   
        if(!Zend_Auth::getInstance()->hasIdentity()) {
			$this->_redirect('admin/login');
		}
		elseif($this->view->identity['user_role'] != 6)
		{
			$this->_redirect('/admin/login/logout');
		}

       	// Set name of current controller for select the current page in manu on layout 
		$this->view->controllerName = $this->getRequest()->getControllerName();
		
		
		// Setting model
		$SettingsModel = new Admin_Model_DbTable_SettingsTable();
		$this->view->setting_sitetitle = $SettingsModel->get('sitetitle');
		




		$this->view->headTitle('MEME CMS -> Admin -> Settings');

    }
    
    
   	public function preDispatch()
	{

	
	}

    public function indexAction()
    {
        // action body
        $settingForm = new Admin_Form_SettingGeneral();
        $settingModel = new Admin_Model_DbTable_SettingsTable();
		$request = $this->getRequest();

			if ($this->getRequest()->isPost()) {
				if ($settingForm->isValid($request->getPost())) {
					//Zend_Debug::dump($settingForm->getValues());
					$settingModel->updateSetting($settingForm->getValues());
					
					$this->_redirect('/admin/settings');
				}
			} else {
				$result = $settingModel->getAllSettingGeneral();
				//Zend_Debug::dump($result);
				$settingForm->populate($result);
			}


		$this->view->settingForm = $settingForm;
          
    }


	
	public function productsAction()
	{

		$pages = new Admin_Model_DbTable_Products_AttributeTable();
		$result = $pages->get();
		$page = $this->_getParam('page',1);
		$paginator = Zend_Paginator::factory($result);
		$paginator->setItemCountPerPage(10);
		$paginator->setCurrentPageNumber($page);
		$this->view->paginator = $paginator;

		

	}




}