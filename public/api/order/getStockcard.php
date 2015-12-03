<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");
date_default_timezone_set('Asia/Bangkok');
#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$branchID = $json->branch->_id;
$month = $json->month;
$itemInput = $json->itemName;


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);
#-> Query the data.

$queryREG = $db->querydb("SELECT DATE(r.registerDate) AS regDate,SUM(i.costPerUnit) AS value, i.itemCode,r.registerCode,i.costPerUnit,ir.registerQuantity FROM   ".TB_BRANCH." b INNER JOIN ".TB_ITEMBRANCH." ib ON b.branchID=ib.branchID 
						INNER JOIN ".TB_REGISTER." r ON r.branchID=b.branchID 
						INNER JOIN ".TB_REGISTERITEM." ir ON r.registerID=ir.registerID
						INNER JOIN ".TB_ITEM." i ON ir.itemID=i.itemID 
						WHERE b.branchID=$branchID  AND i.itemCode='$itemInput' AND month(r.registerDate) = $month
						ORDER BY r.registerDate DESC;");
$queryINV = $db->querydb("SELECT DATE(o.deliverdDate) AS invDate ,SUM(i.costPerUnit) AS value, i.itemCode,o.invoiceCode,i.costPerUnit,io.orderQuantity FROM ".TB_BRANCH." b INNER JOIN ".TB_ITEMBRANCH." ib ON b.branchID=ib.branchID 
						INNER JOIN ".TB_ORDER." o ON o.branchID=b.branchID 
						INNER JOIN ".TB_ORDERITEM." io ON io.orderID=o.orderID 
						INNER JOIN ".TB_ITEM." i ON io.itemID=i.itemID 
						WHERE b.branchID=$branchID  AND i.itemCode='$itemInput'  AND o.invoiceCode IS NOT NULL AND month(o.deliverdDate) = $month
						ORDER BY o.deliverdDate DESC;");
$arr = array();
// var_dump($arr);
if($queryREG && $queryINV) 
	{
	################ Initialize date ###################
	$arr["status"] = "success";
	$i = 0;
	$qtyBAL = 0;
	$valueBAL = 0;
	$getNextReg = 0;
	$getNextInv = 0;
	################ Fetch first date of each transaction ###################
	$resultREG=$db->fetch($queryREG);
	if($resultREG["regDate"]!= NULL)
		{
		$strREG = str_split($resultREG["regDate"]);
		$regDate = $strREG[8].$strREG[9];
		}	
	$resultINV=$db->fetch($queryINV);
	if($resultINV["invDate"]!= NULL)
		{
		$strINV = str_split($resultINV["invDate"]);
		$invDate = $strINV[8].$strINV[9];
		}
	################ Loop all month transaction ###################
	while($resultREG["regDate"]!=NULL || $resultINV["invDate"]!=NULL)
		{
		$regValue = $resultREG["costPerUnit"] * $resultREG["registerQuantity"];
		$invValue = $resultINV["costPerUnit"] * $resultINV["orderQuantity"];
		if($regDate!=NULL && $invDate!=NULL)
			{
			if($regDate < $invDate)
				{
				################ Calculate Balance ###################
				$qtyBAL += $resultREG["registerQuantity"];
				$valueBAL += $regValue;
				################ Store data in array ###################
				$arr["data"][$i]["in"]["attributes"]["date"] = $resultREG["regDate"];
				$arr["data"][$i]["in"]["attributes"]["code"] = $resultREG["registerCode"];
				$arr["data"][$i]["in"]["attributes"]["quantity"] = $resultREG["registerQuantity"];
				$arr["data"][$i]["in"]["attributes"]["cost"] = $resultREG["costPerUnit"];
				$arr["data"][$i]["in"]["attributes"]["value"] = $regValue;
				$arr["data"][$i]["out"]["attributes"]["date"] = "";
				$arr["data"][$i]["out"]["attributes"]["code"] = "";
				$arr["data"][$i]["out"]["attributes"]["quantity"] = "";
				$arr["data"][$i]["out"]["attributes"]["cost"] = "";
				$arr["data"][$i]["out"]["attributes"]["value"] = "";
				$arr["data"][$i]["balance"]["attributes"]["quantity"] = $qtyBAL;
				$arr["data"][$i]["balance"]["attributes"]["cost"] = $resultREG["costPerUnit"];
				$arr["data"][$i]["balance"]["attributes"]["value"] = $valueBAL;
				################ set fetch next data ###################
				$getNextReg = 1;
				################ get next array ###################
				$i++;
				}
			else if($regDate == $invDate)
				{
				################### 
				## Calculate REG ##
				###################
				################ Calculate Balance ###################
				$qtyBAL += $resultREG["registerQuantity"];
				$valueBAL += $regValue;
				################ Store data in array ###################
				$arr["data"][$i]["in"]["attributes"]["date"] = $resultREG["regDate"];
				$arr["data"][$i]["in"]["attributes"]["code"] = $resultREG["registerCode"];
				$arr["data"][$i]["in"]["attributes"]["quantity"] = $resultREG["registerQuantity"];
				$arr["data"][$i]["in"]["attributes"]["cost"] = $resultREG["costPerUnit"];
				$arr["data"][$i]["in"]["attributes"]["value"] = $regValue;
				$arr["data"][$i]["out"]["attributes"]["date"] = "";
				$arr["data"][$i]["out"]["attributes"]["code"] = "";
				$arr["data"][$i]["out"]["attributes"]["quantity"] = "";
				$arr["data"][$i]["out"]["attributes"]["cost"] = "";
				$arr["data"][$i]["out"]["attributes"]["value"] = "";
				$arr["data"][$i]["balance"]["attributes"]["quantity"] = $qtyBAL;
				$arr["data"][$i]["balance"]["attributes"]["cost"] = $resultREG["costPerUnit"];
				$arr["data"][$i]["balance"]["attributes"]["value"] = $valueBAL;
				################ set fetch next data ###################
				$getNextReg = 1;
				################ get next array ###################
				$i++;
				################### 
				## Calculate INV ##
				###################
				################ Calculate Balance ###################
				$qtyBAL -= $resultINV["orderQuantity"];
				$valueBAL -= $invValue;
				################ Store data in array ###################
				$arr["data"][$i]["in"]["attributes"]["date"] = "";
				$arr["data"][$i]["in"]["attributes"]["code"] = "";
				$arr["data"][$i]["in"]["attributes"]["quantity"] = "";
				$arr["data"][$i]["in"]["attributes"]["cost"] = "";
				$arr["data"][$i]["in"]["attributes"]["value"] = "";
				$arr["data"][$i]["out"]["attributes"]["date"] = $resultINV["invDate"];
				$arr["data"][$i]["out"]["attributes"]["code"] = $resultINV["invoiceCode"];
				$arr["data"][$i]["out"]["attributes"]["quantity"] = $resultINV["orderQuantity"];
				$arr["data"][$i]["out"]["attributes"]["cost"] = $resultINV["costPerUnit"];
				$arr["data"][$i]["out"]["attributes"]["value"] = $invValue;
				$arr["data"][$i]["balance"]["attributes"]["quantity"] = $qtyBAL;
				$arr["data"][$i]["balance"]["attributes"]["cost"] = $resultREG["costPerUnit"];
				$arr["data"][$i]["balance"]["attributes"]["value"] = $valueBAL;
				################ set fetch next data ###################
				$getNextInv = 1;
				################ get next array ###################
				$i++;
				}
			else if($regDate > $invDate)
				{
				################### 
				## Calculate INV ##
				###################
				################ Calculate Balance ###################
				$qtyBAL -= $resultINV["orderQuantity"];
				$valueBAL -= $invValue;
				################ Store data in array ###################
				$arr["data"][$i]["in"]["attributes"]["date"] = "";
				$arr["data"][$i]["in"]["attributes"]["code"] = "";
				$arr["data"][$i]["in"]["attributes"]["quantity"] = "";
				$arr["data"][$i]["in"]["attributes"]["cost"] = "";
				$arr["data"][$i]["in"]["attributes"]["value"] = "";
				$arr["data"][$i]["out"]["attributes"]["date"] = $resultINV["invDate"];
				$arr["data"][$i]["out"]["attributes"]["code"] = $resultINV["invoiceCode"];
				$arr["data"][$i]["out"]["attributes"]["quantity"] = $resultINV["orderQuantity"];
				$arr["data"][$i]["out"]["attributes"]["cost"] = $resultINV["costPerUnit"];
				$arr["data"][$i]["out"]["attributes"]["value"] = $invValue;
				$arr["data"][$i]["balance"]["attributes"]["quantity"] = $qtyBAL;
				$arr["data"][$i]["balance"]["attributes"]["cost"] = $resultREG["costPerUnit"];
				$arr["data"][$i]["balance"]["attributes"]["value"] = $valueBAL;
				################ set fetch next data ###################
				$getNextInv = 1;
				################ get next array ###################
				$i++;
				}
			}
		else if($regDate==NULL && $invDate!=NULL)
			{	
			################### 
			## Calculate INV ##
			###################
			################ Calculate Balance ###################
			$qtyBAL -= $resultINV["orderQuantity"];
			$valueBAL -= $invValue;
			################ Store data in array ###################
			$arr["data"][$i]["in"]["attributes"]["date"] = "";
			$arr["data"][$i]["in"]["attributes"]["code"] = "";
			$arr["data"][$i]["in"]["attributes"]["quantity"] = "";
			$arr["data"][$i]["in"]["attributes"]["cost"] = "";
			$arr["data"][$i]["in"]["attributes"]["value"] = "";
			$arr["data"][$i]["out"]["attributes"]["date"] = $resultINV["invDate"];
			$arr["data"][$i]["out"]["attributes"]["code"] = $resultINV["invoiceCode"];
			$arr["data"][$i]["out"]["attributes"]["quantity"] = $resultINV["orderQuantity"];
			$arr["data"][$i]["out"]["attributes"]["cost"] = $resultINV["costPerUnit"];
			$arr["data"][$i]["out"]["attributes"]["value"] = $invValue;
			$arr["data"][$i]["balance"]["attributes"]["quantity"] = $qtyBAL;
			$arr["data"][$i]["balance"]["attributes"]["cost"] = $resultREG["costPerUnit"];
			$arr["data"][$i]["balance"]["attributes"]["value"] = $valueBAL;
			################ set fetch next data ###################
			$getNextInv = 1;
			################ get next array ###################
			$i++;
			}
		else if($regDate!=NULL && $invDate==NULL)
			{
			################### 
			## Calculate REG ##
			###################
			################ Calculate Balance ###################
			$qtyBAL += $resultREG["registerQuantity"];
			$valueBAL += $regValue;
			################ Store data in array ###################
			$arr["data"][$i]["in"]["attributes"]["date"] = $resultREG["regDate"];
			$arr["data"][$i]["in"]["attributes"]["code"] = $resultREG["registerCode"];
			$arr["data"][$i]["in"]["attributes"]["quantity"] = $resultREG["registerQuantity"];
			$arr["data"][$i]["in"]["attributes"]["cost"] = $resultREG["costPerUnit"];
			$arr["data"][$i]["in"]["attributes"]["value"] = $regValue;
			$arr["data"][$i]["out"]["attributes"]["date"] = "";
			$arr["data"][$i]["out"]["attributes"]["code"] = "";
			$arr["data"][$i]["out"]["attributes"]["quantity"] = "";
			$arr["data"][$i]["out"]["attributes"]["cost"] = "";
			$arr["data"][$i]["out"]["attributes"]["value"] = "";
			$arr["data"][$i]["balance"]["attributes"]["quantity"] = $qtyBAL;
			$arr["data"][$i]["balance"]["attributes"]["cost"] = $resultREG["costPerUnit"];
			$arr["data"][$i]["balance"]["attributes"]["value"] = $valueBAL;
			################ set fetch next data ###################
			$getNextReg = 1;
			################ get next array ###################
			$i++;
			}

		################ Fetch Next data ###################
		if($getNextReg == 1)
			{
			$resultREG=$db->fetch($queryREG);
			if($resultREG["regDate"]!= NULL)
				{
				$strREG = str_split($resultREG["regDate"]);
				$regDate = $strREG[8].$strREG[9];
				}
			$getNextReg = 0;	
			}
		if($getNextInv == 1)
			{
			$resultINV=$db->fetch($queryINV);
			if($resultINV["invDate"]!= NULL)
				{
				$strINV = str_split($resultINV["invDate"]);
				$invDate = $strINV[8].$strINV[9];
				}
			$getNextInv = 0;	
			}
		}
	}
#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>