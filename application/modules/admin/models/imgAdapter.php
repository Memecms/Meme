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
 * @version		$Id: imgAdapter.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_Model_imgAdapter 
{

	public function __construct() {
	
		
	}


	public function galleryThumb($file, $newfile, $name, $width = 200, $height = 200) {
	
					require_once '../library/phpthumb/ThumbLib.inc.php';

					$options = array('resizeUp' => true, 'jpegQuality' => 100);

					$path_parts = pathinfo($file); 

					$thumb = PhpThumbFactory::create($file, $options);
					$thumb->resize($width, $height);
					$thumb->save($newfile , $path_parts['extension']);

					unlink($file);
	}






	public function manipulate($file, $patch, $name) {
	
					require_once '../library/phpthumb/ThumbLib.inc.php';

					$tempFile = $file['tmp_name'];
        			$targetPath = $_SERVER['DOCUMENT_ROOT'] . '/../application/meme-tmp/';
        			$targetPath =  str_replace('//','/',$targetPath);
        			$targetFile =  $targetPath . $file['name'];
        			move_uploaded_file($tempFile,$targetFile);

					$options = array('resizeUp' => true, 'jpegQuality' => 100);

					$thumb = PhpThumbFactory::create($targetFile, $options);
					$thumb->resize(300, 300);
					$thumb->save($_SERVER['DOCUMENT_ROOT'] . '/meme-media/' . $patch . $name . '.jpg', 'jpg');

					unlink($targetFile);
	}
	
	
		public function productMore($file, $product_id, $products_attribute_id) {
	
					require_once '../library/phpthumb/ThumbLib.inc.php';

					$tempFile = $file['tmp_name'];
        			$targetPath = $_SERVER['DOCUMENT_ROOT'] . '/../application/meme-tmp/';
        			$targetPath =  str_replace('//','/',$targetPath);
        			$targetFile =  $targetPath . $file['name'];
        			move_uploaded_file($tempFile,$targetFile);

					$options = array('resizeUp' => true, 'jpegQuality' => 100);

					$thumb = PhpThumbFactory::create($targetFile, $options);
					$thumb->resize(300, 300);
					$thumb->save($_SERVER['DOCUMENT_ROOT'] . '/meme-media/' . 'products/'.$product_id.'/img/' . $products_attribute_id . '.jpg', 'jpg');
					
					
					$thumb = PhpThumbFactory::create($targetFile, $options);
					$thumb->resize(300, 300);
					$thumb->save($_SERVER['DOCUMENT_ROOT'] . '/meme-media/' . 'products/'.$product_id.'/thumb/' . $products_attribute_id . '.jpg', 'jpg');
					

					unlink($targetFile);
	}

	
	
}