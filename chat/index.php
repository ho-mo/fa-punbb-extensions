<?php

define('FORUM_ROOT', '../../');
require FORUM_ROOT.'include/common.php';

if ($forum_user['is_guest']) {message($lang_common['No permission']);	}

	// Setup breadcrumbs
	$forum_page['crumbs'] = array(
		array($forum_config['o_board_title'], forum_link($forum_url['index'])),
		$lang_common['Chat'] =CHAT
	);
	$forum_head['css'] = '<link rel="stylesheet" href="chatfiles/chat.css" type="text/css" media="screen" />';
	require FORUM_ROOT.'header.php';
	// START SUBST - <!-- forum_main -->
	ob_start();
?>
	<div class="main-head">
		<h2 class="hn"><span><?php echo $lang_common['Chat'] ?></span></h2>
	</div>

	<div class="main-content main-frm">
		<div id="rules-content" class="ct-box user-box">
			<?php include('chat.php'); ?>
		</div>
	</div>
<?php

	$tpl_temp = forum_trim(ob_get_contents());
	$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->

	require FORUM_ROOT.'footer.php';