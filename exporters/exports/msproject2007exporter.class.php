<?php /* $Id$ $URL$ */
if (!defined('W2P_BASE_DIR')){
	die('You should not access this file directly.');
}

require_once "exporter.class.php";

class MsProject2007Exporter extends Exporter
{

  public function __construct($file, $options = array())
  {
    parent::__construct('2007', $file, $options);
  }

}
