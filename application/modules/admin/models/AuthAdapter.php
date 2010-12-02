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
 * @version		$Id: AuthAdapter.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_Model_AuthAdapter implements Zend_Auth_Adapter_Interface
{
	protected $username;
	protected $password;
	protected $user;
	
	public function __construct($username, $password) {
		$this->username = $username;
		$this->password = $password;
		$this->user = new Admin_Model_DbTable_Users();
	}
	
	public function authenticate()
	{
		$match = $this->user->findCredentials($this->username, $this->password);
		//var_dump($match);
		if(!$match) {
			$result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null);
		} else {
			$user = current($match);
			$result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user);
		}
		return $result;
	}
	
}