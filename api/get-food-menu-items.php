<?php 

require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	//https://www.youtube.com/watch?v=6ZsCYxahJW0

	$param = $_GET['params'];

	$sql = "SELECT * FROM food_items WHERE menu_id = $param AND status = 'A' AND promo_status='I'";

	$result = $conn->query($sql);

	$itemList = array();

	if ($result->num_rows > 0) {
		# code...
		while ($rows = $result->fetch_object()) {
			# code...
			$item = new stdClass();
			$item->item_id = $rows->item_id;
			$item->item_name = $rows->item_name;
			$item->image = $rows->image;
			$item->description = $rows->description;
			$item->status = $rows->status;
			$item->food_item_code = $rows->food_item_code;
			$item->promo_status = $rows->promo_status;
			$item->menu_id = $rows->menu_id;
			$item->rating = ($rows->rating / $result->num_rows);
			$itemList[] = $item;
		}
	}
	$response = array(
		'status' => "Ok",
		'param'  => $param,
		'items'  => $itemList
	);
	$conn->close();
	echo json_encode($response);