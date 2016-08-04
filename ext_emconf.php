<?php

########################################################################
# Extension Manager/Repository config file for ext "chc_forum".
#
# Auto generated 27-07-2011 09:53
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'CHC Forum',
	'description' => 'A discussion board with some mailing list features. Requires typo3 v. 3.6.0.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.4.7',
	'dependencies' => 'cms,newloginbox',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod1',
	'state' => 'beta',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => 'fe_users',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Zach Davis',
	'author_email' => 'zach@crito.org',
	'author_company' => 'City University of New York Honors College',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '3.6.21-0.0.0',
			'php' => '3.0.0-0.0.0',
			'cms' => '',
			'newloginbox' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:204:{s:14:"bugs_fixed.txt";s:4:"a33b";s:13:"changelog.txt";s:4:"29d6";s:20:"class.ext_update.php";s:4:"3e1e";s:12:"ext_icon.gif";s:4:"8e96";s:17:"ext_localconf.php";s:4:"8afa";s:14:"ext_tables.php";s:4:"c184";s:14:"ext_tables.sql";s:4:"9ad2";s:28:"ext_typoscript_constants.txt";s:4:"dd8a";s:24:"ext_typoscript_setup.txt";s:4:"90f2";s:15:"flexform_ds.xml";s:4:"20d6";s:13:"locallang.php";s:4:"6fc1";s:16:"locallang_db.php";s:4:"c286";s:7:"tca.php";s:4:"4f24";s:14:"doc/manual.sxw";s:4:"f9ff";s:15:"icons/Thumbs.db";s:4:"aff1";s:30:"icons/icon_tx_chcforum_cat.gif";s:4:"7780";s:33:"icons/icon_tx_chcforum_cat__d.gif";s:4:"351c";s:33:"icons/icon_tx_chcforum_cat__h.gif";s:4:"844d";s:34:"icons/icon_tx_chcforum_cat__hu.gif";s:4:"d058";s:33:"icons/icon_tx_chcforum_cat__u.gif";s:4:"3c8e";s:33:"icons/icon_tx_chcforum_cat__x.gif";s:4:"351c";s:30:"icons/icon_tx_chcforum_cnf.gif";s:4:"e452";s:33:"icons/icon_tx_chcforum_cnf__h.gif";s:4:"ec15";s:33:"icons/icon_tx_chcforum_cnf__x.gif";s:4:"0b52";s:31:"icons/icon_tx_chcforum_fgrp.gif";s:4:"f8c8";s:34:"icons/icon_tx_chcforum_fgrp__h.gif";s:4:"5ce5";s:34:"icons/icon_tx_chcforum_fgrp__x.gif";s:4:"b330";s:31:"icons/icon_tx_chcforum_post.gif";s:4:"7724";s:34:"icons/icon_tx_chcforum_post__h.gif";s:4:"c348";s:34:"icons/icon_tx_chcforum_post__x.gif";s:4:"b543";s:31:"icons/icon_tx_chcforum_thrd.gif";s:4:"1537";s:34:"icons/icon_tx_chcforum_thrd__h.gif";s:4:"c348";s:34:"icons/icon_tx_chcforum_thrd__x.gif";s:4:"b543";s:15:"mailer/Mail.php";s:4:"b317";s:16:"mailer/error_log";s:4:"304f";s:17:"mailer/mailer.php";s:4:"028d";s:22:"mailer/Mail/RFC822.php";s:4:"5519";s:20:"mailer/Mail/mail.php";s:4:"c1c4";s:24:"mailer/Mail/sendmail.php";s:4:"52cd";s:20:"mailer/Mail/smtp.php";s:4:"c227";s:14:"mod1/clear.gif";s:4:"cc11";s:13:"mod1/conf.php";s:4:"146c";s:14:"mod1/index.php";s:4:"a8ea";s:18:"mod1/locallang.php";s:4:"4107";s:22:"mod1/locallang_mod.php";s:4:"9ec3";s:19:"mod1/moduleicon.gif";s:4:"8e96";s:26:"mod1/templates/add_cat.tpl";s:4:"4f01";s:27:"mod1/templates/add_conf.tpl";s:4:"24e1";s:26:"mod1/templates/add_grp.tpl";s:4:"fa73";s:25:"mod1/templates/f_conf.tpl";s:4:"c654";s:23:"mod1/templates/list.tpl";s:4:"8044";s:27:"mod1/templates/list_grp.tpl";s:4:"044c";s:14:"pi1/ce_wiz.gif";s:4:"2c99";s:32:"pi1/class.tx_chcforum_author.php";s:4:"9f42";s:34:"pi1/class.tx_chcforum_category.php";s:4:"0142";s:36:"pi1/class.tx_chcforum_conference.php";s:4:"02fa";s:33:"pi1/class.tx_chcforum_display.php";s:4:"255f";s:38:"pi1/class.tx_chcforum_display.php.orig";s:4:"bb8c";s:31:"pi1/class.tx_chcforum_fconf.php";s:4:"a79d";s:30:"pi1/class.tx_chcforum_form.php";s:4:"d394";s:33:"pi1/class.tx_chcforum_message.php";s:4:"0f57";s:29:"pi1/class.tx_chcforum_pi1.php";s:4:"55e3";s:37:"pi1/class.tx_chcforum_pi1_wizicon.php";s:4:"8ce5";s:30:"pi1/class.tx_chcforum_post.php";s:4:"6a18";s:32:"pi1/class.tx_chcforum_search.php";s:4:"efb0";s:32:"pi1/class.tx_chcforum_shared.php";s:4:"dbde";s:32:"pi1/class.tx_chcforum_thread.php";s:4:"78d1";s:32:"pi1/class.tx_chcforum_tpower.php";s:4:"c8bc";s:34:"pi1/class.tx_chcforum_tpparser.php";s:4:"718b";s:30:"pi1/class.tx_chcforum_user.php";s:4:"0ebf";s:13:"pi1/clear.gif";s:4:"baf6";s:16:"pi1/kses_lib.php";s:4:"8aae";s:17:"pi1/locallang.php";s:4:"7e94";s:26:"pi1/templates/cat_view.tpl";s:4:"f11d";s:27:"pi1/templates/conf_view.tpl";s:4:"9c9e";s:41:"pi1/templates/cwtcommunity_buddylist.tmpl";s:4:"dbe9";s:41:"pi1/templates/cwtcommunity_guestbook.tmpl";s:4:"eb3e";s:40:"pi1/templates/cwtcommunity_messages.tmpl";s:4:"607e";s:39:"pi1/templates/cwtcommunity_profile.tmpl";s:4:"e578";s:38:"pi1/templates/cwtcommunity_search.tmpl";s:4:"772c";s:40:"pi1/templates/cwtcommunity_userlist.tmpl";s:4:"63d6";s:39:"pi1/templates/cwtcommunity_welcome.tmpl";s:4:"20d8";s:23:"pi1/templates/footer.js";s:4:"1ab6";s:24:"pi1/templates/footer.tpl";s:4:"21b0";s:23:"pi1/templates/global.js";s:4:"095d";s:24:"pi1/templates/header.tpl";s:4:"4502";s:29:"pi1/templates/message_box.tpl";s:4:"f807";s:26:"pi1/templates/post_form.js";s:4:"4bd8";s:27:"pi1/templates/post_form.tpl";s:4:"edcb";s:29:"pi1/templates/search_form.tpl";s:4:"d9a4";s:29:"pi1/templates/single_post.tpl";s:4:"41be";s:34:"pi1/templates/single_post_view.tpl";s:4:"1fae";s:31:"pi1/templates/single_thread.tpl";s:4:"8779";s:30:"pi1/templates/sub_tool_bar.tpl";s:4:"8db2";s:26:"pi1/templates/tool_bar.tpl";s:4:"ebb5";s:27:"pi1/templates/user_list.tpl";s:4:"d0c7";s:27:"pi1/templates/img/Thumbs.db";s:4:"1840";s:25:"pi1/templates/img/aim.gif";s:4:"82a9";s:25:"pi1/templates/img/aim.png";s:4:"6e0f";s:32:"pi1/templates/img/bold_large.gif";s:4:"a5df";s:32:"pi1/templates/img/bold_large.png";s:4:"b5f2";s:26:"pi1/templates/img/chat.gif";s:4:"69f0";s:26:"pi1/templates/img/chat.png";s:4:"5bb3";s:27:"pi1/templates/img/close.gif";s:4:"7c4f";s:27:"pi1/templates/img/close.png";s:4:"1ef5";s:34:"pi1/templates/img/close_thread.gif";s:4:"496f";s:34:"pi1/templates/img/close_thread.png";s:4:"a0d5";s:33:"pi1/templates/img/color_large.gif";s:4:"e0e8";s:33:"pi1/templates/img/color_large.png";s:4:"2139";s:28:"pi1/templates/img/delete.gif";s:4:"45f6";s:28:"pi1/templates/img/delete.png";s:4:"e5ec";s:26:"pi1/templates/img/edit.gif";s:4:"4c44";s:26:"pi1/templates/img/edit.png";s:4:"8c6b";s:27:"pi1/templates/img/email.gif";s:4:"ca8c";s:27:"pi1/templates/img/email.png";s:4:"a017";s:28:"pi1/templates/img/header.gif";s:4:"67fc";s:28:"pi1/templates/img/header.png";s:4:"21fb";s:26:"pi1/templates/img/hide.gif";s:4:"98de";s:26:"pi1/templates/img/hide.png";s:4:"efc9";s:33:"pi1/templates/img/image_large.gif";s:4:"61ea";s:33:"pi1/templates/img/image_large.png";s:4:"2d5a";s:34:"pi1/templates/img/italic_large.gif";s:4:"8da6";s:34:"pi1/templates/img/italic_large.png";s:4:"aa47";s:29:"pi1/templates/img/license.txt";s:4:"7fbc";s:31:"pi1/templates/img/mark_read.gif";s:4:"87c3";s:31:"pi1/templates/img/mark_read.png";s:4:"c4ab";s:25:"pi1/templates/img/new.gif";s:4:"4c44";s:25:"pi1/templates/img/new.png";s:4:"8c6b";s:33:"pi1/templates/img/open_thread.gif";s:4:"1d7f";s:33:"pi1/templates/img/open_thread.png";s:4:"b442";s:29:"pi1/templates/img/profile.gif";s:4:"5658";s:29:"pi1/templates/img/profile.png";s:4:"e51b";s:27:"pi1/templates/img/quote.gif";s:4:"b145";s:27:"pi1/templates/img/quote.png";s:4:"7db7";s:33:"pi1/templates/img/quote_large.gif";s:4:"71f8";s:33:"pi1/templates/img/quote_large.png";s:4:"2960";s:28:"pi1/templates/img/readme.txt";s:4:"dacc";s:27:"pi1/templates/img/reply.gif";s:4:"9e86";s:27:"pi1/templates/img/reply.png";s:4:"1d08";s:28:"pi1/templates/img/search.gif";s:4:"c2f0";s:28:"pi1/templates/img/search.png";s:4:"07f5";s:26:"pi1/templates/img/star.png";s:4:"0183";s:32:"pi1/templates/img/star_empty.png";s:4:"1d3d";s:28:"pi1/templates/img/thread.gif";s:4:"37cc";s:28:"pi1/templates/img/thread.png";s:4:"fd1f";s:35:"pi1/templates/img/thread_closed.gif";s:4:"343b";s:35:"pi1/templates/img/thread_closed.png";s:4:"61db";s:32:"pi1/templates/img/thread_hot.gif";s:4:"0d10";s:32:"pi1/templates/img/thread_hot.png";s:4:"c65e";s:36:"pi1/templates/img/thread_hot_new.gif";s:4:"df19";s:36:"pi1/templates/img/thread_hot_new.png";s:4:"7dea";s:32:"pi1/templates/img/thread_new.gif";s:4:"a6e3";s:32:"pi1/templates/img/thread_new.png";s:4:"4161";s:37:"pi1/templates/img/underline_large.gif";s:4:"2802";s:37:"pi1/templates/img/underline_large.png";s:4:"5626";s:28:"pi1/templates/img/unhide.gif";s:4:"fc2b";s:28:"pi1/templates/img/unhide.png";s:4:"ff85";s:31:"pi1/templates/img/url_large.gif";s:4:"62f7";s:31:"pi1/templates/img/url_large.png";s:4:"b416";s:26:"pi1/templates/img/user.gif";s:4:"8d3d";s:26:"pi1/templates/img/user.png";s:4:"b3a5";s:29:"pi1/templates/img/user_pm.gif";s:4:"83dc";s:29:"pi1/templates/img/user_pm.png";s:4:"dc43";s:39:"pi1/templates/img/user_pm_add_buddy.gif";s:4:"42e3";s:39:"pi1/templates/img/user_pm_add_buddy.png";s:4:"3c0b";s:37:"pi1/templates/img/user_pm_message.gif";s:4:"cff6";s:37:"pi1/templates/img/user_pm_message.png";s:4:"aec8";s:41:"pi1/templates/img/user_pm_message_new.gif";s:4:"ac9f";s:41:"pi1/templates/img/user_pm_message_new.png";s:4:"aa05";s:42:"pi1/templates/img/user_pm_message_read.gif";s:4:"7c8e";s:42:"pi1/templates/img/user_pm_message_read.png";s:4:"5c0f";s:43:"pi1/templates/img/user_pm_message_reply.gif";s:4:"b9ec";s:43:"pi1/templates/img/user_pm_message_reply.png";s:4:"886b";s:37:"pi1/templates/img/user_pm_offline.gif";s:4:"3a25";s:37:"pi1/templates/img/user_pm_offline.png";s:4:"16be";s:35:"pi1/templates/img/user_pm_trash.gif";s:4:"0f67";s:35:"pi1/templates/img/user_pm_trash.png";s:4:"97ad";s:27:"pi1/templates/img/users.gif";s:4:"cbb1";s:27:"pi1/templates/img/users.png";s:4:"460b";s:27:"pi1/templates/img/watch.gif";s:4:"e8e7";s:27:"pi1/templates/img/watch.png";s:4:"6828";s:37:"pi1/templates/img/emoticons/Thumbs.db";s:4:"9fc8";s:37:"pi1/templates/img/emoticons/arrow.gif";s:4:"03a8";s:39:"pi1/templates/img/emoticons/badgrin.gif";s:4:"c260";s:39:"pi1/templates/img/emoticons/biggrin.gif";s:4:"293a";s:40:"pi1/templates/img/emoticons/confused.gif";s:4:"90fc";s:36:"pi1/templates/img/emoticons/cool.gif";s:4:"a557";s:35:"pi1/templates/img/emoticons/cry.gif";s:4:"a7d5";s:37:"pi1/templates/img/emoticons/doubt.gif";s:4:"429d";s:36:"pi1/templates/img/emoticons/evil.gif";s:4:"d247";s:39:"pi1/templates/img/emoticons/exclaim.gif";s:4:"32e9";s:36:"pi1/templates/img/emoticons/idea.gif";s:4:"a620";s:35:"pi1/templates/img/emoticons/lol.gif";s:4:"0172";s:35:"pi1/templates/img/emoticons/mad.gif";s:4:"3170";s:39:"pi1/templates/img/emoticons/neutral.gif";s:4:"9568";s:40:"pi1/templates/img/emoticons/question.gif";s:4:"9281";s:36:"pi1/templates/img/emoticons/razz.gif";s:4:"be49";s:39:"pi1/templates/img/emoticons/redface.gif";s:4:"f41c";s:40:"pi1/templates/img/emoticons/rolleyes.gif";s:4:"7bc8";s:35:"pi1/templates/img/emoticons/sad.gif";s:4:"7cb7";s:37:"pi1/templates/img/emoticons/shock.gif";s:4:"a9ce";s:37:"pi1/templates/img/emoticons/smile.gif";s:4:"2640";s:41:"pi1/templates/img/emoticons/surprised.gif";s:4:"bd90";s:36:"pi1/templates/img/emoticons/wink.gif";s:4:"cba5";}',
	'suggests' => array(
	),
);

?>