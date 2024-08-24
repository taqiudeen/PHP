<?php

	include 'tqcdb.php';

	$dbConn = mysqli_connect("$host", "$usr", "", "$db");
	
	@$id = $_GET['id'];
	
	$sql = "SELECT orders.Orderno, orders.CustID, orders.oDate, customers.Names, customers.Zip ";
	$sql.= "FROM orders ";
	$sql.= "JOIN customers ON orders.CustID = customers.CustID ";
	$sql.= "WHERE orders.OID = $id";
			
	$rs = $dbConn->query($sql);
	$frow = $rs->fetch_array(MYSQLI_ASSOC);
	
	

?>

<a href="custorders.php?id=<?php echo $frow["CustID"]; ?>">BACK</a><br><br>

<?php
	
	echo $frow["CustID"]."  ".$frow["Names"]."  ".$frow["Zip"]."<br><br>";
	
	echo " Order NO # ".$frow["Orderno"]."        Date: ".$frow["oDate"]."<br><br>";
	
	$sql = "SELECT invoices.OID, invoices.PartNo, parts.Price, parts.PartName ";
	$sql.= "FROM invoices ";
	$sql.= "JOIN parts ON parts.PartNo = invoices.PartNo ";
	$sql.= "WHERE invoices.OID = $id";
			
	$rs = $dbConn->query($sql);
	
	//$row = $rs->fetch_array(MYSQLI_ASSOC);
	
	//echo $row["invoices.OID"]."  ".$row["Names"]."  ".$row["parts.Price"]."<br>";
	
	//$sql = "SELECT * FROM `orders` WHERE CustID = $id ORDER BY oDate ";
	
	//$rs = $dbConn->query($sql);
	
	//https://www.mdmsoft.it/code-snippets/post/create-a-simple-invoice-template-in-php.aspx
?>



<?php
	$price = 0;
	while ($row = $rs->fetch_array(MYSQLI_ASSOC)) 
    	{
			$price = $price + $row["Price"];
?>
<a href="parts.php?id=<?php echo $row["PartNo"]; ?>"><?php echo $row["PartName"]." Price: $".$row["Price"]; ?> </a><br>
<?php			
			
		}
	
	
	echo "Order Total : $".$price;

?>