<?php 
	
	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$tableNumber = $_GET['table_number'];

	$sql = "SELECT tm.`transaction_id` FROM `table_management` tm WHERE tm.`table_number` = $tableNumber";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		# code...
		$transactionId = $result->fetch_object()->transaction_id;
	}

	$sql1 = "SELECT cd.`credit`, ch.`cash_amount` FROM `cash_details` cd 
		LEFT JOIN `cash_header` ch ON ch.`transaction_id` = cd.`transaction_id`
		WHERE cd.`transaction_id` = '$transactionId'";
	
	$result1 = $conn->query($sql1);
	$credit = 0.0;
	$cash = 0.0;
	if ($result1->num_rows > 0) {
		# code...
		$row1 = $result1->fetch_object();
		$credit = $row1->credit;
		$cash = $row1->cash_amount;
	}

	$sql = "SELECT oh.`transaction_id`, oh.`date_order`, od.`item_id`,
		od.`serving_id`, od.`side_dish_id`, od.`sauces`, od.`qty`,
		fs.`serving_name`,fp.`price`,sd.`side_dish_name`, fmi.`menu_name`,
		sd.`sd_abbreviation`, fs.`fs_abbreviation`
		FROM `order_header` oh
		LEFT JOIN `order_detail` od ON od.`transaction_id` = oh.`transaction_id`
		LEFT JOIN `cash_header` ch ON ch.`transaction_id` = oh.`transaction_id`
		LEFT JOIN `food_menu_items` fmi ON fmi.`item_id` = od.`item_id`
		LEFT JOIN `food_servings` fs ON fs.`serving_id` = od.`serving_id`
		LEFT JOIN `food_price` fp ON fp.`serving_id` = fs.`serving_id`
		LEFT JOIN `side_dish` sd ON sd.`side_dish_id` = od.`side_dish_id`
		WHERE oh.`transaction_id` = '$transactionId'";

	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		# code...
		while ($row = $result->fetch_object()) {
			# code...
			$sauceIds = unserialize($row->sauces);

			$ids = implode(",", $sauceIds);
			$sqlSauce = "SELECT * FROM `sauce` s WHERE s.`sauce_id` IN($ids)";
			
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
			$item->transaction_id = $row->transaction_id;
			$item->date_order = $row->date_order;
			$item->item_id = $row->item_id;
			$item->item_name = $row->menu_name;
			$item->serving_id = $row->serving_id;
			$item->serving_name = $row->serving_name;
			$item->fs_abbreviation = $row->fs_abbreviation;
			$item->side_dish_id = $row->side_dish_id;
			$item->side_dish_name = $row->side_dish_name;
			$item->sd_abbreviation = $row->sd_abbreviation;
			$item->qty = $row->qty;
			$item->price = $row->price;
			$item->sauces = $sauces;
			$item->credit = $credit;
			$item->cash = $cash;
			$items[] = $item;
		}
	}

	$response = array(
		'status' => "ok",
		'sql'    => $sql,
		'sql1'   => $sql1,
		'param'  => $transactionId,
		'items'  => (array)$items
	);
	echo json_encode($response);
	$conn->close();