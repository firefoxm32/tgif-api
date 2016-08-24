<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$itemId = $_GET['item_id'];

	$sql = "SELECT fp.`price`, fs.`serving_id`, 
	fs.`serving_name`, fs.`fs_abbreviation` FROM `food_price` fp
	LEFT JOIN `food_servings` fs ON fs.`serving_id` = fp.`serving_id`
	WHERE fp.`item_id` = $itemId";

	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		# code...
		while ($rows = $result->fetch_object()) {
			# code...
			$servings[] = $rows;
		}
	}

	$sql = "SELECT s.`sauce_id`, s.`sauce_name`, s.`s_abbreviation` FROM `sauce` s WHERE s.`item_id` = $itemId";

	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		# code...
		while ($rows = $result->fetch_object()) {
			# code...
			$sauces[] = $rows;
		}
	}

	$sql = "SELECT sd.`side_dish_id`, sd.`side_dish_name`, sd.`sd_abbreviation` FROM `side_dish` sd 
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