<!-- Theme/Desgin: Bootstrap / Code by Vy Nghia -->
<html>
<head>
	<meta charset="utf-8" />
	<title>Chia hình ảnh</title>
	<script src="https://www.google.com/recaptcha/api.js?hl=vi"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/home.css">
</head>
<body>
<div class="container" id="main">
	<div class="container"></div>
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Khởi tạo hình ảnh <small>(beta)</small></h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
					<?php
						// Get a key from https://www.google.com/recaptcha/admin/create
						$publickey = "public_recatcha_key";
						?>
						<form id="image" action="/rsc/crop_image.php" method="post">
							<div class="input-group">
								<label for="imageUpload">Tải ảnh lên</label>
								<input type="file" class="btn btn-default" name="imageUpload" id="imageUpload">
							</div>
							
							<div class="radio">
							  <label><input type="radio" name="type" value="2" checked>Chia 2</label>
							</div>
							<div class="radio">
							  <label><input type="radio" name="type" value="4">Chia 4</label>
							</div>
							
							<div class="g-recaptcha" data-sitekey="<?= $publickey ?>"></div>
							<div class="input-group" style="margin: 10px 0">
								<input type="submit" class="btn btn-default" id="GenerateCropImage" value="Khởi tạo">
							</div>
							
							<div><span id="error-message"><!-- ${error_message} --></span><span id="downloadImage" style="display: none;"><a id="downloadAllImage" href="#">Tải xuống tất cả hình ảnh</a></span></div>
							
							<input id="idCropped" type="hidden" value="#"/>
							<div id="croppedImage" style="display: none;  margin: 5px 0;">
								<img src="#" id="croppedImage1"></img>
								<img src="#" id="croppedImage2"></img>
								<div id="cropto4Image" style="display: none; margin: -15px 0;">
								<br />
								<img src="#" id="croppedImage3"></img>
								<img src="#" id="croppedImage4"></img>
								</div>
							</div>
						</form>
						<div class="fb-like" data-href="https://nghia.org/" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
					</div>
				</div>
				<div class="panel-footer">
				<i class="fa fa-code"></i> Code with <span class="fa fa-heart"></span> by <strong><a href="https://www.facebook.com/nghiadev?ref=product-on-nghia.org" target="_blank">Vy Nghia</a></strong><br/>
				Join <strong><a href="https://www.facebook.com/groups/photoshopplease/?ref=product-on-nghia.org">Photoshop for me</a></strong> to follow my products.
				</div>
			</div>
		</div>
	</div>
</div>
<script src="js/jquery-2.1.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
/* 
* v1 release: 6:00 29/11/2018
* lastest release 12:45 09/12/2018 (by @vnghia1308)
*/

// facebook sdk
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
  
$("#image").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: $("#image").attr("action"),
		type: "POST",
		data:  new FormData(this),
		dataType: "json",
		contentType: false,
		processData:false,
		beforeSend: function () {
			$("#error-message").hide()
			$("#GenerateCropImage").text("Processing...").prop("disabled", true)
			
			$("#idCropped").val(null)
			$("#downloadAllImage").attr("href", "#")
			
			$("#downloadImage").hide()
			$("#croppedImage").hide()
			$("#cropto4Image").hide()
		},
		success: function(r) {
			if(r.error)
			{
				$("#error-message").text(r.message).attr("style", "color: red").show()
			}
			else
			{
				if(r.count == 2){
					$("#croppedImage1").attr("src", "/public/api/v1/product/split-image/media/" + r.image + "-1." + r.ext)
					$("#croppedImage2").attr("src", "/public/api/v1/product/split-image/media/" + r.image + "-2." + r.ext)
					
					$("#croppedImage").show()
					
					$("#error-message").html(r.message).attr("style", "color: green").show()
				} else {
					if(r.count == 4){
						$("#croppedImage1").attr("src", "/public/api/v1/product/split-image/media/cropto4Image/" + r.image + "-1." + r.ext)
						$("#croppedImage2").attr("src", "/public/api/v1/product/split-image/media/cropto4Image/" + r.image + "-2." + r.ext)
						$("#croppedImage3").attr("src", "/public/api/v1/product/split-image/media/cropto4Image/" + r.image + "-3." + r.ext)
						$("#croppedImage4").attr("src", "/public/api/v1/product/split-image/media/cropto4Image/" + r.image + "-4." + r.ext)
						
						$("#croppedImage").show()
						$("#cropto4Image").show()
						
						$("#error-message").html(r.message).attr("style", "color: green").show()				
					}
				}
				
				$("#idCropped").val(r.image)
				$("#downloadAllImage").attr("href", "download.php?id=" + r.image + "&_c=" + r.count + "&_e=" + r.ext);$("#downloadImage").show()
			}
		},
		error: function(){
			$("#error-message").text("Server's crashed").attr("style", "color: green").show()
		},
		complete: function(){
			grecaptcha.reset();
			$("#GenerateCropImage").text("Generate").prop("disabled", false)
		}
   });
}));
</script>
</body>
</html>