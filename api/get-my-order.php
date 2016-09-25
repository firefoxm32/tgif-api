<?php 

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();
	

	$tableNumber = $_GET['table_number'];
	$transactionId = $_GET['transaction_id'];
	$stats = $_GET['status'];

	if ($stats == "S" OR $stats == "s") {
		# code...
		$sql = "SELECT id, quantity, od.`status`, od.`item_id`, od.`sauces`,fi.`image`,
		fi.`item_name`, fs.`serving_name`, sd.`side_dish_name`
		FROM `order_detail` od LEFT JOIN
		`food_items` fi ON fi.`item_id` = od.`item_id` LEFT JOIN
		`food_serving` fs ON fs.`serving_id` = od.`serving_id` LEFT JOIN
		`side_dish` sd ON sd.`side_dish_id` = od.`side_dish_id`
		WHERE transaction_id = '$transactionId' AND od.`status` = '$stats'";
	} elseif ($stats == "C" OR $stats == "c") {
		# code...
		$sql = "SELECT id, quantity, od.`status`, od.`item_id`, od.`sauces`,fi.`image`,
		fi.`item_name`, fs.`serving_name`, sd.`side_dish_name`
		FROM `order_detail` od LEFT JOIN
		`food_items` fi ON fi.`item_id` = od.`item_id` LEFT JOIN
		`food_serving` fs ON fs.`serving_id` = od.`serving_id` LEFT JOIN
		`side_dish` sd ON sd.`side_dish_id` = od.`side_dish_id`
		WHERE transaction_id = '$transactionId' AND od.`status` = '$stats'";
	} else {
		$sql = "SELECT id, quantity, tod.`item_id`, tod.`sauces`, fi.`item_name`,fi.`image`,
		fs.`serving_name`, sd.`side_dish_name`
		FROM `temporary_order_detail` tod LEFT JOIN
		`food_items` fi ON fi.`item_id` = tod.`item_id` LEFT JOIN
		`food_serving` fs ON fs.`serving_id` = tod.`serving_id` LEFT JOIN
		`side_dish` sd ON sd.`side_dish_id` = tod.`side_dish_id`
		WHERE transaction_id = '$transactionId' AND tod.`status` = 'P'";
	}

	$item_orders = array();
	
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		# code...
		while ($row = $result->fetch_object()) {
			# code...
			$sauceIds = unserialize($row->sauces);

			$ids = implode(",", $sauceIds);
			$sqlSauce = "SELECT s.`sauce_name` FROM `sauce` s WHERE s.`sauce_id` IN($ids)";
			
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
		    $item->item_id = $row->item_id;
		    $item->item_name = $row->item_name;
		    $item->image = $row->image;
		    $item->serving = $row->serving_name;
		    $item->sauces = $sauces;
		    $item->side_dish = $row->side_dish_name;
		    $item->qty = $row->quantity;
		    $item->status = $row->status;
		    $item_orders[] = $item;
		}
	}
	$response = array(
		'statuss'  => 'Ok',
		'items'   => (array)$item_orders,
		'size'    => sizeof($item_orders),
		'table_number'     => $tableNumber,
		'sql'    => $sql
	);

	echo json_encode($response);
	$conn->close();
 ?>

 <script type="text/javascript">
 var result = <?php echo json_encode($response); ?>
 </script>