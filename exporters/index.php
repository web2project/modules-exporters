<?php /* $Id$ $URL$ */
if (!defined('W2P_BASE_DIR')){
	die('You should not access this file directly.');
}

$canRead = canView($m);
$canEdit = canEdit($m);
if (!$canRead) {
	$AppUI->redirect("m=public&a=access_denied");
}

$tab = (int) w2PgetParam($_GET, "tab", 0);
$AppUI->setState("BackupIdxTab", $tab);

$titleBlock = new CTitleBlock('Project Exporter', 'projectexporter.png', $m, "$m.$a");
$titleBlock->show();

$tabBox = new CTabBox("?m=$m", W2P_BASE_DIR . "/modules/$m/", $tab);
if ($canEdit) {
	$tabBox->add('vw_idx_export', $AppUI->_('Export'));
}
$tabBox->show();
