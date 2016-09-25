<?php 

    require_once("config/DBConnection.php");

    $db = new DBConnection();
    $conn = $db->connect();

    $param = $_GET['param'];

    $sql = "SELECT *
            FROM `food_items` fi
            WHERE fi.`item_id` = $param";
    $result = $conn->query($sql);
    //$servings = array();
    if ($result->num_rows > 0) {
        # code...
        // while ($row = $result->fetch_object()) {
            # code...
            //array('serving_name' => $row->serving_name,'price' => $row->price)
            //$servings[] = $row;
            $row = $result->fetch_object();
            $itemId = $row->item_id;
            $description = $row->description;
        // }
    } // Get item_id FROM food_menu_item

    $sql = "SELECT fp.`price`,fs.`serving_name`,fs.`serving_id` FROM `food_price` fp
            LEFT JOIN `food_serving` fs
            ON fp.`serving_id` = fs.`serving_id` WHERE fp.`item_id` = $itemId";    
    $result = $conn->query($sql);
    $servings = array();
    if ($result->num_rows > 0) {
        # code...
        while ($row = $result->fetch_object()) {
            # code...
            //array('serving_name' => $row->serving_name,'price' => $row->price)
            $servings[] = $row;
            //$itemId = $row->item_id;
        }
    } // Get price and serving_name


    // Get sauces for selected food_item
    $sql = "SELECT s.`sauce_name`, s.`sauce_id` FROM `sauce` s WHERE s.`item_id` = $itemId";

    $result = $conn->query($sql);

    $sauces = array();
    $saucesSerial = array();
    if ($result->num_rows > 0) {
        # code...
        while ($row = $result->fetch_object()) {
            # code...
            $sauces[] = $row; //array('sauce' => $row->sauce_name);
            $saucesSerial[] = $row->sauce_id;
        }
    } 

    // End of sauces

    // Get side dishes for selected food_item
    $sql = "SELECT sd.`side_dish_name`, sd.`side_dish_id` FROM `side_dish` sd WHERE sd.`item_id` = $itemId";

    $result = $conn->query($sql);

    $sideDishes = array();

    if ($result->num_rows > 0) {
        # code...
        while ($row = $result->fetch_object()) {
            # code...
            $sideDishes[] = $row;//array('dish_name' => $row->side_dish_name);
        }
    }

    // End of side dishes

    $item = new stdClass();
    $item->id = $itemId;
    $item->description = $description;
    $item->name = $param;
    $item->servings = $servings;
    $item->sauces = $sauces;
    $item->side_dishes = $sideDishes;

    $response = array(
        'status' => "ok",
        'item'  => (array)$item
    );
    echo json_encode($response);
    $conn->close();
 ?>
 
 <script type="text/javascript">
 var result = <?php echo json_encode($response); ?>
 </script>