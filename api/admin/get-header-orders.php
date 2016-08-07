<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$sql = "SELECT * FROM order_header";

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
		'items'  => $itemList
	);
	echo json_encode($response);
	$conn->close();
 ?>