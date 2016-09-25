<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$itemId = $_POST['item_id'];
	$promoPrice = $_POST['promo_price'];
	$promoStatus = $_POST['promo_status'];


	# code...
	$sql = "UPDATE food_items SET promo_price = $promoPrice, promo_status = '$promoStatus' WHERE item_id = $itemId";

	if (!$conn->query($sql)) {
		# code...
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving promo',
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
			'message' => 'Promo updated',
			'tableNumber' => $tableNumber,
			'sql'     => $sql
		)
	);
	die;




