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
 * Kent links block
 *
 * @package   blocks
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_kent_links extends block_list {
    /**
     * block initializations
     */
    public function init() {
        $this->title = "Kent admin links";
    }

    /**
     * block contents
     *
     * @return object
     */
    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new \stdClass();
        $this->content->icons = null;
        $this->content->items = array();
        $this->content->footer = '';

        if (isguestuser() || !isloggedin()) {
            return $this->content;
        }

        $links = $this->get_links();
        if (empty($links)) {
            return $this->content;
        }

        foreach ($links as $k => $link) {
            $link = \html_writer::tag('a', $k, array(
                'href' => $link
            ));

            $this->content->items[] = $link;
        }

        return $this->content;
    }

    /**
     * Returns a list of admin links.
     */
    public function get_links() {
        global $DB, $USER;

        if (isset($USER->profile['kentacctype']) && $USER->profile['kentacctype'] !== 'staff') {
            return array();
        }

        $links = array();
        $ctx = \context_system::instance();
        $isadmin = has_capability('moodle/site:config', $ctx);

        // Add the rollover links.
        if ($isadmin || \local_kent\User::has_course_update_role($USER->id)) {
            $rolloveradminpath = new \moodle_url("/local/rollover/");
            $links["Rollover admin"] = $rolloveradminpath;
        }

        // Add dep admin links.
        if ($isadmin ||  \local_kent\User::is_dep_admin($USER->id)) {
            $connectadminpath = new \moodle_url("/local/connect/");
            $links["DA pages"] = $connectadminpath;

            $metaadminpath = new \moodle_url("/admin/tool/meta");
            $links["Meta enrolments"] = $metaadminpath;
        }

        // Add CLA links.
        if ($isadmin || has_capability('mod/cla:manage', $ctx)) {
            $clapath = new \moodle_url('/mod/cla/admin.php');
            $links["CLA administration"] = $clapath;
        }

        return $links;
    }

    /**
     * Returns the role that best describes the KCL block... 'navigation'
     *
     * @return string 'navigation'
     */
    public function get_aria_role() {
        return 'navigation';
    }
}
