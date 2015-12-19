<?php /* $Id$ $URL$ */
if (!defined('W2P_BASE_DIR')) {
	die('You should not access this file directly.');
}

global $AppUI, $canRead, $m, $cfg;

require_once( $AppUI->getLibraryClass( 'PEAR/Date/Calc' ) );

if (!$canRead) {
	$AppUI->redirect("m=public&a=access_denied");
}

$projects = array('0' => $AppUI->_('None', UI_OUTPUT_RAW));

$projectObj = new CProject();
$projectList = $projectObj->getAllowedProjects($AppUI->user_id, false);

$company = new CCompany();
$companyList = $company->getCompanies($AppUI);

foreach ($projectList as $pr) {
    $projectslist[$pr['project_id']] = $companyList[$pr['project_company']]['company_name'] . ': ' . $pr['project_name'];
}
asort($projectslist);
$projects = arrayMerge(array('0' => $AppUI->_('(None)', UI_OUTPUT_RAW)), $projectslist);

setLocale(LC_ALL, 'C');
$fystartdate = array_map(array($AppUI, '_'), Date_Calc::getMonthNames());
$msproject = array(
  '2003' => $AppUI->_('MS Project 2003'), 
  '2007' => $AppUI->_('MS Project 2007'),
  '2010' => $AppUI->_('MS Project 2010')
);
$defaulttasktype = array(
  $AppUI->_('Fixed Units'), 
  $AppUI->_('Fixed Duration'), 
  $AppUI->_('Fixed Work')
);
$defaultfixedcostaccrual = array(
  '1' => $AppUI->_('Start'), 
  '2' => $AppUI->_('Prorated'), 
  '3' => $AppUI->_('End')
);
$durationformat = array(
  '3' => $AppUI->_('Minutes'),
  '4' => $AppUI->_('Elapsed minutes'), 
  '5' => $AppUI->_('Hours'), 
  '6' => $AppUI->_('Elapsed hours'), 
  '7' => $AppUI->_('Days'), 
  '8' => $AppUI->_('Elapsed days'), 
  '9' => $AppUI->_('Weeks'), 
  '10' => $AppUI->_('Elapsed weeks'), 
  '11' => $AppUI->_('Months'), 
  '12' => $AppUI->_('Elapsed months'), 
  '19' => $AppUI->_('Percentage'), 
  '20' => $AppUI->_('Elapsed percentage'), 
  '21' => $AppUI->_('No value'), 
  '35' => $AppUI->_('Estimated minutes'), 
  '36' => $AppUI->_('Elapsed estimated minutes'), 
  '37' => $AppUI->_('Estimated hours'), 
  '38' => $AppUI->_('Elapsed estimated hours'), 
  '39' => $AppUI->_('Estimated days'), 
  '40' => $AppUI->_('Elapsed estimated days'), 
  '41' => $AppUI->_('Estimated weeks'), 
  '42' => $AppUI->_('Elapsed estimated weeks'), 
  '43' => $AppUI->_('Estimated months'), 
  '44' => $AppUI->_('Elapsed estimated months'), 
  '51' => $AppUI->_('Estimated percentage'), 
  '52' => $AppUI->_('Elapsed estimated percentage'), 
  '53' => $AppUI->_('No display format')
);
$workformat = array(
  '1' => $AppUI->_('Minutes'),
  '2' => $AppUI->_('Hours'), 
  '3' => $AppUI->_('Days'), 
  '4' => $AppUI->_('Weeks'), 
  '5' => $AppUI->_('Months'), 
);
$newtaskstartdate = array(
  $AppUI->_('Project Start Date'), 
  $AppUI->_('Current Date'), 
);
$defaulttaskevmethod = array(
  '0' => $AppUI->_('% Complete'), 
  '1' => $AppUI->_('Physical % Complete'), 
);
?>

<script language="javascript">
  function submitIt() {
    var f = document.frm;
    var msg ='';
    if (f.project_id.value == 0) {
      msg += '<?php echo $AppUI->_('You must select a project first', UI_OUTPUT_JS); ?>';
      f.project_id.focus();
    }
    
    if (msg.length < 1) {
      f.submit();
    } else {
      alert(msg);
    }
  }
</script>

<form name="frm" action="?m=<?php echo $m; ?>&a=export&suppressHeaders=1" method="post">
<table>
  <tr>
    <td nowrap="nowrap" style="border: outset #eeeeee 1px;background-color:#fffff" >
      <font color="<?php echo bestColor('#ffffff'); ?>">
        <strong>
        <?php echo $AppUI->_('Project'); ?>: 
        <?php echo arraySelect($projects, 'project_id', ' class="text" style="width:500px"', 0); ?>
        </strong>
      </font>
    </td>
    <td><?php echo $AppUI->_('Format');?></td>
    <td><?php echo arraySelect($msproject, 'msproject', ' class="text"', 2003); ?></td>
    <td><?php echo $AppUI->_("Filename (without extension):"); ?></td>
    <td><input type="text" name="sql_file" size="20" /></td>
    <td><input type="checkbox" name="zipped" value="1" /><?php echo $AppUI->_("zipped"); ?></td>
  </tr>
</table>
<br />
<input type="reset" value="<?php echo $AppUI->_('clear');?>" class="button" />
<input type="button" value="<?php echo $AppUI->_('Export project');?>" class="button" onclick="submitIt()"/>
<fieldset>
  <legend><?php echo $AppUI->_('Options');?></legend>
  <table>
    <tr>
      <td><?php echo $AppUI->_('Fiscal year start date');?></td>
      <td><?php echo arraySelect($fystartdate, 'FYStartDate', ' class="text"', 1); ?></td>
      <td><?php echo $AppUI->_('The month the fiscal year begins');?></td>
     </tr>
     <tr>
      <td><?php echo $AppUI->_('Default task type');?></td>
      <td><?php echo arraySelect($defaulttasktype, 'DefaultTaskType', ' class="text"', 0); ?></td>
      <td><?php echo $AppUI->_('The default type for all new tasks in the project');?></td>
    </tr>
    <tr>
      <td><?php echo $AppUI->_('Default fixed cost accrual');?></td>
      <td><?php echo arraySelect($defaultfixedcostaccrual, 'DefaultFixedCostAccrual', ' class="text"', 3); ?></td>
      <td><?php echo $AppUI->_('The default measuring point when fixed costs are accrued');?></td>
    </tr>
    <tr>
      <td><?php echo $AppUI->_('Default standard rate');?></td>
      <td><input type="text" name="DefaultStandardRate" size="8" value="0"/></td>
      <td><?php echo $AppUI->_('The default standard rate for new resources');?></td>
    </tr>
    <tr>
      <td><?php echo $AppUI->_('Default overtime rate');?></td>
      <td><input type="text" name="DefaultOvertimeRate" size="8" value="0"/></td>
      <td><?php echo $AppUI->_('The default overtime rate for new resources');?></td>
    </tr>
    <tr>
      <td><?php echo $AppUI->_('Duration format');?></td>
      <td><?php echo arraySelect($durationformat, 'DurationFormat', ' class="text"', 5); ?></td>
      <td><?php echo $AppUI->_('The default format for all durations in the project');?></td>
    </tr>
    <tr>
      <td><?php echo $AppUI->_('Work format');?></td>
      <td><?php echo arraySelect($workformat, 'WorkFormat', ' class="text"', 2); ?></td>
      <td><?php echo $AppUI->_('The default format for all work durations in the project');?></td>
    </tr>
    <tr>
      <td><?php echo $AppUI->_('New tasks effort driven');?></td>
      <td><input type="checkbox" name="NewTasksEffortDriven" value="1" checked="checked"/></td>
      <td><?php echo $AppUI->_('Indicates whether new tasks are effort-driven');?></td>
    </tr>
    <tr>
      <td><?php echo $AppUI->_('New tasks estimated');?></td>
      <td><input type="checkbox" name="NewTasksEstimated" value="1" checked="checked"/></td>
      <td><?php echo $AppUI->_('Indicates whether new tasks have estimated durations');?></td>
    </tr>
    <tr>
      <td><?php echo $AppUI->_('Auto add new resources');?></td>
      <td><input type="checkbox" name="AutoAddNewResourcesAndTasks" value="1" checked="checked"/></td>
      <td><?php echo $AppUI->_('Indicates whether to automatically add new resources to the resource pool');?></td>
    </tr>
    <tr>
      <td><?php echo $AppUI->_('New task start date');?></td>
      <td><?php echo arraySelect($newtaskstartdate, 'NewTaskStartDate', ' class="text"', 2); ?></td>
      <td><?php echo $AppUI->_('The default start date for a new task');?></td>
    </tr>
    <tr>
      <td><?php echo $AppUI->_('Autolink inserted or moved tasks');?></td>
      <td><input type="checkbox" name="Autolink" value="1"/></td>
      <td><?php echo $AppUI->_('Indicates whether to autolink inserted or moved tasks');?></td>
    </tr>
    <tr>
      <td><?php echo $AppUI->_('Default Task Earned Value Method');?></td>
      <td><?php echo arraySelect($defaulttaskevmethod, 'DefaultTaskEVMethod', ' class="text"', 0); ?></td>
      <td><?php echo $AppUI->_('The default earned value method for tasks');?></td>
    </tr>
  </table>
</fieldset>
</form>
