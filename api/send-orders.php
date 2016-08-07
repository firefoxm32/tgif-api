<?php 

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$tableNumber = $_POST['table_number'];

	$sqlSelect = "SELECT toh.`date_order` FROM `temp_order_header` toh WHERE toh.`table_number` = $tableNumber";

	$result = $conn->query($sqlSelect);

	if ($result->num_rows > 0) {
		# code...
		$rows = $result->fetch_object();
		$dateOrder =  $rows->date_order;
	}

	$sqlInsertHeader = "INSERT INTO `order_header`(table_number, date_order) SELECT `table_number`, `date_order` FROM `temp_order_header` WHERE `table_number` = $tableNumber";
	if (!$conn->query($sqlInsertHeader)) {
		# code...
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving order -> order_header',
				'tableNumber' => $tableNumber,
				'sqlInsertHeader'     => $sqlInsertHeader
			)
		);
		$conn->close();
		die;
	} // INSERT INTO order_header

	$sqlDeleteHeader = "DELETE FROM `temp_order_header` WHERE table_number = $tableNumber";

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

	$sqlInsertDetails = "INSERT INTO `order_detail`(id, table_number, item_id,
			 serving_id, sauces, side_dish_id, qty, date_order)
		SELECT tod.`id`, tod.`table_number`,
			tod.`item_id`, tod.`serving_id`, tod.`sauces`,
			tod.`side_dish_id`, tod.`qty`, tod.`date_order`
		FROM `temporary_order_detail` tod WHERE tod.`table_number` = $tableNumber";

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

	$sqlDeleteDetails = "DELETE FROM `temporary_order_detail` WHERE table_number = $tableNumber";

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
	
	$sqlUpdateDetails = "UPDATE `order_detail` SET status = 'C' WHERE table_number = $tableNumber AND od.`date_order` = $dateOrder";
	
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
			'status' => 'Ok',
			'message' => 'Save Successfull',
			'tableNumber' => $tableNumber,
			'sqlInsertHeader'  => $sqlInsertHeader,
			'sqlDeleteHeader'  => $sqlDeleteHeader,
			'sqlInsertDetails' => $sqlInsertDetails,
			'sqlDeleteDetails' => $sqlDeleteDetails,
			'sqlUpdateDetails' => $sqlUpdateDetails
		)
	);

	$conn->close();
	die;
?>