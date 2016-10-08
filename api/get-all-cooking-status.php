<?php 

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$param = $_POST['param'];

	$sql = "SELECT * FROM `order_detail` od WHERE od.`transaction_id` = '$param' AND od.`status` = 'C'";

	$result = $conn->query($sql);
	$rows;
	if ($result->num_rows > 0) {
		$rows = $result->num_rows;
	}

	$conn->close();
	$response = array(
		'status' => "ok",
		'rows'   => $rows,
		'sql'    => $sql
	);
	echo json_encode($response);
	die;