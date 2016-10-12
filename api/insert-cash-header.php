<?php

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$transactionId = $_POST['transaction_id'];
	$totalPrice = $_POST['total_price'];
	$memberId = $_POST['member_id'];
	$cardName = $_POST['credit_card_name'];
	$cardNumber = $_POST['credit_card_number'];
	
	$sql = "SELECT * FROM membership m WHERE m.`member_id` = '$memberId' AND m.`membership_status` = 'A'";

	if ($memberId != "") {
		$result = $conn->query($sql);
		if ($result->num_rows == 0) {
			# code...
			$conn->close();
			$response = array(
				'status'  => 'error',
				'message'   => 'Member ID doesnt exist'
			);
			echo json_encode($response);
			die;
		}		
	}


	$sql = "INSERT INTO cash_header(transaction_date,transaction_id, member_id, 
			credit_card_number, credit_card_name)VALUES(
			NOW(),'$transactionId', '$memberId',
			'$cardNumber', '$cardName')";

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving cash_header',
				'error'   => mysqli_error($sql),
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
			'message' => 'Payments sent to the cashier',
			'sql'     => $sql
		)
	);
	die;