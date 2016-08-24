<?php 

	// require_once("../config/DBConnection.php");

	// $str = "1,2,3,4,5,6,7,8,9";
	
	$str = $_GET['str'];

	// $str1 = array();

	$str1 = explode(",", $str);

	for ($i=0; $i < 2; $i++) { 
		# code...
		if(sizeof($str1) > $i) {
			echo "ssss";
		} else {
			echo "aaaaa";
		}
	}

	// echo sizeof()eof($str1);
	// if ($str != "") {
		# code...
	// echo sizeof($str1);
		// echo 'if(sizeof($str1) > 0)';
		// for($i = 0; $i < sizeof($str1);$i++) {
		// 	// echo $i;
		// 	if(sizeof($str1) > $i) {
		// 		echo "$str1[$i]".'<br />';
		// 		echo "ssss";		// echo $str1[$i];
		// 	}
		// }

	// }	
