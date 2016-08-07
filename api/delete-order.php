<?php 

	require_once("config/DBConnection.php");
	$db = new DBConnection();
	$conn = $db->connect();

	$id = $_POST['id'];

	$sql = "DELETE FROM `temporary_order_detail` WHERE id = $id";

	if (!$conn->query($sql)) {
		# code...
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in deleting order',
				'error'   => mysqli_error($conn),
				'sql'     => $sql,
				'param'   => $id
			)
		);
		$conn->close();
		die;
	}
	echo json_encode(
		array(
			'status' => 'Ok',
			'message' => 'Delete Successfull',
			'sql'     => $sql,
			'param'   => $id
		)
	);
	$conn->close();
 ?>