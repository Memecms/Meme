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
 * @version		$Id: SettingsTable.php 401 2010-11-18 20:25:30Z alex $
 */



class Admin_Model_DbTable_SettingsTable extends Zend_Db_Table_Abstract {

	protected $_name = 'meme_settings';

	protected $cache;
	
	
	function init()
	{
		$this->cache = Zend_Registry::get('Meme_Cache');
		
		$this->urlAdapter = new Admin_Model_urlAdapter();
	}



	public function get($what)
	{
		$id = 'Settings_get_'.$what;
		$id = $this->urlAdapter->cleanURL($id);

		if(!($data = $this->cache->load($id)))
		{
			$row = $this->fetchRow($this->select()->where('setting_name =?', $what))->setting_value;
			$this->cache->save($row, $id, array($this->_name));
			
			return $row;
		}
		else
		{		
			return $this->cache->load($id);		
		}
	}







	public function updateAll($setting)
	{
	
		
	
		$data = array('setting_value'=> $setting['sitetitle'],);
		$where = $this->getAdapter()->quoteInto('setting_name = ?', 'sitetitle');
		$this->update($data , $where );

		$data = array('setting_value'=> $setting['tagline'],);
		$where = $this->getAdapter()->quoteInto('setting_name = ?', 'tagline');
		$this->update($data , $where );

		$data = array('setting_value'=> $setting['address'],);
		$where = $this->getAdapter()->quoteInto('setting_name = ?', 'address');
		$this->update($data , $where );

		$data = array('setting_value'=> $setting['emailaddress'],);
		$where = $this->getAdapter()->quoteInto('setting_name = ?', 'emailaddress');
		$this->update($data , $where );
		
		$data = array('setting_value'=> $setting['analitycs_id'],);
		$where = $this->getAdapter()->quoteInto('setting_name = ?', 'analitycs_id');
		$this->update($data , $where );

		$data = array('setting_value'=> $setting['analitycs_username'],);
		$where = $this->getAdapter()->quoteInto('setting_name = ?', 'analitycs_username');
		$this->update($data , $where );

		$data = array('setting_value'=> $setting['analitycs_password'],);
		$where = $this->getAdapter()->quoteInto('setting_name = ?', 'analitycs_password');
		$this->update($data , $where );

		
		
		//cache
		if(extension_loaded('apc') == 1)
					{	// clean all records
						$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
 
					}
				else
					{
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array($this->_name));
						$this->cache->clean(Zend_Cache::CLEANING_MODE_OLD);					
					}
		//end cache


	}








//vecchio

	public function getSettingGeneral($setting_name)
	{
		$row = $this->fetchRow($this->select()->where('setting_name =?', $setting_name));
		return $row->setting_value;
	}



	public function getAllSettingGeneral()
	{
	
				
			
		$id = 'Settings_All';
				
				
		if(!($data = $this->cache->load($id)))
			{


	
	
		$result = $this->fetchAll();
		foreach ($result as $setting):
        		{
        			$setting_info[$setting['setting_name']] = $setting['setting_value'];			
        		}	
        endforeach; 
		
		
		
				$this->cache->save($setting_info, $id, array('settings', 'setting'));

				return $setting_info;


				}
		else
			{
						
				return $this->cache->load($id);

					
			}
		
		
	}
	
	
	
	public function updateSetting($post)
	{
	
		
	
		$data = array('setting_value'=> $post['sitetitle'],);
		$where = $this->getAdapter()->quoteInto('setting_name = ?', 'sitetitle');
		$this->update($data , $where );

		$data = array('setting_value'=> $post['tagline'],);
		$where = $this->getAdapter()->quoteInto('setting_name = ?', 'tagline');
		$this->update($data , $where );

		$data = array('setting_value'=> $post['address'],);
		$where = $this->getAdapter()->quoteInto('setting_name = ?', 'address');
		$this->update($data , $where );

		$data = array('setting_value'=> $post['emailaddress'],);
		$where = $this->getAdapter()->quoteInto('setting_name = ?', 'emailaddress');
		$this->update($data , $where );
		
		//cache
		if(extension_loaded('apc') == 1)
					{	// clean all records
						$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
 
					}
				else
					{
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array($this->_name));
						$this->cache->clean(Zend_Cache::CLEANING_MODE_OLD);					
					}
		//end cache


	}


}