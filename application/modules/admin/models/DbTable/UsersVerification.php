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
 * @version		$Id: UsersVerification.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_Model_DbTable_UsersVerification extends Zend_Db_Table_Abstract
{
	/*
	 * @var $_name table name : users
	 */
	protected $_name = 'meme_users_verification';
	
	public function findVerification($key)
	{
		$select = $this->select()->where('user_key = ?', $key);
		$row = $this->fetchRow($select);
		if($row) {
			/*
			 * If success return the row
			 */
			return $row;
		}
		return false;
	}

	
	public function add($key, $user_id)
	{
                $data = array(
					'user_id'=> $user_id,
					'user_key'=> $key,
				);

		$this->insert($data);
		
		$lastid = $this->getAdapter()->lastInsertId();
		
		return $lastid;
	
	}

	


	
	public function deletekey($key)
	{
	
		$where = $this->getAdapter()->quoteInto('user_key = ?', $key);

		$this->delete($where);
	
	}
	
	
	

	
}