<?php 

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$param = $_GET['params'];

	$sqlMenuId = "SELECT fm.`menu_id` FROM `food_menus` fm";
	$result = $conn->query($sqlMenuId);

	$itemId = array();
	if ($result->num_rows > 0) {
		# code...
		while ($row = $result->fetch_object()) {
			# code...
			$sqlItems = "SELECT fmi.`item_id` 
				FROM `food_menu_items` fmi 
				WHERE fmi.`menu_id` = $row->menu_id 
				ORDER BY fmi.`item_id` ASC LIMIT 2";
			
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
	/*$sql = "SELECT * FROM `food_menu_items` fmi
			WHERE fmi.`item_id` IN ($ids)";*/
	$sql = "SELECT fmi.`item_id`, fmi.`menu_id`, fmi.`menu_name`, fmi.`image`,
		fmi.`description`, fmi.`status`, fmi.`abbreviation`, fm.`label` FROM `food_menu_items` fmi
		LEFT JOIN `food_menus` fm ON
		fmi.`menu_id` = fm.`menu_id`
		WHERE fmi.`item_id` IN ($ids)";
	$result = $conn->query($sql);

	$itemList = array();

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_object()) {
			# code...
			$itemList[] = $row;
		}
	}
	//echo implode(",", $menuId);
	$response = array(
		'status' => "ok",
		'items'  => $itemList
	);

	echo json_encode($response);
	$conn->close();
 ?>