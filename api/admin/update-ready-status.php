<?php 
	
	require_once("../config/DBConnection.php");
	$db = new DBConnection();
	$conn = $db->connect();

	$id = $_POST['id'];
	$status = $_POST['ready_status'];

	$sql = "UPDATE `order_detail` SET ready_status = '$status' WHERE id = $id";

	if (!$conn->query($sql)) {
		# code...
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in updating order',
				'error'   => mysqli_error($conn),
				'sql'     => $sql
			)
		);
		$conn->close();
		die;
	}
	echo json_encode(
		array(
			'status' => 'ok',
			'message' => 'Edit Successfull',
			'sql'     => $sql
		)
	);
	$conn->close();
	die;