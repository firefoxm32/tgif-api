<?php 
	
	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$tableNumber = $_POST['table_number'];
	$transactionId = $_POST['transaction_id'];
	$status = $_POST['status'];

	$sql = "UPDATE table_management SET status = '$status', 
		transaction_id = '$transactionId' WHERE table_number = $tableNumber";

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in updating table_management',
				'error'   => mysqli_error($conn),
				'sql'     => $sql
			)
		);
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
