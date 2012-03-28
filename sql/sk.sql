CREATE TABLE IF NOT EXISTS sk_items (
  id int(8) NOT NULL,
  `name` varchar(64) NOT NULL,
  quality tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS sk_item_log (
  entry int(8) NOT NULL AUTO_INCREMENT,
  user_id int(8) NOT NULL,
  item_id int(8) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  list_id int(4) NOT NULL,
  raid_id int(8) NOT NULL DEFAULT '-1',
  pos_old int(4) NOT NULL,
  pos_new int(4) NOT NULL,
  lootmode tinyint(1) NOT NULL,
  signee int(8) NOT NULL,
  PRIMARY KEY (entry)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS sk_lists (
  id int(4) NOT NULL AUTO_INCREMENT,
  title varchar(32) NOT NULL,
  active_raid int(8) NOT NULL DEFAULT '-1',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS sk_list_position (
  list_id int(4) NOT NULL,
  user_id int(4) NOT NULL,
  position int(11) NOT NULL,
  raid_id int(8) NOT NULL DEFAULT '-1',
  PRIMARY KEY (list_id,position)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS sk_raids (
  id int(8) NOT NULL AUTO_INCREMENT,
  list_id int(8) NOT NULL,
  title varchar(64) NOT NULL,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS sk_users (
  id int(4) NOT NULL AUTO_INCREMENT,
  username varchar(32) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY username (username)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

