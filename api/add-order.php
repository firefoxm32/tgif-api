<?php
	
	require_once("config/DBConnection.php");
	$db = new DBConnection();
	$conn = $db->connect();

	$tableNumber = $_POST['table_number'];
	$transactionId = $_POST['transaction_id'];
	$itemId = $_POST['item_id'];
	$servingId = $_POST['serving_id'];
	$sauces = $_POST['sauces'];
	$sideDishId = $_POST['side_dish_id'];
	$qty = $_POST['qty'];

	//Check header if existing
	$sql = "SELECT * FROM `temp_order_header` toh WHERE toh.`transaction_id` = '$transactionId'";
	$result = $conn->query($sql);
	if ($result->num_rows == 0) {
		# code...
		//Insert temp_order_header
		$sql = "INSERT INTO `temp_order_header`(transaction_id, table_number, date_order)VALUES('$transactionId', $tableNumber, NOW())";
		if (!$conn->query($sql)) {
			echo json_encode(
				array(
					'status'  => 'error',
					'message' => 'Error in adding order toh',
					'error'   => mysqli_error($conn),
					'tableNumber' => $tableNumber,
					'sql'     => $sql
				)
			);
			$conn->close();
			die;
		}
	}

	$explodedSauces = explode(",", $sauces);
	$serializedSauces = serialize($explodedSauces);
	
	//Check if Existing
	$sql = "SELECT * FROM `temporary_order_detail` tod
			WHERE tod.`transaction_id` = '$transactionId' AND tod.`item_id` = $itemId
			AND tod.`serving_id` = $servingId 
			AND tod.`sauces` = '$serializedSauces'
			AND tod.`side_dish_id` = $sideDishId";
	// $sql = "SELECT * FROM `temporary_order_detail` tod
	// 		WHERE transaction_id='$transactionId'";

	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		# code...
		//Update record order if existing
		$sql = "UPDATE `temporary_order_detail`
				SET qty = qty + $qty
				WHERE transaction_id = '$transactionId' AND item_id = $itemId
				AND serving_id = $servingId AND sauces = '$serializedSauces'
				AND side_dish_id = $sideDishId";
		// $sql = "UPDATE `temporary_order_detail`
		// 		SET qty = qty + $qty
		// 		WHERE transaction_id='$transactionId'";

		if (!$conn->query($sql)) {
			echo json_encode(
				array(
					'status'  => 'error',
					'message' => 'Error in adding order toh',
					'error'   => mysqli_error($conn),
					'tableNumber' => $tableNumber,
					'sql'     => $sql
				)
			);
			$conn->close();
			die;
		}
		$conn->close();
		echo json_encode(
			array(
				'status' => 'Ok',
				'message' => 'Order successfully added',
				'tableNumber' => $tableNumber,
				'sql'     => $sql
			)
		);
		die;
	}

	$sql = "INSERT INTO `temporary_order_detail`(
			table_number, transaction_id,item_id,serving_id,
			sauces,side_dish_id,qty)VALUES($tableNumber, '$transactionId', $itemId, $servingId,
			'$serializedSauces',$sideDishId,$qty)";

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in adding order tod',
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
			'message' => 'Order successfully added',
			'tableNumber' => $tableNumber,
			'sql'     => $sql
		)
	);
	die;


