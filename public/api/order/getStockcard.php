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
$itemName = $json->itemName;


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Query the data.
$queryREG = $db->querydb("SELECT DATE(r.registerDate) AS regDate,SUM(i.costPerUnit) AS value, i.itemCode,r.registerCode,i.costPerUnit,ir.registerQuantity FROM   ".TB_BRANCH." b INNER JOIN ".TB_ITEMBRANCH." ib ON b.branchID=ib.branchID 
						INNER JOIN ".TB_REGISTER." r ON r.branchID=b.branchID 
						INNER JOIN ".TB_REGISTERITEM." ir ON r.registerID=ir.registerID
						INNER JOIN ".TB_ITEM." i ON ib.itemID=i.itemID 
						WHERE b.branchID=$branchID  AND i.itemName='$itemName' AND month(r.registerDate) = $month
						ORDER BY r.registerDate DESC;");
$queryINV = $db->querydb("SELECT DATE(o.deliverdDate) AS invDate ,SUM(i.costPerUnit) AS value, i.itemCode,o.invoiceCode,i.costPerUnit,io.orderQuantity FROM ".TB_BRANCH." b INNER JOIN itemBranch ib ON b.branchID=ib.branchID 
						INNER JOIN ".TB_ORDER." o ON o.branchID=b.branchID 
						INNER JOIN ".TB_ORDERITEM." io ON io.orderID=o.orderID 
						INNER JOIN ".TB_ITEM." i ON ib.itemID=i.itemID 
						WHERE b.branchID=$branchID  AND i.itemName='$itemName'  AND o.invoiceCode IS NOT NULL AND month(o.deliverdDate) = $month
						ORDER BY o.deliverdDate DESC;");
$arr = array();
if($queryREG && $queryINV) {
	$arr["status"] = "success";
	$i = 0;
	$qtyBAL = 0;
	$valueBAL = 0;
	$resultREG=$db->fetch($queryREG);
	$resultINV=$db->fetch($queryINV);
	$strREG = str_split($resultREG["regDate"]);
	$regDate = $strREG[8].$strREG[9];
	$strINV = str_split($resultINV["invDate"]);
	$invDate = $strINV[8].$strINV[9];
	while($resultREG || $resultINV){

		if($regDate && $invDate){

			if($regDate < $invDate){

				//Calculate balance
				$qtyBAL += $resultREG["registerQuantity"];
				$valueBAL += $resultREG["value"];
				//Store data
				$arr["data"][$i]["in"]["attributes"]["date"] = $result["regDate"];
				$arr["data"][$i]["in"]["attributes"]["code"] = $resultREG["registerCode"];
				$arr["data"][$i]["in"]["attributes"]["quantity"] = $resultREG["registerQuantity"];
				$arr["data"][$i]["in"]["attributes"]["cost"] = $resultREG["costPerUnit"];
				$arr["data"][$i]["in"]["attributes"]["value"] = $resultREG["value"];
				$arr["data"][$i]["out"]["attributes"]["date"] = NULL;
				$arr["data"][$i]["out"]["attributes"]["code"] = NULL;
				$arr["data"][$i]["out"]["attributes"]["quantity"] = NULL;
				$arr["data"][$i]["out"]["attributes"]["cost"] = NULL;
				$arr["data"][$i]["out"]["attributes"]["value"] = NULL;
				$arr["data"][$i]["balance"]["attributes"]["quantity"] = $qtyBAL;
				$arr["data"][$i]["balance"]["attributes"]["cost"] = $resultREG["costPerUnit"];
				$arr["data"][$i]["balance"]["attributes"]["value"] = $valueBAL;
				//Fetch next register data
				$resultREG=$db->fetch($queryREG);
				$strREG = str_split($resultREG["regDate"]);
				$regDate = $strREG[8].$strREG[9];
			}
			else if($regDate == $invDate){
	

				//Calculate balance
				$qtyBAL += $resultREG["registerQuantity"] - $resultINV["orderQuantity"];
				$valueBAL += $resultREG["value"] - $resultINV["value"];
				//Store data
				$arr["data"][$i]["in"]["attributes"]["date"] = $result["regDate"];
				$arr["data"][$i]["in"]["attributes"]["code"] = $resultREG["registerCode"];
				$arr["data"][$i]["in"]["attributes"]["quantity"] = $resultREG["registerQuantity"];
				$arr["data"][$i]["in"]["attributes"]["cost"] = $resultREG["costPerUnit"];
				$arr["data"][$i]["in"]["attributes"]["value"] = $resultREG["value"];
				$arr["data"][$i]["out"]["attributes"]["date"] = $result["invDate"];
				$arr["data"][$i]["out"]["attributes"]["code"] = $resultINV["invoiceCode"];
				$arr["data"][$i]["out"]["attributes"]["quantity"] = $resultINV["orderQuantity"];
				$arr["data"][$i]["out"]["attributes"]["cost"] = $resultINV["costPerUnit"];
				$arr["data"][$i]["out"]["attributes"]["value"] = $resultINV["value"];
				$arr["data"][$i]["balance"]["attributes"]["quantity"] = $qtyBAL;
				$arr["data"][$i]["balance"]["attributes"]["cost"] = $resultREG["costPerUnit"];
				$arr["data"][$i]["balance"]["attributes"]["value"] = $valueBAL;
				$resultREG=$db->fetch($queryREG);
				$resultINV=$db->fetch($queryINV);
				$strREG = str_split($resultREG["regDate"]);
				$regDate = $strREG[8].$strREG[9];
				$strINV = str_split($resultINV["invDate"]);
				$invDate = $strINV[8].$strINV[9];
			}
			else if($regDate > $invDate){
				
				//Calculate balance
				$qtyBAL -= $resultINV["orderQuantity"];
				$valueBAL -= $resultINV["value"];
				//Store data
				$arr["data"][$i]["in"]["attributes"]["date"] = NULL;
				$arr["data"][$i]["in"]["attributes"]["code"] = NULL;
				$arr["data"][$i]["in"]["attributes"]["quantity"] = NULL;
				$arr["data"][$i]["in"]["attributes"]["cost"] = NULL;
				$arr["data"][$i]["in"]["attributes"]["value"] = NULL;
				$arr["data"][$i]["out"]["attributes"]["date"] = $result["invDate"];
				$arr["data"][$i]["out"]["attributes"]["code"] = $resultINV["invoiceCode"];
				$arr["data"][$i]["out"]["attributes"]["quantity"] = $resultINV["orderQuantity"];
				$arr["data"][$i]["out"]["attributes"]["cost"] = $resultINV["costPerUnit"];
				$arr["data"][$i]["out"]["attributes"]["value"] = $resultINV["value"];
				$arr["data"][$i]["balance"]["attributes"]["quantity"] = $qtyBAL;
				$arr["data"][$i]["balance"]["attributes"]["cost"] = $resultREG["costPerUnit"];
				$arr["data"][$i]["balance"]["attributes"]["value"] = $valueBAL;
				//Fetch invoice data
				$resultINV=$db->fetch($queryINV);
				$strINV = str_split($resultINV["invDate"]);
				$invDate = $strINV[8].$strINV[9];
			}else{
				$arr["status"] = "Fail";
				$arr["message"] = "Does NOT pass through condition";
			}
		}
		else if(!$regDate && $invDate){
			//Calculate balance
			$qtyBAL -= $resultINV["orderQuantity"];
			$valueBAL -= $resultINV["value"];
			//Store data
			$arr["data"][$i]["in"]["attributes"]["date"] = NULL;
			$arr["data"][$i]["in"]["attributes"]["code"] = NULL;
			$arr["data"][$i]["in"]["attributes"]["quantity"] = NULL;
			$arr["data"][$i]["in"]["attributes"]["cost"] = NULL;
			$arr["data"][$i]["in"]["attributes"]["value"] = NULL;
			$arr["data"][$i]["out"]["attributes"]["date"] = $result["invDate"];
			$arr["data"][$i]["out"]["attributes"]["code"] = $resultINV["invoiceCode"];
			$arr["data"][$i]["out"]["attributes"]["quantity"] = $resultINV["orderQuantity"];
			$arr["data"][$i]["out"]["attributes"]["cost"] = $resultINV["costPerUnit"];
			$arr["data"][$i]["out"]["attributes"]["value"] = $resultINV["value"];
			$arr["data"][$i]["balance"]["attributes"]["quantity"] = $qtyBAL;
			$arr["data"][$i]["balance"]["attributes"]["cost"] = $resultREG["costPerUnit"];
			$arr["data"][$i]["balance"]["attributes"]["value"] = $valueBAL;
			//Fetch invoice data
			$resultINV=$db->fetch($queryINV);
			$strINV = str_split($resultINV["invDate"]);
			$invDate = $strINV[8].$strINV[9];
		}
		else if($regDate && !$invDate){
			//Calculate balance
			$qtyBAL += $resultREG["registerQuantity"];
			$valueBAL += $resultREG["value"];
			//Store data
			$arr["data"][$i]["in"]["attributes"]["date"] = $result["regDate"];
			$arr["data"][$i]["in"]["attributes"]["code"] = $resultREG["registerCode"];
			$arr["data"][$i]["in"]["attributes"]["quantity"] = $resultREG["registerQuantity"];
			$arr["data"][$i]["in"]["attributes"]["cost"] = $resultREG["costPerUnit"];
			$arr["data"][$i]["in"]["attributes"]["value"] = $resultREG["value"];
			$arr["data"][$i]["out"]["attributes"]["date"] = NULL;
			$arr["data"][$i]["out"]["attributes"]["code"] = NULL;
			$arr["data"][$i]["out"]["attributes"]["quantity"] = NULL;
			$arr["data"][$i]["out"]["attributes"]["cost"] = NULL;
			$arr["data"][$i]["out"]["attributes"]["value"] = NULL;
			$arr["data"][$i]["balance"]["attributes"]["quantity"] = $qtyBAL;
			$arr["data"][$i]["balance"]["attributes"]["cost"] = $resultREG["costPerUnit"];
			$arr["data"][$i]["balance"]["attributes"]["value"] = $valueBAL;
			//Fetch next register data
			$resultREG=$db->fetch($queryREG);
			$strREG = str_split($resultREG["regDate"]);
			$regDate = $strREG[8].$strREG[9];	
		}
		else {
		
			$arr["status"] = "error, result didn't fit to condition";
		}
		$i++;
	}
}
else {
	$arr["status"] = "error";
}


#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>