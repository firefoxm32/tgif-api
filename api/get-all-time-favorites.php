<?php 

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$param = $_GET['params'];

	$sqlMenuId = "SELECT fm.`menu_id` FROM `food_menu` fm";
	$result = $conn->query($sqlMenuId);

	$itemId = array();
	$rowss;
	if ($result->num_rows > 0) {
		$rowss = $result->num_rows;
		# code...
		while ($row = $result->fetch_object()) {
			# code...
			$sqlItems = "SELECT fi.`item_id` 
				FROM `food_items` fi 
				WHERE fi.`menu_id` = $row->menu_id AND fi.`status` = 'A' AND fi.`promo_status` = 'I'
				ORDER BY fi.`rating` DESC LIMIT 1";
			
			$resultItems = $conn->query($sqlItems);
			if ($resultItems->num_rows > 0) {
				# code...
				while ($rowItems = $resultItems->fetch_object()) {
					# code...
					$itemId[] = $rowItems->item_id;
				}
			}
		}
	}
	$ids = implode(",", $itemId);
	/*$sql = "SELECT * FROM `food_menu_items` fi
			WHERE fi.`item_id` IN ($ids)";*/
	$sql = "SELECT fi.`item_id`, fi.`menu_id`, fi.`item_name`, fi.`image`,
		fi.`description`, fi.`status`, fm.`menu_name`, fi.`promo_status`, fi.`rating` FROM `food_items` fi
		LEFT JOIN `food_menu` fm ON
		fi.`menu_id` = fm.`menu_id`
		WHERE fi.`item_id` IN ($ids)";
	$result = $conn->query($sql);

	$itemList = array();

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_object()) {
			# code...
			// $itemList[] = $row;
			$item = new stdClass();
			$item->item_id = $row->item_id;
			$item->menu_id = $row->menu_id;
			$item->item_name = $row->item_name;
			$item->image = $row->image;
			$item->description = $row->description;
			$item->status = $row->status;
			$item->menu_name = $row->menu_name;
			$item->promo_status = $row->promo_status;
			$item->rating = ($row->rating / $rowss);
			$itemList[] = $item;
		}
	}
	//echo implode(",", $menuId);
	$conn->close();
	$response = array(
		'status' => "ok",
		'items'  => $itemList,
		'sql'    => $sql
	);
	echo json_encode($response);
	die;
