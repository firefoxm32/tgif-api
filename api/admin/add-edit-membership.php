<?php 

	require_once("../config/DBConnection.php");

	$db = new DBConnection();
	$conn = $db->connect();

	$memberShipId = $_POST['membership_id'];
	$name = $_POST['name'];
	$address = $_POST['address'];
	$contactNum = $_POST['contact_number'];
	$membershipStatus = $_POST['membership_status'];
	$action = $_POST['action'];

	if($action == "add") {
		$sql = "INSERT INTO membership(member_id, name, address, membership_status, contact_number)VALUES(
			'$memberShipId', '$name', '$address', '$membershipStatus', '$contactNum')";	
	} else {
		$sql = "UPDATE membership SET name = '$name', address = '$address', 
				membership_status = '$membershipStatus',contact_number = '$contactNum'
			WHERE member_id = '$memberShipId'";
	}

	if (!$conn->query($sql)) {
		echo json_encode(
			array(
				'status'  => 'error',
				'message' => 'Error in saving member',
				'error'   => mysqli_error($conn),
				'sql'     => $sql
			)
		);
		die;
	}

	$conn->close();
	echo json_encode(
		array(
			'status' => 'ok',
			'message' => 'Membership save successfull',
			'sql'     => $sql
		)
	);
	die;	