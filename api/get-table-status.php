<?php

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$transactionId = $_GET['transaction_id'];

	$sql = "SELECT * FROM table_management WHERE transaction_id = '$transactionId'";

	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		# code...
		$row = $result->fetch_object();
		$status = $row->status;
	}

	$response = array(
		'status' => "ok",
		'table_status'  => $status
	);
	echo json_encode($response);
	$conn->close();