<?php

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	// $status = $_GET['status'];

	$sql = "SELECT * FROM table_management";

	$result = $conn->query($sql);

	$items = array();

	if ($result->num_rows > 0) {
		# code...
		while ($row = $result->fetch_object()) {
			# code...
			$items[] = $row;
		}
	}

	$response = array(
		'status' => "ok",
		'items'  => $items
	);
	echo json_encode($response);
	$conn->close();