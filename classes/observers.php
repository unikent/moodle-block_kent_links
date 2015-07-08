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
 * Kent Admin Link Block
 *
 * @package    blocks_kent_links
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_kent_links;

defined('MOODLE_INTERNAL') || die();

/**
 * Kent Course Overview observers
 */
class observers {
    /**
     * Triggered when an enrolment or role is updated.
     *
     * @param object $event
     */
    public static function clear_cache($event) {
        $cache = \cache::make('block_kent_links', 'data');
        $cache->delete("links_" . $event->relateduserid);

        return true;
    }
}
