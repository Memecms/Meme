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
 * @version		$Id: PagesController.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_PagesController extends Zend_Controller_Action
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



		//models		
		$this->pageModel = new Admin_Model_DbTable_PagesTable();




		$this->view->headTitle('MEME CMS -> Admin -> Pages');


    }
    
    
    public function indexAction()
    {
        // action body
        
   		$result = $this->pageModel->getAll();
		$page = $this->_getParam('page',1);
		$paginator = Zend_Paginator::factory($result);
		$paginator->setItemCountPerPage(20);
		$paginator->setCurrentPageNumber($page);
		$this->view->paginator = $paginator;

    }


	public function newAction()
    {
        // action body
        
        $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/tiny_mce.js');
        $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php');

		$request = $this->getRequest();
				
		if ($this->getRequest()->isPost()) {
			$page_id = $this->pageModel->add($request->getPost());
			$this->_redirect('/admin/pages/edit/id/'.$page_id);
		}

    }



	public function editAction()
	{

        $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/tiny_mce.js');
        $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php');

		$request = $this->getRequest();
		$page_id = (int)$request->getParam('id');
			

		if ($this->getRequest()->isPost()) {
				$this->pageModel->edit($request->getPost());
				$this->_redirect('/admin/pages/edit/id/'.$page_id);
			
			} else {
				$this->view->page = $this->pageModel->getFromIdPage($page_id);
			}
	}




	public function deleteAction()
	{
	

		$request = $this->getRequest();
		$page_id = (int)$request->getParam('id');
		
		$page = $this->pageModel->getFromIdPage($page_id);
		
		$confirm = $request->getParam('confirm');

		if ($confirm == 'yes') {

				$this->pageModel->deleteFromId($page_id, $page['page_controller'], $page['page_action']);				
				
				
				$this->_redirect('/admin/pages/');


			} else {
				$this->view->page = $page;
			}
	
	}


}