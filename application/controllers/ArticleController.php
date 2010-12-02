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
 * @version		$Id: ArticleController.php 333 2010-09-22 12:25:31Z alex $
 */

class ArticleController extends Zend_Controller_Action
{


    public function init()
    {
    
    	$this->PostsModel = new Admin_Model_DbTable_PostsTable();	
		$this->CategoryModel = new Admin_Model_DbTable_CategoryTable();

		$this->view->headTitle($this->view->Meme_Setting_get('sitetitle'));
		$this->view->headTitle('Article');
    }
    
    
   	public function preDispatch()
	{

	
	}



    public function indexAction()
    {
    
    	$this->view->ItemForPage = 5;

        $request = $this->getRequest();
		$categoryname_url = $request->getParam('category');
    	
    	$page = $this->_getParam('page',1);
	

		if($categoryname_url == 'all'){
			$result = $this->PostsModel->getAll();
    		$this->view->categoryname = $categoryname_url;
		}
		else
		{
			$categoryInfo = $this->CategoryModel->getFromNameUrl($categoryname_url);
			$this->view->categoryname = $categoryInfo->category_name;		
			$result = $this->PostsModel->getFromCategory($categoryInfo->category_id, 'post_date DESC', '', '', true, false);
		}
		
		
		$paginator = Zend_Paginator::factory($result);
		$paginator->setItemCountPerPage($this->view->ItemForPage);
		$paginator->setCurrentPageNumber($page);

    	$this->view->PostList = $paginator;
		
		
		
		
		$this->view->headTitle($this->view->categoryname);

		
 	}
 	
 	
 	
 	public function readAction()
 	{
 	
 				$request = $this->getRequest();
				$id = (int)$request->getParam('id');


 		      	$this->view->post = $this->PostsModel->getFromPostId($id);
 		      	
 		      	
				$this->view->headTitle($this->view->post['post_title']);
				$this->view->headMeta()->appendName('keywords', $this->view->post['post_keywords']);
				$this->view->headMeta()->appendName('Description', $this->view->post['post_description']);
	
 	
 	}

}