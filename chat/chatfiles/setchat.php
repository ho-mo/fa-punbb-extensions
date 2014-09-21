<?php
define('CHATADD', 0);
if(CHATADD !== 1) {
  if(isset($forum_user['username'])) define('CHATUSER', $forum_user['username']);
}

define('MAXROWS', 10);             // Maximum number of rows registered for chat
define('CHATLINK', 1);             // allows links in texts (1), not allow (0)

// For more rooms, add lines with this syntax  $chatrooms[] = 'room_name';
$chatrooms = array();
$chatrooms[] = 'Room One';
$chatrooms[] = 'Room Two';

// Password used to empty chat rooms, just edit 12345 with your password
define('CADMPASS', '12345');

// Name of the directory in which are stored the TXT files for chat rooms
define('CHATDIR', 'chattxt');
include('texts.php');
$lsite = $en_site;
if(!headers_sent()) header('Content-type: text/html; charset=utf-8');
include('class.ChatSimple.php');
$chatS = new ChatSimple($chatrooms);
if(isset($_GET['mod']) && $_GET['mod'] == 'admin') $chatS->emptyChatRooms();