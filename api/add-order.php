<?php
	
	require_once("config/DBConnection.php");
	$db = new DBConnection();
	$conn = $db->connect();

	$tableNumber = $_POST['table_number'];
	$itemId = $_POST['item_id'];
	$servingId = $_POST['serving_id'];
	$sauces = $_POST['sauces'];
	$sideDishId = $_POST['side_dish_id'];
	$qty = $_POST['qty'];

	$explodedSauces = explode(",", $sauces);
	$serializedSauces = serialize($explodedSauces);
	
	//Check if Existing
	$sql = "SELECT * FROM `temporary_order_detail` tod
			WHERE tod.`table_number` = $tableNumber AND tod.`item_id` = $itemId
			AND tod.`serving_id` = $servingId 
			AND tod.`sauces` = '$serializedSauces'
			AND tod.`side_dish_id` = $sideDishId";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		# code...
		//Update record order if existing
		$sql = "UPDATE `temporary_order_detail`
				SET qty = qty + $qty
				WHERE table_number = $tableNumber AND item_id = $itemId
				AND serving_id = $servingId AND sauces = '$serializedSauces'
				AND side_dish_id = $sideDishId";
		if (!$conn->query($sql)) {
			echo json_encode(
				array(
					'status'  => 'error',
					'message' => 'Error in saving order toh',
					'error'   => mysqli_error($conn),
					'tableNumber' => $tableNumber,
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
		die;
	}
	//Check header if existing
	$sql = "SELECT * FROM `temp_order_header` toh WHERE toh.`table_number` = $tableNumber";
	$result = $conn->query($sql);
	if (!$result->num_rows > 0) {
		# code...
		//Insert temp_order_header
		$sql = "INSERT INTO `temp_order_header`(table_number, date_order)VALUES($tableNumber,NOW())";
		if (!$conn->query($sql)) {
			echo json_encode(
				array(
					'status'  => 'error',
					'message' => 'Error in saving order toh',
					'error'   => mysqli_error($conn),
					'tableNumber' => $tableNumber,
					'sql'     => $sql
				)
			);
			die;
		}
	}
	/*INSERT INTO `temporary_order_detail`(
			table_number,item_id,serving_id,
			sauces,side_dish_id,qty,date_order)
VALUES(9,2,2,'a:1:{i:0;s:1:"2";}',0,3,(SELECT tod.`date_order` FROM `temp_order_header` tod WHERE tod.`table_number` = 9))*/
	// insert temp_order_detail
	$sql = "INSERT INTO `temporary_order_detail`(
			table_number,item_id,serving_id,
			sauces,side_dish_id,qty,date_order)VALUES($tableNumber, $itemId, $servingId,
			'$serializedSauces',$sideDishId,$qty,(SELECT tod.`date_order` FROM `temp_order_header` tod WHERE tod.`table_number` = $tableNumber))";

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving order tod',
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
	die;
?>


