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
<title>Smug PHP Uploader</title>

<link rel="stylesheet" type="text/css" href="styles/normalize.css" />
<link rel="stylesheet" type="text/css" href="styles/framework-mx.css" />
<style>
input{ width:50%; }
.album-scroll{
	width: 100%;
	height: 400px;
	overflow-y: scroll;
	overflow-x: hidden 
}
h1{margin-bottom: 20px}
[class*='span-']:last-of-type {
  padding-right: 20px;
  float: left;
}
</style>
</head>
<body>

	<div class="grid">
		<div class="span-1-1 c">
			<h1>Smug PHP Uploader</h1>
			<p>1.) Place these PHP files on your local web server connected to the web.<br>
				2.) create your directories you want to upload from in the same root directory (e.g. test_directory). No support for directory nesting.<br>
				3.) create a directory called, _recycleBin in your upload directory.  This bin will prevent duplicate uploads.
			</p>
			<br>
			<h3>Add an album id of the created smugMug gallery and the Folder path <Br> on this local server you want to auto-upload:</h3>
			<form class="form-left" action="upload.php" method="get">
				<p><input type="text" name="albumID" value="{{albumID}}" placeholder="Album ID (required)"></p>
				<p><input type="text" name="folderPath" value="" placeholder="Local folder path of your upload directory (Required)"></p>
				<p><button type="submit" class="btn btn-first green">Submit</button></p>
			</form>
		</div>

		<div class="span-1-1 no-pad c">
			<h3>Or select an existing SmugMug album below to get the Album ID:</h3>
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

try {
	$f = new phpSmug( "APIKey=<API KEY>", "AppName=Photo Booth Upload" );
	
	// Login With EmailAddress and Password
	$f->login( "EmailAddress=<EMAILADDRESS>", "Password=<PASSWORD>" );	
	
	// Get list of  albums
	$albums = $f->albums_get();

	// Get list of images and other useful information
	// $images = $f->images_get( "AlbumID={$albums['0']['id']}", "AlbumKey={$albums['0']['Key']}", "Heavy=1" );
	// $images = ( $f->APIVer == "1.2.2" ) ? $images['Images'] : $images;
	
	// prints the most recent album id used for uploading
	echo '<ul class="unstyled inline grid album-scroll top-line grey-line">';
	foreach($albums as $key=>$value) {
		echo '<li class="span-1-6 c"><label for="'.$albums[$key]['id'].'"> <input ng-model="albumID" type="radio" name="albums" id="'.$albums[$key]['id'].'" ng-value="'.$albums[$key]['id'].'"> <br> <strong>'.$albums[$key]['Title'].'</strong> <br> '.$albums[$key]['id'].' </label></li>';
	}
	echo '</ul>';

	// need to find a way to list out all the existing albums and their ids so we can choose an id.

}
catch ( Exception $e ) {
	echo "{$e->getMessage()} (Error Code: {$e->getCode()})";
}
?>

		</div><!-- end container -->
	</div><!-- end grid -->

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>

</body>
</html>
