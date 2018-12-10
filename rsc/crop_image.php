<?php

error_reporting(0);

$privatekey = "recatcha_secret_key";

if ($_POST) {
    $response = $_POST["g-recaptcha-response"];
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array(
		'secret' => $privatekey,
		'response' => $_POST["g-recaptcha-response"]
	);
	$options = array(
		'http' => array (
			'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
						"User-Agent:MyAgent/1.0\r\n",
			'method' => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success=json_decode($verify);
	
	if ($captcha_success->success==false) {
		$a = array("error" => true, "message" => "Vui lòng xác nhận reCaptcha");
		echo json_encode($a);
	} else if ($captcha_success->success==true) {
		if (!empty($_FILES['imageUpload'])) {
			$ext = strtolower(pathinfo($_FILES['imageUpload']['name'], PATHINFO_EXTENSION));
			if ($ext === 'jpg' || $ext === 'png') {
				$name = md5(time() + rand(100, 10000));
				if($_POST["type"] == 2){
					$count = cropto2Image($_FILES['imageUpload']['tmp_name'], $name, $ext);
				} else {
					$count = cropto4Image($_FILES['imageUpload']['tmp_name'], $name, $ext);
				}				
				$a = array("error" => false, "message" => "Khởi tạo thành công, sử dụng <strong>Lưu ảnh thành...</strong> để tải xuống hoặc nhấn vào ", "image" => $name, "ext" => $ext, "count" => $count);
			} else { // nếu không phải file ảnh
				$a = array("error" => true, "message" => "Chỉ chấp nhận với hình ảnh jpg/png");
			}
		} else {
			$a = array("error" => true, "message" => "Invail method");
		}
				
		echo json_encode($a);
	}
}

function cropto2Image($imTmp, $name, $ext){
	if($ext === 'jpg'){
		$im = imagecreatefromjpeg($imTmp);
		$size = min(imagesx($im), imagesy($im));
		$im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size / 2, 'height' => $size]);
		if ($im2 !== FALSE) {
			imagejpeg($im2, 'media/' . $name . '-1.jpg');
			imagedestroy($im2);
		}
		imagedestroy($im);
		
		$im = imagecreatefromjpeg($imTmp);
		$size = min(imagesx($im), imagesy($im));
		$im2 = imagecrop($im, ['x' => $size / 2, 'y' => 0, 'width' => $size / 2, 'height' => $size]);
		if ($im2 !== FALSE) {
			imagejpeg($im2, 'media/' . $name . '-2.jpg');
			imagedestroy($im2);
		}
		imagedestroy($im);
	} else {
		$im = imagecreatefrompng($imTmp);
		$size = min(imagesx($im), imagesy($im));
		$im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size / 2, 'height' => $size]);
		if ($im2 !== FALSE) {
			imagepng($im2, 'media/' . $name . '-1.png');
			imagedestroy($im2);
		}
		imagedestroy($im);
		
		$im = imagecreatefrompng($imTmp);
		$size = min(imagesx($im), imagesy($im));
		$im2 = imagecrop($im, ['x' => $size / 2, 'y' => 0, 'width' => $size / 2, 'height' => $size]);
		if ($im2 !== FALSE) {
			imagepng($im2, 'media/' . $name . '-2.png');
			imagedestroy($im2);
		}
		imagedestroy($im);
	}
	
	return 2;
}

function cropto4Image($imTmp, $name, $ext){
	if($ext === 'jpg'){
		$im = imagecreatefromjpeg($imTmp);
		$size = min(imagesx($im), imagesy($im));
		$im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size / 2, 'height' => $size / 2]);
		if ($im2 !== FALSE) {
			imagejpeg($im2, 'media/cropto4Image/' . $name . '-1.jpg');
			imagedestroy($im2);
		}
		imagedestroy($im);
		
		$im = imagecreatefromjpeg($imTmp);
		$size = min(imagesx($im), imagesy($im));
		$im2 = imagecrop($im, ['x' => $size / 2, 'y' => 0, 'width' => $size / 2, 'height' => $size / 2]);
		if ($im2 !== FALSE) {
			imagejpeg($im2, 'media/cropto4Image/' . $name . '-2.jpg');
			imagedestroy($im2);
		}
		imagedestroy($im);
		
		$im = imagecreatefromjpeg($imTmp);
		$size = min(imagesx($im), imagesy($im));
		$im2 = imagecrop($im, ['x' => 0, 'y' => $size / 2, 'width' => $size / 2, 'height' => $size / 2]);
		if ($im2 !== FALSE) {
			imagejpeg($im2, 'media/cropto4Image/' . $name . '-3.jpg');
			imagedestroy($im2);
		}
		imagedestroy($im);
		
		$im = imagecreatefromjpeg($imTmp);
		$size = min(imagesx($im), imagesy($im));
		$im2 = imagecrop($im, ['x' => $size / 2, 'y' => $size / 2, 'width' => $size / 2, 'height' => $size / 2]);
		if ($im2 !== FALSE) {
			imagejpeg($im2, 'media/cropto4Image/' . $name . '-4.jpg');
			imagedestroy($im2);
		}
		imagedestroy($im);
	} else {
		$im = imagecreatefrompng($imTmp);
		$size = min(imagesx($im), imagesy($im));
		$im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size / 2, 'height' => $size / 2]);
		if ($im2 !== FALSE) {
			imagepng($im2, 'media/' . $name . '-1.png');
			imagedestroy($im2);
		}
		imagedestroy($im);
		
		$im = imagecreatefrompng($imTmp);
		$size = min(imagesx($im), imagesy($im));
		$im2 = imagecrop($im, ['x' => $size / 2, 'y' => 0, 'width' => $size / 2, 'height' => $size] / 2);
		if ($im2 !== FALSE) {
			imagepng($im2, 'media/' . $name . '-2.png');
			imagedestroy($im2);
		}
		imagedestroy($im);
		
		$im = imagecreatefrompng($imTmp);
		$size = min(imagesx($im), imagesy($im));
		$im2 = imagecrop($im, ['x' => 0, 'y' => $size / 2, 'width' => $size / 2, 'height' => $size] / 2);
		if ($im2 !== FALSE) {
			imagepng($im2, 'media/' . $name . '-3.png');
			imagedestroy($im2);
		}
		imagedestroy($im);
		
		$im = imagecreatefrompng($imTmp);
		$size = min(imagesx($im), imagesy($im));
		$im2 = imagecrop($im, ['x' => $size / 2, 'y' => $size / 2, 'width' => $size / 2, 'height' => $size] / 2);
		if ($im2 !== FALSE) {
			imagepng($im2, 'media/' . $name . '-4.png');
			imagedestroy($im2);
		}
		imagedestroy($im);
	}
	
	return 4;
}
