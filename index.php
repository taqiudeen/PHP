<?php
	
	include 'tqcdb.php';

	$dbConn = mysqli_connect("$host", "$usr", "", "$db");
	$sql = "SELECT Names, CustID FROM customers ORDER BY Names ";
			
	$rs = $dbConn->query($sql);
	
	while ($row = $rs->fetch_array(MYSQLI_ASSOC)) 
    	{
?>
<a href="custorders.php?id=<?php echo $row["CustID"]; ?>"><?php echo $row["Names"]; ?> </a><br>
<?php			
			
		}
	
	
	

?>

