<?php 

	require_once("config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$param = $_GET['params'];

	$sqlMenuId = "SELECT fm.`menu_id` FROM `food_menu` fm";
	$result = $conn->query($sqlMenuId);

	$itemId = array();
	if ($result->num_rows > 0) {
		# code...
		while ($row = $result->fetch_object()) {
			# code...
			$sqlItems = "SELECT fi.`item_id` 
				FROM `food_items` fi 
				WHERE fi.`menu_id` = $row->menu_id AND fi.`status` = 'A' AND fi.`promo_status` = 'A'";
			
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
			/*SELECT fi.`item_id`, fi.`menu_id`, fi.`item_name`, fi.`image`,
		fi.`description`, fi.`status`, fm.`menu_name`, fi.`promo_status` FROM `food_items` fi
		LEFT JOIN `food_menu` fm ON
		fi.`menu_id` = fm.`menu_id`
		LEFT JOIN `food_serving` fs ON
		fi.`item_id` = fs.`item_id`
		WHERE fi.`item_id` IN ($ids)*/
	$sql = "SELECT fi.`item_id`, fi.`menu_id`, fi.`item_name`, fi.`image`,
		fi.`description`, fi.`status`, fm.`menu_name`, fi.`promo_status`,
		fs.`serving_name`
		FROM `food_items` fi
		LEFT JOIN `food_menu` fm ON
		fi.`menu_id` = fm.`menu_id`
		LEFT JOIN `food_price` fp ON
		fi.`item_id` = fp.`item_id`
		LEFT JOIN `food_serving` fs ON
		fp.`serving_id` = fs.`serving_id`
		WHERE fi.`item_id` IN ($ids)";
	$result = $conn->query($sql);

	$itemList = array();

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_object()) {
			# code...
			$itemList[] = $row;
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
