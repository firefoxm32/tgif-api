<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$label = $_POST['label'];
	$id = $_POST['id'];
	$action = $_POST['action'];

	switch ($action) {
		case 'add':
			$sql = "INSERT INTO food_menus(label)VALUES('$label')";
			$message = "Save Successfull";
			break;
		case 'edit':
			$sql = "UPDATE food_menus SET label = '$label' WHERE menu_id = $id";
			$message = "Update Successfull";
			break;
		default:
			$sql = "UPDATE food_menus SET category_status = 'I' WHERE menu_id = $id";
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