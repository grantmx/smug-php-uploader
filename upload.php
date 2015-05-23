<?php 
/**
 * Copyright (c) 2008 Colin Seymour
 *
 * This file is part of phpSmug.
 *
 * phpSmug is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * phpSmug is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with phpSmug.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright (c) 2015 Marshall Grant
 * Same terms apply as above with this modification	
 */
 ?>
<!doctype html>
<html ng-app>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="refresh" content="30">
<title>Smug PHP Uploader</title>

<link rel="stylesheet" type="text/css" href="styles/normalize.css" />
<link rel="stylesheet" type="text/css" href="styles/framework-mx.css" />
<style>
[class*='span-']:last-of-type {
  padding-right: 20px;
  float: left;
}
</style>
</head>
<body>

	<div class="grid c">
		<div class="span-1-1">
			<h1 class="no-margin">Uploading to album ID: <?php echo $_GET["albumID"]; ?></h1>
			<h3>Uploading photos from: <?php echo $_GET["folderPath"] ?> in <span id="timeLeft">30</span> seconds.</h3>
		</div> 

		<div class="span-1-1">
			<h3>Currently Uploaded photos</h3>
<?php
/* Last updated with phpSmug 3.0
 *
 * This example file shows you how to get a list of albums from your own gallery, 
 * using your email address and password to authenticate and then display 
 * thumbnails of all the images in the first album found.
 *
 * You'll need to replace:
 * - <API KEY> with one provided by SmugMug: http://www.smugmug.com/hack/apikeys 
 * - <APP NAME/VER (URL)> with your application name, version and URL
 * - <EMAILADDRESS> with your email address
 * - <PASSWORD> with your SmugMug password
 *
 * The <APP NAME/VER (URL)> is NOT required, but it's encouraged as it will
 * allow SmugMug diagnose any issues users may have with your application if
 * they request help on the SmugMug forums.
 *
 * You can see this example in action at http://phpsmug.com/examples/
 */
require_once( "phpSmug.php" );
	
$folderPath = $_GET["folderPath"];
$albumID = $_GET['albumID'];

try {
	$f = new phpSmug( "APIKey=<API KEY>", "AppName=Photo Booth Upload" );
	
	// Login With EmailAddress and Password
	$f->login( "EmailAddress=<EMAILADDRESS>", "Password=<PASSWORD>" );	
	
	// Get list of  albums
	$albums = $f->albums_get();	


	// this uploads a given image.  Thinking we can put this in a loop of sorts or somehow only detect the recent images in a folder and only upload those every few seconds
	// if we upload the same file twice, it will create duplicates in the SmugMug album so we move them to the /_recyclebin
	foreach (new DirectoryIterator($folderPath) as $file) {
		if( $file->isFile() === TRUE && $file->getBasename() !== '.DS_Store'){
			$f->images_upload("AlbumID={$albumID}", "File=".$folderPath."/".$file->getFilename());
		}
	}

	// Display the thumbnails and link to the medium image for each image
	// Get list of images and other useful information
	$images = $f->images_get( "AlbumID={$albums['0']['id']}", "AlbumKey={$albums['0']['Key']}", "Heavy=1" );
	$images = ( $f->APIVer == "1.2.2" ) ? $images['Images'] : $images;

	foreach ( $images as $image ) {
		echo '<div class="span-1-10"><a href="'.$image['MediumURL'].'"><img src="'.$image['TinyURL'].'" title="'.$image['Caption'].'" alt="'.$image['id'].'" /></a></div>';
	}

	// this will move files out of the upload directory in to the recycle bin of that same directory
	foreach (new DirectoryIterator($_GET["folderPath"]) as $file) {
		if($file->isDot()) continue;
		rename($folderPath."/".$file->getFilename(), $folderPath."/"."_recycleBin/". $file->getFilename());
	}

	
}
catch ( Exception $e ) {
	echo "{$e->getMessage()} (Error Code: {$e->getCode()})";
}
?>
		</div>		
	</div>

<script>
!function (document){
	var timeSpan = document.getElementById("timeLeft"),
		interval,
		counter = 30;

	interval = setInterval(function(){
		counter --;

		if(counter < 0) {
			 clearInterval(interval);
		}else{
			timeSpan.innerHTML = counter.toString();
		}

	},1000);
}(document);
</script>
</body>
</html>
