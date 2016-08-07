<?php 

require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	//https://www.youtube.com/watch?v=6ZsCYxahJW0

	$param = $_GET['params'];

	$sql = "SELECT * FROM food_menu_items WHERE menu_id = $param AND status = 'A'";

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
	echo json_encode($response);
	$conn->close();
 ?>