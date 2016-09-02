<?php 

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$transactionId = $_POST['transaction_id'];

	$sql = "SELECT od.`qty`, fp.`price` FROM `order_detail` od
		LEFT JOIN `food_price` fp ON fp.`serving_id` = od.`serving_id`
		WHERE od.`transaction_id` = '$transactionId'";

	$totalPrice = 0.0;
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
		while ($row = $result->fetch_object()) {
			# code...
			$totalPrice += (($row->price)*($row->qty));
		}
	}

	$response = array(
		'status'  => 'ok',
		'total_price'     => $totalPrice
	);

	echo json_encode($response);
	$conn->close();