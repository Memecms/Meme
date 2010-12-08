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
 * @version		$Id: ProductsTable.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_Model_DbTable_ProductsTable extends Zend_Db_Table_Abstract {

	protected $_name = 'meme_products';


	function init()
	{
		$this->cache = Zend_Registry::get('Meme_Cache');
		
		//define Model
		$this->urlAdapter = new Admin_Model_urlAdapter();
		$this->CategoryAddModel = new Admin_Model_DbTable_CategoryAddTable();
		$this->UserModel = new Admin_Model_DbTable_Users();
		$this->SettingsModel = new Admin_Model_DbTable_SettingsTable();
		$this->SitemapModel = new Admin_Model_sitemap();
	}

	
	
		
    /**
     * Result all post 
     *
	 * @param  string	$order			Ordere for return of posts
	 * @param  int		$count			Number of item result
	 * @param  int		$offset			Offset limit result item
	 * @param  boolean	$published		Status of post request
	 * @param  boolean	$home			Visible on home
	 *
	 * @return Array	Result posts          
     *                          
     *
     */
 
	public function getAll($order = 'product_date DESC', $count = '', $offset = '', $where = null)
	{
		
		$id = 'Porducts_getAll_'.$order.'_'.$count.'_'.$offset.'_'.$where;	
		$id = $this->urlAdapter->cleanURL($id);
		
		if(!($data = $this->cache->load($id)))
			{
				$select = $this->select()->order($order)
                	    	      		 ->limit($count, $offset);
                
                if($where != null)
                {
                $select = $select->where('product_id IN ('.$where.')');
				}

               	$products = $this->fetchAll($select)->toArray();
				
               	$categories = $this->CategoryAddModel->getAllAdd(3);
				
            	$i = 0;
				foreach ($products as $attributes):
					$products[$i]['category'] = array();
					$products[$i]['product_user'] = $this->UserModel->getUsername($attributes['product_user']);
								
					$i_cat = 0;
					foreach ($categories as $category):
						if($category['object_id'] == $attributes['product_id'])
						{
	        				$products[$i]['category'][$i_cat] = $category;
						}
						$i_cat++;
        			endforeach;
				
					$i++;
        		endforeach;
               	
               	
				$this->cache->save($products, $id, array($this->_name));
				return $products;
			}
		else
			{
				return $this->cache->load($id);
			}
	}	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
     * Add post on database
     *
	 * @param  int  $post  Post information for add
	 *
	 * @return int	Id post assigned on database          
     *                          
     *
     */
     
	public function add($product)
	{

            Zend_Date::setOptions(array(
                'format_type'   => 'php'
            ));

            $dateTime = $product['datepicker'].' '.$product['hh'].':'.$product['mn'];
            $date = new Zend_Date($dateTime, 'Y-m-d H:i');
            $unixTimestamp = $date->get();


		$data = array( 
		
				'product_name'=> $product['product_name'],
				'product_content'=> stripslashes($product['product_content']),
				'product_description'=> $product['product_description'],
				'product_keywords'=> $product['product_keywords'],
				'product_date'=> $unixTimestamp,
				'product_user'=> $product['product_user'],
				'product_url' => $this->urlAdapter->cleanURL($product['product_name']),
					);

		$this->insert($data);
		
		$lastid = $this->getAdapter()->lastInsertId();

		//sitemap
		
        $this->SitemapModel->add(array(
			'loc'=> $this->SettingsModel->get('address').'/products/'.$lastid.'/'.$data['product_url'],
			'changefreq'=>'monthly',
			'priority'=>'0.5'
				));

		$this->SitemapModel->save();
		//-----------------------


		//cache
		if(extension_loaded('apc') == 1)
					{	// clean all records
						$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
 
					}
				else
					{
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array($this->_name));
						$this->cache->clean(Zend_Cache::CLEANING_MODE_OLD);					
					}
		//end cache
		
		return $lastid;

	}

	
	
	
	
	
	/**
     * Edit  post
     *
	 * @param  array  $post  Post information for edit
	 *
	 * @return null      
     *                          
     *
     */
	
	public function edit($product)
	{

            Zend_Date::setOptions(array(
                'format_type'   => 'php'
            ));

            $dateTime = $product['datepicker'].' '.$product['hh'].':'.$product['mn'];
            $date = new Zend_Date($dateTime, 'Y-m-d H:i');
            $unixTimestamp = $date->get();


		$data = array(
				'product_name'=> $product['product_name'],
				'product_content'=> stripslashes($product['product_content']),
				'product_description'=> $product['product_description'],
				'product_keywords'=> $product['product_keywords'],
				'product_date'=> $unixTimestamp,
				'product_url' => $this->urlAdapter->cleanURL($product['product_name']),
				'product_user' => $product['product_user'],
				);

		$where = 'product_id = '.$product['product_id'];
		
		
		//sitemap
 		$this->SitemapModel->delete(array('node'=>'loc','value'=> $this->SettingsModel->get('address').'/product/'.$product['product_id'].'/'.$prodcut['product_url_old']));

        $this->SitemapModel->add(array(
			'loc'=> $this->SettingsModel->get('address').'/product/'.$prodcut['product_id'].'/'.$data['product_url'],
			'changefreq'=>'monthly',
			'priority'=>'0.5'
				));

		$this->SitemapModel->save();
		
		//-----------------------

		
		//cache
		if(extension_loaded('apc') == 1)
					{	// clean all records
						$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
 
					}
				else
					{
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array($this->_name));
						$this->cache->clean(Zend_Cache::CLEANING_MODE_OLD);					
					}
		//end cache
		
		
		
		$this->update($data , $where);
	}

	
	
	
	
	
	
	
	
		
	/**
     * Search post from id post
     *
	 * @param  int  $post_id  Post id for search
	 *
	 * @return Array    Post      
     *                          
     *
     */
	
	public function getFromProductId($product_id)
	{
	
		$product_id = (int)$product_id;
		$id = 'Product_getFromProductId_'.$product_id;
		
		if(!($data = $this->cache->load($id)))
			{

					$product = $this->fetchRow('product_id = '. $product_id)->toArray();
	              	
	              	$categories = $this->CategoryAddModel->getFromObjectId(3, $product_id);
					$product['category'] = array();
					$product['product_user'] = $this->UserModel->getUsername($product['product_user']);
								
					$i_cat = 0;
					foreach ($categories as $category):
	        				$product['category'][$i_cat] = $category;
						$i_cat++;
        			endforeach;
				
		
				
				$this->cache->save($product, $id, array($this->_name));
				return $product;

			}
			else
			{
				return $this->cache->load($id);
			}
		
	}
	
	
	
	


	/**
	 * Delete product.
	 * 
	 * @access public
	 * @param int $product_id	Product id for delete
	 * @return void
	 */
	 
	 
	public function deleteFromProductId($product_id)
	{
	
		$where = $this->getAdapter()->quoteInto('product_id = ?', $id);
		
		
		
		
		
		//cache
		if(extension_loaded('apc') == 1)
					{	// clean all records
						$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
 
					}
				else
					{
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array($this->_name));
						$this->cache->clean(Zend_Cache::CLEANING_MODE_OLD);					
					}
		//end cache

		$this->delete($where);
	}


}