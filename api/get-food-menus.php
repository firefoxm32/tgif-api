<?php 

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$where = isset($_GET['param']) ? "AND menu_name LIKE '%".$_GET['param']."%'" : "";

 	$sql = "SELECT * FROM food_menu fm WHERE fm.`menu_status` = 'A' $where";//food_menus
	$result = $conn->query($sql);	

	// $itemList = array();

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_object()) {
			// $itemList[] = $row;

			$sqlCtr = "SELECT COUNT(*) as ctr FROM `food_items` fi WHERE fi.`menu_id` = $row->menu_id";
			$result1 = $conn->query($sqlCtr);
			$row1 = $result1->fetch_object();

			$item = new stdClass();
			$item->id = $row->menu_id;
			$item->menu_name = $row->menu_name;
			$item->images = $row->images;
			$item->ctr = $row1->ctr;
			$itemList[] = $item;
		}
	}
	$conn->close();
	$response = array(
		'status' => "ok",
		'items' => (array)$itemList,
		'sql' => $sql	
	);
	echo json_encode($response);
	die;