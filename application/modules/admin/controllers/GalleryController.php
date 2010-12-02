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
 * @version		$Id: GalleryController.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_GalleryController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        
        
        $this->view->identity = Zend_Auth::getInstance()->getIdentity();
        //Login verificated   
        if(!Zend_Auth::getInstance()->hasIdentity()) {
			$this->_redirect('admin/login');
		}
		elseif($this->view->identity['user_role'] != 6)
		{
			$this->_redirect('/admin/login/logout');
		}

       	// Set name of current controller for select the current page in manu on layout 
		$this->view->controllerName = $this->getRequest()->getControllerName();
		
		
		
		
		// Setting model
		$SettingsModel = new Admin_Model_DbTable_SettingsTable();
		$this->view->setting_sitetitle = $SettingsModel->get('sitetitle');
		
		
		
		
		
		
		$this->view->headTitle('MEME CMS -> Admin -> Gallery');

    }
    
    
   	public function preDispatch()
	{


	}

    public function indexAction()
    {
        // action body
        
        
        $pages = new Admin_Model_DbTable_Gallery_GalleryTable();
		$result = $pages->getGallery();
		$page = $this->_getParam('page',1);
		$paginator = Zend_Paginator::factory($result);
		$paginator->setItemCountPerPage(10);
		$paginator->setCurrentPageNumber($page);
		$this->view->paginator = $paginator;


	}
	
	
	public function gallerynewAction()
    {
    
        $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/tiny_mce.js');
        $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php');
 
 		$request = $this->getRequest();

 		if ($this->getRequest()->isPost()) {
			
			$model = new Admin_Model_DbTable_Gallery_GalleryTable();
			
			$field = $request->getPost();
						
			
			$id = $model->saveGallery($field);				
						
		
			$this->_redirect('/admin/gallery/galleryedit/id/'.$id);


		}


    }


	public function galleryeditAction()
	{
	    $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/tiny_mce.js');
        $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php');

	
		$request = $this->getRequest();
		$id = (int)$request->getParam('id');
			
		$Model = new Admin_Model_DbTable_Gallery_GalleryTable();
		

		if ($this->getRequest()->isPost()) {


			
				$requestPost = $request->getPost();
			

			
			
				$Model->updateGallery($requestPost);
				
				
				
				
				



				$this->_redirect('/admin/gallery/galleryedit/id/'.$id);

		}
		else
		{
				

				
				$this->view->gallery = $Model->getGalleryRow($id);


		}
	
	}




	public function gallerydeleteAction()
	{
	
		$Model = new Admin_Model_DbTable_Gallery_GalleryTable();
		
		$modelAlbumAdd = new Admin_Model_DbTable_Gallery_AlbumAddTable();
		
		$request = $this->getRequest();
		
		$galleryid = (int)$request->getParam('id');


		$result = $Model->getGalleryRow($galleryid);
		

		$confirm = $request->getParam('confirm');

		$this->view->galleryid = $galleryid;


		if ($confirm == 'yes') {
		

				$Model->deleteGallery($galleryid);
				
				$modelAlbumAdd->deleteAllGallery($galleryid);
		
							
				
				$this->_redirect('/admin/gallery/');


			} else {

				$this->view->gallery = $result;
				
			}
	
	}




    public function albumAction()
    {
        // action body
        
        
        $pages = new Admin_Model_DbTable_Gallery_AlbumTable();
		$result = $pages->getAlbum();
		$page = $this->_getParam('page',1);
		$paginator = Zend_Paginator::factory($result);
		$paginator->setItemCountPerPage(10);
		$paginator->setCurrentPageNumber($page);
		$this->view->paginator = $paginator;


	}



	public function albumnewAction()
    {
    
        $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/tiny_mce.js');
        $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php');
 
 		$request = $this->getRequest();
 		
 		
 		$gallerymodel = new Admin_Model_DbTable_Gallery_GalleryTable();
 		
 		

 		if ($this->getRequest()->isPost()) {
			
			$model = new Admin_Model_DbTable_Gallery_AlbumTable();
			
			$galleryadd = new Admin_Model_DbTable_Gallery_AlbumAddTable();
			
			$field = $request->getPost();
						
			// estrazione gallery selected

			$galleryList = $gallerymodel->getGallery();
			
			
			$albumid = $model->saveAlbum($field);				

			
			foreach ($galleryList as $gallery):

					if($field['gallery'.$gallery['gallery_id']] == '1')
					{
        				$galleryadd->addGallery($albumid, $gallery['gallery_id']);
					}        		

        	endforeach; 
		
	
			
						
		
			$this->_redirect('/admin/gallery/albumedit/id/'.$albumid);


		}
		else
		{
		 $this->view->gallery = $gallerymodel->getGallery();
		}


    }



	public function albumeditAction()
	{
	    $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/tiny_mce.js');
        $this->view->headScript()->appendFile('/meme-content/js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php');

	
		$request = $this->getRequest();
		$id = (int)$request->getParam('id');
			
		$Model = new Admin_Model_DbTable_Gallery_AlbumTable();
		 		
		$gallerymodel = new Admin_Model_DbTable_Gallery_GalleryTable();

		$albumAddmodel = new Admin_Model_DbTable_Gallery_AlbumAddTable();


		if ($this->getRequest()->isPost()) {


			
			$field = $request->getPost();
				
			// estrazione gallery selected

			$galleryList = $gallerymodel->getGallery();
			
			$albumAddmodel->deleteAllAlbum($id);

			
			foreach ($galleryList as $gallery):

					if($field['gallery'.$gallery['gallery_id']] == '1')
					{
        				$albumAddmodel->addGallery($id, $gallery['gallery_id']);
					}        		

        	endforeach; 
		
		

			
			
				$Model->updateAlbum($field);



				$this->_redirect('/admin/gallery/albumedit/id/'.$id);

		}
		else
		{

				$this->view->album = $Model->getAlbumRow($id);
				$this->view->albumadd = $albumAddmodel->getGallery($id);
				$this->view->gallery = $gallerymodel->getGallery();


		}
	
	}


	public function albumdeleteAction()
	{
	
		$Model = new Admin_Model_DbTable_Gallery_AlbumTable();
		$modelAlbumAdd = new Admin_Model_DbTable_Gallery_AlbumAddTable();
		$modelImageAdd = new Admin_Model_DbTable_Gallery_ImageAddTable();
		
		$request = $this->getRequest();
		
		$id = (int)$request->getParam('id');


		$result = $Model->getAlbumRow($id);
		

		$confirm = $request->getParam('confirm');

		$this->view->albumid = $id;


		if ($confirm == 'yes') {
		

				$Model->deleteAlbum($id);
				
				$modelAlbumAdd->deleteAllAlbum($id);
				
				
				$modelImageAdd->deleteAllAlbum($id);
				
				
				
				
				$this->_redirect('/admin/gallery/album/');


			} else {

				$this->view->album = $result;
				
			}
	
	}


    public function imagesAction()
    {
        // action body
        
        
        $pages = new Admin_Model_DbTable_Gallery_ImageTable();
		$result = $pages->getImages();
		$page = $this->_getParam('page',1);
		$paginator = Zend_Paginator::factory($result);
		$paginator->setItemCountPerPage(10);
		$paginator->setCurrentPageNumber($page);
		$this->view->paginator = $paginator;


	}


    public function imageseditAction()
    {


		$request = $this->getRequest();
		$id = (int)$request->getParam('id');

			$GalleryModel = new Admin_Model_DbTable_Gallery_GalleryTable();
			$AlbumModel = new Admin_Model_DbTable_Gallery_AlbumTable();
			$addAlbumModel = new Admin_Model_DbTable_Gallery_AlbumAddTable();
			$this->view->GalleryList = $GalleryModel->getGallery();
			$this->view->AlbumList = $AlbumModel->getAlbum();
			$this->view->AddAlbum = $addAlbumModel->getAllGallery();

			$ImagesModel = new Admin_Model_DbTable_Gallery_ImageTable();


			$imageAdd = new Admin_Model_DbTable_Gallery_ImageAddTable();
		
				
		
		if ($this->getRequest()->isPost()) {
		
			$field = $request->getPost();
			Zend_Debug::dump($field);
			

			$ImagesModel->updateImage($field);
			
			
			$imageAdd->deleteImage($id);
			
			
			//ciclo inserisci add
				foreach ($this->view->AlbumList as $album):
        			if(isset($field['album'.$album['album_id']]) == 1){
        				$imageAdd->addImage($id, $album['album_id']);
					}
        			
        		endforeach; 



				$this->_redirect('/admin/gallery/imagesedit/id/'.$id);

			
			
			
		}else {
		
				$this->view->image = $ImagesModel->getImage($id);
				
				$this->view->imageAdd = $imageAdd->getImage($id);
				

		
		}
		

	}






	public function imagesdeleteAction()
	{
	
		$Model = new Admin_Model_DbTable_Gallery_ImageTable();
		$modelImageAdd = new Admin_Model_DbTable_Gallery_ImageAddTable();
		
		$request = $this->getRequest();
		
		$id = (int)$request->getParam('id');


		$result = $Model->getImage($id);
		

		$confirm = $request->getParam('confirm');



		if ($confirm == 'yes') {
		

				$Model->deleteImage($id);
				
				$modelImageAdd->deleteImage($id);
				
				
				// delete img
				$file = 'meme-media/gallery/img/'.$result['img_file'];

				unlink($file);
				
				// delete thumb
				$file = 'meme-media/gallery/thumb/'.$result['img_file'];

				unlink($file);

				
				$this->_redirect('/admin/gallery/images/');


			} else {

				$this->view->image = $result;
				
			}
	
	}






	public function uploadimagesAction()
    {
    	
		

    
    	
    	$this->view->request = $this->getRequest();
    		




		if ($this->getRequest()->isPost()) {
			
			$GalleryModel = new Admin_Model_DbTable_Gallery_GalleryTable();
			$AlbumModel = new Admin_Model_DbTable_Gallery_AlbumTable();
			$addAlbumModel = new Admin_Model_DbTable_Gallery_AlbumAddTable();
		
			$this->view->GalleryList = $GalleryModel->getGallery();
			$this->view->AlbumList = $AlbumModel->getAlbum();
			$this->view->AddAlbum = $addAlbumModel->getAllGallery();

		
			$this->view->images =  $this->view->request->getPost();
			

			
			if (isset($this->view->images['count']))
			{
				$imagesModel = new Admin_Model_DbTable_Gallery_ImageTable();
				$imageAddTable = new Admin_Model_DbTable_Gallery_ImageAddTable();
				$imgAdapter = new Admin_Model_imgAdapter();
				
				
				for ($a=0; $a <= $this->view->images['count']-1; $a++){
				
					//percorso file uplodato
					$file = '../application/meme-tmp/plupload/'.$this->view->images['img_file'.$a];
					//percorso destinazione file
					$newfile = 'meme-media/gallery/img/'.$this->view->images['img_file'.$a];

					//sposto il file dalla dir temp alla dir gallery
					if (copy($file, $newfile)) {
   						
   						//inserisco img nel database
						$idImg = $imagesModel->saveImg($this->view->images['img_file'.$a], $this->view->images['img_title'.$a], $this->view->images['img_link'.$a], $this->view->images['img_content'.$a]);
						
						
						//creare thumb --------- da finire
						
						$imgAdapter->galleryThumb($file, 'meme-media/gallery/thumb/'.$this->view->images['img_file'.$a], $width = 200, $height = 200);
						
						
						
						//cancello il file temporaneo
						// annullato perch lo sta cancellando $imgAdapter
						unlink($file);
						
						
						
						//determino gli album appartenenti a questa foto e li inserisco nel db
						foreach ($this->view->AlbumList as $album):

							if(isset($this->view->images['album'.$album['album_id'].'_id'.$a])==1)
							{
        						$imageAddTable->addImage($idImg, $album['album_id']);
							}        		

        				endforeach; 

												
					}


				}

				
				$this->_redirect('/admin/gallery/images');


			}
			
			
			

		}


	}




}