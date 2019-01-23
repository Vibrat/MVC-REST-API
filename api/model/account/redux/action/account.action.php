<?php

define("ACCOUNT_ADD_NEW", "ACCOUNT_ADD");
define("ACCOUNT_GROUP_LIST", 'SELECT * FROM `%susers_group` WHERE id = %d');
define("ACCOUNT_GROUP_NUM", 'SELECT COUNT(*) as total  FROM `%susers_group` WHERE id = %d');
define("ACCOUNT_GROUP_NUM_BY_NAME", 'SELECT COUNT(*) as total FROM `%susers_group` WHERE name = "%s"');
define("ACCOUNT_GROUP_CREATE",  "INSERT INTO `%susers_group` SET name = '%s', permission = '%s'");
define("ACCOUNT_GROUP_DELETE", "DELETE FROM `%susers_group` WHERE name  = '%s'");
define("ACCOUNT_PERMISSION_NUM", 'SELECT COUNT(*) as total FROM `%susers_permission` WHERE user_id = %d AND group_permission_id = %d');
define("ACCOUNT_PERMISSION_ADD", 'INSERT INTO `%susers_permission` SET user_id = %d, group_permission_id = %d'); 
define("ACCOUNT_PERMISSION_LIST", 'SELECT * FROM `%susers_group` WHERE id  = %d');