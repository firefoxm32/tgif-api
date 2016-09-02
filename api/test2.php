<?php 
	
	$fName = array('Erick','Erick','Erick','Erick');
	$mName = array('V','V','V','V');
	$lName = array('Laxamana','Laxamana','Laxamana','Laxamana');

	echo '<table cellpadding="0" cellspacing="0" border="1px">';
	echo '<tr><th>FName</th><th>MName</th><th>LName</th></tr>';

	for ($i=0; $i < sizeof($fName); $i++) { 
		# code...
		echo '<tr>';
		// for ($j=0; $j < 1; $j++) { 
			# code...
			echo '<td>'.$fName[$i].'</td>';
			echo '<td>'.$mName[$i].'</td>';
			echo '<td>'.$lName[$i].'</td>';
		// }
		echo '</tr>';
	}

	echo "</table>";
 ?>