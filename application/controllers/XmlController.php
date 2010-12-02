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
 * @version		$Id: XmlController.php 401 2010-11-18 20:25:30Z alex $
 */

class XmlController extends Zend_Controller_Action
{


    public function init()
    {
		$this->meme = new Admin_Model_MemeStart();

        $this->settings = $this->meme->getSetting();
        
        $this->cache = Zend_Registry::get('Meme_Cache');

    }
    
    
   	public function preDispatch()
		{
			//Disable layout

			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
	
		}


	public function indexAction()
	{
	
		$request = $this->getRequest();
		$gallery =  $request->getParam('gallery');
		
		header("Content-type: text/xml");

	
$cache_id = 'gallery_'.$gallery;		
		
if(!($data = $this->cache->load($cache_id)))
	{
					

		
		$GalleryTable = new Admin_Model_DbTable_Gallery_GalleryTable();
		$AddAlbumTable = new Admin_Model_DbTable_Gallery_AlbumAddTable();
		$AlbumTable = new Admin_Model_DbTable_Gallery_AlbumTable();

		$AddImageTable = new Admin_Model_DbTable_Gallery_ImageAddTable();
		$imagesTable = new Admin_Model_DbTable_Gallery_ImageTable();
		
				
		$gallery = $GalleryTable->getGalleryRow($gallery);



		$xml = '<?xml version="1.0" encoding="UTF-8"?>';


		$xml = $xml.'<gallery title="'.$gallery->gallery_title.'" description="'.$gallery->gallery_content.'">';
		
		
		foreach ($AddAlbumTable->getAlbumFromIdGallery($gallery->gallery_id) as $albums):
				$album = $AlbumTable->getAlbumRow($albums['album_id']);
				$xml = $xml.'<album id="'.$album['album_id'].'" lgPath="/meme-media/gallery/img/" tnPath="/meme-media/gallery/thumb/" title="'.$album['album_title'].'" description="'.$album['album_content'].'" tn="gallery/album1/preview.jpg">';
		
					foreach ($AddImageTable->getImageFromAlbum($album['album_id']) as $images):
						$image = $imagesTable->getImage($images['img_id']);
						
						$xml = $xml.'<img src="'.$image->img_file.'" id="'.$image->img_id.'" title="'.$image->img_title.'" caption="'.$image->img_content.'" link="'.$image->img_link.'" target="_blank" pause="" />';
					
					endforeach;

				$xml = $xml.'</album>';
        endforeach; 

		
		$xml = $xml.'</gallery>';


						
$this->cache->save($xml, $cache_id, array('gallery'));


		echo $xml;

					}
					else
					{
						
						echo $this->cache->load($cache_id);

					
					}

		
		
	}



}