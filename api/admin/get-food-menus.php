<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	//$tblName = $_GET['tablename'];


	$where = isset($_GET['param']) ? "AND menu_name LIKE '%".$_GET['param']."%'" : "";

 	$sql = "SELECT * FROM food_menu a WHERE a.`menu_status` = 'A' $where";//food_menus
	$result = $conn->query($sql);	

	$itemList = array();

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_object()) {
			// $itemList[] = $row;
			$sqlCount = "SELECT COUNT(*) as ctr FROM `food_items` fi 
			WHERE fi.`menu_id` = $row->menu_id";
			$result1 = $conn->query($sqlCount);
			$row1 = $result1->fetch_object();
			
			$item = new stdClass();
			$item->id = $row->menu_id;
			$item->menu_name = $row->menu_name;
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