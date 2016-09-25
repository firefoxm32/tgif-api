<?php 

	require_once('config/DBConnection.php');

	$db = new DBConnection();
	$conn = $db->connect();

	$transactionId = $_POST['transaction_id'];
	$customerName = $_POST['customer_name'];
	$message = $_POST['message'];

	$sql = "INSERT INTO feedback(transaction_id,customer_name, message)VALUES(
		'$transactionId', '$customerName', '$message')";


	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving feedback',
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
			'message' => 'Feedback sent',
			'sql'     => $sql
		)
	);
	die;