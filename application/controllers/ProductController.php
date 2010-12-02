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
 * @version		$Id: ProductController.php 346 2010-09-30 21:37:23Z alex $
 */

class ProductController extends Zend_Controller_Action
{


    public function init()
    {
		$this->view->meme = new Admin_Model_MemeStart();

        $this->view->settings = $this->view->meme->getSetting();


		$this->view->headTitle($this->view->settings['setting_sitetitle']);
		
		
		
      	$this->view->headTitle('Products');

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
		
			$result = $this->view->meme->getProductsList();    		
    		$this->view->categoryname = $categoryname_url;

		}
		else
		{

			$categoryInfo = $this->view->meme->getProductsCategoryAllFromNameUrl($categoryname_url);
			
			$this->view->categoryname = $categoryInfo->category_name;
			
			$result = $this->view->meme->getProductsFromIdCategory($categoryInfo->category_id);

		}
		
		
		$paginator = Zend_Paginator::factory($result);
		$paginator->setItemCountPerPage($this->view->ItemForPage);
		$paginator->setCurrentPageNumber($page);

    	$this->view->ProductsList = $paginator;
		
		
		
		
		$this->view->headTitle($this->view->categoryname);

		
 	}
 	
 	
 	
 	public function readAction()
 	{
 	
 				$request = $this->getRequest();
				$id = (int)$request->getParam('id');


 		      	$this->view->product = $this->view->meme->productGet($id);
 		      	
 		      	
				$this->view->headTitle($this->view->product->product_name);
				$this->view->headMeta()->appendName('keywords', $this->view->product->product_keywords);
				$this->view->headMeta()->appendName('Description', $this->view->product->product_description);

		$valueModel = new Admin_Model_DbTable_Products_ValueTable();
        $attribute = new Admin_Model_DbTable_Products_AttributeTable();

		

		$this->view->field = $attribute->get();
		$this->view->value = $valueModel->getValues($id);
 	


	
 	
 	}
 	
 	

}