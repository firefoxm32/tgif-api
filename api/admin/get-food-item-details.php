<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$itemId = $_GET['item_id'];

	$sql = "SELECT fp.`price`, fs.`serving_id`, 
	fs.`serving_name`, fs.`serving_code` FROM `food_price` fp
	LEFT JOIN `food_serving` fs ON fs.`serving_id` = fp.`serving_id`
	WHERE fp.`item_id` = $itemId";

	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		# code...
		while ($rows = $result->fetch_object()) {
			# code...
			$servings[] = $rows;
		}
	}

	$sql = "SELECT s.`sauce_id`, s.`sauce_name`, s.`sauce_code` FROM `sauce` s WHERE s.`item_id` = $itemId";

	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		# code...
		while ($rows = $result->fetch_object()) {
			# code...
			$sauces[] = $rows;
		}
	}

	$sql = "SELECT sd.`side_dish_id`, sd.`side_dish_name`, sd.`side_dish_code` FROM `side_dish` sd 
		WHERE sd.`item_id` = $itemId";

	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		# code...
		while ($rows = $result->fetch_object()) {
			# code...
			$sideDishes[] = $rows;
		}
	}	

	$item = new stdClass();
	$item->servings = $servings;
	$item->sauces = $sauces;
	$item->side_dishes = $sideDishes;

	$conn->close();
	$response = array(
		'status' => "ok",
		'item'  => $item
	);

	echo json_encode($response);
	die;