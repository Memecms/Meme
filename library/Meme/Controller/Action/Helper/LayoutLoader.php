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
 * @version		$Id: Bootstrap.php 397 2010-11-17 14:54:21Z alex $
 */

class Meme_Controller_Action_Helper_LayoutLoader extends Zend_Controller_Action_Helper_Abstract 
{
	
	public function preDispatch() 
	{
		$bootstrap = $this->getActionController()->getInvokeArg('bootstrap');
		$config = $bootstrap->getOptions();
		$module = $this->getRequest()->getModuleName();
		if (isset($config[$module]['resources']['layout']['layout'])) {
			$layoutScript = $config[$module]['resources']['layout']['layout'];
			$this->getActionController()
				 ->getHelper('layout')
				 ->setLayout($layoutScript);
		}
	}
	
}