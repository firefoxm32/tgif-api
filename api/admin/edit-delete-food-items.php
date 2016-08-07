<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$menuId = $_POST['category_id'];
	$menuName = $_POST['item_name'];
	$image = $_POST['image'];
	$description = $_POST['description'];
	$itemId = $_POST['item_id'];
	$action = $_POST['action'];


	if ($action == 'edit') {
		# code...
		$sql = "UPDATE `food_menu_items` 
		SET menu_id = $menuId, menu_name = '$menuName', 
			image = '$image', description = '$description' 
		WHERE item_id = $itemId";
		$message = "Edit Successfull";
	} else {
		$sql = "UPDATE `food_menu_items` SET status = 'I' WHERE item_id = 10";
		$message = "Delete Successfull";
	}

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving food item',
				'error'   => mysqli_error($conn),
				'sql'     => $sql
			)
		);
		die;
	}

	echo json_encode(
		array(
			'status' => 'Ok',
			'message' => $message,
			'tableNumber' => $tableNumber,
			'sql'     => $sql
		)
	);
	$conn->close();
 ?>