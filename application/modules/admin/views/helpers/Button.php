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
 * @version		$Id: Button.php 401 2010-11-18 20:25:30Z alex $
 */


class Zend_View_Helper_Button extends Zend_View_Helper_Abstract {    
    
        public function button( $who ) {
        
        		$jquery = $this->view->jQuery()->enable()->uiEnable();
		
		$jqHandler = ZendX_JQuery_View_Helper_JQuery::getJQueryHandler();
 
        $function = '("'.$who.'").button();';
        $jquery->addOnload($jqHandler . $function);
        
        return "";
    }

    
}
?>