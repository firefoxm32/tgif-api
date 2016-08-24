<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$menuId = $_POST['category_id'];
	$menuName = $_POST['item_name'];
	$image = $_POST['image'];
	$description = $_POST['description'];
	$servingName = $_POST['serving_name'];
	$price = $_POST['price'];
	$sauceName = $_POST['sauce_name'];
	$sideDishName = $_POST['side_dish_name'];	

	$sql = "INSERT INTO `food_menu_items`(menu_id, menu_name, image, description)
		VALUES($menuId,'$menuName','$image','$description')";

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

	$itemId = $conn->insert_id;

	if($servingName != "") {
		$arrServinName = explode(",", $servingName);
		$arrPrice = explode(",", $price);
		for($i = 0; $i < sizeof($arrServinName); $i++) {
			$sql = "INSERT INTO food_servings(serving_name, fs_abbreviation)VALUES('$arrServinName[$i]', 'FS')";

			if (!$conn->query($sql)) {
				echo json_encode(
					array(
						'status'  => 'error',
						'message' => 'Error in saving food item serving',
						'error'   => mysqli_error($conn),
						'sql'     => $sql
					)
				);
				$conn->close();
				die;
			}
			$lastId = $conn->insert_id;

			$sqlPrice = "INSERT INTO food_price(serving_id, item_id, price)VALUES($lastId, $itemId, $arrPrice[$i])";
			
			if (!$conn->query($sqlPrice)) {
				echo json_encode(
					array(
						'status'  => 'error',
						'message' => 'Error in saving food item price',
						'error'   => mysqli_error($conn),
						'sql'     => $sql
					)
				);
				$conn->close();
				die;
			}
		}
	}

	if ($sauceName != "") {
		# code...
		$arrSauceName = explode(",", $sauceName);
		for($i = 0; $i < sizeof($arrSauceName); $i++) {
			$sql = "INSERT INTO sauce(sauce_name, item_id, s_abbreviation)VALUES('$arrSauceName[$i]', $itemId, 'S')";
			
			if (!$conn->query($sql)) {
				echo json_encode(
					array(
						'status'  => 'error',
						'message' => 'Error in saving food item sauce',
						'error'   => mysqli_error($conn),
						'sql'     => $sql
					)
				);
				$conn->close();
				die;
			}
		}
	}

	if ($sideDishName != "") {
		# code...
		$arrSideDishName = explode(",", $sideDishName);
		for ($i=0; $i < sizeof($arrSideDishName); $i++) { 
			# code...
			$sql = "INSERT INTO side_dish(side_dish_name, item_id, sd_abbreviation)VALUES(
				'$arrSideDishName[$i]', $itemId, 'SD')";
			
			if (!$conn->query($sql)) {
				echo json_encode(
					array(
						'status'  => 'error',
						'message' => 'Error in saving food item side_dish',
						'error'   => mysqli_error($conn),
						'sql'     => $sql
					)
				);
				$conn->close();
				die;
			}
		}
	}

	echo json_encode(
		array(
			'status' => 'ok',
			'message' => 'Save Successfull',
			'tableNumber' => $tableNumber,
			'sql'     => $sql,
			'sqlPrice' => $sqlPrice
		)
	);

	$conn->close();
