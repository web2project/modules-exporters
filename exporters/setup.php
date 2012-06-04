<?php /* $Id$ $URL$ */
if (!defined('W2P_BASE_DIR')){
	die('You should not access this file directly.');
}

/**
 *  Name: Project Exporter
 *  Directory: exporters
 *  Version 1.0
 *  Type: user
 *  UI Name: Project Exporter
 *  UI Icon: projectexporter.png
 */

$config = array();
$config['mod_name'] = 'Project Exporter'; // name the module
$config['mod_version'] = '1.0';           // add a version number
$config['mod_directory'] = 'exporters';   // tell dotProject where to find this module
$config['mod_setup_class'] = 'CSetupProjectExporter';  // the name of the PHP setup class (used below)
$config['mod_type'] = 'user';             // 'core' for modules distributed with dP by standard, 'user' for additional modules from dotmods
$config['mod_ui_name'] = $config['mod_name'];  // the name that is shown in the main menu of the User Interface
$config['mod_ui_icon'] = 'projectexporter.png';// name of a related icon
$config['mod_description'] = 'Export data from projects'; // some description of the module
$config['mod_config'] = false;            // show 'configure' link in viewmods

if (@$a == 'setup') {
	echo w2PshowModuleConfig( $config );
}

class CSetupProjectExporter 
{

	public function install() {
		global $AppUI;
		$perms = $AppUI->acl();
		return $perms->registerModule('Project Exporter', 'exporters');
	}

	public function configure() { return false; }

	public function remove() {
		global $AppUI;
		$perms = $AppUI->acl();
		return $perms->unregisterModule('exporters');
	}

	public function upgrade($old_version) { return false; }
}
