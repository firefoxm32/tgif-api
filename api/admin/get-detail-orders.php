<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$param = $_GET['table_number'];

	$sql = "SELECT tm.`transaction_id` FROM table_management tm WHERE tm.`table_number` = $param";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		# code...
		$row = $result->fetch_object();
		$transactionId = $row->transaction_id;
	}

	$sql1 = "SELECT od.`id`, od.`item_id`, od.`ready_status`, od.`order_type`,fi.`item_name`, fs.`serving_name`, 
			fs.`serving_code`, sd.`side_dish_code`, od.`status`,
			sd.`side_dish_name`, od.`quantity`, od.`sauces`
			FROM `order_detail` od
			LEFT JOIN `food_items` fi ON od.`item_id` = fi.`item_id`
			LEFT JOIN `food_serving` fs ON od.`serving_id` = fs.`serving_id`
			LEFT JOIN `side_dish` sd ON od.`side_dish_id` = sd.`side_dish_id`
			WHERE od.`transaction_id` = '$transactionId' AND od.`status` = 'C'";
	$result = $conn->query($sql1);
	if ($result->num_rows > 0) {
		# code...
		while ($row = $result->fetch_object()) {
			# code...
			$sauceIds = unserialize($row->sauces);

			$ids = implode(",", $sauceIds);
			$sqlSauce = "SELECT s.`sauce_name`, s.`sauce_code` FROM `sauce` s WHERE s.`sauce_id` IN($ids)";
			
			$sauceResult = $conn->query($sqlSauce);
			$sauces = array();
			if ($sauceResult->num_rows > 0) {
					# code...
				while ($sauce = $sauceResult->fetch_object()) {
					# code...
					$sauces[] = $sauce;
				}
			}	


			$item = new stdClass();
			$item->id = $row->id;
			$item->table_number = $param;
		    $item->item_id = $row->item_id;
		    $item->item_name = $row->item_name;
		    $item->serving = $row->serving_name;
		    $item->serving_code = $row->serving_code;
		    $item->sauces = $sauces;
		    $item->side_dish = $row->side_dish_name;
		    $item->side_dish_code = $row->side_dish_code;
		    $item->quantity = $row->quantity;
		    $item->status = $row->status;
		    $item->ready_status = $row->ready_status;
		    $item->order_type = $row->order_type;
		    $item_orders[] = $item;
		}
	}
	$response = array(
		'status'  => 'ok',
		'items'   => (array)$item_orders,
		'size'    => sizeof($item_orders),
		'transaction_id' => $transactionId,
		'sql' => $sql1
	);
	echo json_encode($response);
	$conn->close();
 ?>