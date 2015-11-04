<?php
// if(eregi("config.php",$_SERVER['PHP_SELF'])) {
// 	header("Location: ../index.php");
// }

#-> config MySQL Connected
define ("DB_HOST","localhost");
define ("DB_USER","root");
define ("DB_PASS","root");
define ("DB_NAME","warehouse");

#-> Table

#define TB_BRANCH "branch";

define ("TB_BRANCH","branch");
define ("TB_COMPANY","company");
define ("TB_COMPANYTYPE","companyType");
define ("TB_ITEM","item");
define ("TB_ITEMTYPE","itemType");
define ("TB_ITEMBRANCH","itemBranch");
define ("TB_ORDER","order_list");
define ("TB_ORDERITEM","orderItem");
define ("TB_ORDERTYPE","orderType");
define ("TB_POSITION","position");
define ("TB_REGISTER","register");
define ("TB_REGISTERITEM","registerItem");
define ("TB_STAFF","staff");
define ("TB_STATUS","status");

?>