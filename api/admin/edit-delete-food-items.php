<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$menuId = $_POST['menu_id'];
	$itemName = $_POST['item_name'];
	$image = $_POST['image'];
	$description = $_POST['description'];
	$itemId = $_POST['item_id'];
	$action = $_POST['action'];

	$servingName = $_POST['serving_name'];
	$price = $_POST['price'];
	$sauceName = $_POST['sauce_name'];
	$sideDishName = $_POST['side_dish_name'];

	$servingId = $_POST['serving_id'];
	$sauceId = $_POST['sauce_id'];
	$sideDishId = $_POST['side_dish_id'];

	$rServingId = $_POST['r_serving_id'];
	$rSauceId = $_POST['r_sauce_id'];
	$rSideDishId = $_POST['r_side_dish_id'];

	if ($action == 'delete') {
		# code...
		// $sql = "UPDATE `food_menu_items` 
		// SET menu_id = $menuId, menu_name = '$itemName', 
		// 	image = '$image', description = '$description' 
		// WHERE item_id = $itemId";
		// $message = "Edit Successfull";
	// } else {
		$sql = "UPDATE `food_items` SET status = 'I' WHERE item_id = $itemId";
		$message = "Delete Successfull";

		if (!$conn->query($sql)) {
			echo json_encode(
				array(
					'status'  => 'error',
					'message' => 'Error in saving food item',
					'error'   => mysqli_error($conn),
					'sql'     => $sql
				)
			);
			$conn->close();
			die;
		}
		$conn->close();
		echo json_encode(
			array(
				'status' => 'ok',
				'message' => $message,
				'tableNumber' => $tableNumber,
				'sql'     => $sql
			)
		);
		die;
	}

	$sql = "UPDATE `food_items` 
		SET menu_id = $menuId, item_name = '$itemName', 
			image = '$image', description = '$description' 
		WHERE item_id = $itemId";
		$message = "Edit Successfull";

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving food item',
				'error'   => mysqli_error($conn),
				'sql'     => $sql
			)
		);
		$conn->close();
		die;
	}

	if ($servingName != "") {
		# code...
		$arrServingName = explode(",", $servingName);
		$arrPrice = explode(",", $price);
		$arrServingId = explode(",", $servingId);
		for ($i=0; $i < sizeof($arrServingName); $i++) { 
			# code...
			// if ($servingId != "") {
			 	# code...
			 	if(sizeof($arrServingId) > $i && $servingId != "") {
					$sql = "UPDATE food_serving SET serving_name = '$arrServingName[$i]', serving_code = 'FS' WHERE serving_id = $arrServingId[$i]";
					if (!$conn->query($sql)) {
						echo json_encode(
							array(
								'status'  => 'error',
								'message' => 'Error in updating food item serving',
								'error'   => mysqli_error($conn),
								'sql'     => $sql
							)
						);
						$conn->close();
						die;
					}
					$sql = "UPDATE food_price SET item_id = $itemId, price = $arrPrice[$i] WHERE serving_id = $arrServingId[$i]";
					if (!$conn->query($sql)) {
						echo json_encode(
							array(
								'status'  => 'error',
								'message' => 'Error in updating food item price',
								'error'   => mysqli_error($conn),
								'sql'     => $sql
							)
						);
						$conn->close();
						die;
					}
				// }
			 } else {
				$sql = "INSERT INTO food_serving(serving_name, serving_code)VALUES(
					'$arrServingName[$i]', 'SF')"; 
				if (!$conn->query($sql)) {
					echo json_encode(
						array(
							'status'  => 'error',
							'message' => 'Error in inserting food item serving',
							'error'   => mysqli_error($conn),
							'sql'     => $sql
						)
					);
					$conn->close();
					die;
				}

				$lastId = $conn->insert_id;
				$sql = "INSERT INTO food_price(serving_id, item_id, price)VALUES($lastId, $itemId, $arrPrice[$i])";				
				if (!$conn->query($sql)) {
					echo json_encode(
						array(
							'status'  => 'error',
							'message' => 'Error in inserting food item price',
							'error'   => mysqli_error($conn),
							'sql'     => $sql
						)
					);
					$conn->close();
					die;
				}
			}			
		}
	}
	if ($rServingId != "") {
		# code...
		$sql = "DELETE FROM food_serving WHERE serving_id IN($rServingId)";
		if (!$conn->query($sql)) {
			echo json_encode(
				array(
					'status'  => 'error',
					'message' => 'Error in deleting food item serving',
					'error'   => mysqli_error($conn),
					'sql'     => $sql
				)
			);
			$conn->close();
			die;
		}
		$sql = "DELETE FROM food_price WHERE item_id = $itemId AND serving_id IN($rServingId)";
		if (!$conn->query($sql)) {
			echo json_encode(
				array(
					'status'  => 'error',
					'message' => 'Error in deleting food item price',
					'error'   => mysqli_error($conn),
					'sql'     => $sql
				)
			);
			$conn->close();
			die;
		}
	} // servings

	if ($sauceName != "") {
		# code...
		$arrSauceName = explode(",", $sauceName);
		$arrSauceId = explode(",", $sauceId);
		for ($i=0; $i < sizeof($arrSauceName); $i++) { 
			# code...
			// if ($sauceId != "") {
				# code...
				if (sizeof($arrSauceId) > $i && $sauceId != "") {
				# code...
					$sql = "UPDATE sauce SET item_id = $itemId, sauce_name = '$arrSauceName[$i]', 
					sauce_code = 'S' WHERE sauce_id = $arrSauceId[$i]";
					if (!$conn->query($sql)) {
						echo json_encode(
							array(
								'status'  => 'error',
								'message' => 'Error in updating food item sauce',
								'error'   => mysqli_error($conn),
								'sql'     => $sql
							)
						);
						$conn->close();
						die;
					}
				// }
			} else {
				$sql = "INSERT INTO sauce(sauce_name, item_id, sauce_code)VALUES('$arrSauceName[$i]', $itemId, 'S')";
				if (!$conn->query($sql)) {
					echo json_encode(
						array(
							'status'  => 'error',
							'message' => 'Error in inserting food item sauce',
							'error'   => mysqli_error($conn),
							'sql'     => $sql
						)
					);
					$conn->close();
					die;
				}
			}
		}
	}
	if ($rSauceId != "") {
		# code...
		$sql = "DELETE FROM sauce WHERE item_id = $itemId AND 	sauce_id IN($rSauceId)";
		if (!$conn->query($sql)) {
			echo json_encode(
				array(
					'status'  => 'error',
					'message' => 'Error in deleting food item sauce',
					'error'   => mysqli_error($conn),
					'sql'     => $sql
				)
			);
			$conn->close();
			die;
		}
	} // sauce


	if ($sideDishName != "") {
		# code...
		$arrSideDishName = explode(",", $sideDishName);
		$arrSideDishId = explode(",", $sideDishId);
		for ($i=0; $i < sizeof($arrSideDishName); $i++) { 
			# code...
			// if ($sideDishId != "") {
			//  	# code...
		 	if(sizeof($arrSideDishId) > $i && $sideDishId != "") {
				$sql = "UPDATE side_dish SET item_id = $itemId, 
				side_dish_name = '$arrSideDishName[$i]', side_dish_code = 'SD' 
				WHERE side_dish_id = $arrSideDishId[$i]";

				if (!$conn->query($sql)) {
					echo json_encode(
						array(
							'status'  => 'error',
							'message' => 'Error in updating food item side_dish',
							'error'   => mysqli_error($conn),
							'sql'     => $sql
						)
					);
					$conn->close();
					die;
				}
			// }
			 } else {
				$sql = "INSERT INTO side_dish(side_dish_name, item_id, side_dish_code)VALUES(
				'$arrSideDishName[$i]', $itemId, 'SD')";
			
				if (!$conn->query($sql)) {
					echo json_encode(
						array(
							'status'  => 'error',
							'message' => 'Error in inserting food item side_dish',
							'error'   => mysqli_error($conn),
							'sql'     => $sql
						)
					);
					$conn->close();
					die;
				}
			}
		}
	}
	if ($rSideDishId != "") {
		# code...
		$sql = "DELETE FROM side_dish WHERE item_id = $itemId AND side_dish_id IN($rSideDishId)";
		if (!$conn->query($sql)) {
			echo json_encode(
				array(
					'status'  => 'error',
					'message' => 'Error in deleting food item side_dish',
					'error'   => mysqli_error($conn),
					'sql'     => $sql
				)
			);
			$conn->close();
			die;
		}
	}

	echo json_encode(
		array(
			'status' => 'ok',
			'message' => 'Successfully updated',
			'tableNumber' => $tableNumber,
			'sql'     => $sql
		)
	);
	$conn->close();