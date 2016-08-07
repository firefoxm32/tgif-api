<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$param = $_GET['table_number'];

	$sql = "SELECT od.`id`,od.`table_number`, od.`item_id`, fmi.`menu_name`, fs.`serving_name`, fs.`fs_abbreviation`,
            sd.`sd_abbreviation`,
			sd.`side_dish_name`, od.`qty`, od.`sauces`
			FROM `order_detail` od
			LEFT JOIN `food_menu_items` fmi ON od.`item_id` = fmi.`item_id`
			LEFT JOIN `food_servings` fs ON od.`serving_id` = fs.`serving_id`
			LEFT JOIN `side_dish` sd ON od.`side_dish_id` = sd.`side_dish_id`
			WHERE od.`table_number`= $param AND od.`status` = 'C'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		# code...
		while ($row = $result->fetch_object()) {
			# code...
			$sauceIds = unserialize($row->sauces);

			$ids = implode(",", $sauceIds);
			$sqlSauce = "SELECT s.`sauce_name`, s.`s_abbreviation` FROM `sauce` s WHERE s.`sauce_id` IN($ids)";
			
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
			$item->table_number = $row->table_number;
		    $item->item_id = $row->item_id;
		    $item->item_name = $row->menu_name;
		    $item->serving = $row->serving_name;
		    $item->fs_abbreviation = $row->fs_abbreviation;
		    $item->sauces = $sauces;
		    $item->side_dish = $row->side_dish_name;
		    $item->sd_abbreviation = $row->sd_abbreviation;
		    $item->qty = $row->qty;
		    $item->status = $row->status;
		    $item_orders[] = $item;
		}
	}
	$response = array(
		'status'  => 'Ok',
		'items'   => (array)$item_orders,
		'size'    => sizeof($item_orders),
		'table_number'     => $tableNumber
	);
	echo json_encode($response);
	$conn->close();
 ?>