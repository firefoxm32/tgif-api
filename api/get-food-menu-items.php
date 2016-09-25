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
			$itemList[] = $rows;
		}
	}
	$response = array(
		'status' => "Ok",
		'param'  => $param,
		'items'  => $itemList
	);
	$conn->close();
	echo json_encode($response);