<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);

$companyTypeName = "aaass";

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

//ADD COMPANY TABLE 
if(strlen($companyTypeName) == 0) {
	$query = false;
} else {
	$table = TB_COMPANYTYPE;
	$data = array("companyTypeName"=>"Supplier_rawMaterial");
	$query = $db->add($table,$data);
}

#-> Preparing data for return.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$query = $db->querydb("SELECT * FROM ".TB_COMPANYTYPE." INNER JOIN ".TB_COMPANY." ON ".TB_COMPANY.".companyTypeID = ".TB_COMPANYTYPE.".companyTypeID");
	$i = 0;
	while($result = $db->fetch($query)) {
		$arr["data"][$i]["attributes"]["name"] = $result["companyTypeName"];
		$arr["data"][$i]["attributes"]["_id"] = $result["companyTypeID"];
		
		$arr["data"][$i]["relationships"]["companyID"]["id"] = $result["companyID"];
		$arr["data"][$i]["relationships"]["companyName"]["name"] = $result["companyName"];
		$arr["data"][$i]["relationships"]["companyAddress"]["address"] = $result["companyAddress"];
		$arr["data"][$i]["relationships"]["companyTel"]["telephone"] = $result["companyTel"];
		$i++;
	}
	// echo json_encode($arr);
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Company type name is missing values, failed to add";
}

#-> Return the data.
echo json_encode($arr);
	

#-> Close database.
$db->closedb();

?>