<?php

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$sql = "SELECT * FROM membership m";

	$result = $conn->query($sql);

	$memberList = array();

	if ($result->num_rows > 0) {
		# code...
		while ($row = $result->fetch_object()) {
			# code...
			$memberList[] = $row;
		}
	}
	$conn->close();
	$response = array(
		'status'  => 'ok',
		'items'   => $memberList
	);
	echo json_encode($response);
	die;