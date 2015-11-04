<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

// IF ADD: 		$query = $db->add($table,$data)
// IF UPDATE:   $query = $db->update($table,$data,$where)
// IF DELETE:	$query = $db->delete($table,$where)
// IF QUERY: 	$query = $db->querydb("QUERY STATEMENT");
//
// SEE MORE ./includes/class_mysql.php

//ADD COMPANY TABLE 
$table = TB_COMPANY;
$data = array("companyName"=>"Ocean Bank","companyAddress"=>"High Road NY 89024","companyTel"=>"022-688-51","companyTypeID"=>1);
$query = $db->add($table,$data);
// var_dump($query);
$arr = array();
if($query)
	{
		$query = $db->querydb("SELECT * FROM ".TB_COMPANY." INNER JOIN ".TB_COMPANYTYPE." ON ".TB_COMPANY.".companyTypeID = ".TB_COMPANYTYPE.".companyTypeID");
		$result = $db->fetch($query);
		$arr["data"]["attributes"]["id"] = $result["companyID"];
		$arr["data"]["attributes"]["name"] = $result["companyName"];
		$arr["data"]["attributes"]["address"] = $result["companyAddress"];
		$arr["data"]["attributes"]["telephone"] = $result["companyTel"];
		$arr["data"]["relations"]["companyType"]["name"] = $result["companyTypeName"];
	}

//UPDATE COMPANY TABLE 
$table = TB_COMPANY;
$data = array("companyName"=>"OCEAN Bank","companyAddress"=>"High Road NY 89024","companyTel"=>"022-688-51","companyTypeID"=>1);
$where = "companyID=1";
$query = $db->update($table,$data,$where);
$arr = array();
if($query)
	{
		$query = $db->querydb("SELECT * FROM ".TB_COMPANY." INNER JOIN ".TB_COMPANYTYPE." ON ".TB_COMPANY.".companyTypeID = ".TB_COMPANYTYPE.".companyTypeID");
		while($result = $db->fetch($query)) {
			$arr["data"][$i]["attributes"]["id"] = $result["companyID"];
			$arr["data"][$i]["attributes"]["name"] = $result["companyName"];
			$arr["data"][$i]["attributes"]["address"] = $result["companyAddress"];
			$arr["data"][$i]["attributes"]["telephone"] = $result["companyTel"];
			$arr["data"][$i]["relations"]["companyType"]["name"] = $result["companyTypeName"];
			$i++;
		}
	}


//DELETE COMPANY TABLE 
$table = TB_COMPANY;
$where = "companyID=7";
$db->delete($table,$where);

//QUERY COMPANY TABLE 
$query = $db->querydb("SELECT * FROM ".TB_COMPANY." INNER JOIN ".TB_COMPANYTYPE." ON ".TB_COMPANY.".companyTypeID = ".TB_COMPANYTYPE.".companyTypeID");

//-------------------------------------------------------------

//ADD COMPANYTYPE TABLE 
$table = TB_COMPANYTYPE;
$data = array("companyTypeName"=>"Supplier_rawMaterial");
$query = $db->add($table,$data);
// var_dump($query);
$arr = array();
if($query)
	{
		$query = $db->querydb("SELECT * FROM ".TB_COMPANY." INNER JOIN ".TB_COMPANYTYPE." ON ".TB_COMPANY.".companyTypeID = ".TB_COMPANYTYPE.".companyTypeID");
		$result = $db->fetch($query);
		$arr["data"]["attributes"]["id"] = $result["companyID"];
		$arr["data"]["attributes"]["name3"] = $result["companyName"];
		$arr["data"]["attributes"]["address"] = $result["companyAddress"];
		$arr["data"]["attributes"]["telephone"] = $result["companyTel"];
		$arr["data"]["relations"]["companyType"]["name"] = $result["companyTypeName"];
	}

//UPDATE COMPANYTYPE TABLE 
$table = TB_COMPANYTYPE;
$data = array("companyTypeName"=>"Supplier");
$where = "companyTypeID=3";
$query = $db->update($table,$data,$where);
if($query)
	{
		$query = $db->querydb("SELECT * FROM ".TB_COMPANY." INNER JOIN ".TB_COMPANYTYPE." ON ".TB_COMPANY.".companyTypeID = ".TB_COMPANYTYPE.".companyTypeID");
		while($result = $db->fetch($query)) {
			$arr["data"][$i]["attributes"]["id"] = $result["companyID"];
			$arr["data"][$i]["attributes"]["name"] = $result["companyName"];
			$arr["data"][$i]["attributes"]["address"] = $result["companyAddress"];
			$arr["data"][$i]["attributes"]["telephone"] = $result["companyTel"];
			$arr["data"][$i]["relations"]["companyType"]["name"] = $result["companyTypeName"];
			$i++;
		}
	}


//DELETE COMPANYTYPE TABLE 
$table = TB_COMPANYTYPE;
$where = "companyTypeID=3";
$db->delete($table,$where);

//QUERY COMPANYTYPE TABLE 
$table = TB_COMPANYTYPE;
$query = $db->querydb("SELECT * FROM ".TB_COMPANY." INNER JOIN ".TB_COMPANYTYPE." ON ".TB_COMPANY.".companyTypeID = ".TB_COMPANYTYPE.".companyTypeID");

//var_dump ($query); -> work: bool(true), not work:  bool()false)
// $arr = array();

#-> Preparing return data.
/*************** JSON SHOULD BE *******************
**
** {
**	 status: "success or error",
**   messages: "error messages",
**   data: {
**     attributes: {
**        columns1: data1,
**        columns2: data2,
**		  ..
**	   }
**	   relations: {
**		  tables1: {
**			columns1: data1,
**			columns2: data2,
**			..
**		  },
**		  tables2: {
**			columns1: data1,
**			columns2: data2,
**			..
**		  }
**	   }
**   }	
** }
**
***************************************************/
/*if($query) {
	$result = $db->fetch($query);
	$arr["status"] = "success";
	// ASSIGN DATA TO ARRAY
	} 
	else {
	$arr["status"] = "success";
	// IF NO RESULT
	}*/

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>