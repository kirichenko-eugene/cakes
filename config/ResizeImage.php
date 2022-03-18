<?php 

class ResizeImage
{
	private $maxWidth = 2500;
	private $maxHeight = 2500;
	private $maxFileSize = 10485760;
	private $file;

	public function __construct()
	{
		$this->session = new SessionShell;
	}

	public function getImageName()
	{
		if(isset($this->file)) {
			$imageName = basename($this->file['name']);
		}
		return $imageName;
	}

	public function setFile($file)
	{
		$this->file = $file;
	}

	public function saveImage($file, $newWidth, $newHeight, $path)
	{
		if ($this->checkImageSize()) {
			$image = '';
			$quality = 100;

			switch ($this->getImageMime()) {
				case 'image/jpeg':
				$image = imagecreatefromjpeg($this->imageFileName());
				break;
				case 'image/png':
				$image = imagecreatefrompng($this->imageFileName());
				break;
				case 'image/gif':
				$image = imagecreatefromgif($this->imageFileName());
				break;
			}

			if ($newWidth == 0 && $newHeight == 0) {
				$newWidth = 100;
				$newHeight = 100;
			}

			$newWidth = min ($newWidth, $this->maxWidth);
			$newHeight = min ($newHeight, $this->maxHeight);

        	//get original image h/w
			$width = imagesx ($image);
			$height = imagesy ($image);

	        //$align = 'b';
			$zoomCrop = 1;
			$origin_x = 0;
			$origin_y = 0;

	        // generate new w/h if not provided
			if ($newWidth && !$newHeight) {
				$newHeight = floor ($height * ($newWidth / $width));
			} else if ($newHeight && !$newWidth) {
				$newWidth = floor ($width * ($newHeight / $height));
			}

			if ($zoomCrop == 3) {

				$finalHeight = $height * ($newWidth / $width);

				if ($finalHeight > $newHeight) {
					$newWidth = $width * ($newHeight / $height);
				} else {
					$newHeight = $finalHeight;
				}
			}

	        // create a new true color image
			$canvas = imagecreatetruecolor ($newWidth, $newHeight);
			imagealphablending ($canvas, false);


			// if (strlen ($canvasColor) < 6) {
			// 	$canvasColor = 'ffffff';       
			// }

			// $canvasColor_R = hexdec (substr ($canvasColor, 0, 2));
			// $canvasColor_G = hexdec (substr ($canvasColor, 2, 2));
			// $canvasColor_B = hexdec (substr ($canvasColor, 2, 2));

	        // Create a new transparent color for image
			// $color = imagecolorallocatealpha ($canvas, $canvasColor_R, $canvasColor_G, $canvasColor_B, 127);

	        // Completely fill the background of the new image with allocated color.
			// imagefill ($canvas, 0, 0, $color);

			if ($zoomCrop == 2) {
				$finalHeight = $height * ($newWidth / $width);

				if ($finalHeight > $newHeight) {
					$origin_x = $newWidth / 2;
					$newWidth = $width * ($newHeight / $height);
					$origin_x = round ($origin_x - ($newWidth / 2));
				} else {
					$origin_y = $newHeight / 2;
					$newHeight = $finalHeight;
					$origin_y = round ($origin_y - ($newHeight / 2));
				}
			}

	        // Restore transparency blending
			imagesavealpha ($canvas, true);

			if ($zoomCrop > 0) {

				$src_x = $src_y = 0;
				$src_w = $width;
				$src_h = $height;

				$cmp_x = $width / $newWidth;
				$cmp_y = $height / $newHeight;

            // calculate x or y coordinate and width or height of source
				if ($cmp_x > $cmp_y) {
					$src_w = round ($width / $cmp_x * $cmp_y);
					$src_x = round (($width - ($width / $cmp_x * $cmp_y)) / 2);
				} else if ($cmp_y > $cmp_x) {
					$src_h = round ($height / $cmp_y * $cmp_x);
					$src_y = round (($height - ($height / $cmp_y * $cmp_x)) / 2);
				}

            // positional cropping!
				// if ($align) {
				// 	if (strpos ($align, 't') !== false) {
				// 		$src_y = 0;
				// 	}
				// 	if (strpos ($align, 'b') !== false) {
				// 		$src_y = $height - $src_h;
				// 	}
				// 	if (strpos ($align, 'l') !== false) {
				// 		$src_x = 0;
				// 	}
				// 	if (strpos ($align, 'r') !== false) {
				// 		$src_x = $width - $src_w;
				// 	}
				// }

            // positional cropping!
				imagecopyresampled ($canvas, $image, $origin_x, $origin_y, $src_x, $src_y, $newWidth, $newHeight, $src_w, $src_h);

			} else {       
				imagecopyresampled ($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
			}

			if (preg_match('/^image\/(?:jpg|jpeg)$/i', $this->getImageMime())){                       
				return imagejpeg($canvas, $path.$this->getImageName(), $quality);  

			} else if (preg_match('/^image\/png$/i', $this->getImageMime())){                         
				return imagepng($canvas, $path.$this->getImageName(), floor($quality * 0.09)); 

			} else if (preg_match('/^image\/gif$/i', $this->getImageMime())){               
				return imagegif($canvas, $path.$this->getImageName()); 
			}	
		} else {
			return false;
		}
	}

	public function simpleSaveImage($path)
	{		
		return copy($this->imageFileName(), $path . $this->getImageName());
	}

	public function getImageMime()
	{
		return $this->getImageInfo()['mime'];
	}

	public function getSvgType($file)
	{
		return $file['type'];
	}

	public function getSupportTypes()
	{
		return array('image/gif', 'image/png', 'image/jpeg', 'image/svg+xml');
	}

	public function getFullFileName()
	{
		$fullName = explode( '.', $this->getImageName());
		return $fullName[0];
	}

	public function imageRegExp($name)
	{
		if(preg_match("/^[a-zA-Z0-9_.-]{3,30}$/", $name)) {
			return true;
		} else {
			return false;
		}
	}

	private function getImageInfo()
	{
		return getimagesize($this->imageFileName());
	}

	private function imageFileName()
	{
		return $this->file['tmp_name'];
	}

	private function checkImageSize()
	{
		$imageSize = $this->file['size'];
		if ($imageSize < $this->maxFileSize) {
			return true;
		} else {
			return false;
		}
	}
}