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
 * @version		$Id: SetupEditor.php 401 2010-11-18 20:25:30Z alex $
 */


class Zend_View_Helper_SetupEditor {    
    
        function setupEditor( $textareaId ) {
        return "<script type=\"text/javascript\">
					tinyMCE.init({
					// General options
					mode : \"exact\",
					elements : \"". $textareaId ."\",
					theme : \"advanced\",
					plugins : \"safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template\",
					// Theme options
					theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,|,image,media,|,pagebreak,|,fullscreen,code\",
					theme_advanced_buttons2 : \"\",
					theme_advanced_toolbar_location : \"top\",
					theme_advanced_toolbar_align : \"left\",
					theme_advanced_statusbar_location : \"bottom\",
					theme_advanced_resizing : true,
					theme_advanced_resize_horizontal : false,
					theme_advanced_resizing_use_cookie : true,
					
					file_browser_callback : 'tinyBrowser',
					
			
                	convert_urls : false,

							});
					</script>";
    }

    
}
?>