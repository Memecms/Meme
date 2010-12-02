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
 * @version		$Id: PagesTable.php 401 2010-11-18 20:25:30Z alex $
 */



class Admin_Model_DbTable_PagesTable extends Zend_Db_Table_Abstract {

	protected $_name = 'meme_pages';
	
	
	protected $cache;
	
	
	function init()
	{
		$this->cache = Zend_Registry::get('Meme_Cache');
		
		//define Model
		$this->SettingsModel = new Admin_Model_DbTable_SettingsTable();
		$this->SitemapModel = new Admin_Model_sitemap();
	}






    /**
     * find page row from controller name and action name
     *
	 * @param  string  $controller  Name of controller
	 * @param  string  $action  	Name of action
	 *
	 * @return Object         
     *                          
     *
     */
     
	public function getFromControllerAndAction($controller, $action)
	{
		$id = 'Page_getFromControllerAndAction_'.$controller.'_'.$action;		
		
		if(!($data = $this->cache->load($id)))
			{
					
				$select = $this->fetchRow($this->select()->where('page_controller =?', $controller)
														 ->where('page_action =?', $action));

						
				$this->cache->save($select, $id, array($this->_name));
				return $select;

			}
			else
			{			
				return $this->cache->load($id);
			}	
	}






	/**
     * Get all page
     *
	 * @return object       
     *                          
     *
     */
     
	public function getAll()
	{
		$id = 'Page_getAll';		
		
		if(!($data = $this->cache->load($id)))
			{
				$orderby = array('page_controller DESC');
				$result = $this->fetchAll('1', $orderby )->toArray();
				
				$this->cache->save($result, $id, array($this->_name));

				return $result;
			}
		else
			{
				return $this->cache->load($id);
			}
	}

	
	
	/**
     * Add new page
     *
	 * @param  array  $post  array content all information for page
	 * @return int		page id generated from id          
     *                          
     *
     */
     
	public function add($post)
	{
		$data = array( 
		
				'page_controller'=> $post['page_controller'],
				'page_action'=> $post['page_action'],
				'page_title'=> $post['page_title'],
				'page_date'=> mktime(),
				'page_description'=> $post['page_description'],
				'page_keywords'=> $post['page_keywords'],
				'page_content'=> stripslashes($post['page_content']),
				
					);

		$this->insert($data);
		
		
		//sitemap
       	$this->SitemapModel->add(array(
			'loc'=> $this->SettingsModel->get('address').'/'.$post['page_controller'].'/'.$post['page_action'],
			'changefreq'=>'monthly',
			'priority'=>'0.2'
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

		return $this->getAdapter()->lastInsertId();
	}







	/**
     * Search page from id page
     *
	 * @param  int  $page_id  Id page
	 * @return Object	Return information page request          
     *                          
     *
     */

	public function getFromIdPage($page_id)
	{
		
		$page_id = (int)$page_id;
		$id = 'Page_getFromIdPage_'.$page_id;		
		
		if(!($data = $this->cache->load($id)))
			{
				$row = $this->fetchRow('page_id = ' . $page_id);

				$this->cache->save($row, $id, array($this->_name));

				return $row;
			}
		else
			{
				return $this->cache->load($id);
			}
		
	}

	
	
	
	
	
	
	/**
     * Edit existent page
     *
	 * @param  array  $post  Array content edit page
	 * @return 	null         
     *                          
     *
     */
	
	public function edit($post)
	{
		$data = array(
		
				'page_controller'=> $post['page_controller'],
				'page_action'=> $post['page_action'],
				'page_title'=> $post['page_title'],
				'page_date'=> mktime(),
				'page_description'=> $post['page_description'],
				'page_keywords'=> $post['page_keywords'],
				'page_content'=> stripslashes($post['page_content']),

				);
		$where = 'page_id = '.$post['page_id'];
		
		
		//sitemap		
		$this->SitemapModel->delete(array('node'=>'loc','value'=> $this->SettingsModel->get('address').'/'.$post['page_controller_old'].'/'.$post['page_action_old']));

		
        $this->SitemapModel->add(array(
			'loc'=> $this->SettingsModel->get('address').'/'.$post['page_controller'].'/'.$post['page_action'],
			'changefreq'=>'monthly',
			'priority'=>'0.2'
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
     * Delete page fro id page
     *
	 * @param  int  $page_id  Id page
	 * @param  string  $controller  Name of controller
	 * @param  string  $action  	Name of action
	 *
	 * @return null          
     *                          
     *
     */

	public function deleteFromId($page_id, $controller, $action)
	{
	
		$where = $this->getAdapter()->quoteInto('page_id = ?', $page_id);


		//sitemap
		$this->SitemapModel->delete(array('node'=>'loc','value'=> $this->SettingsModel->get('address').'/'.$controller.'/'.$action));
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


		$this->delete($where);
	
	}
}