<?php

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$transactionId = $_POST['transaction_id'];
	$tableNumber = $_POST['table_number'];
	$cashAmount = $_POST['cash_amount'];

	$sql = "INSERT INTO cash_header(transaction_id, table_number, 
			cash_amount)VALUES('$transactionId', $tableNumber, $cashAmount)";

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving cash_header',
				'error'   => mysqli_error($conn),
				'sql'     => $sql
			)
		);
		$conn->close();
		die;
	}

	$sql = "UPDATE table_management SET status = 'C' WHERE transaction_id = '$transactionId'";

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in updating table_management',
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
			'message' => 'Save Successfull',
			'sql'     => $sql
		)
	);
	die;