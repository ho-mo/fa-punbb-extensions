<?php

if (!defined('FORUM'))
	die();

($hook = get_hook('drafts_start')) ? eval($hook) : null;

if ($forum_user['g_read_board'] == '0' or $forum_user['is_guest'])
	message($lang_common['No view']);

// User pressed the cancel button
if (isset($_POST['cancel']))
	redirect(forum_link('misc.php?section=drafts'), $lang_common['Cancel redirect']);

// Load the topic.php language file
require FORUM_ROOT.'lang/'.$forum_user['language'].'/topic.php';

if(isset($_GET['delete']))
{
	$id=intval($_GET['delete']);
	$query = array(
		'SELECT'	=> 'f.id AS fid, f.forum_name, t.id AS tid, COALESCE(d.subject, t.subject) AS subject, d.id, d.message, d.hide_smilies',
		'FROM'		=> 'drafts AS d',
		'JOINS'		=> array(
			array(
				'LEFT JOIN'	=> 'topics AS t',
				'ON'		=> 't.id=d.topic_id'
			),
			array(
				'INNER JOIN'	=> 'forums AS f',
				'ON'			=> '(d.forum_id IS NULL AND f.id=t.forum_id OR f.id=d.forum_id)'
			)
		),
		'WHERE'		=> 'd.id='.$id,
	);
	($hook = get_hook('drafts_delete_qr_draft')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	$cur_draft = $forum_db->fetch_assoc($result);
	if(!isset($cur_draft) || !isset($cur_draft['id']))
		message($lang_common['Bad request']);

	// User pressed the delete button
	if(isset($_POST['delete']))
	{
		if (!isset($_POST['req_confirm']))
			redirect(forum_link('misc.php?section=drafts'), $lang_common['No confirm redirect']);

		if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== generate_form_token(get_current_url()))
			csrf_confirm_form();

		$id=intval($_GET['delete']);
		$query = array(
			'DELETE'	=> 'drafts',
			'WHERE'		=> 'id='.$id
		);
		($hook = get_hook('drafts_qr_delete_draft')) ? eval($hook) : null;
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

		redirect(forum_link('misc.php?section=drafts'), $lang_draft['Draft del redirect']);
	}

	($hook = get_hook('drafts_delete_pre_header_load')) ? eval($hook) : null;
	define('FORUM_PAGE', 'delete_draft');
	require FORUM_ROOT.'header.php';

	// START SUBST - <!-- forum_main -->
	ob_start();

	($hook = get_hook('drafts_delete_main_output_start')) ? eval($hook) : null;
?>
	<div class="main-content main-frm">
<?php
	if (!defined('FORUM_PARSER_LOADED'))
		require FORUM_ROOT.'include/parser.php';

	// Load the delete.php language file
	require FORUM_ROOT.'lang/'.$forum_user['language'].'/delete.php';

	// Setup form
	$forum_page['group_count'] = $forum_page['item_count'] = $forum_page['fld_count'] = 0;
	$forum_page['form_action'] = forum_link('misc.php?section=drafts&delete=$1', $cur_draft['id']);
	$forum_page['hidden_fields'] = array(
		'form_sent'		=> '<input type="hidden" name="form_sent" value="1" />',
		'csrf_token'	=> '<input type="hidden" name="csrf_token" value="'.generate_form_token($forum_page['form_action']).'" />'
	);

	$forum_page['message'] = array();

	// Generate the post title
	if (!isset($cur_draft['tid']))
		$forum_page['item_subject'] = sprintf($lang_topic['Topic title'], $cur_draft['subject']);
	else
		$forum_page['item_subject'] = sprintf($lang_topic['Reply title'], $cur_draft['subject']);

	$forum_page['item_subject'] = forum_htmlencode($forum_page['item_subject']);

	// Perform the main parsing of the message (BBCode, smilies, censor words etc)
	$forum_page['message']['message'] = parse_message($cur_draft['message'], $cur_draft['hide_smilies']);


	($hook = get_hook('drafts_delete_row_pre_display')) ? eval($hook) : null;

?>
		<div class="post singlepost">
			<div id="d<?php echo $cur_draft['id'] ?>" class="posthead">
				<h3 id="dr<?php echo $cur_draft['id'] ?>" class="hn post-ident">
					<span class="post-byline"><strong><?php echo forum_htmlencode($cur_draft['forum_name']) ?></strong></span>
					<span class="post-link"><?php echo $forum_page['item_subject'] ?></span>
				</h3>
			</div>
			<div class="postbody">
				<div class="post-entry">
					<div class="entry-content">
						<?php echo implode("\n\t\t\t\t\t\t", $forum_page['message'])."\n" ?>
					</div>
<?php
	($hook = get_hook('drafts_delete_row_new_post_entry_data')) ? eval($hook) : null; ?>
				</div>
			</div>
		</div>
		<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo $forum_page['form_action'] ?>">
			<div class="hidden">
				<?php echo implode("\n\t\t\t\t", $forum_page['hidden_fields'])."\n" ?>
			</div>
<?php ($hook = get_hook('drafts_delete_pre_confirm_delete_fieldset')) ? eval($hook) : null; ?>
			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
				<legend class="group-legend"><strong><?php echo $lang_delete['Delete draft'] ?></strong></legend>
<?php ($hook = get_hook('drafts_delete_pre_confirm_delete_checkbox')) ? eval($hook) : null; ?>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page['fld_count'] ?>" name="req_confirm" value="1" checked="checked" /></span>
						<label for="fld<?php echo $forum_page['fld_count'] ?>"><span><?php echo $lang_delete['Please confirm'] ?></span> <?php if(isset($cur_draft['tid'])) printf($lang_draft['Delete draft reply confirm'], forum_htmlencode($cur_draft['subject'])); else echo $lang_draft['Delete draft topic confirm'] ?></label>
					</div>
				</div>
<?php ($hook = get_hook('drafts_delete_pre_confirm_delete_fieldset_end')) ? eval($hook) : null; ?>
			</fieldset>
<?php ($hook = get_hook('drafts_delete_confirm_delete_fieldset_end')) ? eval($hook) : null; ?>
			<div class="frm-buttons">
				<span class="submit"><input type="submit" name="delete" value="<?php echo $lang_draft['Delete draft'] ?>" /></span>
				<span class="cancel"><input type="submit" name="cancel" value="<?php echo $lang_common['Cancel'] ?>" /></span>
			</div>
		</form>
	</div>
<?php
	($hook = get_hook('drafts_delete_end')) ? eval($hook) : null;

	$tpl_temp = forum_trim(ob_get_contents());
	$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->
	require FORUM_ROOT.'footer.php';
}

$query = array(
	'SELECT'	=> 'COUNT(*) AS num_drafts',
	'FROM'		=> 'drafts AS d',
	'WHERE'		=> 'd.user_id='.$forum_user['id']
);

($hook = get_hook('drafts_qr_get_drafts_info')) ? eval($hook) : null;
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
$row = $forum_db->fetch_row($result);
$num_drafts = $row[0];
if ($num_drafts == 0)
	message($lang_draft['No drafts']);

// Setup breadcrumbs
$forum_page['crumbs'] = array(
	array($forum_config['o_board_title'], forum_link($forum_url['index'])),
	array($lang_draft['Drafts'], forum_link('misc.php?section=drafts')),
);

// Determine the post offset (based on $_GET['p'])
$forum_page['num_pages'] = ceil(($num_drafts) / $forum_user['disp_posts']);
$forum_page['page'] = (!isset($_GET['p']) || !is_numeric($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $forum_page['num_pages']) ? 1 : $_GET['p'];
$forum_page['start_from'] = $forum_user['disp_posts'] * ($forum_page['page'] - 1);
$forum_page['finish_at'] = min(($forum_page['start_from'] + $forum_user['disp_posts']), ($num_drafts));
$forum_page['items_info'] =  generate_items_info($lang_draft['Drafts'], ($forum_page['start_from'] + 1), ($num_drafts));

($hook = get_hook('drafts_modify_page_details')) ? eval($hook) : null;

// Generate paging and posting links
$forum_page['page_post']['paging'] = '<p class="paging"><span class="pages">'.$lang_common['Pages'].'</span> '.paginate($forum_page['num_pages'], $forum_page['page'], 'misc.php?section=drafts', $lang_common['Paging separator']).'</p>';

($hook = get_hook('drafts_pre_header_load')) ? eval($hook) : null;

define('FORUM_PAGE', 'drafts');
require FORUM_ROOT.'header.php';

// START SUBST - <!-- forum_main -->
ob_start();

($hook = get_hook('drafts_main_output_start')) ? eval($hook) : null;

// Fetch some info about the drafts, the topics and the forums
$query = array(
	'SELECT'	=> 'f.id AS fid, f.forum_name, t.id AS tid, COALESCE(d.subject, t.subject) AS subject, d.id, d.message, d.hide_smilies',
	'FROM'		=> 'drafts AS d',
	'JOINS'		=> array(
		array(
			'LEFT JOIN'	=> 'topics AS t',
			'ON'		=> 't.id=d.topic_id'
		),
		array(
			'INNER JOIN'	=> 'forums AS f',
			'ON'			=> '(d.forum_id IS NULL AND f.id=t.forum_id OR f.id=d.forum_id)'
		)
	),
	'WHERE'		=> 'd.user_id='.$forum_user['id'],
	'ORDER BY'		=> 'd.id DESC',
	'LIMIT'		=> $forum_page['start_from'].','.$forum_user['disp_posts']
);

($hook = get_hook('drafts_qr_get_drafts')) ? eval($hook) : null;
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

?>
	<div class="main-head">
<?php

	if (!empty($forum_page['main_head_options']))
		echo "\n\t\t".'<p class="options">'.implode(' ', $forum_page['main_head_options']).'</p>';

?>
		<h2 class="hn"><span><?php echo $forum_page['items_info'] ?></span></h2>
	</div>
	<div id="drafts<?php echo $forum_user['id'] ?>" class="main-content main-topic">
<?php

if (!defined('FORUM_PARSER_LOADED'))
	require FORUM_ROOT.'include/parser.php';

$forum_page['item_count'] = 0;	// Keep track of draft numbers

while ($cur_draft = $forum_db->fetch_assoc($result))
{
	($hook = get_hook('drafts_loop_start')) ? eval($hook) : null;

	++$forum_page['item_count'];

	$forum_page['draft_actions'] = array();
	$forum_page['draft_options'] = array();
	$forum_page['message'] = array();

	// Generate the draft action links
	$forum_page['draft_actions']['edit'] = '<span class="edit-draft'.(empty($forum_page['draft_actions']) ? ' first-item' : '').'"><a href="'.(isset($cur_draft['tid'])?forum_link('post.php?tid=$1&draft=$2', array($cur_draft['tid'], $cur_draft['id'])):forum_link('post.php?fid=$1&draft=$2', array($cur_draft['fid'], $cur_draft['id']))).'">'.$lang_draft['Edit'].'</span></a></span>';
	$forum_page['draft_actions']['delete'] = '<span class="delete-draft'.(empty($forum_page['draft_actions']) ? ' first-item' : '').'"><a href="'.forum_link('misc.php?section=drafts&delete=$1', $cur_draft['id']).'">'.$lang_draft['Delete'].'</span></a></span>';

	($hook = get_hook('drafts_row_pre_post_actions_merge')) ? eval($hook) : null;

	if (!empty($forum_page['draft_actions']))
		$forum_page['draft_options']['actions'] = '<p class="post-actions">'.implode(' ', $forum_page['draft_actions']).'</p>';

	// Give the post some class
	$forum_page['item_status'] = array(
		'post',
		($forum_page['item_count'] % 2 != 0) ? 'odd' : 'even'
	);

	if ($forum_page['item_count'] == 1)
		$forum_page['item_status']['firstpost'] = 'firstpost';

	if (($forum_page['start_from'] + $forum_page['item_count']) == $forum_page['finish_at'])
		$forum_page['item_status']['lastpost'] = 'lastpost';

	// Generate the post title
	if (!isset($cur_draft['tid']))
		$forum_page['item_subject'] = sprintf($lang_topic['Topic title'], $cur_draft['subject']);
	else
		$forum_page['item_subject'] = sprintf($lang_topic['Reply title'], $cur_draft['subject']);

	$forum_page['item_subject'] = forum_htmlencode($forum_page['item_subject']);

	// Perform the main parsing of the message (BBCode, smilies, censor words etc)
	$forum_page['message']['message'] = parse_message($cur_draft['message'], $cur_draft['hide_smilies']);

	($hook = get_hook('drafts_row_pre_display')) ? eval($hook) : null;

?>
		<div class="<?php echo implode(' ', $forum_page['item_status']) ?>">
			<div id="d<?php echo $cur_draft['id'] ?>" class="posthead">
				<h3 id="dr<?php echo $cur_draft['id'] ?>" class="hn post-ident">
					<span class="post-byline"><strong><?php echo forum_htmlencode($cur_draft['forum_name']) ?></strong></span>
					<span class="post-link"><?php echo $forum_page['item_subject'] ?></span>
				</h3>
			</div>
			<div class="postbody">
				<div class="post-entry">
					<div class="entry-content">
						<?php echo implode("\n\t\t\t\t\t\t", $forum_page['message'])."\n" ?>
					</div>
<?php ($hook = get_hook('drafts_row_new_post_entry_data')) ? eval($hook) : null; ?>
				</div>
			</div>
<?php if (!empty($forum_page['draft_options'])): ?>
			<div class="postfoot">
				<div class="post-options">
					<?php echo implode("\n\t\t\t\t\t", $forum_page['draft_options'])."\n" ?>
				</div>
			</div>
<?php endif; ?>
		</div>
<?php

}

?>
	</div>

	<div class="main-foot">
<?php

	if (!empty($forum_page['main_foot_options']))
		echo "\n\t\t\t".'<p class="options">'.implode(' ', $forum_page['main_foot_options']).'</p>';

?>
		<h2 class="hn"><span><?php echo $forum_page['items_info'] ?></span></h2>
	</div>
<?php
($hook = get_hook('drafts_end')) ? eval($hook) : null;

$tpl_temp = forum_trim(ob_get_contents());
$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
ob_end_clean();
// END SUBST - <!-- forum_main -->

require FORUM_ROOT.'footer.php';
