<?php	
$target_dir = "../images/";
$target_file = $target_dir . basename($_FILES["image"]["name"]);
if ( move_uploaded_file ( $_FILES["image"]["tmp_name"] , 
	     $target_file ) )
	   echo "Download completed";
	else
	   echo "Error";
