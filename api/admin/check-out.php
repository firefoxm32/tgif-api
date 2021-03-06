<?php 
	
	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$transactionId = $_POST['transaction_id'];
	$cashierId = $_POST['cashier_id'];
	$tableNumber = $_POST['table_number'];
	$senior = $_POST['senior_citizen'];
	$cashAmount = $_POST['cash_amount'];
	$orNumber = $_POST['or_number'];
	$ccName = $_POST['credit_card_name'];
	$ccNumber = $_POST['credit_card_number'];

	$sql = "UPDATE cash_header SET cashier_id = '$cashierId', 
		senior_citizen_discount = $senior, cash_amount = $cashAmount
		or_number = '$orNumber', credit_card_number = '$ccNumber',
		credit_card_name = '$ccName'
		WHERE transaction_id = '$transactionId'";

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