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
 * @version		$Id: Users.php 401 2010-11-18 20:25:30Z alex $
 */



/* user_role

0- Delete
1- Pending E-mail verification
2- Gust
3- Contributor
4- Author
5- Editor
6- Administrator

*/

class Admin_Model_DbTable_Users extends Zend_Db_Table_Abstract
{


	protected $_name = 'meme_users';
	
	protected $cache;
	
	
	function init()
	{
		$this->cache = Zend_Registry::get('Meme_Cache');
	}


	
	
	public function findCredentials($username, $pwd)
	{
		$select = $this->select()->where('user_username = ?', $username)
			->where('user_password = ?', $this->hashPassword($pwd));
		$row = $this->fetchRow($select);
		if($row) {
			/*
			 * If success return the row
			 */
			return $row;
		}
		return false;
	}

	protected function hashPassword($pwd)
	{
		/*
		 * return an md5 hash
		 */
		return md5($pwd);
	}

	protected function who($user_id)
	{
		$row = $this->fetchRow('user_id = ' . $user_id);
		if (!$row) {
			throw new Exception("Count not find row $user_id");
		}
		return $row;

	}
	
	
	
	public function signup($data)
	{
                $data = array(
					'user_firstname'=> $data['firstname'],
					'user_lastname'=> $data['lastname'],
					'user_username'=> $data['username'],
					'user_password'=> md5($data['password']),
					'user_role'=> 1,
					'user_email'=> $data['email'],
					'user_timesigned'=> mktime()
				);

		$this->insert($data);
		
		$lastid = $this->getAdapter()->lastInsertId();
		
		return $lastid;
	
	}

	
	
	public function UpdateRole($role, $user_id)
	{
		$data = array(
    		'user_role' => $role
		);
 
		$where = $this->getAdapter()->quoteInto('user_id = ?', $user_id);
 
		$this->update($data, $where);
	}
	
	
	
	
	public function getUsername($user_id)
	{
		$id = 'User_getUsername_'.$user_id;
		
		if(!($data = $this->cache->load($id)))
			{
				$row = $this->fetchRow($this->select()->from('meme_users', array('user_username', 'user_id', 'user_firstname', 'user_lastname','user_email'))->where('user_id = ?' , $user_id))->toArray();
		
		
				$this->cache->save($row, $id, array($this->_name));

				return $row;
			}
			else
			{		
				return $this->cache->load($id);
			}

	}
	
	
	
}