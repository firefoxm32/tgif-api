<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$id = $_POST['id'];

	$sql = "UPDATE order_detail SET status = 'S' WHERE id = $id";

	if (!$conn->query($sql)) {
		# code...
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in updating order -> order_detail',
				'id' 	  => $id,
				'sql'     => $sql
			)
		);
		$conn->close();
		die;
	}

	echo json_encode(
		array(
			'status'  => 'ok',
			'message' => 'Save Successfull'
		)
	);

	$conn->close();
	die;
 ?>