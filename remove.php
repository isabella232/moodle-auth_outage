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
 * Delete an outage.
 *
 * @package    auth_outage
 * @author     Daniel Thee Roperto <daniel.roperto@catalyst-au.net>
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use \auth_outage\outage;
use \auth_outage\outageutils;
use \auth_outage\outagedb;
use \auth_outage\outagedeleteform;

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/formslib.php');

$renderer = outageutils::pagesetup();

$mform = new outagedeleteform();
if ($mform->is_cancelled()) {
    redirect('/auth/outage/list.php');
} else if ($fromform = $mform->get_data()) {
    outagedb::get()->delete($fromform->id);
    redirect('/auth/outage/list.php');
}

$id = required_param('id', PARAM_INT);
$outage = outagedb::get()->getbyid($id);
if ($outage == null) {
    throw new invalid_parameter_exception('Outage #' . $id . ' not found.');
}

$dataid = new stdClass();
$dataid->id = $outage->id;
$mform->set_data($dataid);

echo $OUTPUT->header();

echo $renderer->renderdeleteconfirmation($outage);

$mform->display();

echo $OUTPUT->footer();