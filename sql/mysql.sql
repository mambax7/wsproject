# wsProject - database structur
# Version 1.0


#
# Table structure for table 'ws_restrictions'
#

CREATE TABLE ws_restrictions (
  res_id     TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id    TINYINT(3) UNSIGNED          DEFAULT '0',
  group_id   TINYINT(3) UNSIGNED          DEFAULT '0',
  task_id    TINYINT(3) UNSIGNED          DEFAULT '0',
  project_id TINYINT(3) UNSIGNED          DEFAULT '0',
  user_rank  TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (res_id),
  UNIQUE KEY res_id (res_id),
  KEY res_id_2 (res_id)
)
  ENGINE = MyISAM;

#
# Table structure for table 'project'
# This table contains your configuration
#

CREATE TABLE ws_project (
  conf_id    TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  conf_name  VARCHAR(20)         NOT NULL DEFAULT '0',
  conf_value VARCHAR(20)         NOT NULL DEFAULT '0',
  UNIQUE KEY conf_id (conf_id),
  KEY conf_id_2 (conf_id)
)
  ENGINE = MyISAM
  COMMENT ='Config for wsProject';

#
# Table structure for table 'projects'
# This table contains your projects
#

CREATE TABLE ws_projects (
  project_id     INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name           VARCHAR(100)              DEFAULT NULL,
  startdate      DATE                      DEFAULT NULL,
  enddate        DATE                      DEFAULT NULL,
  description    TEXT,
  completed      TINYINT(1)                DEFAULT '0',
  completed_date DATE                      DEFAULT NULL,
  deleted        TINYINT(1) UNSIGNED       DEFAULT '0',
  PRIMARY KEY (project_id)
)
  ENGINE = MyISAM;

#
# Table structure for table 'tasks'
#

CREATE TABLE ws_tasks (
  task_id     INT(10) UNSIGNED      NOT NULL AUTO_INCREMENT,
  project_id  INT(10) UNSIGNED      NOT NULL DEFAULT '1',
  user_id     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
  title       VARCHAR(100)                   DEFAULT NULL,
  hours       DECIMAL(4, 2)         NOT NULL DEFAULT '1.00',
  startdate   DATETIME                       DEFAULT NULL,
  enddate     DATETIME                       DEFAULT NULL,
  description TEXT,
  status      INT(3) UNSIGNED                DEFAULT '0',
  public      TINYINT(1) UNSIGNED   NOT NULL DEFAULT '1',
  parent_id   INT(10)               NOT NULL DEFAULT '0',
  image       VARCHAR(50)           NOT NULL DEFAULT 'none',
  deleted     TINYINT(1) UNSIGNED            DEFAULT '0',
  PRIMARY KEY (task_id),
  KEY project_id (project_id),
  KEY user_id (user_id)
)
  ENGINE = MyISAM;

