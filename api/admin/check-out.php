<?php 
	
	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$transactionId = $_POST['transaction_id'];
	$cashierId = $_POST['cashier_id'];
	$tableNumber = $_POST['table_number'];
	$senior = $_POST['senior_citizen'];
	$cashAmount = $_POST['cash_amount'];

	$sql = "UPDATE cash_header SET cashier_id = '$cashierId', senior_citizen_discount = $senior WHERE transaction_id = '$transactionId'";

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
				'message' => 'Error in updating user',
				'error'   => mysqli_error($conn),
				'sql'     => $sql
			)
		);
		die;
	}

	$sql = "INSERT INTO cash_details(debit, transaction_id)VALUES($cashAmount, '$transactionId')";

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving cashier_details',
				'error'   => mysqli_error($conn),
				'sql'     => $sql
			)
		);
		die;
	}

	echo json_encode(
		array(
			'status' => 'ok',
			'message' => 'Processing payments complete',
			'sql'     => $sql
		)
	);
$conn->close();