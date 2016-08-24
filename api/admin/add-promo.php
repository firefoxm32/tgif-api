<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$itemId = $_POST['item_id'];
	$promoPrice = $_POST['promo_price'];
	$promoStatus = $_POST['promo_status'];

	$sql = "INSERT INTO promo(item_id, promo_price, promo_status)VALUES(
		$itemId, $promoPrice, '$promoStatus')";

