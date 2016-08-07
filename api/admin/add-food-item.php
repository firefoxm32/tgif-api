<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$menuId = $_POST['category_id'];
	$menuName = $_POST['item_name'];
	$image = $_POST['image'];
	$description = $_POST['description'];

	$sql = "INSERT INTO `food_menu_items`(menu_id, menu_name, image, description)
		VALUES($menuId,'$menuName','$image','$description')";

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
			'message' => 'Save Successfull',
			'tableNumber' => $tableNumber,
			'sql'     => $sql
		)
	);

	$conn->close();
