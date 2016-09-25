<?php

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$transactionId = $_POST['transaction_id'];
	$cashAmount = $_POST['cash_amount'];
	$totalPrice = $_POST['total_price'];

	$sql = "INSERT INTO cash_header(transaction_date,transaction_id, 
			cash_amount)VALUES(NOW(),'$transactionId', $cashAmount)";

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

	$sql = "INSERT INTO cash_details(credit,transaction_id)VALUES($totalPrice, '$transactionId')";

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving cash_details',
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