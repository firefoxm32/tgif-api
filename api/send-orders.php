<?php 

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$tableNumber = $_POST['table_number'];
	$transactionId = $_POST['transaction_id'];
	// $transactionId = 'a56fab2a-6280-4a7b-ae53-ec0a795735e6';

	// $sql = "SELECT * FROM `order_detail` od
	// 		WHERE od.`transaction_id` = $transactionId AND od.`item_id` = $itemId
	// 		AND od.`serving_id` = $servingId 
	// 		AND od.`sauces` = '$serializedSauces'
	// 		AND od.`side_dish_id` = $sideDishId";

	// $result = $conn->query($sql);
	// if ($result->num_rows > 0) {
	// 	# code...
	// 	//Update record order if existing
	// 	// $sql = "UPDATE `temporary_order_detail`
	// 	// 		SET qty = qty + $qty
	// 	// 		WHERE table_number = $tableNumber AND item_id = $itemId
	// 	// 		AND serving_id = $servingId AND sauces = '$serializedSauces'
	// 	// 		AND side_dish_id = $sideDishId";
	// 	$sql = "UPDATE `temporary_order_detail`
	// 			SET qty = qty + $qty
	// 			WHERE transaction_id='$transactionId'";

	// 	if (!$conn->query($sql)) {
	// 		echo json_encode(
	// 			array(
	// 				'status'  => 'error',
	// 				'message' => 'Error in saving order toh',
	// 				'error'   => mysqli_error($conn),
	// 				'tableNumber' => $tableNumber,
	// 				'sql'     => $sql
	// 			)
	// 		);
	// 		die;
	// 	}
	// 	echo json_encode(
	// 		array(
	// 			'status' => 'Ok',
	// 			'message' => 'Save Successfull',
	// 			'tableNumber' => $tableNumber,
	// 			'sql'     => $sql
	// 		)
	// 	);

	// 	$conn->close();
	// 	die;
	// }

	$sql = "SELECT * FROM order_header WHERE transaction_id = '$transactionId'";// check if exist
	$result = $conn->query($sql);

	if($result->num_rows > 0) {
		$sqlDeleteHeader = "DELETE FROM temp_order_header WHERE transaction_id = '$transactionId'";
		if (!$conn->query($sqlDeleteHeader)) {
		# code...
			echo json_encode(
				array(
					'status'  => 'error',
					'error'   => mysqli_error($conn),
					'message' => 'Error in deleting order -> order_header',
					'tableNumber' => $tableNumber,
					'sqlDeleteHeader'     => $sqlDeleteHeader
				)
			);
			$conn->close();
			die;
		}
	} else {
		$sqlInsertHeader = "INSERT INTO `order_header` SELECT * FROM temp_order_header WHERE transaction_id = '$transactionId'";
		if (!$conn->query($sqlInsertHeader)) {
			# code...
			echo json_encode(
				array(
					'status'  => 'error',
					'error'   => mysqli_error($conn),
					'message' => 'Error in saving order -> order_header',
					'tableNumber' => $tableNumber,
					'sqlInsertHeader'     => $sqlInsertHeader
				)
			);
			$conn->close();
			die;
		} // INSERT INTO order_header

		$sqlDeleteHeader = "DELETE FROM `temp_order_header` WHERE transaction_id = '$transactionId'";

		if (!$conn->query($sqlDeleteHeader)) {
			# code...
			echo json_encode(
				array(
					'status'  => 'error',
					'message' => 'Error in deleting order -> order_header',
					'tableNumber' => $tableNumber,
					'sqlDeleteHeader'     => $sqlDeleteHeader
				)
			);
			$conn->close();
			die;
		} // DELETE FROM temp_order_header	
	}


	

	$sqlInsertDetails = "INSERT INTO `order_detail`(id, table_number, item_id,
			 serving_id, sauces, side_dish_id, qty, transaction_id)
		SELECT tod.`id`, tod.`table_number`,
			tod.`item_id`, tod.`serving_id`, tod.`sauces`,
			tod.`side_dish_id`, tod.`qty`, tod.`transaction_id`
		FROM `temporary_order_detail` tod WHERE tod.`transaction_id` = '$transactionId'";

	if (!$conn->query($sqlInsertDetails)) {
		# code...
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving order -> order_detail',
				'tableNumber' => $tableNumber,
				'sqlInsertDetails'     => $sqlInsertDetails
			)
		);
		$conn->close();
		die;
	} // INSERT INTO order_detail

	$sqlDeleteDetails = "DELETE FROM `temporary_order_detail` WHERE transaction_id = '$transactionId'";

	if (!$conn->query($sqlDeleteDetails)) {
		# code...
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in deleting order -> temporary_order_detail',
				'tableNumber' => $tableNumber,
				'sqlDeleteDetails'     => $sqlDeleteDetails
			)
		);
		$conn->close();
		die;	
	} // DELETE FROM temporary_order_detail
	
	$sqlUpdateDetails = "UPDATE `order_detail` SET status = 'C' WHERE transaction_id = '$transactionId'";
	
	if (!$conn->query($sqlUpdateDetails)) {
		# code...
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in updating order -> order_detail',
				'tableNumber' => $tableNumber,
				'sqlUpdateDetails'     => $sqlUpdateDetails
			)
		);
		$conn->close();
		die;
	} // UPDATE order_detail SET status = 'S'

	echo json_encode(
		array(
			'status' => 'ok',
			'message' => 'Save Successfull',
		)
	);

	$conn->close();
	die;
?>