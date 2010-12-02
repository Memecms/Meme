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
 * @version		$Id: MemeStart.php 401 2010-11-18 20:25:30Z alex $
 */

class Admin_Model_MemeStart 
{

	
	public function __construct() {
	
	}
	
	
	
	/**
     * Connect function getFromControllerAndAction on Class Admin_Model_DbTable_PagesTable
     *
     */
	
	public function Meme_Page_get()
	{
		$PageModel = new Admin_Model_DbTable_PagesTable();
		
		$request = Zend_Controller_Front::getInstance()->getRequest();
		
		return $PageModel->getFromControllerAndAction($request->getControllerName(), $request->getActionName());

	}

	
	
	
	
	// setting
	public function Meme_Setting_get()
	{
		$SettingModel = new Admin_Model_DbTable_SettingsTable();
		return $SettingModel->getAllSettingGeneral();
	}

	
	
	
	
	
	
	
	
	
	
	
	
	//article
	
	public function getPostList($count = '', $home = '' , $offset = '')		//approvated
		{
			
			$PostTable = new Admin_Model_DbTable_PostsTable();
			
			$where = array(array('where' => 'post_status = 0'));
			
			if($home == 'home')
				{array_push($where,array('where' => 'post_home = 1'));}
			
			return $PostTable->getPosts($where, 'post_date DESC', $count, $offset);

		
		}

	public function getPostCategoryAllFromNameUrl($categoryNameUrl)
	{
		$CategoryTable = new Admin_Model_DbTable_Posts_CategoryTable();

		return $CategoryTable->getCategoryFromNameUrl($categoryNameUrl);
	}




	public function getPostsFromIdCategory($categoryId)
	{
	
	
		$PostsTable = new Admin_Model_DbTable_PostsTable();
		$CategoryTable = new Admin_Model_DbTable_Posts_CategoryTable();
		$CategoryAddTable = new Admin_Model_DbTable_Posts_CategoryAddTable();
		

		$postsId =  array();
		
		foreach ($CategoryAddTable->getCategoryAdd('meme_posts_category.category_id = '.$categoryId) as $attributes ):
        	array_push($postsId, $attributes['post_id']);
        endforeach; 
        
        $count = count($postsId);
		
		
		if ($count == 0){
				$Posts = array();
			}
		else
			{
				$Posts = $PostsTable->getPosts($postsId, 'post_date DESC', '', '' , true, false,  true);
			}
		
		
		return $Posts;


	}



	public function getPostFromId($id)
	{

		$PostTable = new Admin_Model_DbTable_PostsTable();
		return $PostTable->getPost($id);
	
	}
	


	public function getPostCategory()
	{
	
		$CategoryTable = new Admin_Model_DbTable_Posts_CategoryTable();
		return $CategoryTable->get();
	
	}




	
	public function getCategoryFromPost($post_id)
	
		{
			
			$CategoryTable = new Admin_Model_DbTable_Posts_CategoryTable();
			$CategoryAddTable = new Admin_Model_DbTable_Posts_CategoryAddTable();
			
			
			$categoryId =  array();
			
			foreach ($CategoryAddTable->getcategoryFromIdPost($post_id) as $attributes ):
        			
        		array_push($categoryId,$attributes['category_id']);

        	endforeach; 
		
			return $CategoryTable->findCategoryFromId($categoryId);
		
		
		}














	//product

	public function getProductsList($count = '', $offset = '')
	{
	
			$ProductsTable = new Admin_Model_DbTable_ProductsTable();
			
//			$where = array(array('where' => 'post_status = 0'));

			
			
			return $ProductsTable->get('', 'product_date DESC', $count, $offset);			
	
	}
	
	
	public function getProductsCategoryAllFromNameUrl($categoryNameUrl)
	{
		$CategoryTable = new Admin_Model_DbTable_Products_CategoryTable();

		return $CategoryTable->getCategoryFromNameUrl($categoryNameUrl);
	}


	
	
	public function getProductsFromIdCategory($categoryId)
	{
	
	
		$ProductsTable = new Admin_Model_DbTable_ProductsTable();
		$CategoryTable = new Admin_Model_DbTable_Products_CategoryTable();
		$CategoryAddTable = new Admin_Model_DbTable_Products_CategoryAddTable();
		

		$productsId =  array();
		
		foreach ($CategoryAddTable->getCategoryAdd('meme_products_category.category_id = '.$categoryId) as $attributes ):
        	array_push($productsId, $attributes['product_id']);
        endforeach; 
        
        $count = count($productsId);
		
		
		if ($count == 0){
				$Products = array();
			}
		else
			{
				$Products = $ProductsTable->get($productsId, 'product_date DESC', '', '' , true, false,  true);
			}
		
		
		return $Products;


	}

	
	
	
	
	
	public function getProductCategory()
	{
		$ProductsCategoryTable = new Admin_Model_DbTable_Products_CategoryTable();
			

		return $ProductsCategoryTable->get();
	
	
	}

	
	
	
	public function getSubCategoryList($categoryname)
	{
			$ProductsCategoryTable = new Admin_Model_DbTable_Products_CategoryTable();
			
			$resultCategory = $ProductsCategoryTable->getFromNameUrlCategory($categoryname);
			
			$idCategory = $resultCategory['category_id'];

	
		return $ProductsCategoryTable->getSubCategoryFromId($idCategory);
	
	
	}
	
	

	
	
	
	
	public function productGet($id)
	{
	
		$products = new Admin_Model_DbTable_ProductsTable();
		
		return $products->getProduct($id);
	
	}

	
	
	
	
	
	public function getCategoryFromProduct($product_id)
	
		{
			
			$CategoryTable = new Admin_Model_DbTable_Products_CategoryTable();
			$CategoryAddTable = new Admin_Model_DbTable_Products_CategoryAddTable();
			
			
			$categoryId =  array();
			
			foreach ($CategoryAddTable->getcategoryFromIdProduct($product_id) as $attributes ):
        			
        		array_push($categoryId,$attributes['category_id']);

        	endforeach; 
		
			return $CategoryTable->findCategoryFromId($categoryId);
		
		
		}


	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function productsListWhere($category)
	{
		$categoryadd = new Admin_Model_DbTable_Products_CategoryAddTable();
		$products = new Admin_Model_DbTable_ProductsTable();

		return $products->getWHERE($categoryadd->getProducts($category));	
	}



}