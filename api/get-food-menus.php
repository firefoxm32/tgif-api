<?php 

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$where = isset($_GET['param']) ? "AND label LIKE '%".$_GET['param']."%'" : "";

 	$sql = "SELECT * FROM food_menus a WHERE a.`category_status` = 'A' $where";//food_menus
	$result = $conn->query($sql);	

	// $itemList = array();

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_object()) {
			// $itemList[] = $row;

			$sqlCtr = "SELECT COUNT(*) as ctr FROM `food_menu_items` fmi WHERE fmi.`menu_id` = $row->menu_id";
			$result1 = $conn->query($sqlCtr);
			$row1 = $result1->fetch_object();

			$item = new stdClass();
			$item->id = $row->menu_id;
			$item->label = $row->label;
			$item->images = $row->images;
			$item->ctr = $row1->ctr;
			$itemList[] = $item;
		}
	}
	$response = array(
		'status' => "ok",
		'items' => (array)$itemList,
		'sql' => $sql	
	);

	echo json_encode($response);
	$conn->close();
?>