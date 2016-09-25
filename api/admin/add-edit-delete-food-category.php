<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$menuName = $_POST['menu_name'];
	$id = $_POST['id'];
	$action = $_POST['action'];

	switch ($action) {
		case 'add':
			$sql = "INSERT INTO food_menu(menu_name)VALUES('$menuName')";
			$message = "Save Successfull";
			break;
		case 'edit':
			$sql = "UPDATE food_menu SET menu_name = '$menuName' WHERE menu_id = $id";
			$message = "Update Successfull";
			break;
		default:
			$sql = "UPDATE food_menu SET menu_status = 'I' WHERE menu_id = $id";
			$message = "Delete Successfull";
			break;
	}

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving food category',
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
			'message' => $message,
			'tableNumber' => $tableNumber,
			'sql'     => $sql
		)
	);
 	die;