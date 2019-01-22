<?php

define("SIGN_CHECK_ACCOUNT",  "SELECT COUNT(*) as total FROM `%susers` WHERE username = '%s'");
define("SIGN_CREATE_ACCOUNT", "INSERT INTO `%susers` SET username = '%s', password = '%s'");