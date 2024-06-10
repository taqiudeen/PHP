<?php

include '../usdb.php';

//db conn
$mysqli = new mysqli("$host", "$usr", "$pwd", "$db");
//db conn

	@$bino = $_GET['bino'];
	@$acct = $_GET['acct'];
	
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$BudgetItemName = $_POST['BudgetItemName'];
		$BudgetItemAmount = $_POST['BudgetItemAmount'];
		$OldAmount = $_POST['OldSavedAmount'];
		$SavedBudgetItemAmount = $_POST['SavedBudgetItemAmount'];
		$PayCheckNo = $_POST['PayCheckNo'];
		$DueDay = $_POST['DueDay'];
		$AccountNo = $_POST['AccountNo'];
		@$BudgetItemNo = $_POST['BudgetItemNo'];
		$Front = $_POST['Front'];
		$List = $_POST['list'];
		
		if(empty($Front)){$Front = 0;}else{$Front=1;}
		if(empty ($DueDay)){$DueDay = 0;}
		
		if(empty($BudgetItemNo)){	
			
			$sql  = " INSERT INTO budgetitems ";
        	$sql .= " (budgetitemname,budgetitemamount,paycheckno,budgetitemdueday,budgetitemacctno,budgetitemsaved,front) VALUES ";
        	$sql .= " ('$BudgetItemName','$BudgetItemAmount','$PayCheckNo','$DueDay','$AccountNo','$SavedBudgetItemAmount','$Front') ";
			
			if (!$mysqli->query($sql)) {
				printf("Errormessage: %s\n", $mysqli->error);
				echo $sql;
			}			
			
			$bino = $mysqli->insert_id;
			
			// insert new budget record //6/23/16 this is entered to track the amount saved each time
			$InsertSavedBudgetRecord =  " INSERT INTO budgetitemdeposits ";
			$InsertSavedBudgetRecord  .= " (depositno,budgetitemno,savedbudgetitemamount) VALUES ";
			$InsertSavedBudgetRecord  .= " (0,'$bino','$SavedBudgetItemAmount') ";
			
			$mysqli->query($InsertSavedBudgetRecord);

		}else{ // update budget item
		
			if($SavedBudgetItemAmount != $OldAmount){
					
					$bidep = $SavedBudgetItemAmount - $OldAmount;
			
					// insert new budget record tracked to show extra amount outside of a check
					$InsertSavedBudgetRecord =  " INSERT INTO budgetitemdeposits ";
        			$InsertSavedBudgetRecord  .= " (depositno,budgetitemno,savedbudgetitemamount) VALUES ";
        			$InsertSavedBudgetRecord  .= " (0,'$BudgetItemNo','$bidep') ";
					
					$mysqli->query($InsertSavedBudgetRecord);
			
			}
		
			//I am going to make a depoist before I update
			$UpdateBudgetItem = "UPDATE budgetitems ";
			$UpdateBudgetItem .= "SET budgetitemname='$BudgetItemName',budgetitemamount='$BudgetItemAmount',paycheckno='$PayCheckNo',budgetitemdueday='$DueDay', budgetitemsaved='$SavedBudgetItemAmount', front='$Front' ";
			$UpdateBudgetItem .= "WHERE budgetitemno= $BudgetItemNo ";
			
			$mysqli->query($UpdateBudgetItem);
			
		}//if update
		if(empty($List)){header("Location: ../accountdisplay.php?acct=$AccountNo");}else{header("Location: ../accountdisplay.php?bis=$AccountNo");
	}
		exit;  
	}//post
	
if(!empty($bino)){ //why 7// still don't know
    	$sql  = " SELECT * FROM budgetitems ";
    	$sql .= " Where budgetitemno = $bino";

    # execute SQL statement
    	$rs = $mysqli->query($sql);
   
    	while ($row = $rs->fetch_array(MYSQLI_ASSOC)) 
    	{
			$BudgetItemName = $row["budgetitemname"];
        	$BudgetItemSaved = $row["budgetitemsaved"];
			$BudgetItemAmount= $row["budgetitemamount"];
			$PayCheckNo = $row["paycheckno"];
			$BudgetItemDueDay = $row["budgetitemdueday"];
			$BudgetItemNo = $row["budgetitemno"];
			$Front = $row["front"];
    	}
	}
?>
<html>
<head>
<title><?php if(!empty($bino)){ ?>Update <?php }else{ ?>Add <?php } ?>Budget Item</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<form name="form1" method="post" action="">
  <table width="75%" border="0" align="center">
    <tr> 
      <td width="14%">Name</td>
      <td width="86%"><input name="BudgetItemName" type="text" id="BudgetItemName" size="50" maxlength="75"<?php if(!empty($bino)){?> value="<?php echo $BudgetItemName; ?>"<?php }?>>      </td>
    </tr>
    <tr> 
      <td>Amount Saved<br> </td>
      <td><input name="SavedBudgetItemAmount" type="text" id="SavedBudgetItemAmount" size="15" maxlength="15"<?php if(!empty($bino)){?> value="<?php echo $BudgetItemSaved; ?>"<?php }?>> 
         </td>
    </tr>
        <tr>
      <td>Amount to be Saved</td>
      <td><input name="BudgetItemAmount" type="text" id="BudgetItemAmount" size="15" maxlength="15"<?php if(!empty($bino)){?> value="<?php echo $BudgetItemAmount; ?>"<?php }?>> 
       </td>
    </tr>
    <tr> 
      <td>Paycheck</td>
      <td><select name="PayCheckNo" id="PayCheckNo">
      	  <option value="0"<?php if(!empty($bino) AND $PayCheckNo == 0){?> selected <?php }?>>Unschedulded</option>
          <option value="1"<?php if(!empty($bino) AND $PayCheckNo == 1){?> selected <?php }?>>First Paycheck</option>
          <option value="2"<?php if(!empty($bino) AND $PayCheckNo == 2){?> selected <?php }?>>Second Paycheck</option>
          <option value="3"<?php if(!empty($bino) AND $PayCheckNo == 3){?> selected <?php }?>>Every Paycheck</option>
        </select></td>
    </tr>
    <tr> 
      <td>Due Date</td>
    <td> <input name="DueDay" type="text" id="DueDay" size="4" maxlength="2"<?php if(!empty($bino)){?> value="<?php echo $BudgetItemDueDay; ?>"<?php }?>>    </tr>
    <tr>
      <td>Active 
       <input type="checkbox" name="Front"  value="true" <?php if($Front == 1){ ?>  checked <?php }?>></td>
      <td><table width="100%" border="0">
          <tr> 
            <td width="87%"><input name="AccountNo" type="hidden" value="<?php echo $acct; ?>"><?php if(!empty($bino)){ ?><input name="BudgetItemNo" type="hidden" id="BudgetItemNo" value="<?php echo $BudgetItemNo; ?>"><?php } ?></td>
            <td width="13%"><input type="submit" name="Submit" value="<?php if(!empty($bino)){ ?>Update <?php }else{ ?>Save <?php } ?>Budget Item"></td>
          </tr>
        </table></td>
    </tr>
  </table>
</form>
<?php
	if(!empty($bino)){
		$Loop = 0;
					$GetSavedBIs = "SELECT budgetitemdeposits.savedbudgetitemno, budgetitemdeposits.savedbudgetitemamount, deposits.depositdate, budgetitems.budgetitemname ";
					$GetSavedBIs .= "FROM budgetitemdeposits, deposits, budgetitems WHERE budgetitemdeposits.depositno = deposits.depositno AND budgetitemdeposits.budgetitemno = budgetitems.budgetitemno AND budgetitemdeposits.budgetitemno = $bino ORDER BY deposits.depositdate DESC LIMIT 0,10";
					//echo $GetSavedBIs;
					$SavedBiInfo = $mysqli->query($GetSavedBIs);
				
	
					while ($SavedBiRecord = $SavedBiInfo->fetch_array(MYSQLI_ASSOC) AND $Loop < 10 ) {
						$SavedBiDescription = $SavedBiRecord["budgetitemname"];
						$SavedBiAmount = $SavedBiRecord["savedbudgetitemamount"];
						$SavedBiNo = $SavedBiRecord["savedbudgetitemno"];
						$SavedBiDate = $SavedBiRecord["depositdate"];
						$SavedBiDate = substr($SavedBiDate,5,5);
?>
                    
<table width="75%" border="1" align="center" bgcolor="#CEFFCE">
  <tr> 
                        <td width="12%"><?php echo $SavedBiDate; ?></td>
                        <td width="47%"><div align="center"><a href="saved.php?bino=<?php echo $SavedBiNo; ?>"><?php echo $SavedBiDescription; ?></a></div></td>
                        <td width="13%"><div align="center">$<?php echo $SavedBiAmount; ?></div></td>
                      </tr>
                    </table>
<?php 		$Loop++;				
		}//deposit record while
	}//if
?>
</body>
</html>
