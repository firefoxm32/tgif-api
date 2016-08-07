<?php 
	
	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();
	$valid = false;
	$message = false;
	$status = 'error';
	$userName = $_POST['username'];
	$pass = $_POST['password'];

	$sql = "SELECT u.`user_status`, u.`table_number` FROM users u WHERE u.`username` = '$userName' 
		AND u.`password` = '$pass' AND u.`user_type`='M'";
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

	if ($result->num_rows > 0) {
		$row = $result->fetch_object();
		if (strtoupper($row->user_status) == 'I') {
			$valid = true;
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
		'message'=> $message
	);

	$conn->close();
	echo json_encode($response);
	die;
	