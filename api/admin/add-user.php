<?php 
	
	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$userName = $_POST['username'];
	$pass = $_POST['password'];
	$tableNumber = $_POST['table_number'];
	$userType = $_POST['user_type'];
	$action = $_POST['action'];

	if ($action == "add") {
		# code...
		$sql = "INSERT INTO users(username, password, table_number, user_type)
		VALUES('$userName','$pass',$tableNumber,'$userType')";
	} else {
		$sql = "UPDATE users SET password = '$pass', 
		table_number = $tableNumber, 
		user_type = '$userType' WHERE username='$userName'";
	}

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

	if ($action == "add") {
		# code...
		$sql = "INSERT INTO `table_management`(table_number)VALUES($tableNumber)";
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
	}

	echo json_encode(
		array(
			'status' => 'Ok',
			'message' => 'Save Successfull',
			'sql'     => $sql
		)
	);
$conn->close();