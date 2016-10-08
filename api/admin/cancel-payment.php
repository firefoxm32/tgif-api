<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$param = $_POST['transaction_id'];


	$sql = "UPDATE table_management SET status = 'O' WHERE transaction_id = '$param'";

	if (!$conn->query($sql)) {
		# code...
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in cancelling payment',
				'error'   => mysqli_error($conn),
				'sql'     => $sql
			)
		);
		$conn->close();
		die;
	}

	$sql = "DELETE FROM `cash_header` WHERE transaction_id = '$param'";

	if (!$conn->query($sql)) {
		# code...
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in deleting cash header',
				'error'   => mysqli_error($conn),
				'sql'     => $sql
			)
		);
		$conn->close();
		die;
	}

	$sql = "DELETE FROM `cash_details` WHERE transaction_id = '$param'";

	if (!$conn->query($sql)) {
		# code...
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in deleting cash header',
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
			'message' => 'Payment cancelled',
			'sql'     => $sql
		)
	);
	die;