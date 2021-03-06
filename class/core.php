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

// set the error reporting level for this script

include_once XOOPS_ROOT_PATH . '/modules/wsproject/class/functions.php';

if (!defined('WS_PROJECT')) {
    die('Hacking attempt');
}

/**
 * @desc    wsProject ist die Basisklasse des Projektmanagement Moduls <br>
 * <br>
 * Hier werden alle grundlegenden Funktionen zusammen gefasst jedoch sollte
 * diese Klasse nie genutzt werden um davon ab zu leiten.
 * Dafür ist wsClass gedacht.
 *
 * @author  Stefan Marr <wspm@stefan-marr.de>
 * @version 0.0.1, 07/23/2003
 * @public
 */
class wsProject
{
    /** @privatesection */
    private $__errors;
    /** Speichert die Fehlermeldungen. */
    private $__msg;
    /** Speichert die Hinweismeldungen. */

    /** @protectedsection */
    protected $_vars;
    /** Enthält die Variablen aus POST und GET (incl. stripslashes) */

    /**
     * @desc Der Konstruktor bereitet alle Variablen auf die spätere Verwendung vor
     * @prama $processInput wenn True, dann wird das passende Objekt gesucht und damit das aktuelle überschrieben
     */
    public static function getInstance()
    {

        //Holen der Eingaben und ausführen von stripslashes
        //global $HTTP_GET_VARS, $HTTP_POST_VARS;
        foreach ($_GET as $varname => $value) {
            if (is_string($value)) {
                $value = addslashes($value);
            }
            $vars[$varname] = $value;
        }
        foreach ($_POST as $varname => $value) {
            if (is_string($value)) {
                $value = addslashes($value);
            }
            $vars[$varname] = $value;
        }

        //handle xoops comments and notify
        if (isset($vars['task_id']) and !isset($vars['op'])) {
            $vars['op'] = 'showtask';
        } elseif (isset($vars['project_id']) and !isset($vars['op'])) {
            $vars['op'] = 'showproject';
        }

        //Hier beginnt die Auswahl des richtigen Objects
        if (isset($vars['op'])) {
            switch ($vars['op']) {
                case 'listprojects':
                    $action = new listProjects();
                    break;
                case 'listcompletedprojects':
                    $action = new listCompletedProjects();
                    break;
                case 'showproject':
                    $action = showProject::getInstance();
                    break;
                case 'editproject':
                    $action = editProject::getInstance();
                    break;
                case 'addproject':
                    $action = addProject::getInstance();
                    break;
                case 'deleteproject':
                    $action = deleteProject::getInstance();
                    break;
                case 'showtask':
                    $action = showTask::getInstance();
                    break;
                case 'addtask':
                    $action = addTask::getInstance();
                    break;
                case 'edittask':
                    $action = editTask::getInstance();
                    break;
                case 'deletetask':
                    $action = deleteTask::getInstance();
                    break;
                default:
                    if ($GLOBALS['xoopsUser'] === null) {
                        $action = new listProjects();
                    } else {
                        $action = new myTasks();
                    }
                    break;
            }
        } else {
            if ($GLOBALS['xoopsUser'] === null) {
                $action = new listProjects();
            } else {
                $action = new myTasks();
            }
        }

        //Initialisierung der Variablen
        if ('null !== $vars') {
            $action->_vars = $vars;
        }

        return $action;
    }

    /**
     * new constructor, doesn't do anything special, only setting up intial values
     */
    protected function __construct()
    {
        $this->__errors = '';
        $this->__msg    = '';
    }

    /**
     * @desc Fügt Nachricht für den Benutzer zur Ausgabe hinzu <br>
     * <br>
     * Diese Funktion ist dazu gedacht, als Schnittstelle zu $this->__msg zu fungieren, da auf diese<br>
     * Variable nur von der Basisklasse zu gegriffen werden sollte. <br>
     * @param $text enthält die Nachricht als String, sie wird nach einem Zeilenumbruch angefügt angehängt
     */
    public function addMessage($text)
    {
        if ($text != '') {
            if ($this->__msg != '') {
                $this->__msg .= "\n";
            }
            $this->__msg .= $text;
        }
    }

    /**
     * @desc Fügt eine Fehlermeldung zur Ausgabe hinzu <br>
     * <br>
     * Diese Funktion ist ähnlich wie AddMessage (@see AddMessage()), nur speziell für <br>
     * Fehler gedacht. Auch hier sollte immer dieser Funktion vor dem direkten Zugriff <br>
     * auf $this->__errors der Vorang gewährt werden.
     * @param $text enthält die Fehlermeldung als String, sie wird nach einem Zeilenumbruch angefügt angehängt
     */
    public function addError($text)
    {
        if ($text != '') {
            if ($this->__errors != '') {
                $this->__errors .= "\n";
            }
            $this->__errors .= $text;
        }
    }

    /**
     * @desc Gibt die Fehler und die Nachrichten in einem Array zurück <br>
     * <br>
     * Fungiert als Zugriffsmethode um auf die privaten Attribute zugreifen zu können.
     * @return array mit den Elementen 'err' und 'msg' welche
     * die entsprechenden Strings enthalten
     */
    public function getErrAndMsg()
    {
        return ['err' => $this->__errors, 'msg' => $this->__msg];
    }

    /**
     * @desc Gibt alle Variablen frei.
     */
    public function destroy()
    {
        unset($this->__errors);
        unset($this->__msg);

        unset($this->_vars);
    }
}

/**
 * Class wsClass
 */
class wsClass extends wsProject
{
    /** @privatesection */
    public $__data;
    /** Enthält die zur Ausgabe aufbereiteten Daten */
    public $__lang;
    /** Enthält die zur Ausgabe nötigen Strings (entsprechend übersetzt) */
    public $__db;
    /** Ermöglicht den Zugriff auf die Datenbank */
    public $__tpl;
    /** Speichert den Namen des für die Ausgabe vorgesehenen Templates */
    public $__isAdmin;
    /** Speichert Ergebnis der Überprüfung */

    /** @protectedsection */
    protected $_vars;
    /** Enthält die Variablen aus POST und GET (incl. stripslashes) */
    protected $_xoopsUser;
    /** Ermöglicht den Zugriff auf das Xoops User-Object */
    protected $_xoopsTpl;
    /** Ermöglicht den Zugriff auf das Xoops Template-Object */
    protected $_xoopsOption;
    /** Ermöglicht den Zugriff auf das Xoops Template-Object */
    protected $_memberHandler;

    //var $_isUser;		/** Ist der Benutzer ein befugter Nutzer? */

    /**
     * wsClass constructor.
     */
    protected function __construct()
    {
        $this->__tpl     = '';
        $this->__data    = null;
        $this->__isAdmin = null;
        $this->__db      = XoopsDatabaseFactory::getDatabaseConnection();

        //Hole Xoops Variablen
        global $xoopsTpl, $xoopsOption;
        $memberHandler        = xoops_getHandler('member');
        $this->_xoopsUser     = $GLOBALS['xoopsUser'];
        $this->_xoopsTpl      = $xoopsTpl;
        $this->_xoopsOption   = $xoopsOption;
        $this->_memberHandler = $memberHandler;
    }

    /**
     * @desc Gibt den Namen des Templates zurück, das für die Ausgabe benötigt wird. <br>
     * Sollte erst nach dem Verarbeiten der Eingaben abgefragt werden.
     * @return string
     */
    public function getTemplate()
    {
        return $this->__tpl;
    }

    /**
     * @desc Gibt die lokalisierten Strings in einem Array zurück
     * Sollte erst nach dem Verarbeiten der Eingaben abgefragt werden.
     * @return Array mit lokalisierten Strings
     */
    public function getLanguageData()
    {
        return $this->__lang;
    }

    /**
     * @desc Gibt die aufbereiteten Daten in einem Array zurück
     * Sollte erst nach dem Verarbeiten der Eingaben abgefragt werden.
     * @return array of strings
     */
    public function getData()
    {
        return $this->__data;
    }

    /**
     * @desc Holt alle wichtigen Daten und stellt diese in $this->__data zur Verfügung
     * @protected
     */
    protected function _getData()
    {
        exit;
    }

    /**
     * @desc Überprüft, verarbeitet Benutzereingaben
     */
    public function processInput()
    {
        exit;
    }

    /**
     * @desc Gibt True zurück, wenn Xoops Kommentare angezeigt werden sollen
     */
    public function showComments()
    {
        return false;
    }

    /**
     * @desc protected setzt den Status der Elternaufgaben zurück auf 90% wenn notwendig
     * @param task_id
     * @protected
     */
    public function _restartParentTasks($task_id)
    {
        $sql        = 'SELECT parent_id FROM ' . $this->__db->prefix('wsproject_tasks') . ' WHERE task_id=' . $task_id;
        $qry_result = $this->__db->queryF($sql);
        $task       = $this->__db->fetchArray($qry_result);
        $parent_id  = $task['parent_id'];
        while ($parent_id != 0) {
            $sql        = 'SELECT status, parent_id FROM ' . $this->__db->prefix('wsproject_tasks') . ' WHERE task_id=' . $parent_id;
            $qry_result = $this->__db->queryF($sql);
            $task       = $this->__db->fetchArray($qry_result);
            if ($task['status'] == '100') {
                $sql        = 'UPDATE ' . $this->__db->prefix('wsproject_tasks') . ' SET status=90 WHERE task_id=' . $parent_id;
                $qry_result = $this->__db->queryF($sql);
            }
            $parent_id = $task['parent_id'];
        }
    }

    /**
     * @desc protected gibt die id's der subtask einer Aufgabe zurück
     * @param task_id
     * @return array
     * @protected
     */
    public function _getSubTasks($task_id)
    {
        $result     = [];
        $sql        = 'SELECT task_id FROM ' . $this->__db->prefix('wsproject_tasks') . ' WHERE parent_id=' . $task_id;
        $qry_result = $this->__db->queryF($sql);
        while ($task = $this->__db->fetchArray($qry_result)) {
            $result[] = $task['task_id'];
            $result   = array_merge($result, $this->_getSubTasks($task['task_id']));
        }

        return $result;
    }

    /**
     * @desc protected setzt die richtige Einrückweite, kann notwendig sein, wenn der Parent-Task weiter hinten in der DB steht
     * Die Seite wird aus den Daten in $this->__data generiert.
     * @protected
     * @param $task_id
     */
    public function _setCorrectIndent($task_id)
    {
        foreach ($this->__data['tasks'][$task_id]['children'] as $key => $value) {
            $this->__data['tasks'][$task_id]['children'][$key]['indent'] += $this->__data['tasks'][$task_id]['indent'];
        }
    }

    /**
     * @desc sortiert das Feld so, dass die Teilaufgaben, jeweils zu den Hauptaufgaben zugehörig erscheinen
     */
    public function sortTasksBySubTasks()
    {
        if (isset($this->__data['tasks'])) {
            $new_array = [];
            foreach ($this->__data['tasks'] as $key => $value) {
                if ($value['parent_id'] == '0') {
                    //FIX for PHP5
                    /*$withoutChild = $value;
                    if ($value['children'] != NULL) {
                        $withoutChild['children'] = 1;
                    }
                    else {
                        $withoutChild['children'] = NULL;
                    }
                    $new_array[] = $withoutChild;*/
                    $new_array[] = $value;
                    if ($value['children'] != null) {
                        $this->addSubTasks($new_array, $value);
                    }
                }
            }
            $this->__data['tasks'] = $new_array;
        }
    }

    /**
     * @desc Fügt die Unteraufgaben von $parent zu $array hinzu
     * @param $array  benötigt &$feld zu dem die SubTasks von $parent hinzugefügt werden sollen
     * @param $parent ist eine Aufgabe mit Unteraufgaben
     */
    public function addSubTasks(&$array, $parent)
    {
        foreach ($parent['children'] as $value) {
            /*$withoutChild = $value;
            if ($value['children'] != NULL) {
                $withoutChild['children'] = 1;
            }
            else {
                $withoutChild['children'] = NULL;
            }
            $array[] = $withoutChild;*/
            $array[] = $value;
            if ($value['children'] != null) {
                $this->addSubTasks($array, $value);
            }
        }
    }

    /**
     * @desc Setzt die lokalisierten Strings
     */
    public function setLanguageData()
    {
        exit;
    }

    /**
     * @desc protected ermittelt, ob der aktuelle Benutzer Admin ist
     * @protected
     * @return bool
     */
    public function _isAdmin()
    {
        if (!is_object($this->_xoopsUser) || null === $this->_xoopsUser) {
            return false;
        }
        if ($this->__isAdmin === null) {
            $groups = $this->_memberHandler->getGroupsByUser($this->_xoopsUser->getVar('uid'), false);
            //Bestimme ob der User in einer der AdminGruppen ist.
            $admingroup      = array_intersect($groups, getAdminGroups());
            $this->__isAdmin = !empty($admingroup);
        }

        return $this->__isAdmin;
    }

    /**
     * @desc protected ermittelt, ob der aktuelle Benutzer beim angegebenen Projekt Admin ist
     * @protected
     * @param      $project_id
     * @param null $task_id
     * @return bool
     */
    public function _isProjectAdmin($project_id, $task_id = null)
    {
        if (!is_object($this->_xoopsUser) || null === $this->_xoopsUser) {
            return false;
        }
        if ($this->_isAdmin()) {
            return true;
        } else {
            if ($project_id === null) {
                //get project_id
                $sql    = 'SELECT project_id FROM ' . $this->__db->prefix('wsproject_tasks') . " WHERE task_id='" . $task_id . "'";
                $result = $this->__db->queryF($sql);
                $task   = $this->__db->fetchArray($result);
                $this->__db->freeRecordSet($result);
                $project_id = $task['project_id'];
            }

            $sql         = 'SELECT group_id FROM ' . $this->__db->prefix('wsproject_restrictions') . " WHERE project_id='" . $project_id . "' AND user_rank='" . _WSRES_ADMIN . "'";
            $result      = $this->__db->queryF($sql);
            $admingroups = [];
            while ($id = $this->__db->fetchArray($result)) {
                $admingroups[] = $id['group_id'];
            }
            $this->__db->freeRecordSet($result);

            $groups = $this->_memberHandler->getGroupsByUser($this->_xoopsUser->getVar('uid'), false);
            //Bestimme ob der User in einer der AdminGruppen ist.
            $admingroup = array_intersect($groups, $admingroups);

            return !empty($admingroup);
        }
    }

    /**
     * @desc protected ermittelt, ob der aktuelle Benutzer diese Aufgabe bearbeitet
     * @protected
     * @param $task_id
     * @return bool
     */
    public function _isTaskOwner($task_id)
    {
        if (!is_object($this->_xoopsUser) || null === $this->_xoopsUser) {
            return false;
        }
        if ($this->_isAdmin()) {
            return true;
        } else {
            $sql    = 'SELECT user_id FROM ' . $this->__db->prefix('wsproject_tasks') . " WHERE task_id='" . $task_id . "'";
            $result = $this->__db->queryF($sql);
            $id     = $this->__db->fetchArray($result);
            if (isset($id['user_id'])) {
                $r = ($id['user_id'] == $this->_xoopsUser->getVar('uid')) ? true : false;
            }
            $this->__db->freeRecordSet($result);

            return $r;
        }
    }

    /**
     * @desc protected ermittelt, ob der aktuelle Benutzer beim angegebenen Projekt ein eingetragener Nutzer ist
     * @protected
     * @param      $project_id
     * @param null $task_id
     * @return bool
     */
    public function _isProjectUser($project_id, $task_id = null)
    {
        if (!is_object($this->_xoopsUser) || null === $this->_xoopsUser) {
            return false;
        }
        if ($this->_isAdmin()) {
            return true;
        } else {
            if ($project_id === null) {
                //get project_id
                $sql    = 'SELECT project_id FROM ' . $this->__db->prefix('wsproject_tasks') . " WHERE task_id='" . $task_id . "'";
                $result = $this->__db->queryF($sql);
                $task   = $this->__db->fetchArray($result);
                $this->__db->freeRecordSet($result);
                $project_id = $task['project_id'];
            }

            $sql        = 'SELECT group_id FROM ' . $this->__db->prefix('wsproject_restrictions') . " WHERE project_id='" . $project_id . "' AND user_rank='" . _WSRES_USER . "'";
            $result     = $this->__db->queryF($sql);
            $usergroups = [];
            while ($id = $this->__db->fetchArray($result)) {
                $usergroups[] = $id['group_id'];
            }
            $this->__db->freeRecordSet($result);

            $groups = $this->_memberHandler->getGroupsByUser($this->_xoopsUser->getVar('uid'), false);
            //Bestimme ob der User in einer der AdminGruppen ist.
            $group = array_intersect($groups, $usergroups);

            return !empty($group);
        }
    }

    /**
     * @desc protected ermittelt, die Gruppen, welche bei diesem Projekt als Admin eingetragen sind
     * @protected
     * @param $project_id
     * @return array
     */
    public function _getProjectAdminGroups($project_id)
    {
        $sql    = 'SELECT group_id FROM ' . $this->__db->prefix('wsproject_restrictions') . " WHERE project_id='" . $project_id . "' AND user_rank='" . _WSRES_ADMIN . "'";
        $result = $this->__db->queryF($sql);
        $r      = [];
        while ($id = $this->__db->fetchArray($result)) {
            $r[] = $id['group_id'];
        }
        $this->__db->freeRecordSet($result);

        return $r;
    }

    /**
     * @desc protected ermittelt, die Gruppen, welche bei diesem Projekt als Benutzer eingetragen sind
     * @protected
     * @param $project_id
     * @return bool
     */
    public function _getProjectUserGroups($project_id)
    {
        $sql    = 'SELECT group_id FROM ' . $this->__db->prefix('wsproject_restrictions') . " WHERE project_id='" . $project_id . "' AND user_rank='" . _WSRES_USER . "'";
        $result = $this->__db->queryF($sql);
        $r      = [];
        while ($id = $this->__db->fetchArray($result)) {
            $r[] = $id['group_id'];
        }
        $this->__db->freeRecordSet($result);

        return $r;
    }
}

/**
 * @desc    myTasks zeigt die Aufgaben des aktuellen User's an<br>
 *
 * @author  Stefan Marr <wspm@stefan-marr.de>
 * @version 0.0.1, 07/23/2003
 * @public
 */
class myTasks extends wsClass
{
    /**
     * myTasks constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function processInput()
    {
        //Fertigstellen und Löschen der Aufgaben
        $this->_getData();
        $this->__tpl = 'myTasks.tpl';
        $this->setLanguageData();
    }

    /**
     * @desc protected holt die Daten aus der DB
     * @protected
     */
    protected function _getData()
    {
        if (!is_object($this->_xoopsUser) || null === $this->_xoopsUser) {
            return false;
        }
        //init data variable
        $this->__data = [];

        $tb_tasks    = $this->__db->prefix('wsproject_tasks');
        $tb_projects = $this->__db->prefix('wsproject_projects');

        //general user info
        //$this->_xoopsUser->rank();
        $this->__data['uname'] = $this->_xoopsUser->getVar('uname');
        $this->__data['name']  = $this->_xoopsUser->getVar('name');
        $tmp                   = $this->_xoopsUser->rank();
        $this->__data['rank']  = $tmp['title'];

        //get the projects with open tasks
        $sql    = 'SELECT DISTINCT '
                  . $tb_projects
                  . '.project_id, '
                  . $tb_projects
                  . '.name, '
                  . $tb_projects
                  . '.startdate, '
                  . $tb_projects
                  . '.enddate, '
                  . $tb_projects
                  . '.description FROM '
                  . $tb_projects
                  . ' LEFT JOIN '
                  . $tb_tasks
                  . ' ON '
                  . $tb_projects
                  . '.project_id = '
                  . $tb_tasks
                  . '.project_id WHERE '
                  . $tb_tasks
                  . ".user_id='"
                  . $this->_xoopsUser->getVar('uid')
                  . "' AND "
                  . $tb_tasks
                  . '.status<100 AND '
                  . $tb_tasks
                  . '.deleted = 0';
        $result = $this->__db->queryF($sql);

        while ($project = $this->__db->fetchArray($result)) {
            $project['done']                                                          = 0; //initialiseren, falls eine spätere query keien daten zu dem projekt liefert
            $project['todo']                                                          = 0;
            $project['name']                                                          = stripslashes($project['name']);
            $project['description']                                                   = stripslashes($project['description']);
            $this->__data['projects'][$project['project_id']]                         = $project;
            $this->__data['projects'][$project['project_id']]['user']['projectadmin'] = ($this->_isProjectAdmin($project['project_id']) or $this->_isAdmin());
        }
        $this->__data['project_count'] = $this->__db->getRowsNum($result);
        $this->__db->freeRecordSet($result);

        // Get all open tasks
        // Modified by Simon Yates
        // simon.yates@mythagostudios.com
        // 07-07-05

        $sql = 'SELECT task_id, project_id, title, hours, description, startdate, enddate, status, public, parent_id, image ' . 'FROM ' . $tb_tasks . " WHERE user_id='" . $this->_xoopsUser->getVar('uid') . "' AND status < 100 AND deleted = '0' AND parent_id = '0' ORDER BY title ASC";

        $result       = $this->__db->queryF($sql);
        $projects_ids = '';
        while ($task = $this->__db->fetchArray($result)) {
            $task['title']       = stripslashes($task['title']);
            $task['hours']       = stripslashes($task['hours']);
            $task['description'] = stripslashes($task['description']);
            $task['startdate']   = stripslashes($task['startdate']);
            $task['enddate']     = stripslashes($task['enddate']);
            $task['status']      = stripslashes($task['status']);
            $task['image']       = stripslashes($task['image']);

            $parent_task_index                                        = isset($this->__data['projects'][$task['project_id']]['tasks']) ? count($this->__data['projects'][$task['project_id']]['tasks']) : 0;
            $this->__data['projects'][$task['project_id']]['tasks'][] = $task;

            $sql = 'SELECT task_id, project_id, title, hours, description, startdate, enddate, status, public, parent_id, image '
                   . 'FROM '
                   . $tb_tasks
                   . " WHERE user_id='"
                   . $this->_xoopsUser->getVar('uid')
                   . "' AND status < 100 AND deleted = '0' AND parent_id = '"
                   . $task['task_id']
                   . "' AND project_id = '"
                   . $task['project_id']
                   . "' ORDER BY title ASC";

            $result_sub = $this->__db->queryF($sql);

            while ($task_sub = $this->__db->fetchArray($result_sub)) {
                $task_sub['title']                                        = stripslashes($task_sub['title']);
                $task_sub['hours']                                        = stripslashes($task_sub['hours']);
                $task_sub['description']                                  = stripslashes($task_sub['description']);
                $task_sub['startdate']                                    = stripslashes($task_sub['startdate']);
                $task_sub['enddate']                                      = stripslashes($task_sub['enddate']);
                $task_sub['status']                                       = stripslashes($task_sub['status']);
                $task_sub['image']                                        = stripslashes($task_sub['image']);
                $this->__data['projects'][$task['project_id']]['tasks'][] = $task_sub;

                $this->__data['projects'][$task['project_id']]['tasks'][$parent_task_index]['hours'] += $task_sub['hours'];
            }

            $this->__data['projects'][$task['project_id']]['tasks'][$parent_task_index]['hours'] = sprintf('%.02f', $this->__data['projects'][$task['project_id']]['tasks'][$parent_task_index]['hours']);

            $this->__db->freeRecordSet($result_sub);
        }

        // End of Mods ( SY )

        if (isset($this->__data['projects'])) {
            foreach ($this->__data['projects'] as $key => $value) {
                if ($projects_ids == '') {
                    $projects_ids .= ' project_id=' . $key;
                } else {
                    $projects_ids .= ' OR project_id=' . $key;
                }
            }
        }
        $this->__data['task_count'] = $this->__db->getRowsNum($result);
        $this->__db->freeRecordSet($result);

        //Nur ausführen, wenn Projekte vorhanden
        if ($projects_ids != '') {
            //get sum houres done
            $sql    = 'SELECT SUM(hours) AS done, project_id FROM ' . $tb_tasks . " WHERE user_id='" . $this->_xoopsUser->getVar('uid') . "' AND STATUS = 100 AND deleted = 0 AND (" . $projects_ids . ') GROUP BY project_id';
            $result = $this->__db->queryF($sql);
            while ($done = $this->__db->fetchArray($result)) {
                $this->__data['projects'][$done['project_id']]['done'] = $done['done'];
            }

            //get sum houres open
            $sql    = 'SELECT SUM(hours) AS todo, project_id FROM ' . $tb_tasks . " WHERE user_id='" . $this->_xoopsUser->getVar('uid') . "' AND STATUS < 100 AND deleted = 0 AND (" . $projects_ids . ') GROUP BY project_id';
            $result = $this->__db->queryF($sql);
            while ($todo = $this->__db->fetchArray($result)) {
                $this->__data['projects'][$todo['project_id']]['todo'] = $todo['todo'];
            }

            //calculate curent status and set info text's
            if (isset($this->__data['projects'])) {
                foreach ($this->__data['projects'] as $key => $value) {
                    getStatusInfos($this->__data['projects'][$key]);
                }
            }
        }
        $this->__data['user']['admin'] = $this->_isAdmin();
    }

    /**
     * @desc Setzt die lokalisierten Strings
     */
    public function setLanguageData()
    {
        $this->__lang['userdetails'] = _WS_USERDETAILSFOR;
        $this->__lang['username']    = _WS_USERNAME;
        $this->__lang['rank']        = _WS_RANK;
        $this->__lang['projects']    = _WS_PROJECTS;
        $this->__lang['project']     = _WS_PROJECT;
        $this->__lang['opentasks']   = _WS_OPENTASKS;
        $this->__lang['addtask']     = _WS_ADDTASK;
        $this->__lang['edit']        = _WS_EDIT;
        $this->__lang['delete']      = _WS_DELETE;
        $this->__lang['hours']       = _WS_HOURS;
        $this->__lang['note']        = _WS_NOTE;
        $this->__lang['title']       = _WS_TITLE;
        $this->__lang['description'] = _WS_DESCRIPTION;
        $this->__lang['action']      = _WS_ACTION;
        $this->__lang['status']      = _WS_STATUS;
    }
}

/**
 * @desc    listProjects zeigt die laufenden Projekte an<br>
 *
 * @author  Stefan Marr <wspm@stefan-marr.de>
 * @version 0.0.1, 07/23/2003
 * @public
 */
class listProjects extends wsClass
{
    /**
     * listProjects constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function processInput()
    {
        if (isset($this->_vars['subop'])) {
            if ($this->_vars['subop'] == 'deleteproject') {
                //delete project
                $sql    = 'UPDATE ' . $this->__db->prefix('wsproject_projects') . ' SET deleted=1 WHERE project_id=' . $this->_vars['project_id'];
                $result = $this->__db->queryF($sql);

                //delete all tasks
                $sql    = 'UPDATE ' . $this->__db->prefix('wsproject_tasks') . ' SET deleted=1 WHERE project_id=' . $this->_vars['project_id'];
                $result = $this->__db->queryF($sql);
            } elseif (($this->_vars['subop'] == 'add') and $this->_isAdmin()) {
                $start = $this->_vars['startyear'] . '-' . $this->_vars['startmonth'] . '-' . $this->_vars['startday'];
                $end   = $this->_vars['endyear'] . '-' . $this->_vars['endmonth'] . '-' . $this->_vars['endday'];

                $sql    = 'INSERT INTO ' . $this->__db->prefix('wsproject_projects') . " (name, startdate, enddate,
						description, completed, completed_date, deleted)
						VALUES ('" . $this->_vars['name'] . "',
								'" . $start . "',
								'" . $end . "',
								'" . $this->_vars['description'] . "',
								'0', NULL, '0')";
                $result = $this->__db->queryF($sql);

                //MERKEN: Holt die letzte ID die durch ein Insert generiert wurde
                $project_id = $this->__db->getInsertId();

                //add user restrictions
                $tb_res = $this->__db->prefix('wsproject_restrictions');
                foreach ($this->_vars['projectadmin_groups'] as $group_id) {
                    $sql    = 'INSERT INTO ' . $tb_res . " (group_id, project_id, user_rank)
						VALUES ('" . $group_id . "',
								'" . $project_id . "',
								'" . _WSRES_ADMIN . "')";
                    $result = $this->__db->queryF($sql);
                }

                foreach ($this->_vars['projectuser_groups'] as $group_id) {
                    $sql    = 'INSERT INTO ' . $tb_res . " (group_id, project_id, user_rank)
						VALUES ('" . $group_id . "',
								'" . $project_id . "',
								'" . _WSRES_USER . "')";
                    $result = $this->__db->queryF($sql);
                }

                //notify user
                $extra_tags['PROJECT_NAME'] = $this->_vars['name'];
                $extra_tags['PROJECT_URL']  = XOOPS_URL . '/modules/wsproject/?project_id=' . $project_id;

                //uses XoopsNotify System
                $notificationHandler = xoops_getHandler('notification');
                $notificationHandler->triggerEvent('global', 0, 'new_project', $extra_tags);
            }
        }

        if (isset($this->_vars['order'])) {
            $this->_getData($this->_vars['order']);
        } else {
            $this->_getData('nameup');
        }
        $this->__tpl = 'listProjects.tpl';
        $this->setLanguageData();
    }

    /**
     * @desc protected holt die Daten aus der DB
     * @protected
     * @param $sortorder
     */
    protected function _getData($sortorder = null)
    {
        switch ($sortorder) {
            case 'nameup':
                $order = 'name ASC';
                break;
            case 'namedown':
                $order = 'name DESC';
                break;
            case 'deadlineup':
                $order = 'enddate ASC';
                break;
            case 'deadlinedown':
                $order = 'enddate DESC';
                break;
        }
        //init data variable
        $this->__data = [];

        $tb_tasks    = $this->__db->prefix('wsproject_tasks');
        $tb_projects = $this->__db->prefix('wsproject_projects');

        //Alle Projekte holen die nicht gelöscht oder fertig sind
        $sql    = 'SELECT project_id, name, startdate, enddate, description FROM ' . $tb_projects . " WHERE completed='0' AND deleted='0' ORDER BY " . $order;
        $result = $this->__db->queryF($sql);

        while ($project = $this->__db->fetchArray($result)) {
            $project['name']        = stripslashes($project['name']);
            $project['description'] = stripslashes($project['description']);

            $project['done']                                              = 0; //initialiseren, falls eine spätere query keien daten zu dem projekt liefert
            $project['todo']                                              = 0;
            $this->__data[$project['project_id']]                         = $project;
            $this->__data[$project['project_id']]['user']['projectadmin'] = ($this->_isProjectAdmin($project['project_id']) or $this->_isAdmin());
            $this->__data[$project['project_id']]['user']['projectuser']  = ($this->_isProjectUser($project['project_id']) or $this->__data[$project['project_id']]['user']['projectadmin'] or $this->_isAdmin());
        }
        $this->__db->freeRecordSet($result);

        if (count($this->__data) > 0) {
            //get sum houres done
            $sql    = 'SELECT SUM(hours) AS done, project_id FROM ' . $tb_tasks . ' WHERE status = 100 AND deleted = 0 GROUP BY project_id';
            $result = $this->__db->queryF($sql);
            while ($done = $this->__db->fetchArray($result)) {
                $this->__data[$done['project_id']]['done'] = $done['done'];
            }

            //get sum houres open
            $sql    = 'SELECT SUM(hours) AS todo, project_id FROM ' . $tb_tasks . ' WHERE status < 100 AND deleted = 0 GROUP BY project_id';
            $result = $this->__db->queryF($sql);
            while ($todo = $this->__db->fetchArray($result)) {
                $this->__data[$todo['project_id']]['todo'] = $todo['todo'];
            }

            //calculate curent status and set info text's
            foreach ($this->__data as $key => $value) {
                getStatusInfos($this->__data[$key]);
            }
            //Umorganisieren des arrays, da noch eine variable fehlt im template
            $temp                     = $this->__data;
            $this->__data             = [];
            $this->__data['projects'] = $temp;
        }
        $this->__data['sort'] = $sortorder;

        $this->__data['user']['admin'] = $this->_isAdmin();
    }

    /**
     * @desc Setzt die lokalisierten Strings
     */
    public function setLanguageData()
    {
        $this->__lang['description'] = _WS_DESCRIPTION;
        $this->__lang['projectname'] = _WS_PROJECTNAME;
        $this->__lang['progress']    = _WS_PROGRESS;
        $this->__lang['deadline']    = _WS_DEADLINE;
        $this->__lang['action']      = _WS_ACTION;
        $this->__lang['edit']        = _WS_EDIT;
        $this->__lang['delete']      = _WS_DELETE;
        $this->__lang['addtask']     = _WS_ADDTASK;
    }
}

/**
 * @desc    listCompletedProjects zeigt die abgschlossenen Projekte an<br>
 *
 * @author  Stefan Marr <wspm@stefan-marr.de>
 * @version 0.0.1, 07/23/2003
 * @public
 */
class listCompletedProjects extends wsClass
{
    /**
     * listCompletedProjects constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function processInput()
    {
        if (isset($this->_vars['order'])) {
            $this->_getData($this->_vars['order']);
        } else {
            $this->_getData('nameup');
        }
        $this->__tpl = 'listCompletedProjects.tpl';
        $this->setLanguageData();
    }

    /**
     * @desc protected holt die Daten aus der DB
     * @protected
     * @param $sortorder
     */
    protected function _getData($sortorder = null)
    {
        switch ($sortorder) {
            case 'nameup':
                $order = 'name ASC';
                break;
            case 'namedown':
                $order = 'name DESC';
                break;
            case 'endup':
                $order = 'enddate ASC';
                break;
            case 'enddown':
                $order = 'enddate DESC';
                break;
        }
        //init data variable
        $this->__data = [];

        $tb_tasks    = $this->__db->prefix('wsproject_tasks');
        $tb_projects = $this->__db->prefix('wsproject_projects');

        //Alle Projekte holen die nicht gelöscht aber fertig sind
        $sql    = 'SELECT project_id, name, startdate, enddate, completed_date, description FROM ' . $tb_projects . " WHERE completed='1' AND deleted='0' ORDER BY " . $order;
        $result = $this->__db->queryF($sql);

        while ($project = $this->__db->fetchArray($result)) {
            $project['name']        = stripslashes($project['name']);
            $project['description'] = stripslashes($project['description']);

            $project['done']                                              = 0; //initialiseren, falls eine spätere query keien daten zu dem projekt liefert
            $project['todo']                                              = 0;
            $this->__data[$project['project_id']]                         = $project;
            $this->__data[$project['project_id']]['user']['projectadmin'] = ($this->_isProjectAdmin($project['project_id']) or $this->_isAdmin());
            $this->__data[$project['project_id']]['user']['projectuser']  = ($this->_isProjectUser($project['project_id']) or $this->__data[$project['project_id']]['user']['projectadmin'] or $this->_isAdmin());
        }
        $this->__db->freeRecordSet($result);

        //get sum houres done
        $sql    = 'SELECT SUM(hours) AS done, project_id FROM ' . $tb_tasks . ' WHERE status = 100 AND deleted = 0 GROUP BY project_id';
        $result = $this->__db->queryF($sql);
        while ($done = $this->__db->fetchArray($result)) {
            if (isset($this->__data[$done['project_id']]['done'])) {
                $this->__data[$done['project_id']]['done'] = $done['done'];
            }
        }
        //Umorganisieren des arrays, da noch eine variable fehlt im template
        $temp                          = $this->__data;
        $this->__data                  = [];
        $this->__data['projects']      = $temp;
        $this->__data['sort']          = $sortorder;
        $this->__data['user']['admin'] = $this->_isAdmin();
    }

    /**
     * @desc Setzt die lokalisierten Strings
     */
    public function setLanguageData()
    {
        $this->__lang['description'] = _WS_DESCRIPTION;
        $this->__lang['projectname'] = _WS_PROJECTNAME;
        $this->__lang['completed']   = _WS_COMPLETED2;
        $this->__lang['hours']       = _WS_HOURS;
        $this->__lang['action']      = _WS_ACTION;
        $this->__lang['edit']        = _WS_EDIT;
        $this->__lang['delete']      = _WS_DELETE;
        $this->__lang['reactivate']  = _WS_REACTIVATE;
    }
}

/**
 * @desc    showProject zeigt das gewählte Projekte an<br>
 * es werden alle erledingten und noch zu erledigenden Aufgaben angezeigt
 *
 * @author  Stefan Marr <wspm@stefan-marr.de>
 * @version 0.0.1, 07/23/2003
 * @public
 */
class showProject extends wsClass
{

    /** @private */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \listProjects|\showProject
     */
    public static function getInstance()
    {
        $action = new showProject();
        if (!$action->_isProjectUser($GLOBALS['project_id'])) {
            $action = new listProjects();
        }

        return $action;
    }

    public function processInput()
    {
        if (isset($this->_vars['subop'])) {
            if ($this->_vars['subop'] == 'addtask' and $this->_isProjectAdmin($this->_vars['project_id'])) {
                $sql    = 'INSERT INTO ' . $this->__db->prefix('wsproject_tasks') . " (project_id, user_id, title,
						hours, startdate, enddate, description, status, public, parent_id, image, deleted)
						VALUES ('" . $this->_vars['project_id'] . "',
								'" . $this->_vars['user_id'] . "',
								'" . $this->_vars['title'] . "',
								'" . $this->_vars['hours'] . "',
								NOW(),
								NULL,
								'" . $this->_vars['description'] . "',
								'0',
								'" . (isset($this->_vars['public']) ? '1' : '0') . "',
								'" . $this->_vars['parent_id'] . "', 'none',	'0')";
                $result = $this->__db->queryF($sql);
                //MERKEN: Holt die letzte ID die durch ein Insert generiert wurde
                $task_id = $this->__db->getInsertId();

                // add a series task if the series tasks are selected
                if ($_REQUEST['series']) {
                    $this->addSeriesTask($_REQUEST['series'], $task_id);
                }
                //notify user
                $extra_tags['TASK_NAME']  = $this->_vars['title'];
                $extra_tags['TASK_HOURS'] = $this->_vars['hours'];
                $extra_tags['TASK_DESC']  = $this->_vars['description'];
                $extra_tags['TASK_URL']   = XOOPS_URL . '/modules/wsproject/?task_id=' . $task_id;

                //uses XoopsNotify System
                //$users = array($this->_memberHandler->getUser($this->_vars['user_id']));
                $users               = [$this->_vars['user_id']];
                $notificationHandler = xoops_getHandler('notification');
                $notificationHandler->triggerEvent('project', $this->_vars['project_id'], 'new_task', $extra_tags, $users);
            } elseif ($this->_vars['subop'] == 'deletetask' and $this->_isProjectAdmin($this->_vars['project_id'])) {
                $tasks    = 'task_id=' . $this->_vars['task_id'] . ' ';
                $children = $this->_getSubTasks($this->_vars['task_id']);
                foreach ($children as $child) {
                    $tasks .= 'OR task_id=' . $child . ' ';
                }

                $sql    = 'UPDATE ' . $this->__db->prefix('wsproject_tasks') . ' SET deleted=1 WHERE ' . $tasks;
                $result = $this->__db->queryF($sql);
                //delete xoops comments
            } elseif ($this->_vars['subop'] == 'finishtask' and $this->_isTaskOwner($this->_vars['task_id'])) {
                $tasks    = 'task_id=' . $this->_vars['task_id'] . ' ';
                $children = $this->_getSubTasks($this->_vars['task_id']);
                foreach ($children as $child) {
                    $tasks .= 'OR task_id=' . $child . ' ';
                }

                $sql    = 'UPDATE ' . $this->__db->prefix('wsproject_tasks') . ' SET status=100, enddate=NOW() WHERE ' . $tasks;
                $result = $this->__db->queryF($sql);
            } elseif ($this->_vars['subop'] == 'restarttask' and $this->_isTaskOwner($this->_vars['task_id'])) {
                $sql    = 'UPDATE ' . $this->__db->prefix('wsproject_tasks') . ' SET status=0 WHERE task_id=' . $this->_vars['task_id'];
                $result = $this->__db->queryF($sql);
                $this->_restartParentTasks($this->_vars['task_id']);
            } elseif ($this->_vars['subop'] == 'reactivate' and $this->_isAdmin()) {
                $sql    = 'UPDATE ' . $this->__db->prefix('wsproject_projects') . ' SET completed=0 WHERE project_id=' . $this->_vars['project_id'];
                $result = $this->__db->queryF($sql);
            } elseif ($this->_vars['subop'] == 'complete' and $this->_isAdmin()) {
                $sql    = 'UPDATE ' . $this->__db->prefix('wsproject_projects') . ' SET completed=1, completed_date=NOW() WHERE project_id=' . $this->_vars['project_id'];
                $result = $this->__db->queryF($sql);
            }
        }
        if (isset($this->_vars['order'])) {
            $this->_getData($this->_vars['order']);
        } else {
            $this->_getData('nameup');
        }

        //Fertigstellen und Löschen der Aufgaben

        $this->__tpl = 'showProject.tpl';
        $this->setLanguageData();
    }

    /**
     * @desc protected holt die Daten aus der DB
     * @protected
     * @param $sortorder
     */
    protected function _getData($sortorder = null)
    {
        switch ($sortorder) {
            case 'nameup':
                $order = 'title ASC';
                break;
            case 'namedown':
                $order = 'title DESC';
                break;
            case 'userup':
                $order = 'user_id ASC';
                break;
            case 'userdown':
                $order = 'user_id DESC';
                break;
            case 'statusup':
                $order = 'status ASC';
                break;
            case 'statusdown':
                $order = 'status DESC';
                break;
            case 'hoursup':
                $order = 'hours ASC';
                break;
            case 'hoursdown':
                $order = 'hours DESC';
                break;
        }
        //init data variable
        $this->__data = [];

        $tb_tasks    = $this->__db->prefix('wsproject_tasks');
        $tb_projects = $this->__db->prefix('wsproject_projects');

        //Das ausgewählte Projekt holen
        $sql    = 'SELECT project_id, name, startdate, enddate, completed, completed_date, description, deleted FROM ' . $tb_projects . ' WHERE project_id=' . $this->_vars['project_id'];
        $result = $this->__db->queryF($sql);

        $project                = $this->__db->fetchArray($result);
        $project['name']        = stripslashes($project['name']);
        $project['description'] = stripslashes($project['description']);
        $project['done']        = 0; //initialiseren, falls eine spätere query keien daten zu dem projekt liefert
        $project['todo']        = 0;
        $this->__data           = $project;
        $this->__db->freeRecordSet($result);

        $this->__data['sort'] = $sortorder;
        //get sum houres done
        $sql    = 'SELECT SUM(hours) AS done FROM ' . $tb_tasks . ' WHERE status = 100 AND deleted = 0 AND project_id=' . $this->_vars['project_id'];
        $result = $this->__db->queryF($sql);
        while ($done = $this->__db->fetchArray($result)) {
            $this->__data['done'] = (($done['done'] != '') ? $done['done'] : 0);
        }

        //get sum houres open
        $sql    = 'SELECT SUM(hours) AS todo FROM ' . $tb_tasks . ' WHERE status < 100 AND deleted = 0 AND project_id=' . $this->_vars['project_id'];
        $result = $this->__db->queryF($sql);
        while ($todo = $this->__db->fetchArray($result)) {
            $this->__data['todo'] = (($todo['todo'] != '') ? $todo['todo'] : 0);
        }

        //calculate curent status and set info text's
        getStatusInfos($this->__data);

        //get all tasks
        $sql    = 'SELECT task_id, user_id, title, hours, status, public, parent_id FROM ' . $tb_tasks . ' WHERE deleted = 0 AND project_id=' . $this->_vars['project_id'] . ' ORDER BY ' . $order;
        $result = $this->__db->queryF($sql);
        while ($task = $this->__db->fetchArray($result)) {
            //hier nur setzen der Felder, da eventuell schon andere Info's gesetzt wurden, wenn bereits eine Unteraufgabe in der DB stand
            $this->__data['tasks'][$task['task_id']]['task_id']       = $task['task_id'];
            $this->__data['tasks'][$task['task_id']]['user_id']       = $task['user_id'];
            $this->__data['tasks'][$task['task_id']]['title']         = stripslashes($task['title']);
            $this->__data['tasks'][$task['task_id']]['hours']         = $task['hours'];
            $this->__data['tasks'][$task['task_id']]['status']        = $task['status'];
            $this->__data['tasks'][$task['task_id']]['public']        = $task['public'];
            $this->__data['tasks'][$task['task_id']]['parent_id']     = $task['parent_id'];
            $this->__data['tasks'][$task['task_id']]['user']['owner'] = ($this->_isProjectAdmin($this->_vars['project_id']) or ($this->_xoopsUser->getVar('uid') == $task['user_id']));

            if (!isset($this->__data['tasks'][$task['task_id']]['somechildrendone'])) {
                $this->__data['tasks'][$task['task_id']]['somechildrendone'] = 'false';
            }

            if (!isset($this->__data['tasks'][$task['task_id']]['children'])) {
                $this->__data['tasks'][$task['task_id']]['children']          = null;
                $this->__data['tasks'][$task['task_id']]['childrencompleted'] = 'true';
            }
            if ($task['parent_id'] != '0') {
                $this->__data['tasks'][$task['parent_id']]['children'][] =& $this->__data['tasks'][$task['task_id']];

                //Wenn noch nicht gesetzt, schon mal setzen, wird korregiert, wenn der Datensatz kommt
                if (!isset($this->__data['tasks'][$task['parent_id']]['indent'])) {
                    $this->__data['tasks'][$task['parent_id']]['indent'] = 0;
                }

                if ($task['status'] != '100') {
                    $this->__data['tasks'][$task['parent_id']]['childrencompleted'] = 'false';
                } elseif (!isset($this->__data['tasks'][$task['parent_id']]['childrencompleted'])) {
                    $this->__data['tasks'][$task['parent_id']]['childrencompleted'] = 'true';
                }

                if ($task['status'] == '100') {
                    $this->__data['tasks'][$task['parent_id']]['somechildrendone'] = 'true';
                    if ($task['parent_id'] != 0) {
                        $t = $this->__data['tasks'][$task['parent_id']];
                        while (isset($t['parent_id']) and ($t['parent_id'] != 0) and ($t['parent_id'] != null)) {
                            $this->__data['tasks'][$t['parent_id']]['somechildrendone'] = 'true';
                            $t                                                          = $this->__data['tasks'][$t['parent_id']];
                        }
                    }
                }

                $this->__data['tasks'][$task['task_id']]['indent'] = $this->__data['tasks'][$task['parent_id']]['indent'] + 10;

                if ($this->__data['tasks'][$task['task_id']]['children'] != null) {
                    $this->_setCorrectIndent($task['task_id']);
                }
            } else {
                $this->__data['tasks'][$task['task_id']]['indent'] = 0;
            }

            $user = $this->_memberHandler->getUser($task['user_id']);
            if ($user != null) {
                $this->__data['tasks'][$task['task_id']]['uname'] = $user->getVar('uname');
            }
        }
        $this->sortTasksBySubTasks();
        $this->__data['user']['admin']        = $this->_isAdmin();
        $this->__data['user']['projectadmin'] = $this->_isProjectAdmin($this->_vars['project_id']);
    }

    /**
     * @desc Setzt die lokalisierten Strings
     */
    public function setLanguageData()
    {
        $this->__lang['completeproject'] = _WS_COMPLETEPROJECT;
        $this->__lang['addtask']         = _WS_ADDTASK;
        $this->__lang['delete']          = _WS_DELETE;
        $this->__lang['edit']            = _WS_EDIT;
        $this->__lang['projectstart']    = _WS_PROJECTSTART;
        $this->__lang['projectdeadline'] = _WS_PROJECTDEADLINE;
        $this->__lang['todo']            = _WS_TODO;
        $this->__lang['respuser']        = _WS_RESP_USER;
        $this->__lang['hours']           = _WS_HOURS;
        $this->__lang['status']          = _WS_STATUS;
        $this->__lang['action']          = _WS_ACTION;
        $this->__lang['completedtasks']  = _WS_COMPLETEDTASKS;
        $this->__lang['hourstodo']       = _WS_HOURSTODO;
        $this->__lang['hoursdone']       = _WS_HOURSDONE;
        $this->__lang['reactivate']      = _WS_REACTIVATE;
    }

    /**
     * @param     $series_id
     * @param     $newParent_id
     * @param int $sParent_id
     */
    public function addSeriesTask($series_id, $newParent_id, $sParent_id = 0)
    {
        $sql    = 'SELECT * FROM `' . $this->__db->prefix('wsproject_tasks') . "` WHERE project_id like $series_id AND parent_id = $sParent_id AND NOT deleted";
        $result = $this->__db->queryF($sql);
        while ($task = $GLOBALS['xoopsDB']->fetchBoth($result, MYSQL_ASSOC)) {
            $nextParent_id = $this->addNewTask($newParent_id, $task['user_id'], $task['title'], $task['hours'], $task['description']);
            $this->addSeriesTask($series_id, $nextParent_id, $task['task_id']);
        }
    }

    /**
     * @param        $parent_id
     * @param        $user_id
     * @param        $title
     * @param int    $hours
     * @param string $description
     * @return mixed
     */
    public function addNewTask($parent_id, $user_id, $title, $hours = 1, $description = '')
    {
        $sql    = 'INSERT INTO ' . $this->__db->prefix('wsproject_tasks') . " (project_id, user_id, title,
        hours, startdate, enddate, description, status, public, parent_id, image, deleted)
        VALUES ('" . $this->_vars['project_id'] . "',
	   '$user_id',
	   '$title',
	   '$hours',
        NOW(),
	   '0',
	   '$description',
	   '0',
	   '" . (isset($this->_vars['public']) ? '1' : '0') . "',
	   '$parent_id', 'none',	'0')";
        $result = $this->__db->queryF($sql);

        //  return $GLOBALS['xoopsDB']->getInsertId($result);
        return $this->__db->getInsertId();
        //echo "<br> $sql Result:". $result;
    }
}

/**
 * @desc    showProject zeigt das gewählte Projekte an<br>
 * es werden alle erledingten und noch zu erledigenden Aufgaben angezeigt
 *
 * @author  Stefan Marr <wspm@stefan-marr.de>
 * @version 0.0.1, 07/23/2003
 * @public
 */
class showTask extends wsClass
{
    /** @private */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \listProjects|\showTask
     */
    public static function getInstance()
    {
        $action = new showTask();
        if (!$action->_isProjectUser(null, $GLOBALS['task_id'])) {
            $action = new listProjects();
        }

        return $action;
    }

    public function processInput()
    {
        if (isset($this->_vars['subop'])) {
            if (($this->_vars['subop'] == 'save') and $this->_isTaskOwner($this->_vars['task_id'])) {
                $sql      = 'SELECT enddate, status FROM ' . $this->__db->prefix('wsproject_tasks') . ' WHERE task_id=' . $this->_vars['task_id'];
                $result   = $this->__db->queryF($sql);
                $old      = $this->__db->fetchArray($result);
                $qry_part = ''; //Genutz um die Query zu verändern

                if (($old['status'] != $this->_vars['status']) and ($this->_vars['status'] == '100')) {
                    $qry_part = ', enddate = NOW()';

                    $tasks    = 'task_id=' . $this->_vars['task_id'] . ' ';
                    $children = $this->_getSubTasks($this->_vars['task_id']);
                    foreach ($children as $child) {
                        $tasks .= 'OR task_id=' . $child . ' ';
                    }

                    $sql = 'UPDATE ' . $this->__db->prefix('wsproject_tasks') . ' SET status=100, enddate=NOW() WHERE ' . $tasks;

                    $result = $this->__db->queryF($sql);
                } elseif (($old['status'] != $this->_vars['status']) and ($old['status'] == '100')) {
                    $this->_restartParentTasks($this->_vars['task_id']);
                }

                if ($this->_isProjectAdmin($this->_vars['task_id'])) {
                    $sql = 'UPDATE ' . $this->__db->prefix('wsproject_tasks') . " SET 
								user_id='" . $this->_vars['user_id'] . "',
								title='" . $this->_vars['title'] . "',
								hours='" . $this->_vars['hours'] . "',
								description='" . $this->_vars['description'] . "',
								status='" . $this->_vars['status'] . "',
								public='" . (isset($this->_vars['public']) ? '1' : '0') . "',
								parent_id='" . $this->_vars['parent_id'] . "'
								 " . $qry_part . ' WHERE task_id=' . $this->_vars['task_id'];
                } else {
                    $sql = 'UPDATE ' . $this->__db->prefix('wsproject_tasks') . " SET
								hours='" . $this->_vars['hours'] . "',								
								status='" . $this->_vars['status'] . "'
								 " . $qry_part . ' WHERE task_id=' . $this->_vars['task_id'];
                }
                $result = $this->__db->queryF($sql);

                $sql        = 'SELECT project_id FROM ' . $this->__db->prefix('wsproject_tasks') . ' WHERE task_id=' . $this->_vars['task_id'];
                $result     = $this->__db->queryF($sql);
                $project_id = $this->__db->fetchArray($result);
                $project_id = $project_id['project_id'];

                //notify user
                $extra_tags['TASK_NAME']   = $this->_vars['title'];
                $extra_tags['TASK_HOURS']  = $this->_vars['hours'];
                $extra_tags['TASK_DESC']   = $this->_vars['description'];
                $extra_tags['TASK_URL']    = XOOPS_URL . '/modules/wsproject/?task_id=' . $this->_vars['task_id'];
                $extra_tags['TASK_STATUS'] = $this->_vars['status'] . '%';

                //uses XoopsNotify System
                $notificationHandler = xoops_getHandler('notification');
                $notificationHandler->triggerEvent('project', $project_id, 'edit_task', $extra_tags);
            }
        }
        $this->_getData();
        $this->__tpl = 'showTask.tpl';
        $this->setLanguageData();
    }

    /**
     * @desc protected holt die Daten aus der DB
     * @protected
     */
    protected function _getData()
    {
        //init data variable
        $this->__data = [];

        $tb_tasks    = $this->__db->prefix('wsproject_tasks');
        $tb_projects = $this->__db->prefix('wsproject_projects');

        //Die gewählte Aufgabe holen
        $sql    = 'SELECT task_id, project_id, user_id, title, hours, startdate, enddate, status, description, public, parent_id FROM ' . $tb_tasks . ' WHERE task_id=' . $this->_vars['task_id'];
        $result = $this->__db->queryF($sql);

        while ($task = $this->__db->fetchArray($result)) {
            $task['title']       = stripslashes($task['title']);
            $task['description'] = stripslashes($task['description']);
            $this->__data        = $task;
        }
        $this->__db->freeRecordSet($result);

        //get parent task
        $sql    = 'SELECT task_id, project_id, user_id, title, hours, startdate, enddate, status, description, public FROM ' . $tb_tasks . ' WHERE task_id=' . $this->__data['parent_id'];
        $result = $this->__db->queryF($sql);
        while ($parent = $this->__db->fetchArray($result)) {
            $parent['title']        = stripslashes($parent['title']);
            $parent['description']  = stripslashes($parent['description']);
            $this->__data['parent'] = $parent;
        }
        $this->__db->freeRecordSet($result);

        //get children tasks
        $sql    = 'SELECT task_id, project_id, user_id, title, hours, startdate, enddate, status, description, public FROM ' . $tb_tasks . ' WHERE parent_id=' . $this->__data['task_id'];
        $result = $this->__db->queryF($sql);
        while ($child = $this->__db->fetchArray($result)) {
            $child['title']             = stripslashes($child['title']);
            $child['description']       = stripslashes($child['description']);
            $this->__data['children'][] = $child;
        }
        $this->__db->freeRecordSet($result);

        //get Projekt Name
        $sql    = 'SELECT name FROM ' . $tb_projects . ' WHERE project_id=' . $this->__data['project_id'];
        $result = $this->__db->queryF($sql);
        while ($projekt = $this->__db->fetchArray($result)) {
            $this->__data['project_name'] = stripslashes($projekt['name']);
        }
        $this->__db->freeRecordSet($result);

        $user = $this->_memberHandler->getUser($this->__data['user_id']);
        if ($user != null) {
            $this->__data['uname'] = $user->getVar('uname');
        }

        $this->__data['user']['owner'] = $this->_isTaskOwner($this->_vars['task_id']);
    }

    /**
     * @desc Setzt die lokalisierten Strings
     */
    public function setLanguageData()
    {
        $this->__lang['showproject'] = _WS_SHOWPROJECT;
        $this->__lang['projectname'] = _WS_PROJECTNAME;
        $this->__lang['taskname']    = _WS_TASKNAME;
        $this->__lang['assignedto']  = _WS_ASSIGNEDTO;
        $this->__lang['notifyuser']  = _WS_NOTIFYUSER;
        $this->__lang['quotedhours'] = _WS_QUOTEDHOURS;
        $this->__lang['public']      = _WS_PUBLIC;
        $this->__lang['subtaskof']   = _WS_SUBTASKOF;
        $this->__lang['toplevel']    = _WS_TOPLEVEL;
        $this->__lang['description'] = _WS_DESCRIPTION;
        $this->__lang['addtask']     = _WS_ADDTASK;
        $this->__lang['subtasks']    = _WS_SUBTASKS;
        $this->__lang['edit']        = _WS_EDIT;
        $this->__lang['status']      = _WS_STATUS;
    }

    /**
     * @desc Gibt True zurück, wenn Xoops Kommentare angezeigt werden sollen
     */
    public function showComments()
    {
        return true;
    }
}

/**
 * @desc    addTask behandelt das hinzufügen von Aufgaben zu Projekten
 *
 * @author  Stefan Marr <wspm@stefan-marr.de>
 * @version 0.0.1, 07/23/2003
 * @public
 */
class addTask extends wsClass
{

    /** @private */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \addTask|\listProjects
     */
    public static function getInstance()
    {
        $action = new addTask();
        if (!$action->_isProjectAdmin($GLOBALS['project_id'])) {
            $action = new listProjects();
        }

        return $action;
    }

    public function processInput()
    {
        //Fertigstellen und Löschen der Aufgaben
        $this->_getData();
        $this->_getSeriesOption();
        $this->__tpl = 'addTask.tpl';
        $this->setLanguageData();
    }

    public function _getSeriesOption()
    {
        $count  = 0;
        $table  = "<table>\n";
        $table  .= "<tr><td><input type=radio name='series' value=''> " . _WS_SERIES_NONE . " </td></tr>\n";
        $sql    = 'SELECT * FROM ' . $this->__db->prefix('wsproject_projects') . ' WHERE NOT deleted';
        $result = $this->__db->queryF($sql);
        while ($projects = $this->__db->fetchArray($result)) {
            $projectName = stripslashes($projects['name']);
            if (strpos(' ' . $projectName, '*')) {
                if ($projects[project_id] != $this->__data['project_id']) {
                    $projectID = stripslashes($projects['project_id']);
                    $table     .= "<tr><td><input type=radio name='series' value='$projectID'> $projectName </td></tr>\n";
                    $count++;
                }
            }
        }
        $table .= "</table>\n";
        if ($count) {
            $this->__data['series_option'] = $table;
        } else {
            $this->__data['series_option'] = _WS_SERIES_HINT;
        }
    }

    /**
     * @desc protected holt die Daten aus der DB
     * @protected
     */
    protected function _getData()
    {
        //init data variable
        $this->__data = [];

        $tb_tasks    = $this->__db->prefix('wsproject_tasks');
        $tb_projects = $this->__db->prefix('wsproject_projects');

        //get Projekt Name
        $sql    = 'SELECT name FROM ' . $tb_projects . ' WHERE project_id=' . $this->_vars['project_id'];
        $result = $this->__db->queryF($sql);
        while ($projekt = $this->__db->fetchArray($result)) {
            $this->__data['project_name'] = stripslashes($projekt['name']);
            $this->__data['project_id']   = $this->_vars['project_id'];
        }
        $this->__db->freeRecordSet($result);

        //Benutzer holen, denen Aufgaben zugewiesen werden können
        $this->__data['users'] = [];
        $groups                = $this->_getProjectUserGroups($this->_vars['project_id']);
        foreach ($groups as $group) {
            $userslist = $this->_memberHandler->getUsersByGroup($group, true);
            /** Sorting Patch on 12/08/2005
             * Added username sorting algorythm before output in tasks. By Azmeen */
            $z = [];    /* Create a new temp array to store sorted username list */
            foreach ($userslist as $user) {
                $u['uid']   = $user->getVar('uid');
                $u['uname'] = $user->getVar('uname');
                /*	Hack to sort users alphabetically...
                        This should be much more simple to implement!
                        But seeing the way how crazy the OOP can be in Xoops,
                        this became a severe PITA!!!
                */
                $z['' . $u['uid'] . ''] = $u['uname'];    /* The magic's in the makeup */
                //$this->__data['users'][] = $u;
            }
            /* "Make it so!" */
            asort($z);
            foreach ($z as $key => $value) {
                //$bwargh .= "Key: $key		Value: $value\n";
                $fx['uid']               = $key;
                $fx['uname']             = $value;
                $this->__data['users'][] = $fx;
            }
            /* Patch End */
        }
        //wenn kein user gefunden werden sollte, z.B. wenn keine gruppe gewählt wurde
        if (count($this->__data['users']) == 0) {
            $u['uid']                         = 0;
            $u['uname']                       = _WS_PLEASEADDGROUP;
            $this->__data['users'][$u['uid']] = $u;
        }

        //get all tasks
        $sql    = 'SELECT task_id, title, parent_id FROM ' . $tb_tasks . ' WHERE deleted = 0 AND status < 100 AND project_id=' . $this->_vars['project_id'] . ' ORDER BY parent_id';
        $result = $this->__db->queryF($sql);
        while ($task = $this->__db->fetchArray($result)) {
            //hier nur setzen der Felder, da eventuell schon andere Info's gesetzt wurden, wenn bereits eine Unteraufgabe in der DB stand
            $this->__data['tasks'][$task['task_id']]['task_id']   = $task['task_id'];
            $this->__data['tasks'][$task['task_id']]['title']     = stripslashes($task['title']);
            $this->__data['tasks'][$task['task_id']]['parent_id'] = $task['parent_id'];

            if (!isset($this->__data['tasks'][$task['task_id']]['somechildrendone'])) {
                $this->__data['tasks'][$task['task_id']]['somechildrendone'] = 'false';
            }

            if (!isset($this->__data['tasks'][$task['task_id']]['children'])) {
                $this->__data['tasks'][$task['task_id']]['children']          = null;
                $this->__data['tasks'][$task['task_id']]['childrencompleted'] = 'true';
            }
            if ($task['parent_id'] != '0') {
                $this->__data['tasks'][$task['parent_id']]['children'][] =& $this->__data['tasks'][$task['task_id']];

                //Wenn noch nicht gesetzt, schon mal setzen, wird korregiert, wenn der Datensatz kommt
                if (!isset($this->__data['tasks'][$task['parent_id']]['indent'])) {
                    $this->__data['tasks'][$task['parent_id']]['indent'] = 0;
                }

                $this->__data['tasks'][$task['task_id']]['indent'] = $this->__data['tasks'][$task['parent_id']]['indent'] + 10;

                if ($this->__data['tasks'][$task['task_id']]['children'] != null) {
                    $this->_setCorrectIndent($task['task_id']);
                }
            } else {
                $this->__data['tasks'][$task['task_id']]['indent'] = 0;
            }
        }
        $this->sortTasksBySubTasks();
        $this->__data['user']['projectadmin'] = $this->_isProjectAdmin($this->_vars['project_id']);
        $this->__data['user']['admin']        = $this->_isAdmin();
    }

    /**
     * @desc Setzt die lokalisierten Strings
     */
    public function setLanguageData()
    {
        $this->__lang['showproject'] = _WS_SHOWPROJECT;
        $this->__lang['projectname'] = _WS_PROJECTNAME;
        $this->__lang['taskname']    = _WS_TASKNAME;
        $this->__lang['assignedto']  = _WS_ASSIGNEDTO;
        $this->__lang['notifyuser']  = _WS_NOTIFYUSER;
        $this->__lang['quotedhours'] = _WS_QUOTEDHOURS;
        $this->__lang['public']      = _WS_PUBLIC;
        $this->__lang['subtaskof']   = _WS_SUBTASKOF;
        $this->__lang['toplevel']    = _WS_TOPLEVEL;
        $this->__lang['description'] = _WS_DESCRIPTION;
        $this->__lang['addtask']     = _WS_ADDTASK;
        $this->__lang['series']      = _WS_SERIES;
        $this->__lang['series_none'] = _WS_SERIES_NONE;
    }
}

/**
 * @desc    editProject behandelt das Bearbeiten von Projekten
 *
 * @author  Stefan Marr <wspm@stefan-marr.de>
 * @version 0.0.1, 07/23/2003
 * @public
 */
class editProject extends wsClass
{
    /**
     * editProject constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \editProject|\listProjects
     */
    public static function getInstance()
    {
        $action = new editProject();
        if (!$action->_isAdmin()) {
            $action = new listProjects();
        }

        return $action;
    }

    public function processInput()
    {
        if (isset($this->_vars['subop'])) {
            if ($this->_vars['subop'] == 'save') {
                $start  = $this->_vars['startyear'] . '-' . $this->_vars['startmonth'] . '-' . $this->_vars['startday'];
                $end    = $this->_vars['endyear'] . '-' . $this->_vars['endmonth'] . '-' . $this->_vars['endday'];
                $sql    = 'UPDATE ' . $this->__db->prefix('wsproject_projects') . " SET
								name = '" . $this->_vars['name'] . "',
								startdate = '" . $start . "',
								enddate = '" . $end . "',
								description= '" . $this->_vars['description'] . "' WHERE project_id='" . $this->_vars['project_id'] . "'";
                $result = $this->__db->queryF($sql);

                $tb_res = $this->__db->prefix('wsproject_restrictions');
                //delete old restrictions
                $sql    = 'DELETE FROM ' . $tb_res . " WHERE project_id='" . $this->_vars['project_id'] . "'";
                $result = $this->__db->queryF($sql);

                //add user restrictions
                if (isset($this->_vars['projectadmin_groups'])) {
                    foreach ($this->_vars['projectadmin_groups'] as $group_id) {
                        $sql    = 'INSERT INTO ' . $tb_res . " (group_id, project_id, user_rank)
							VALUES ('" . $group_id . "',
									'" . $this->_vars['project_id'] . "',
									'" . _WSRES_ADMIN . "')";
                        $result = $this->__db->queryF($sql);
                    }
                }

                if (isset($this->_vars['projectuser_groups'])) {
                    foreach ($this->_vars['projectuser_groups'] as $group_id) {
                        $sql    = 'INSERT INTO ' . $tb_res . " (group_id, project_id, user_rank)
							VALUES ('" . $group_id . "',
									'" . $this->_vars['project_id'] . "',
									'" . _WSRES_USER . "')";
                        $result = $this->__db->queryF($sql);
                    }
                }
            }
        }

        //Fertigstellen und Löschen der Aufgaben
        $this->_getData();
        $this->__tpl = 'editProject.tpl';
        $this->setLanguageData();
    }

    /**
     * @desc protected holt die Daten aus der DB
     * @protected
     */
    protected function _getData()
    {
        //init data variable
        $this->__data = [];
        $this->__data = getDateInfo();
        $tb_projects  = $this->__db->prefix('wsproject_projects');

        //get Projekt Name
        $sql     = 'SELECT name, startdate, enddate, description FROM ' . $tb_projects . ' WHERE project_id=' . $this->_vars['project_id'];
        $result  = $this->__db->queryF($sql);
        $project = $this->__db->fetchArray($result);

        $project['name']        = stripslashes($project['name']);
        $project['description'] = stripslashes($project['description']);

        $this->__data               = array_merge($this->__data, $project);
        $this->__data['project_id'] = $this->_vars['project_id'];
        $this->__db->freeRecordSet($result);

        $this->__data['startyear']  = (int)substr($project['startdate'], 0, 4);
        $this->__data['startmonth'] = (int)substr($project['startdate'], 5, 2);
        $this->__data['startday']   = (int)substr($project['startdate'], 8, 2);
        $this->__data['endyear']    = (int)substr($project['enddate'], 0, 4);
        $this->__data['endmonth']   = (int)substr($project['enddate'], 5, 2);
        $this->__data['endday']     = (int)substr($project['enddate'], 8, 2);

        $this->__data['groups']             = $this->_memberHandler->getGroupList();
        $this->__data['projectadmingroups'] = $this->_getProjectAdminGroups($this->_vars['project_id']);
        $this->__data['projectusergroups']  = $this->_getProjectUserGroups($this->_vars['project_id']);
    }

    /**
     * @desc Setzt die lokalisierten Strings
     */
    public function setLanguageData()
    {
        $this->__lang['showproject']      = _WS_SHOWPROJECT;
        $this->__lang['projectname']      = _WS_PROJECTNAME;
        $this->__lang['deadline']         = _WS_DEADLINE;
        $this->__lang['startdate']        = _WS_STARTDATE;
        $this->__lang['comments']         = _WS_COMMENTS;
        $this->__lang['updateproject']    = _WS_UPDATEPROJECT;
        $this->__lang['restore']          = _WS_RESTORE;
        $this->__lang['projectuser']      = _WS_PROJECTUSER;
        $this->__lang['projectusernote']  = _WS_PROJECTUSERNOTE;
        $this->__lang['projectadmin']     = _WS_PROJECTADMIN2;
        $this->__lang['projectadminnote'] = _WS_PROJECTADMINNOTE;
    }
}

/**
 * @desc    editProject behandelt das Bearbeiten von Projekten
 *
 * @author  Stefan Marr <wspm@stefan-marr.de>
 * @version 0.0.1, 07/23/2003
 * @public
 */
class addProject extends wsClass
{

    /** @private */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \addProject|\listProjects
     */
    public static function getInstance()
    {
        $action = new addProject();
        if (!$action->_isAdmin()) {
            $action = new listProjects();
        }

        return $action;
    }

    public function processInput()
    {
        //Fertigstellen und Löschen der Aufgaben
        $this->_getData();
        $this->__tpl = 'addProject.tpl';
        $this->setLanguageData();
    }

    /**
     * @desc protected holt die Daten aus der DB
     * @protected
     */
    protected function _getData()
    {
        //init data variable
        $this->__data = [];
        $tb_projects  = $this->__db->prefix('wsproject_projects');

        $this->__data = getDateInfo();

        $this->__data['groups'] = $this->_memberHandler->getGroupList();
    }

    /**
     * @desc Setzt die lokalisierten Strings
     */
    public function setLanguageData()
    {
        $this->__lang['projectoverview']  = _WS_PROJECTOVERVIEW;
        $this->__lang['projectname']      = _WS_PROJECTNAME;
        $this->__lang['deadline']         = _WS_DEADLINE;
        $this->__lang['startdate']        = _WS_STARTDATE;
        $this->__lang['comments']         = _WS_COMMENTS;
        $this->__lang['addproject']       = _WS_ADDPROJECT;
        $this->__lang['projectuser']      = _WS_PROJECTUSER;
        $this->__lang['projectusernote']  = _WS_PROJECTUSERNOTE;
        $this->__lang['projectadmin']     = _WS_PROJECTADMIN2;
        $this->__lang['projectadminnote'] = _WS_PROJECTADMINNOTE;
    }
}

/**
 * @desc    deleteProject behandelt das Löschen eines Projektes
 *
 * @author  Stefan Marr <wspm@stefan-marr.de>
 * @version 0.0.1, 07/23/2003
 * @public
 */
class deleteProject extends wsClass
{

    /** @private */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \deleteProject|\listProjects
     */
    public static function getInstance()
    {
        $action = new deleteProject();
        if (!$action->_isAdmin()) {
            $action = new listProjects();
        }

        return $action;
    }

    public function processInput()
    {
        //Fertigstellen und Löschen der Aufgaben
        $this->_getData();
        $this->__tpl = 'deleteProject.tpl';
        $this->setLanguageData();
    }

    /**
     * @desc protected holt die Daten aus der DB
     * @protected
     */
    protected function _getData()
    {
        //init data variable
        $this->__data = [];

        $tb_projects = $this->__db->prefix('wsproject_projects');

        //get Projekt Name
        $sql    = 'SELECT name FROM ' . $tb_projects . ' WHERE project_id=' . $this->_vars['project_id'];
        $result = $this->__db->queryF($sql);
        while ($projekt = $this->__db->fetchArray($result)) {
            $this->__data['name']       = stripslashes($projekt['name']);
            $this->__data['project_id'] = $this->_vars['project_id'];
        }
        $this->__db->freeRecordSet($result);
    }

    /**
     * @desc Setzt die lokalisierten Strings
     */
    public function setLanguageData()
    {
        $this->__lang['yes']         = _YES;
        $this->__lang['no']          = _NO;
        $this->__lang['delproject1'] = _WS_DELPROJECT1;
        $this->__lang['delproject2'] = _WS_DELPROJECT2;
        $this->__lang['delwarning']  = _WS_DELWARNING;
    }
}

/**
 * @desc    deleteTask behandelt das Löschen einer Aufgabe
 *
 * @author  Stefan Marr <wspm@stefan-marr.de>
 * @version 0.0.1, 08/01/2003
 * @public
 */
class deleteTask extends wsClass
{

    /** @private */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \deleteTask|\listProjects
     */
    public static function getInstance()
    {
        $action = new deleteTask();
        if (!$action->_isProjectAdmin(null, $GLOBALS['task_id'])) {
            $action = new listProjects();
        }

        return $action;
    }

    public function processInput()
    {
        //Fertigstellen und Löschen der Aufgaben
        $this->_getData();
        $this->__tpl = 'deleteTask.tpl';
        $this->setLanguageData();
    }

    /**
     * @desc protected holt die Daten aus der DB
     * @protected
     */
    protected function _getData()
    {
        //init data variable
        $this->__data = [];

        $tb_tasks = $this->__db->prefix('wsproject_tasks');

        //get Projekt Name
        $sql                        = 'SELECT title, project_id FROM ' . $tb_tasks . ' WHERE task_id=' . $this->_vars['task_id'];
        $result                     = $this->__db->queryF($sql);
        $task                       = $this->__db->fetchArray($result);
        $this->__data['title']      = stripslashes($task['title']);
        $this->__data['project_id'] = $task['project_id'];
        $this->__data['task_id']    = $this->_vars['task_id'];
        $this->__db->freeRecordSet($result);

        //Check for subtasks
        $sql                         = 'SELECT count(*) AS subcount FROM ' . $tb_tasks . ' WHERE parent_id=' . $this->_vars['task_id'];
        $result                      = $this->__db->queryF($sql);
        $count                       = $this->__db->fetchArray($result);
        $this->__data['haschildren'] = (($count['subcount'] > 0) ? true : false);
        $this->__db->freeRecordSet($result);
    }

    /**
     * @desc Setzt die lokalisierten Strings
     */
    public function setLanguageData()
    {
        $this->__lang['yes']          = _YES;
        $this->__lang['no']           = _NO;
        $this->__lang['warning']      = _WS_WARNING;
        $this->__lang['childwarning'] = _WS_CHILDWARNING;
        $this->__lang['deltask']      = _WS_DELTASK;
    }
}

/**
 * @desc    editTask behandelt das Bearbeiten von Aufgaben
 *
 * @author  Stefan Marr <wspm@stefan-marr.de>
 * @version 0.0.1, 07/23/2003
 * @public
 */
class editTask extends wsClass
{

    /** @private */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \editTask|\listProjects
     */
    public static function getInstance()
    {
        $action = new editTask();
        if (!$action->_isTaskOwner($GLOBALS['task_id'])) {
            $action = new listProjects();
        }

        return $action;
    }

    public function processInput()
    {
        //Fertigstellen und Löschen der Aufgaben
        $this->_getData();
        $this->__tpl = 'editTask.tpl';
        $this->setLanguageData();
    }

    /**
     * @desc protected holt die Daten aus der DB
     * @protected
     */
    protected function _getData()
    {
        //init data variable
        $this->__data = [];

        $tb_tasks    = $this->__db->prefix('wsproject_tasks');
        $tb_projects = $this->__db->prefix('wsproject_projects');

        //get task
        $sql                 = 'SELECT project_id, user_id, title, hours, description, status, public, parent_id FROM ' . $tb_tasks . ' WHERE task_id=' . $this->_vars['task_id'];
        $result              = $this->__db->queryF($sql);
        $task                = $this->__db->fetchArray($result);
        $task['title']       = stripslashes($task['title']);
        $task['description'] = stripslashes($task['description']);
        $this->__db->freeRecordSet($result);
        $this->__data            = $task;
        $this->__data['task_id'] = $this->_vars['task_id'];

        if ($task['parent_id'] == '0') {
            $this->__data['parent_name'] = _WS_TOPLEVEL;
        }

        $user = $this->_memberHandler->getUser($task['user_id']);
        if ($user != null) {
            $this->__data['user_name'] = $user->getVar('uname');
        }

        //get Projekt Name
        $sql                          = 'SELECT name FROM ' . $tb_projects . ' WHERE project_id=' . $task['project_id'];
        $result                       = $this->__db->queryF($sql);
        $projekt                      = $this->__db->fetchArray($result);
        $this->__data['project_name'] = stripslashes($projekt['name']);
        $this->__db->freeRecordSet($result);

        //Benutzer holen, denen Aufgaben zugewiesen werden können
        $this->__data['users'] = [];
        $groups                = $this->_getProjectUserGroups($task['project_id']);
        foreach ($groups as $group) {
            $userslist = $this->_memberHandler->getUsersByGroup($group, true);
            $z         = [];    /* Create a new temp array to store sorted username list */
            foreach ($userslist as $user) {
                $u['uid']   = $user->getVar('uid');
                $u['uname'] = $user->getVar('uname');
                /*	Hack to sort users alphabetically...
                        This should be much more simple to implement!
                        But seeing the way how crazy the OOP can be in Xoops,
                        this became a severe PITA!!!
                */
                $z['' . $u['uid'] . ''] = $u['uname'];    /* The magic's in the makeup */
                //$this->__data['users'][] = $u;
            }
            /* "Make it so!" */
            asort($z);
            foreach ($z as $key => $value) {
                //$bwargh .= "Key: $key		Value: $value\n";
                $fx['uid']               = $key;
                $fx['uname']             = $value;
                $this->__data['users'][] = $fx;
            }
            /* "It has been made!" */
        }

        //get all tasks
        $sql    = 'SELECT task_id, title, parent_id FROM ' . $tb_tasks . ' WHERE deleted = 0 AND status < 100 AND project_id=' . $task['project_id'] . ' ORDER BY parent_id';
        $result = $this->__db->queryF($sql);
        while ($task = $this->__db->fetchArray($result)) {
            //Nammen der Übergeordneten aufgabe setzen
            if ($task['task_id'] == $this->__data['parent_id']) {
                $this->__data['parent_name'] = stripslashes($task['title']);
            }

            //hier nur setzen der Felder, da eventuell schon andere Info's gesetzt wurden, wenn bereits eine Unteraufgabe in der DB stand
            $this->__data['tasks'][$task['task_id']]['task_id']   = $task['task_id'];
            $this->__data['tasks'][$task['task_id']]['title']     = $task['title'];
            $this->__data['tasks'][$task['task_id']]['parent_id'] = $task['parent_id'];

            if (!isset($this->__data['tasks'][$task['task_id']]['somechildrendone'])) {
                $this->__data['tasks'][$task['task_id']]['somechildrendone'] = 'false';
            }

            if (!isset($this->__data['tasks'][$task['task_id']]['children'])) {
                $this->__data['tasks'][$task['task_id']]['children']          = null;
                $this->__data['tasks'][$task['task_id']]['childrencompleted'] = 'true';
            }
            if ($task['parent_id'] != '0') {
                $this->__data['tasks'][$task['parent_id']]['children'][] =& $this->__data['tasks'][$task['task_id']];

                //Wenn noch nicht gesetzt, schon mal setzen, wird korregiert, wenn der Datensatz kommt
                if (!isset($this->__data['tasks'][$task['parent_id']]['indent'])) {
                    $this->__data['tasks'][$task['parent_id']]['indent'] = 0;
                }

                $this->__data['tasks'][$task['task_id']]['indent'] = $this->__data['tasks'][$task['parent_id']]['indent'] + 10;

                if ($this->__data['tasks'][$task['task_id']]['children'] != null) {
                    $this->_setCorrectIndent($task['task_id']);
                }
            } else {
                $this->__data['tasks'][$task['task_id']]['indent'] = 0;
            }
        }

        $this->sortTasksBySubTasks();
        $this->__data['user']['projectadmin'] = $this->_isProjectAdmin($this->__data['project_id']);
        $this->__data['user']['owner']        = $this->_isTaskOwner($this->_vars['task_id']);
    }

    /**
     * @desc Setzt die lokalisierten Strings
     */
    public function setLanguageData()
    {
        $this->__lang['back']        = _BACK;
        $this->__lang['projectname'] = _WS_PROJECTNAME;
        $this->__lang['taskname']    = _WS_TASKNAME;
        $this->__lang['assignedto']  = _WS_ASSIGNEDTO;
        $this->__lang['notifyuser']  = _WS_NOTIFYUSER;
        $this->__lang['quotedhours'] = _WS_QUOTEDHOURS;
        $this->__lang['status']      = _WS_STATUS;
        $this->__lang['public']      = _WS_PUBLIC;
        $this->__lang['subtaskof']   = _WS_SUBTASKOF;
        $this->__lang['toplevel']    = _WS_TOPLEVEL;
        $this->__lang['description'] = _WS_DESCRIPTION;
        $this->__lang['restore']     = _WS_RESTORE;
        $this->__lang['updatetask']  = _WS_UPDATETASK;
    }
}
