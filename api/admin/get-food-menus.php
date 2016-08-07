<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	//$tblName = $_GET['tablename'];


	$where = isset($_GET['param']) ? "AND label LIKE '%".$_GET['param']."%'" : "";

 	$sql = "SELECT * FROM food_menus a WHERE a.`category_status` = 'A' $where";//food_menus
	$result = $conn->query($sql);	

	$itemList = array();

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_object()) {
			// $itemList[] = $row;
			$sqlCount = "SELECT COUNT(*) as ctr FROM `food_menu_items` fmi 
			WHERE fmi.`menu_id` = $row->menu_id";
			$result1 = $conn->query($sqlCount);
			$row1 = $result1->fetch_object();
			
			$item = new stdClass();
			$item->id = $row->menu_id;
			$item->label = $row->label;
			$item->ctr = $row1->ctr;
			$items[] = $item;
		}
	}
	
	$response = array(
		'status' => "ok",
		'items' => (array) $items,
		'sql' => $sql	
	);

	echo json_encode($response);
	$conn->close();
?>