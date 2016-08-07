<?php

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$sql = "SELECT * FROM users";

	$result = $conn->query($sql);

	$userList = array();

	if ($result->num_rows > 0) {
		# code...
		while ($row = $result->fetch_object()) {
			# code...
			$userList[] = $row;
		}
	}

	$response = array(
		'status'  => 'Ok',
		'items'   => $userList
	);
	echo json_encode($response);
	$conn->close();