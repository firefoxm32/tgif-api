<?php 
	
	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$transactionId = $_POST['transaction_id'];
	$cashierId = $_POST['cashier_id'];
	$tableNumber = $_POST['table_number'];
	
	$sql = "INSERT INTO cash_header(transaction_id, table_number, transaction_date, cashier_id)
			VALUES('$transactionId', $tableNumber, NOW(), $cashierId)";

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving user',
				'error'   => mysqli_error($conn),
				'sql'     => $sql
			)
		);
		die;
	}

	$sql = "UPDATE table_management SET status = 'W' WHERE table_number = $tableNumber";

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving user',
				'error'   => mysqli_error($conn),
				'sql'     => $sql
			)
		);
		die;
	}

	echo json_encode(
		array(
			'status' => 'Ok',
			'message' => 'Save Successfull',
			'sql'     => $sql
		)
	);
$conn->close();