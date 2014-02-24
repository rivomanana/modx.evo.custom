<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('new_template')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id=$_GET['id'];

// duplicate template
$newid = $modx->db->insert(
	array(
		'templatename'=>'',
		'description'=>'',
		'content'=>'',
		'category'=>'',
		), $modx->getFullTableName('site_templates'), // Insert into
	"CONCAT('Duplicate of ',templatename) AS templatename, description, content, category", $modx->getFullTableName('site_templates'), "id='{$id}'"); // Copy from

// duplicate TV values
$modx->db->insert(
	array(
		'tmplvarid'=>'',
		'templateid'=>'',
		'rank'=>'',
		), $modx->getFullTableName('site_tmplvar_templates'), // Insert into
	"tmplvarid, '{$newid}', rank", $modx->getFullTableName('site_tmplvar_templates'), "templateid='{$id}'"); // Copy from

// Set the item name for logger
$name = $modx->db->getValue($modx->db->select('templatename', $modx->getFullTableName('site_templates'), "id='{$newid}'"));
$_SESSION['itemname'] = $name;

// finish duplicating - redirect to new template
$header="Location: index.php?r=2&a=16&id=$newid";
header($header);
?>