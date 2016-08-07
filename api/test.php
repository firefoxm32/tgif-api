<?php 
$image = array(
	'name' => 'traditional wings',
	'items' => array('traditional_wings.jpg','traditional_wings2.jpg')
);

$serImage = serialize($image);

echo $serImage.'<br />';
echo '<pre>';
print_r(unserialize($serImage));
echo "</pre>";



?>