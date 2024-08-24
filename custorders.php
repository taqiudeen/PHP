<a href="index.php">BACK</a><br><br>

<?php
	
	include 'tqcdb.php';

	$dbConn = mysqli_connect("$host", "$usr", "", "$db");
	
	@$id = $_GET['id'];
	
	$sql = "SELECT * FROM customers WHERE CustID = $id";
			
	 $rs = $dbConn->query($sql);
	
	 $row = $rs->fetch_array(MYSQLI_ASSOC);
	
	echo $row["CustID"]."  ".$row["Names"]."  ".$row["Zip"]."<br><br>";
	
	//$sql = "SELECT * FROM `orders` WHERE CustID = $id ORDER BY oDate ";
	
	//$sql = " SELECT * FROM `customers` JOIN orders ON orders.CustID = customers.CustID ";
	//$sql .= "WHERE orders.CustID = $id ORDER BY orders.oDate ";
	$sql = "SELECT orders.OID, orders.OrderNo, orders.oDate,( ";
	$sql .= "SELECT SUM(parts.Price) AS t  ";
    $sql .= "FROM parts  ";
    $sql .= "JOIN invoices ON invoices.PartNo = parts.PartNo  ";
	$sql .= "WHERE invoices.OID = orders.OID  ";
	$sql .= ") as oTotal ";
	$sql .= "FROM orders ";
	$sql .= "WHERE CustID = $id ";
	$sql .= "ORDER BY oDate; ";
		
	$rs = $dbConn->query($sql);
	
	//$row = $rs->fetch_array(MYSQLI_ASSOC);
	
	
?>



<?php
	
	$count = 0;
	while ($row = $rs->fetch_array(MYSQLI_ASSOC)) 
    	{
			//if($count == 0){ echo $row["CustID"]."  ".$row["Names"]."  ".$row["Zip"]."<br><br>";}
?>
<a href="orders.php?id=<?php echo $row["OID"]; ?>"><?php echo $row["oTotal"]."  ".$row["oDate"]."  ".$row["OrderNo"]; ?> </a><br>
<?php			
		$count++;	
		}
	
	
	

?>