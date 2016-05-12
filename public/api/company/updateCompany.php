<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$companyID = $json->company->attributes->_id;
$companyName = $json->company->attributes->name;
$companyAddress = $json->company->attributes->address;
$companyTel = $json->company->attributes->telephone;
$companyTypeID = $json->company->relationships->companyType->attributes->_id;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> UPDATE COMPANY TABLE 
$table = TB_COMPANY;
$data = array("companyName" => $companyName,
			"companyAddress" => $companyAddress,
			"companyTel" => $companyTel,
			"companyTypeID" => $companyTypeID);
$where = "companyID = $companyID";
$query = $db->update($table,$data,$where);

#-> Preparing the data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$query = $db->querydb("SELECT * FROM ".TB_COMPANY." INNER JOIN ".TB_COMPANYTYPE." ON ".TB_COMPANY.".companyTypeID = ".TB_COMPANYTYPE.".companyTypeID");
	$i = 0;
	while($result = $db->fetch($query)) {
		$arr["data"]["attributes"]["id"] = $result["companyID"];
		$arr["data"]["attributes"]["name"] = $result["companyName"];
		$arr["data"]["attributes"]["address"] = $result["companyAddress"];
		$arr["data"]["attributes"]["telephone"] = $result["companyTel"];
		
		$arr["data"]["relationships"]["companyType"]["name"] = $result["companyTypeName"];
		$arr["data"]["relationships"]["companyType"]["_id"] = $result["companyTypeID"];
		$i++;
	}
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Failed to update";
}

#-> Return json data.
echo json_encode($arr,JSON_NUMERIC_CHECK);

#-> Close database.
$db->closedb();

?>