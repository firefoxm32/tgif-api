<?php 
	
	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();
	$valid = false;
	$message = false;
	$status = 'error';
	$userName = $_POST['username'];
	$pass = $_POST['password'];
	$withMobileUser = (isset($_POST['user_type'])) ? "" : "AND u.`user_type`= 'M'";


	$sql = "SELECT u.`user_status`, u.`table_number`, u.`user_type` FROM users u WHERE u.`username` = '$userName' 
		AND u.`password` = '$pass'  $withMobileUser";
	$result = $conn->query($sql);
	
	if(!$result) {
		$message = mysqli_error($conn);
		$response = array(
			'status' => $status,
			'message'=> $message
		);
		$conn->close();
		echo json_encode($response);
		die;
	}
	$tableNumber = 0;
	$userType = "";
	if ($result->num_rows > 0) {
		$row = $result->fetch_object();
		if (strtoupper($row->user_status) == 'I') {
			$valid = true;
			$tableNumber = $row->table_number;
			$userType = $row->user_type;
			$message = 'Login successfull';
			$status = 'ok';
		} else {
			$message = 'User already login';
		}
	} else {
		$message = 'Invalid username and password';
	}

	if($valid) {
		$sql = "UPDATE users SET user_status = 'A' WHERE username = '$userName'";
		$result = $conn->query($sql);
		
		if(!$result) {
			$message = mysqli_error($conn);
			$response = array(
				'status' => $status,
				'message'=> $message
			);
			$conn->close();
			echo json_encode($response);
			die;
		}
	}

	$response = array(
		'status' => $status,
		'message'=> $message,
		'table_number' => $tableNumber,
		'user_type' => $userType,
	);

	$conn->close();
	echo json_encode($response);
	die;
	