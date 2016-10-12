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

	$sql1 = "SELECT cd.`credit`, ch.`member_id`, ch.`credit_card_number`,
					ch.`credit_card_name`, ch.`or_number`
	    FROM `cash_details` cd 
		LEFT JOIN `cash_header` ch ON ch.`transaction_id` = cd.`transaction_id`
		WHERE cd.`transaction_id` = '$transactionId'";
	
	$result1 = $conn->query($sql1);
	$credit = 0.0;
	$cash = 0.0;
	$memberId;
	$ccNumber;
	$ccName;
	$orNumber;
	if ($result1->num_rows > 0) {
		# code...
		$row1 = $result1->fetch_object();
		$credit = $row1->credit;
		$memberId = $row1->member_id;
		$ccNumber = $row1->credit_card_number;
		$ccName = $row1->credit_card_name;
		$orNumber = $row1->or_number;
	}

	$sql = "SELECT oh.`transaction_id`, oh.`date_order`, od.`item_id`,
		od.`serving_id`, od.`side_dish_id`, od.`sauces`, od.`quantity`,
		fs.`serving_name`,fp.`price`,sd.`side_dish_name`, fi.`item_name`,
		sd.`side_dish_code`, fs.`serving_code`
		FROM `order_header` oh
		LEFT JOIN `order_detail` od ON od.`transaction_id` = oh.`transaction_id`
		LEFT JOIN `food_items` fi ON fi.`item_id` = od.`item_id`
		LEFT JOIN `food_serving` fs ON fs.`serving_id` = od.`serving_id`
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
			$item->item_name = $row->item_name;
			$item->serving_id = $row->serving_id;
			$item->serving_name = $row->serving_name;
			$item->serving_code = $row->serving_code;
			$item->side_dish_id = $row->side_dish_id;
			$item->side_dish_name = $row->side_dish_name;
			$item->side_dish_code = $row->side_dish_code;
			$item->quantity = $row->quantity;
			$item->price = $row->price;
			$item->sauces = $sauces;
			$item->credit = $credit;
			$item->member_id = $memberId;
			$item->credit_card_number = $ccNumber;
			$item->credit_card_name = $ccName;
			$item->or_number = $orNumber;
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