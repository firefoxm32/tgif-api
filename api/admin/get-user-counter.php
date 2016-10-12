<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$sql = "SELECT * FROM users WHERE table_number > 0";

	$result = $conn->query($sql);

	$response = array(
		'status'  => 'Ok',
		'size'   => $result->num_rows
	);
	echo json_encode($response);
	$conn->close();