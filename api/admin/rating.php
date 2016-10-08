<?php

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$itemId = $_POST['item_id'];
	$rating = $_POST['rating'];

	$sql = "UPDATE food_items SET rating = (rating + $rating) WHERE item_id = $itemId";

	if (!$conn->query($sql)) {
		# code...
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in updating rating',
				'error'   => mysqli_error($conn),
				'sql'     => $sql
			)
		);
		$conn->close();
		die;
	}
	$conn->close();
	echo json_encode(
		array(
			'status' => 'ok',
			'message' => 'Rating updated',
			'sql'     => $sql
		)
	);
	die;