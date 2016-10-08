<?php 
	
	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$userName = $_POST['username'];
	$tableNumber = (isset($_POST['table_number'])) ? $_POST['table_number'] : false;

	$sql = "UPDATE users SET user_status = 'I' WHERE username = '$userName'";

	$conn->query($sql);
	if ($tableNumber) {
		$sql = "UPDATE table_management SET status = 'W', transaction_id='Null' WHERE table_number=$tableNumber";

		$conn->query($sql);
	}


	$response = array(
		'status' => "ok"
	);
	$conn->close();
	echo json_encode($response);
	die;