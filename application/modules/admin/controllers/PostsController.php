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
 * @version		$Id: PostsController.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_PostsController extends Zend_Controller_Action
{

    public function init()
    {
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
		$this->categoryModel = new Admin_Model_DbTable_CategoryTable();
		$this->categoryAddModel = new Admin_Model_DbTable_CategoryAddTable();
		
		$this->postModel = new Admin_Model_DbTable_PostsTable();


		
		
		$this->view->headTitle('MEME CMS -> Admin -> Posts');


    }

    public function indexAction()
    {
		$result = $this->postModel->getAll('post_date DESC', '', '', false);
		
		$page = $this->_getParam('page',1);
		$paginator = Zend_Paginator::factory($result);
		$paginator->setItemCountPerPage(20);
		$paginator->setCurrentPageNumber($page);
		$this->view->paginator = $paginator;	
    }




	public function newAction()
	{
		$this->view->headScript()->appendFile('/meme-content/js/tiny_mce/tiny_mce.js');
		$this->view->headScript()->appendFile('/meme-content/js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php');
		
		
		$request = $this->getRequest();
				
		if ($this->getRequest()->isPost()) {
			
			$field = $request->getPost();
			$post_id = $this->postModel->add($field);				

			foreach ($this->categoryModel->getAll(1) as $category):
        			
        		if($field['category'.$category['category_id']] == '1'){
        			$this->categoryAddModel->addObject(1, $post_id, $category['category_id']);
				}
        			
        	endforeach;

			$this->_redirect('/admin/posts/edit/id/'.$post_id);


		} else {
		    $this->view->category = $this->categoryModel->getAll(1);
		}	
	}




	public function editAction()
	{

        $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/tiny_mce.js');
        $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php');

		$request = $this->getRequest();
		$post_id = (int)$request->getParam('id');

		if ($this->getRequest()->isPost()) {
				$requestPost = $request->getPost();
			
				$this->postModel->edit($requestPost);
				
				$this->categoryAddModel->deleteFromObjectId($post_id);

				
				foreach ($this->categoryModel->getAll(1) as $category):
        			if($requestPost['category'.$category['category_id']] == '1'){
        				$this->categoryAddModel->addObject(1, $post_id, $category['category_id']);
					}
        		endforeach; 


				$this->_redirect('/admin/posts/edit/id/'.$post_id);

			} else {
				
		    	$this->view->category = $this->categoryModel->getAll(1);
				$this->view->categoryadd = $this->categoryAddModel->getFromObjectId(1, $post_id);
				
				$this->view->post = $this->postModel->getFromPostId($post_id);
				
			}



	}


	public function deleteAction()
	{
	
		$request = $this->getRequest();
		$post_id = (int)$request->getParam('id');

		$this->view->post = $this->postModel->getFromPostId($post_id);
		
		$confirm = $request->getParam('confirm');

		if ($confirm == 'yes') {		

				$this->postModel->deletePostId($post_id);
				$this->categoryAddModel->deleteFromObjectId($post_id);
				
						
							
				
				$this->_redirect('/admin/posts/');


			}	
	}


	public function categoriesAction()
    {
		
		$this->view->category = $this->categoryModel->getAll(1);

    }






    public function categorynewAction()
    {
		$request = $this->getRequest();
		if ($this->getRequest()->isPost()) {
		
			$category_id = $this->categoryModel->add(1, $request->getPost(), $this->view->identity['user_id']);
		
			$this->_redirect('/admin/posts/categoryedit/id/'.$category_id);
		
		}else{
			$this->view->categorytype = $this->categoryModel->getAll(1);

        }
    }





    public function categoryeditAction()
    {
    
    	$request = $this->getRequest();
		$category_id = (int)$request->getParam('id');
		
		
		if ($this->getRequest()->isPost()) {

			$this->categoryModel->edit(1, $request->getPost(), $this->view->identity['user_id']);

			$this->_redirect('/admin/posts/categoryedit/id/'.$category_id);
			
		}else{

			$this->view->categorytype = $this->categoryModel->getAll(1);
			$this->view->category = $this->categoryModel->getFromId($category_id);

        }
    }




	public function categorydeleteAction()
	{

    	$request = $this->getRequest();
		$category_id = (int)$request->getParam('id');
		
		$confirm = $request->getParam('confirm');

		if ($confirm == 'yes') {

				$this->categoryModel->deleteFromId($category_id);
				$this->categoryAddModel->deleteFromCategory($category_id, 1);
				
				$this->_redirect('/admin/posts/categories');

			} else {

				$this->view->category = $this->categoryModel->getFromId($category_id);
			}
	
	}





}

