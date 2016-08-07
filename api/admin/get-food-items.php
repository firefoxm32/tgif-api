<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	//https://www.youtube.com/watch?v=6ZsCYxahJW0
	$field = $_GET['field'];
	$val = $_GET['value'];

	/*$where = isset($_GET['field']) ? "WHERE 
		".$_GET['field']." LIKE '%$val%'" : "";*/
	if ($field == "") {
		# code...
		$sql = "SELECT fmi.`item_id`, fmi.`menu_id`, fm.`label`, fmi.`menu_name`, 
			fmi.`image`, fmi.`description` 
		FROM `food_menu_items` fmi LEFT JOIN `food_menus` fm 
		ON fmi.`menu_id` = fm.`menu_id`";
	} else {
		$sql = "SELECT fmi.`item_id`, fmi.`menu_id`, fm.`label`, fmi.`menu_name`, 
			fmi.`image`, fmi.`description` 
		FROM `food_menu_items` fmi LEFT JOIN `food_menus` fm 
		ON fmi.`menu_id` = fm.`menu_id` WHERE $field LIKE '%$val%'";
	}

	
	$result = $conn->query($sql);
	$itemList = array();

	if ($result->num_rows > 0) {
		# code...
		while ($rows = $result->fetch_object()) {
			# code...
			$itemList[] = $rows;
		}
	}
	$response = array(
		'status' => "Ok",
		'sql'    => $sql,
		'param'  => $param,
		'items'  => $itemList
	);
	echo json_encode($response);
	$conn->close();