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
 * @version		$Id: urlAdapter.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_Model_urlAdapter 
{

	public function __construct() {
	
		
	}	
	
	public function cleanURL($string){
		$url = str_replace("'", '', $string);
		
		$url = str_replace("ˆ","a",$url);
		$url = str_replace("","e",$url);
		$url = str_replace("Ž","e",$url);
		$url = str_replace("˜","o",$url);
		$url = str_replace("","u",$url);
		$url = str_replace("“","i",$url);
		
		$url = str_replace('%20', ' ', $url);
		$url = preg_replace('~[^\\pL0-9_]+~u', '_', $url); // substitutes anything but letters, numbers and '_' with separator
		$url = trim($url, "-");
		$url = iconv("utf-8", "us-ascii//TRANSLIT", $url);// you may opt for your own custom character map for encoding.
		$url = strtolower($url);
		$url = preg_replace('~[^-a-z0-9_]+~', '', $url); // keep only letters, numbers, '_' and separator
		
		return $url;
	}	


}