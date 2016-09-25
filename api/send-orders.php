<?php 

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$tableNumber = $_POST['table_number'];
	$transactionId = $_POST['transaction_id'];

	$sql = "SELECT * FROM order_header WHERE transaction_id = '$transactionId'";// check if exist
	$result = $conn->query($sql);

	if($result->num_rows > 0) {
		$sqlDeleteHeader = "DELETE FROM temporary_order_header WHERE transaction_id = '$transactionId'";
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
		$sqlInsertHeader = "INSERT INTO `order_header` SELECT * FROM temporary_order_header WHERE transaction_id = '$transactionId'";
		if (!$conn->query($sqlInsertHeader)) {
			# code...
			echo json_encode(
				array(
					'status'  => 'error',
					'error'   => mysqli_error($conn),
					'message' => 'Error in sending order -> order_header',
					'tableNumber' => $tableNumber,
					'sqlInsertHeader'     => $sqlInsertHeader
				)
			);
			$conn->close();
			die;
		} // INSERT INTO order_header

		$sqlDeleteHeader = "DELETE FROM `temporary_order_header` WHERE transaction_id = '$transactionId'";

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
		} // DELETE FROM temp_order_header	
	}


	

	$sqlInsertDetails = "INSERT INTO `order_detail`(id, item_id,
			 serving_id, sauces, side_dish_id, quantity, transaction_id)
		SELECT tod.`id`, tod.`item_id`, tod.`serving_id`, tod.`sauces`,
			tod.`side_dish_id`, tod.`quantity`, tod.`transaction_id`
		FROM `temporary_order_detail` tod WHERE tod.`transaction_id` = '$transactionId'";

	if (!$conn->query($sqlInsertDetails)) {
		# code...
		echo json_encode(
			array(
				'status'  => 'error',
				'error'   => mysqli_error($conn),
				'message' => 'Error in sending order -> order_detail',
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
				'error'   => mysqli_error($conn),
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
				'error'   => mysqli_error($conn),
				'message' => 'Error in updating order -> order_detail',
				'tableNumber' => $tableNumber,
				'sqlUpdateDetails'     => $sqlUpdateDetails
			)
		);
		$conn->close();
		die;
	} // UPDATE order_detail SET status = 'S'
	$conn->close();
	echo json_encode(
		array(
			'status' => 'ok',
			'message' => 'Order successfully sent to kitchen'
		)
	);
	die;