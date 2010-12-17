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
 * @version		$Id: ProductsController.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_ProductsController extends Zend_Controller_Action
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
		$this->CategoryModel = new Admin_Model_DbTable_CategoryTable();
		$this->CategoryAddModel = new Admin_Model_DbTable_CategoryAddTable();
		
		$this->ProductsModel = new Admin_Model_DbTable_ProductsTable();
		
        $this->AttributeModel = new Admin_Model_DbTable_Products_AttributeTable();
        $this->ValueModel = new Admin_Model_DbTable_Products_ValueTable();

		
		$this->GalleryModel = new Admin_Model_DbTable_Gallery_GalleryTable();
		$this->GalleryAddModel = new Admin_Model_DbTable_Gallery_GalleryAddTable();
		$this->imgAdapther = new Admin_Model_imgAdapter();



		$this->view->headTitle('MEME CMS -> Admin -> Products');

    }
    
    
   	public function preDispatch()
	{


	}

    public function indexAction()
    {
        // action body
		
		$result = $this->ProductsModel->getAll('product_date DESC', '', '');
		$page = $this->_getParam('page',1);
		$paginator = Zend_Paginator::factory($result);
		$paginator->setItemCountPerPage(10);
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
							
				$product_id = $this->ProductsModel->add($field);

				mkdir('meme-media/products/'.$product_id);
				
				mkdir('meme-media/products/'.$product_id.'/thumb');
				mkdir('meme-media/products/'.$product_id.'/img');


				if(!empty($_FILES['product_thumb']['name'])){

					$this->imgAdapther->resize($_FILES['product_thumb'], 'products/'.$product_id.'/', 'thumb', 300, 300);
				}

				foreach ($this->AttributeModel->getAll() as $attributes):
        				
        			if($attributes['products_attribute_type'] == 'text_field'){
        				$this->ValueModel->add($product_id, $attributes['products_attribute_id'], $field['field'.$attributes['products_attribute_id']]);
					}
					elseif($attributes['products_attribute_type'] == 'text_area'){
        				$this->ValueModel->add($product_id, $attributes['products_attribute_id'], $field['field'.$attributes['products_attribute_id']]);
					}
					elseif($attributes['products_attribute_type'] == 'media_image'){
					
						if(!empty($_FILES['field'.$attributes['products_attribute_id']]['name'])){
							$this->imgAdapther->productMore($_FILES['field'.$attributes['products_attribute_id']], $product_id, $attributes['products_attribute_id']);
							$this->ValueModel->saveValue($product_id, $attributes['products_attribute_id'], '/meme-media/products/'.$product_id.'/');
						}
						
					}

        		endforeach; 
			
			
			
				
			foreach ($this->CategoryModel->getAll(3) as $category):
        			
        		if($field['category'.$category['category_id']] == '1'){
        			$this->CategoryAddModel->addObject(3, $product_id, $category['category_id']);
				}
        			
        	endforeach;
				
				
				
				
				
				//add gallery
				if ($field['gallery'] != 'none')
				{
					$galleryAdd = new Admin_Model_DbTable_Gallery_GalleryAddTable();
					
					$galleryAdd->add('3', $product_id, $field['gallery']);
				}
				
				
				



				
				$this->_redirect('/admin/products/edit/id/'.$product_id);
			
				
		} else {

		    $this->view->field = $this->AttributeModel->getAll();
		    $this->view->category = $this->CategoryModel->getAll(3);
		    $this->view->gallery = $this->GalleryModel->getGallery();

		}


    }


    public function editAction(){
    
		$this->view->headScript()->appendFile('/meme-content/js/tiny_mce/tiny_mce.js');
		$this->view->headScript()->appendFile('/meme-content/js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php');
		
    	$request = $this->getRequest();
		$product_id = (int)$request->getParam('id');

		if ($this->getRequest()->isPost()) {
			
				$field = $request->getPost();
			
				$this->ProductsModel->edit($field);
				$requestPost = $request->getPost();
				
				
				if($requestPost['delete_thumb'] == 1){
					unlink('meme-media/products/'.$requestPost['product_id'].'/thumb.jpg');
				}
				
				if(!empty($_FILES['product_thumb']['name'])){
					$this->imgAdapther->manipulate($_FILES['product_thumb'], 'products/'.$requestPost['product_id'].'/', 'thumb');
				}


				foreach ($this->AttributeModel->getAll() as $attributes ):
        			

        			if($attributes['products_attribute_type'] == 'text_field'){
        				$this->ValueModel->edit($requestPost['field'. $attributes['products_attribute_id']], $requestPost['product_id'], $attributes['products_attribute_id']);
					}
					elseif($attributes['products_attribute_type'] == 'text_area'){
        				$this->ValueModel->edit($requestPost['field'. $attributes['products_attribute_id']], $requestPost['product_id'], $attributes['products_attribute_id']);
					}
					elseif($attributes['products_attribute_type'] == 'media_image'){

						if($requestPost['delete_img'. $attributes['products_attribute_id']] == 1){
							unlink('meme-media/products/'.$requestPost['product_id'].'/img/'.$attributes['products_attribute_id'].'.jpg');
							unlink('meme-media/products/'.$requestPost['product_id'].'/thumb/'.$attributes['products_attribute_id'].'.jpg');
						}

						if(!empty($_FILES['field'.$attributes['products_attribute_id']]['name'])){
							$this->imgAdapther->productMore($_FILES['field'.$attributes['products_attribute_id']], $requestPost['product_id'], $attributes['products_attribute_id']);
							$this->ValueModel->edit('/meme-media/products/'.$product_id.'/', $requestPost['product_id'], $attributes['products_attribute_id']);
						}

					}

        		endforeach; 







				$this->CategoryAddModel->deleteFromObjectId($product_id);

				
				foreach ($this->CategoryModel->getAll(3) as $category):
        			if($requestPost['category'.$category['category_id']] == '1'){
        				$this->CategoryAddModel->addObject(3, $product_id, $category['category_id']);
					}
        		endforeach; 


					
        	
				
				//add gallery
				if ($requestPost['gallery'] == 'none')
				{
					if ($this->GalleryAddModel->getFromIdObject('3', $product_id) != '')
					{
						$this->GalleryAddModel->deleteObject('3', $product_id);
					}
				
				}
				else
				{	
				
					if ($this->GalleryAddModel->getFromIdObject('3', $product_id) == '')
					{
					
						$this->GalleryAddModel->add('3', $product_id, $field['gallery']);
					
					}
					else
					{
						$this->GalleryAddModel->deleteObject('3', $product_id);
						$this->GalleryAddModel->add('3', $product_id, $field['gallery']);
					}
							
				}





				$this->_redirect('/admin/products/edit/id/'.$product_id);
			
			} else {
			
				$this->view->product = $this->ProductsModel->getFromProductId($product_id);
				$this->view->field = $this->AttributeModel->getAll();
				$this->view->value = $this->ValueModel->getAllFromProductId($product_id);
				
				$this->view->category = $this->CategoryModel->getAll(3);
				$this->view->categoryadd = $this->CategoryAddModel->getFromObjectId(3, $product_id);


				$this->view->gallery = $this->GalleryModel->getGallery();
				
				$this->view->galleryAdd = $this->GalleryAddModel->getFromIdObject(3, $product_id);

				
			}


    

    }
    

    
    public function deleteAction(){
	
		
		$request = $this->getRequest();
		$product_id = (int)$request->getParam('id');
		
		$deleteDir = new Admin_Model_deleteDir();
		
		$confirm = $request->getParam('confirm');


		if ($confirm == 'yes') {
		
				$this->ProductsModel->deleteProduct($product_id);
				
				$categoryadd->deleteAllProduct($product_id);
				
				$productValue->deleteAllProduct($product_id);
				
				
				$deleteDir->delTree('meme-media/products/'.$product_id);
				
				$galleryAdd = new Admin_Model_DbTable_Gallery_GalleryAddTable();
				$galleryAdd->deleteObject(3, $product_id);
				
							
		//Add Pages in sitemap
		$settings = new Admin_Model_DbTable_SettingsTable();
        $sitemap = new Admin_Model_sitemap();

		$post = $request->getPost();

		$sitemap->delete(array('node'=>'loc','value'=> $settings->getSettingGeneral('setting_address').'/product/'. $product_id));
		
		
		$sitemap->save();
		//-----------------------
				
				
				
				$this->_redirect('/admin/products/');

			} else {

				$this->view->product = $productModel->getProduct($product_id);
				
			}

	
	}



	public function categoryAction()
    {
		
		$this->view->category = $this->CategoryModel->getAll(3);

    }






    public function categorynewAction()
    {
		$request = $this->getRequest();
		if ($this->getRequest()->isPost()) {
		
			$category_id = $this->CategoryModel->add(3, $request->getPost(), $this->view->identity['user_id']);
		
			$this->_redirect('/admin/products/categoryedit/id/'.$category_id);
		
		}else{
			$this->view->categorytype = $this->CategoryModel->getAll(3);

        }
    }





    public function categoryeditAction()
    {
    
    	$request = $this->getRequest();
		$category_id = (int)$request->getParam('id');
		
		
		if ($this->getRequest()->isPost()) {

			$this->CategoryModel->edit(3, $request->getPost(), $this->view->identity['user_id']);

			$this->_redirect('/admin/products/categoryedit/id/'.$category_id);
			
		}else{

			$this->view->categorytype = $this->CategoryModel->getAll(3);
			$this->view->category = $this->CategoryModel->getFromId($category_id);

        }
    }




	public function categorydeleteAction()
	{

    	$request = $this->getRequest();
		$category_id = (int)$request->getParam('id');
		
		$confirm = $request->getParam('confirm');

		if ($confirm == 'yes') {

				$this->CategoryModel->deleteFromId($category_id);
				$this->CategoryAddModel->deleteFromCategory($category_id, 3);
				
				$this->_redirect('/admin/products/category');

			} else {

				$this->view->category = $this->CategoryModel->getFromId($category_id);
			}
	
	}






}