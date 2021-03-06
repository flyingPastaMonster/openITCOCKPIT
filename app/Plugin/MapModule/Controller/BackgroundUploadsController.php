<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('UUID', 'Lib');

class BackgroundUploadsController extends MapModuleAppController {

	public $layout = 'Admin.default';
	//prevent asking for a view
	public $autoRender = false;
	//public $backgroundFolder = new Folder(APP .'Plugin'. DS .'MapModule'. DS .'Upload');

	public function upload(){
		if(!empty($_FILES)){
			debug($_FILES);
			$backgroundFolder = new Folder(APP .'Plugin'. DS .'MapModule'. DS .'webroot'. DS .'img'. DS .'backgrounds');

			//check if upload folder exist
			if(!is_dir($backgroundFolder->path)){
				mkdir($backgroundFolder->path);
			}

			//this name should be also stored in the Database 
			//-> displayed name when you choose the background
			// must be unique
			//$_FILES['file']['name'];

			$fileExtension = pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
			$filename = UUID::v4();
			$fullFilePath = $backgroundFolder->path.DS.$filename.'.'.$fileExtension;
			if(move_uploaded_file($_FILES['file']['tmp_name'], $fullFilePath)){
				echo 'successfull';
				$obj = [
					'fullPath'=>$fullFilePath,
					'uuidFilename'=>$filename,
					'fileExtension'=>$fileExtension,
					'folderInstance'=>$backgroundFolder
				];
				$this->createThumbnailsFromBackgrounds($obj);
				return true;
			}else{
				return false;
			}
			
		}else{
			echo 'there is no file to store';
		}
	}

	public function createThumbnailsFromBackgrounds($obj){
		$file = $obj['fullPath'];
		$folderInstance = $obj['folderInstance'];

		//check if thumb folder exist
		if(!is_dir($folderInstance->path.DS. 'thumb')){
			mkdir($folderInstance->path.DS. 'thumb');
		}

		$imgsize = getimagesize($file);
		$width = $imgsize[0];
		$height = $imgsize[1];
		$imgtype = $imgsize[2];
		$aspectRatio = $width/$height;

		$thumbnailWidth = 150;
		$thumbnailHeight = 150;


		switch($imgtype){
			// 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM
			case 1: 
				$srcImg = imagecreatefromgif($file);
				break;
			case 2:
				$srcImg = imagecreatefromjpeg($file);
				break;
			case 3:
				$srcImg = imagecreatefrompng($file);
				break;
			default:
				echo __('Filetype not supported!');
				break;
		}

		//calculate the new height or width and keep the aspect ration
		if($aspectRatio == 1){
			//source image X = Y 
			$newWidth = $thumbnailWidth;
			$newHeight = $thumbnailHeight;
		}elseif($aspectRatio > 1) {
			//source image X > Y
			$newWidth = $thumbnailWidth;
			$newHeight = ($thumbnailHeight / $aspectRatio);
		}else{
			//source image X < Y
			$newWidth = ($thumbnailWidth * $aspectRatio);
			$newHeight = $thumbnailHeight;
		}

		$destImg = imagecreatetruecolor($newWidth, $newHeight);
		$transparent = imagecolorallocatealpha( $destImg, 0, 0, 0, 127 );
		imagefill( $destImg, 0, 0, $transparent); 
		imageCopyResized($destImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		imagealphablending($destImg, false); 
		imagesavealpha($destImg,true); 

		switch($imgtype){
			// 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM
			case 1: 
				header('Content-Type: image/gif');
				imagegif($destImg, $folderInstance->path.DS. 'thumb'.DS.'thumb_'.$obj['uuidFilename'].'.'.$obj['fileExtension']);
				break;
			case 2:
				header('Content-Type: image/jpeg');
				imagejpeg($destImg, $folderInstance->path.DS. 'thumb'.DS.'thumb_'.$obj['uuidFilename'].'.'.$obj['fileExtension']);
				break;
			case 3:
				header('Content-Type: image/png');
				imagepng($destImg, $folderInstance->path.DS. 'thumb'.DS.'thumb_'.$obj['uuidFilename'].'.'.$obj['fileExtension']);
				break;
			default:
				echo __('Filetype not supported!');
				break;
		}
		imagedestroy($destImg);
	}

	public function delete(){

	}

}
