<?php 

	require_once("config/DBConnection.php");
	$db = new DBConnection();
	$conn = $db->connect();

	$id = $_POST['id'];
	$qty = $_POST['qty'];

	$sql = "UPDATE `temporary_order_detail` SET qty = $qty WHERE id = $id";

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
			'status' => 'Ok',
			'message' => 'Edit Successfull',
			'sql'     => $sql
		)
	);
	$conn->close();
 ?>