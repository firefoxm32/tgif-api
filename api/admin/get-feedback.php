<?php

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$sql = "SELECT * FROM feedback";

	$result = $conn->query($sql);

	$feedbackList = array();

	if ($result->num_rows > 0) {
		# code...
		while ($row = $result->fetch_object()) {
			# code...
			$feedbackList[] = $row;
		}
	}

	$response = array(
		'status'  => 'Ok',
		'items'   => $feedbackList
	);
	echo json_encode($response);
	$conn->close();