<?php
// $Id$
//  ------------------------------------------------------------------------ //
//              wsProject - A XOOPS Project Management Modul                 //
//                  Copyright (c) 2005 stefan-marr.de                        //
//                    <http://www.stefan-marr.de/>                           //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

define('_WSRES_ADMIN', 1);
define('_WSRES_USER', 2);

/**
 * @param $startdate
 * @param $enddate
 * @return int|string
 */
function percentTimeComplete($startdate, $enddate)
{
    $elapsed = time() - strtotime($startdate);
    $total   = strtotime($enddate) - strtotime($startdate);
    if ($total == 0) {
        return 100;
    } else {
        if ((100 * $elapsed / $total) == 0) {
            return 0;
        } else {
            return sprintf('%02d', 100 * $elapsed / $total);
        }
    }
}

/**
 * @param $done
 * @param $todo
 * @return int|string
 */
function percentComplete($done, $todo)
{
    $total = $todo + $done;
    if ($total > 0) {
        $percent     = 100 * ($done / $total);
        $percentdone = sprintf('%2d', $percent);

        return $percentdone;
    } else {
        return 0;
    }
}

/**
 * @param $colour
 * @param $percent
 * @return string
 */
function createBar($colour, $percent)
{
    //Modified 25.07.2003 Stefan Marr
    // This function takes the integer (from 0-100) $percent as an argument
    // to build a bargraph using small images. It doesn't depend on the gd lib.
    // safety: normalise $percent if it's not between 0 and 100.
    // FUNCTION SUBMITTED BY: DAVID HUGHES  (David.W.Hughes@cern.ch)

    $percent  = max(0, min(100, $percent));
    $l_colour = ($percent == 0 ? 'grey' : $colour);
    $r_colour = ($percent == 100 ? $colour : 'grey');
    // This is a hack to avoid bad browser behaviour from using <img width=0>
    if ($percent == 0) {
        $percent = 1;
    }
    if ($percent == 100) {
        $percent = 99;
    }
    $result = '<img src="' . XOOPS_URL . "/modules/wsproject/assets/images/bars/bar-left-$l_colour.gif\" border=\"0\" height=\"12\" width=\"5\">";
    $result .= '<img src="' . XOOPS_URL . "/modules/wsproject/assets/images/bars/bar-tile-$l_colour.gif\" border=\"0\" height=\"12\" width=\"" . ($percent * 2) . '">';
    $result .= '<img src="' . XOOPS_URL . "/modules/wsproject/assets/images/bars/bar-tile-$r_colour.gif\" border=\"0\" height=\"12\" width=\"" . (200 - $percent * 2) . '">';
    $result .= '<img src="' . XOOPS_URL . "/modules/wsproject/assets/images/bars/bar-right-$r_colour.gif\" border=\"0\" height=\"12\" width=\"5\">";

    return $result;
}

/**
 * @param $colour
 * @param $percent
 * @return string
 */
function createMiniBar($colour, $percent)
{
    //Modified 25.07.2003 Stefan Marr
    // This function takes the integer (from 0-100) $percent as an argument
    // to build a bargraph using small images. It doesn't depend on the gd lib.
    // safety: normalise $percent if it's not between 0 and 100.
    // FUNCTION SUBMITTED BY: DAVID HUGHES  (David.W.Hughes@cern.ch)

    $percent  = max(0, min(100, $percent));
    $l_colour = ($percent == 0 ? 'grey' : $colour);
    $r_colour = ($percent == 100 ? $colour : 'grey');
    // This is a hack to avoid bad browser behaviour from using <img width=0>
    if ($percent == 0) {
        $percent = 1;
    }
    if ($percent == 100) {
        $percent = 99;
    }
    $result = '<img src="' . XOOPS_URL . "/modules/wsproject/assets/images/bars/bar-left-$l_colour.gif\" border=\"0\" height=\"12\" width=\"5\">";
    $result .= '<img src="' . XOOPS_URL . "/modules/wsproject/assets/images/bars/bar-tile-$l_colour.gif\" border=\"0\" height=\"12\" width=\"" . $percent . '">';
    $result .= '<img src="' . XOOPS_URL . "/modules/wsproject/assets/images/bars/bar-tile-$r_colour.gif\" border=\"0\" height=\"12\" width=\"" . (100 - $percent) . '">';
    $result .= '<img src="' . XOOPS_URL . "/modules/wsproject/assets/images/bars/bar-right-$r_colour.gif\" border=\"0\" height=\"12\" width=\"5\">";

    return $result;
}

/**
 * @desc Gibt die ID's der Gruppen zurück, deren User Aufgaben zugewiesen bekommen können
 * @return array with id's
 *
 * function getUsedGroups() {
 * $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
 *
 * $groups = array();
 *
 * $sql = "SELECT conf_value FROM ".$xoopsDB->prefix("wsproject_project")." WHERE conf_name = 'used_group'";
 * $result = $xoopsDB->queryF($sql);
 * while ($group_id = $xoopsDB->fetchRow($result)) {
 * $groups[] = $group_id[0];
 * }
 *
 * $xoopsDB->freeRecordSet($result);
 * return $groups;
 * }
 */
/**
 * @desc Speichert die Gruppen, der User Aufgaben bekommen können
 * @param array with group id's
 *
 * function setUsedGroups($groups) {
 * $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
 *
 * $tb_conf = $xoopsDB->prefix("wsproject_project");
 *
 * $sql = "DELETE FROM ".$tb_conf." WHERE conf_name='used_group'";
 * $result = $xoopsDB->queryF($sql);
 *
 * foreach ($groups as $value) {
 * $sql = "INSERT INTO ".$tb_conf." (conf_name, conf_value) VALUES ('used_group', '".$value."')";
 * $result = $xoopsDB->queryF($sql);
 * }
 * }
 */

/**
 * @desc Gibt die ID's der Gruppen zurück, deren User Aufgaben und Projekte bearbeiten können
 * @return array with id's
 */
function getAdminGroups()
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $groups = [];

    $sql    = 'SELECT conf_value FROM ' . $xoopsDB->prefix('wsproject_project') . " WHERE conf_name = 'admin_group'";
    $result = $xoopsDB->queryF($sql);
    while ($group_id = $xoopsDB->fetchRow($result)) {
        $groups[] = $group_id[0];
    }

    $xoopsDB->freeRecordSet($result);

    return $groups;
}

/**
 * @desc Speichert die Gruppen, deren User Aufgaben und Projekte bearbeiten können
 * @param array with group id's
 */
function setAdminGroups($groups)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $tb_conf = $xoopsDB->prefix('wsproject_project');

    $sql    = 'DELETE FROM ' . $tb_conf . " WHERE conf_name='admin_group'";
    $result = $xoopsDB->queryF($sql);

    foreach ($groups as $value) {
        $sql    = 'INSERT INTO ' . $tb_conf . " (conf_name, conf_value) VALUES ('admin_group', '" . $value . "')";
        $result = $xoopsDB->queryF($sql);
    }
}

/**
 * @desc gibt die Namen und die Id's alelr Projekte zurück
 * @return array [name] [id]
 */
function getProjectsIdAndName()
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $tb_projects = $xoopsDB->prefix('wsproject_projects');

    $r = [];

    $sql    = 'SELECT name, project_id FROM ' . $tb_projects;
    $result = $xoopsDB->queryF($sql);
    while ($project = $xoopsDB->fetchArray($result)) {
        $r[] = $project;
    }

    return $r;
}

/**
 * @desc sortiert das Feld so, dass die Teilaufgaben, jeweils zu den Hauptaufgaben zugehörig erscheinen
 * @param $project
 */
function sortTasksBySubTasks(&$project)
{
    if (isset($project['tasks'])) {
        $new_array = [];
        foreach ($project['tasks'] as $key => $value) {
            if (isset($value['parent_id']) and $value['parent_id'] == '0') {
                $withoutChild = $value;
                if ($value['children'] != null) {
                    $withoutChild['children'] = 1;
                } else {
                    $withoutChild['children'] = null;
                }
                $new_array[] = $withoutChild;
                if ($value['children'] != null) {
                    addSubTasks($new_array, $value);
                }
            }
        }
        if (count($new_array) < 1) {
            foreach ($project['tasks'] as $key => $value) {
                if (isset($value['parent_id'])) {
                    $withoutChild = $value;
                    if ($value['children'] != null) {
                        $withoutChild['children'] = 1;
                    } else {
                        $withoutChild['children'] = null;
                    }
                    $new_array[] = $withoutChild;
                    if ($value['children'] != null) {
                        addSubTasks($new_array, $value);
                    }
                }
            }
        }
        $project['tasks'] = $new_array;
    }
}

/**
 * @param $data
 * @param $task_id
 */
function _setCorrectIndent($data, $task_id)
{
    foreach ($data['tasks'][$task_id]['children'] as $key => $value) {
        $data['tasks'][$task_id]['children'][$key]['indent'] += $data['tasks'][$task_id]['indent'];
    }
}

/**
 * @param $array
 * @param $parent
 */
function addSubTasks($array, $parent)
{
    foreach ($parent['children'] as $value) {
        $withoutChild = $value;
        if ($value['children'] != null) {
            $withoutChild['children'] = 1;
        } else {
            $withoutChild['children'] = null;
        }
        $array[] = $withoutChild;
        if ($value['children'] != null) {
            $this->addSubTasks($array, $value);
        }
    }
}

/**
 * @desc Setzt erweiterte Statusinfos im Array das es übergeben bekommt
 * @param $data
 */
function getStatusInfos(&$data)
{
    $data['timedone'] = max(0, min(100, percentTimeComplete($data['startdate'], $data['enddate'])));
    $data['workdone'] = percentComplete($data['done'], $data['todo']);

    //set the different status texts
    if ($data['workdone'] > $data['timedone']) {
        $data['timebar'] = createBar('green', $data['timedone']);
    } elseif ($data['timedone'] < 100) {
        $data['timebar'] = createBar('yellow', $data['timedone']);
    } else {
        $data['timebar'] = createBar('red', $data['timedone']);
    }

    $data['progressbar'] = createBar('green', $data['workdone']);

    if ($data['timedone'] <= 0) {
        $data['timeinfo'] = _WS_TASKPLANED;
    } elseif ($data['timedone'] >= 100) {
        $data['timeinfo'] = '<b>' . _WS_OVERDUE . '</b>';
    } else {
        $data['timeinfo'] = $data['timedone'] . '% ' . _WS_TIME;
    }

    if ($data['workdone'] <= 0) {
        $data['workinfo'] = _WS_NOTSTARTED;
    } elseif ($data['workdone'] >= 100) {
        $data['workinfo'] = '<b>' . _WS_COMPLETED . '</b>';
    } else {
        $data['workinfo'] = $data['workdone'] . '% ' . _WS_COMPLETE;
    }
}

/**
 * @desc Gibt ein Array mit allen Tagen, Monaten und Jahren zurück, unter [current] die aktuellen
 * @return array
 */
function getDateInfo()
{
    $re = [];
    for ($i = 1; $i <= 12; $i++) {
        $re['month'][$i] = date('M', mktime(0, 0, 0, $i, 1, 2000));
    }
    $re['month']['current'] = date('M');

    for ($i = 1; $i <= 31; $i++) {
        $re['day'][$i] = sprintf('%02d', $i);
    }
    $re['day']['current'] = date('d');

    $re['year']['current'] = date('Y');
    for ($i = (int)$re['year']['current'] - 3; $i <= (int)$re['year']['current'] + 3; $i++) {
        $re['year'][$i] = "$i";
    }

    return $re;
}
