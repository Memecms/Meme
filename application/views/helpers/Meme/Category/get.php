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
 * @version		$Id: Category.php 401 2010-11-18 20:25:30Z alex $
 */


class Zend_View_Helper_Meme_Category_get {

 
    function Meme_Category_get($what) {
    	
    	if ($what == 'post'){$object_type = 1;}
    	elseif($what == 'page'){$object_type = 2;}
    	elseif($what == 'product'){$object_type = 3;}

    	
        $CategoryModel = new Admin_Model_DbTable_CategoryTable();
    	$CategoryList = $CategoryModel->getAll($object_type);
		
		$categories = '<h3>Posts Categories</h3>';
		if(count($CategoryList)):
			foreach($CategoryList as $category):
				if($category['category_type'] == 0 ){
    			$categories = $categories.'<a href="/articles/'.$category['category_name_url'].'">'.$category['category_name'].'</a>';
    			foreach($CategoryList as $subcategory): 
						if($subcategory['category_type'] == $category['category_id'])
							{
								$categories = $categories.'<a href="/articles/'.$subcategory['category_name_url'].'" class="sub">'.$subcategory['category_name'].'</a>';
							} 

				endforeach;
				
				}
    		
    		
    		
    		endforeach;
			
		else :
			$categories = $categories.'0 Categories';
		endif;
		

    	return $categories;
    }

}

?>