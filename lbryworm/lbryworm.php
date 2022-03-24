<?php

/*
Plugin Name: LBRYworm
Plugin URI: https://www.lbryworm.com
Description: <strong>LBRYworm</strong> plugin for WordPress
Author: Alojz Jakob / CODEBOT
Author URI: mailto:alojzjakob@gmail.com
Version: 1.0
*/

global $LBRYworm;

include 'class/AJsToolBox.php';

include 'class/ChainQuery.php';

include 'class/LBRYwormRooms.php';
include 'class/LBRYwormShelves.php';
include 'class/LBRYwormBooks.php';

include 'class/LBRYworm.php';

include 'class/LBRYwormUser.php';
include 'class/LBRYwormWordpress.php';
include 'class/LBRYwormAdmin.php';

$LBRYworm = new LBRYworm();
