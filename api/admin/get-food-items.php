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
		$sql = "SELECT fi.`item_id`, fi.`menu_id`, fm.`menu_name`, fi.`item_name`, 
			fi.`image`, fi.`description`, fi.`promo_price`, fi.`promo_status`
		FROM `food_items` fi LEFT JOIN `food_menus` fm 
		ON fi.`menu_id` = fm.`menu_id` WHERE fi.`status` = 'A'";
	} else {
		$sql = "SELECT fi.`item_id`, fi.`menu_id`, fm.`menu_name`, fi.`item_name`, 
			fi.`image`, fi.`description` 
		FROM `food_items` fi LEFT JOIN `food_menus` fm 
		ON fi.`menu_id` = fm.`menu_id` WHERE $field LIKE '%$val%' AND WHERE fi.`status` = 'A'";
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
	$conn->close();
	$response = array(
		'status' => "ok",
		'sql'    => $sql,
		'param'  => $param,
		'items'  => $itemList
	);
	echo json_encode($response);
	die;