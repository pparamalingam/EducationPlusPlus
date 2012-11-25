<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of educationplusplus
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @subpackage educationplusplus
 * @copyright  2011 Husain Fazal, Preshoth Paramalingam, Robert Stancia
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/// (Replace educationplusplus with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
// Education++ Classes
require 'eppClasses/PointEarningScenario.php';
require 'eppClasses/Requirement.php';
require 'eppClasses/Activity.php';


$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // educationplusplus instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('educationplusplus', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $educationplusplus  = $DB->get_record('educationplusplus', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $educationplusplus  = $DB->get_record('educationplusplus', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $educationplusplus->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('educationplusplus', $educationplusplus->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

add_to_log($course->id, 'educationplusplus', 'createAPES', "createAPES.php?id={$cm->id}", $educationplusplus->name, $cm->id);

/// Print the page header

$PAGE->set_url('/mod/educationplusplus/createAPES.php', array('id' => $cm->id));
$PAGE->set_title(format_string($educationplusplus->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');
//$PAGE->add_body_class('educationplusplus-'.$somevar);

// Retrieve All Assignments to Display as Options for Requirements
// Retrieve from DB all Activities
global $DB;
$table = 'assign';
$result = $DB->get_records($table,array('course'=>$course->id));
$constructedSelectOptions;

// Output starts here
echo $OUTPUT->header();
if ($result){
	foreach ($result as $row){
		$constructedSelectOptions = $constructedSelectOptions . '\n<option value="' . $row->id . '">' . $row->name . '</option>';
		//var_dump($row);
		//array_push($arrayOfAssignNames, $row->name);
		//array_push($arrayOfAssignIDs, $row->id);
	}
}

if ($educationplusplus->intro) { // Conditions to show the intro can change to look for own settings or whatever
    echo $OUTPUT->box(format_module_intro('educationplusplus', $educationplusplus, $cm->id), 'generalbox mod_introbox', 'educationplusplusintro');
}

// Replace the following lines with you own code
echo $OUTPUT->heading('Education++');

echo $OUTPUT->box('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
<script>
counter = 0;
function addRequirements(){
	var newdiv = document.createElement(\'div\');

	newdiv.innerHTML = \'<select id="reqAct[]" name="reqAct[]" style="margin-left:50px;">' . $constructedSelectOptions . '</select><select id="reqCond[]" name="reqCond[]"><option value="0">Completed</option><option value="1">&gt;</option><option value="2">&gt;=</option><option value="3">=</option></select><input type="text" id="reqGradeToAchieve[]" name="reqGradeToAchieve[]" style="width:50px;text-align:right;" placeholder="%">\';
	counter++;

	document.getElementById("requirementsDIV").appendChild(newdiv);
}
addRequirements();
</script>

<div id="form" style="width:400px;height:400px;overflow:auto;">
	<form method="post" action="persistPES.php?id='. $cm->id .'" name="pes-creator" id="pes-creator" style="padding-left:10px;padding-right:10px;">
		<h3>Point Earning Scenario</h3>
		<table>
			<tr>
				<td style="width:100px">Name</td>
				<td><input type="text" style="margin-right:10px;width:200px;" id="pesName" name="pesName"></td>
			</tr>
			<tr>
				<td>Point Value</td>
				<td><input type="text" style="margin-right:10px;width:200px;" id="pesPointValue" name="pesPointValue"></td>
			</tr>
			<tr>
				<td>Expiry Date</td>
				<td><input type="date" style="margin-right:10px;width:200px;" id="pesExpiryDate" name="pesExpiryDate"></td>
			</tr>
			<tr>
				<td style="vertical-align:top;">Description</td>
				<td><textarea name="pesDescription" id="pesDescription" style="margin-right:10px;width:200px;" style></textarea></td>
			</tr>
		</table>
		<hr/>
		Requirement(s)<br/>
		<div id="requirementsDIV" name="requirementsDIV">	
			<select id="reqAct[]" name="reqAct[]" style="margin-left:50px;">' . $constructedSelectOptions . '</select><select id="reqCond[]" name="reqCond[]"><option value="0">Completed</option><option value="1">&gt;</option><option value="2">&gt;=</option><option value="3">=</option></select><input type="text" id="reqGradeToAchieve[]" name="reqGradeToAchieve[]" style="width:50px;text-align:right;" placeholder="%">
		</div>
		<br/><br/>
		<input name="Submit" type="button" style="margin: 0 auto; display:block; border:1px solid #000000; height:20px; padding-left:2px; padding-right:2px; padding-top:0px; padding-bottom:2px; line-height:14px; background-color:#EFEFEF;" onclick="addRequirements()" value="Add Another Requirement"/>
		<br/>
		<input name="Submit" type="submit" style="float:right; display:block; border:1px solid #000000; height:20px; padding-left:2px; padding-right:2px; padding-top:0px; padding-bottom:2px; line-height:14px; background-color:#EFEFEF;" value="Create New Point Earning Scenario"/>
	</form>
</div>');

// Finish the page
echo $OUTPUT->footer();
