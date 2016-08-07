<?php 
	
	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$userName = $_POST['username'];

	$sql = "UPDATE users SET user_status = 'I' WHERE username = '$userName'";

	$conn->query($sql);

	$response = array(
		'status' => "ok"
	);
	$conn->close();
	echo json_encode($response);
	die;