<?php
# Set these include path correctly to reflect the relative path to localconf.php,
# which is stored in the typo3conf folder. The default setting should suffice
# if you have installed this extension locally rather than globally.
require_once('../../../localconf.php');
require_once('../../../../t3lib/class.t3lib_div.php');

//error_reporting(E_ALL);

$mailer = new chc_mailer();

/*
 TO DO:
 write process log function
 clear out the queue function
 add tstamp to message sent
*/

class chc_mailer {
	var $utf8 = false;

	var $debug = false;
	var $debug_mail = 'postmaster@localhost';
	var $developer_copy = false;
	
	var $db_connection;

	var $mail_header_separator = "\n";

	var $fconf;
			
	var $forum_url;
	var $mail_from;
	
	function chc_mailer() {
		$this->db_connection = mysql_connect($GLOBALS['typo_db_host'], $GLOBALS['typo_db_username'], $GLOBALS['typo_db_password']) or die (mysql_error());
		mysql_select_db ($GLOBALS['typo_db']) or die (mysql_error());

    // Check the log. It should be empty. If it isn't process its contents.
    $this->checkLog();

		// Select all the posts from the posts table that haven't been sent out yet.
		$posts_not_sent = $this->getMessages();

		// Begin a loop through each message
		if ($posts_not_sent) {
			foreach ($posts_not_sent as $message) {
				// Insert each message in the mail queue and set the sent flag to true in the post table
				$this->addPostToQueue($message);
			}
		}

		// Time to send whatever is in the queue.
		$queue_not_send = $this->getQueue();
		if (count($queue_not_send) > 0) $this->send_queue($queue_not_send);
	}

	function send_queue($queue_not_send) {
		$current_pid = -1;
		
		// Begin a loop through each message in the queue
		// For each message
		foreach ($queue_not_send as $message) {			
			// set configuration for this message's forum.
			if ($message['pid'] != $current_pid) {
				$this->fconf = $this->getFconf($message['pid']);
				if ($this->fconf['mailer_disable']['vDEF'] == 1) die;
				$this->forum_url = $this->fconf['mailer_forum_url']['vDEF'].'index.php?id=' . $this->fconf['forum_instance_pid'];
				
				$this->mail_from = $this->fconf['mailer_email']['vDEF'];
				$current_pid = $message['pid'];                                
			}
			
			// Get the recipient list
			$all_recipients = $this->getRecipients($message,$message['post_author']);
			
			// And build the message
			$text = $message['post_text'];
			$subject = $message['post_subject'];
			$author = $this->getAuthor($message['post_author']);
			$author_email = $author['email'];
			$author_name = $author['username'];
			$day_time = strftime('%b %d %Y', $message['post_tstamp']);
			$name = $email='';
			
			$this_message = $this->makeMessage($text, $author_name, $author_email, $day_time, $name, $email, $message['conf_uid'], $message['thread_uid']);
			
			// Update the log so that it contains all the users
			if ($all_recipients) {
				foreach ($all_recipients as $recipient) {
					$query = sprintf("INSERT INTO tx_chcforum_mail_log VALUES ('%d', '%d')", $recipient['uid'],$message['uid']);
					$query_results = mysql_query($query,$this->db_connection);
					if ($this->debug) {
						print "<strong>Added to Log:</strong> The following query was run to insert the queue ID and recipient IDs into the Log<br>";
						print $query;
						print "<br><br>";
					}
				}
			}
			
			// For each recipient, send the message.
			
			if ($all_recipients) {
				foreach ($all_recipients as $recipient) {
					$email = $recipient['email'];
					$name = $recipient['name'];
					if ($this->debug) {
						print "Message is being sent to <strong>$recipient[email]</strong><br><br>";
						print "<strong>The message is</strong>:<br>";
						print $this_message.'<br><br>';
					}
	
	
					// The line that actually sends the message!
					$this->sendMessage($email, $subject, $this_message);
					
					// And delete the entry in the log
					$query = 'DELETE FROM tx_chcforum_mail_log WHERE recipient_uid = ' . $recipient['uid'] . ' AND message_uid = ' . $message['uid'];
					$query_results = mysql_query($query,$this->db_connection);
					if ($this->debug) {
						print "<strong>Deleted from Log:</strong> The following query was run to delete the queue ID and recipient IDs from the Log<br>";
						print $query;
						print "<br><br>";
					}
				}
			}
			// Mark message as reminder is already sent 
			$query1 = 'UPDATE tx_chcforum_mail_queue SET sent_flag = 1 WHERE uid= '.$message['uid'];
			$query_results = mysql_query($query1,$this->db_connection);
		}
	}		
	/**
	* [Describe function...]
	*
	* @param [type]  $text: ...
	* @param [type]  $author_name: ...
	* @param [type]  $author_email: ...
	* @param [type]  $day_time: ...
	* @param [type]  $name: ...
	* @param [type]  $email: ...
	* @param [type]  $conf_uid: ...
	* @param [type]  $thread_uid: ...
	* @return [type]  ...
	*/
	function makeMessage($text, $author_name, $author_email, $day_time, $name, $email, $conf_uid, $thread_uid) {
		$message = $this->fconf['mailer_msg_tmpl']['vDEF'];
		if(!$message) {
			$message = "Posted by: {author_name}\nConference: {conference}\nThread: {thread}\n\n{text}\n\n\nThis message was sent because you have opted to receive new posts via email.\n{link}";       	
		}
		
		$conference = $this->getConference($conf_uid);
		$thread = $this->getThread($thread_uid);
		
		$link_conf['view'] = 'single_thread';
		$link_conf['thread_uid'] = $thread_uid;
		
		$link_conf['linktext'] = 'please click here';
		$link = $this->makeLink($link_conf);
		
		$author_name = $this->htmlspecialchars_decode($author_name);
		$conference = $this->htmlspecialchars_decode($conference);
		$thread = $this->htmlspecialchars_decode($thread);
		$text = $this->htmlspecialchars_decode($text);
		
		// escape brackets
		$author_name = str_replace('{','{/',$author_name);
		$author_name = str_replace('}','\}',$author_name);
		$conference =str_replace('{','{/',$conference);
		$conference = str_replace('}','\}',$conference);
		$thread = str_replace('{','{/',$thread);
		$thread = str_replace('}','\}',$thread);
		$text = str_replace('{','{/',$text);
		$text = str_replace('}','\}',$text);
		
		$message = str_replace('{author_name}',$author_name,$message);
		$message = str_replace('{conference}',$conference,$message);
		$message = str_replace('{thread}',$thread,$message);
		$message = str_replace('{text}',$text,$message);
		$message = str_replace('{link}',$link,$message);
		
		$message = str_replace('{/','{',$message);
		$message = str_replace('\}','}',$message);
		
		return $message;
	}

	/**
	* [Describe function...]
	*
	* @param [type]  $link_conf: ...
	* @return [type]  ...
	*/
	function makeLink($link_conf) {
		$add_uid =	$add_view = $internal_content = '';
		$flag = '&flag=last';
		
		if ($link_conf['view']) {
			$add_view = '&view='.$link_conf['view'];
		}
		
		if ($link_conf['thread_uid']) {
			$add_uid = '&thread_uid='.$link_conf['thread_uid'];
		}
		
		if ($link_conf['linktext']) {
			$add_ltext = $link_conf['linktext'];
		}
		
		$internal_content = $this->forum_url.'&no_cache=1'.$add_view.$add_uid.$flag;
		
		return $internal_content;
	}
			
			
	function htmlspecialchars_decode($value)	{
		// htmlspecialchars_decode() is part of PHP 5 >= 5.1.0RC1
		if ( !function_exists('htmlspecialchars_decode') ) {
			$value = str_replace('&gt;','>',$value);
			$value = str_replace('&lt;','<',$value);
			$value = str_replace('&quot;','"',$value);
			$value = str_replace('&amp;','&',$value);
			$value = str_replace('&auml;','ä',$value);
			$value = str_replace('&ouml;','ö',$value);
			$value = str_replace('&uuml;','ü',$value);
			$value = str_replace('&Auml;','Ä',$value);
			$value = str_replace('&Ouml;','Ö',$value);
			$value = str_replace('&Uuml;','Ü',$value);
			$value = str_replace('&szlig;','ß',$value);
			$value = str_replace('&sect;','§',$value);
			$value = str_replace('&acute;','\'',$value);
		} else {
			$value = htmlspecialchars_decode($value);
		}
		return $value;
	}

	/**
	* [Describe function...]
	*
	* @param [type]  $id: ...
	* @return [type]  ...
	*/
	function getConference($id) {
		$data_out = '';
		
		$query = 'SELECT conference_name FROM tx_chcforum_conference WHERE uid = ' . $id;
		$query_results = mysql_query($query,$this->db_connection);

		if($name = @mysql_fetch_assoc($query_results)) {
			$data_out = $name['conference_name'];
		} 
		mysql_free_result($query_results);
		return $data_out;
	}

	/**
	* [Describe function...]
	*
	* @param [type]  $id: ...
	* @return [type]  ...
	*/
	function getThread($id) {
		$data_out = '';
		
		$query = 'SELECT thread_subject FROM tx_chcforum_thread WHERE uid = ' . $id;
		$query_results = mysql_query($query,$this->db_connection);

		if($name = @mysql_fetch_assoc($query_results)) {
			$data_out = $name['thread_subject'];
		}
		
		mysql_free_result($query_results);
		return $data_out;
	}
	
	/**
	* [Describe function...]
	*
	* @param [type]  $email: ...
	* @param [type]  $subject: ...
	* @param [type]  $message: ...
	* @return [type]  ...
	*/
	function sendMessage($email, $subject, $message) {	
		// CBY 18/02/2007 this flag will force UTF-8 headers for mail
		$headers =
		'From: ' . $this->mail_from . $this->mail_header_separator .
		'Reply-To: ' . $email . $this->mail_header_separator .
		'X-Mailer: PHP/' . phpversion() . $this->mail_header_separator .
		'MIME-Version: 1.0' . $this->mail_header_separator;
		
		if($this->utf8) {
			$headers .= 'Content-Type: text/plain; charset=utf-8' . $this->mail_header_separator . 'Content-Transfer-Encoding: 8bit' . $this->mail_header_separator . $this->mail_header_separator;
		}
		
		// Manipulate recipient if debugging
		if($this->debug) $email = $this->debug_mail;
		mail($email, $subject, $message,$headers);
		
		// Send copy to developer if not debugging (Would produce duplicate mails)
		if($this->developer_copy && (!$this->debug)) mail($this->debug_mail, $subject, $message,$headers);
	}

	/**
	* [Describe function...]
	*
	* @param [type]  $author_id: ...
	* @return [type]  ...
	*/
	function getAuthor($author_id) {
		$data_out =array();
		
		$query = 'SELECT name,username,email FROM fe_users WHERE uid = ' . $author_id;
		$query_results = mysql_query($query,$this->db_connection);

		if($user = @mysql_fetch_assoc($query_results)) {
			$data_out = $user;
		}
		
		mysql_free_result($query_results);
		return $data_out;
	}

	function getOneRecipient($feuser_uid) {
		$user = array();

		$query = 'SELECT uid,username,usergroup,name,email FROM fe_users WHERE uid = ' . $feuser_uid;
		$query_results = mysql_query($query,$this->db_connection);

		$user = @mysql_fetch_assoc($query_results);

		mysql_free_result($query_results);
		return $user;
	}


	/**
	* Get the recipients for a message in the queue
	*
	* @param [type]  $message: ...
	* @return [type]  ...
	*/        
	function getRecipients($message,$author_id) {
		$data_out = array();
		$uids = array();
		
		// get recipients who are watching this conference
		$query = '
			SELECT
			*
			FROM tx_chcforum_user_conf
			WHERE
				mailer_confs = ' . $message['conf_uid'] . '
				AND
				user_uid != ' . $author_id
		;
		$results = mysql_query($query,$this->db_connection);
		
		while ($uid = mysql_fetch_assoc($results)) {
			$query = '
				SELECT
					uid,username,usergroup,name,email
					FROM fe_users
					WHERE uid = ' . $uid['user_uid'] . '
					AND
					deleted = 0
			';
			$query_results = mysql_query($query,$this->db_connection);
			while ($user = @mysql_fetch_assoc($query_results)) {
				if ($this->confAuth($user['usergroup'], $user['uid'], $message['conf_uid']) == 'true') {
					$data_out[] = $user;
					$uids[] = $user['uid'];
				}
			}
		}
		
		// get recipients who are watching this thread
		$query = '
			SELECT
			*
			FROM tx_chcforum_user_thread
			WHERE
				mailer_threads = ' . $message['thread_uid'] . '
				AND
				user_uid != ' . $author_id
		;
		$results = mysql_query($query,$this->db_connection);
		
		while ($uid = mysql_fetch_assoc($results)) {
			$query = '
				SELECT
				uid,username,usergroup,name,email
				FROM fe_users
				WHERE
					uid = ' . $uid['user_uid'] . '
					AND
					deleted = 0
			';
			$query_results = mysql_query($query,$this->db_connection);
			while ($user = @mysql_fetch_assoc($query_results)) {
				if ($this->confAuth($user['usergroup'], $user['uid'], $message['conf_uid']) == 'true') {
					if (!in_array($user['uid'],$uids)) $data_out[] = $user;
				}
			}
		}
		
		return $data_out;
	}

	/**
	* This function returns true if the logged in user can access the conference with the uid
	* of $conf_id. Returns false if user cannot access it -- we probably need to update this to
	* reflect the changes that have been made to category access -- this should include a check 
	* of whether or not the user can access the parent category. Not a major problem, but something
	* that should be fixed for the sake of consistency.
	*
	* @param [type]  $user_groups: ...
	* @param [type]  $user_uid: ...
	* @param [type]  $conf_uid: ...
	* @return [type]  ...
	*/
	function confAuth($user_groups, $user_uid, $conf_uid) {
		$user_authenticated = false;
		$group_authenticatede = false;
		$success = false;
		
		if (!empty($user_uid)) {
			$query = 'SELECT * FROM tx_chcforum_conference WHERE uid = ' . $conf_uid;
			$results = mysql_query($query,$this->db_connection);
			
			if (!empty($results)) {
				while ($row = mysql_fetch_assoc($results)) {
					if (!empty($row['auth_forumgroup_r'])) {
						// Explode string containing the IDs of the forum groups that can view this conference
						$groups = explode(',', $row['auth_forumgroup_r']);

						// For each forumgroup attached to the conference, get the info for that forum
						foreach ($groups as $value) {
							$query1 = 'SELECT * FROM tx_chcforum_forumgroup WHERE uid = ' . $value;
							$results1 = mysql_query($query1,$this->db_connection);

							// For each forum group, check and see if this user belongs to it
							while ($row1 = mysql_fetch_assoc($results1)) {
								// Do the auth for each forum
								// Explode string containing users and groups for this forum group
								$auth_users = explode(',', $row1['forumgroup_users']);
								$auth_groups = explode(',', $row1['forumgroup_groups']);
								$user_groups = explode(',', $user_groups);
								// Does this user's ID along to the list of user IDs allowed to access this forum (as per the FG)?
								if (in_array($user_uid, $auth_users))	$fg_user_authenticated = true;
								
								// Does this user belong to all the groups that belong to this forumgroup?
								foreach ($auth_groups as $value) {
									// For each of the groups belonging to this forumgroup, see if it is contained in the user_groups array
									if (!in_array($value, $user_groups)) {
										$fg_group_authenticated = false;
										break; // If the group isn't in the user_group array, stop this loop
									} else {
										// As long as the loop hasn't stopped, continue to authenticate
										$fg_group_authenticated = true;
									}
								}
							}
							// If, coming out of the authentication subroutine, the user is authenticated, set $auth to true. This value
							// cannot be unset after this, since the user only needs to belong to one forum group to have access to the
							// conference (although she must have access to all the groups within the forumgroup to be authenticated.
							if ($fg_group_authenticated  || $fg_user_authenticated) {
								$conf_auth = true;
							}
						}
						if ($conf_auth) $success = true;
					} else {
						// No user groups for the conference. Go ahead and authenticate
						$success = true;
					} 
				}
			}
		}
		return $success;
	}

	/**
	* Get all messages in the queue that have not been sent.
	*
	* @return [type]  ...
	*/
	function getQueue() {
		$data_array=array();
		
		$query = 'SELECT * FROM tx_chcforum_mail_queue WHERE sent_flag = 0';
		$query_results = mysql_query($query,$this->db_connection);

		while ($row = @mysql_fetch_assoc($query_results)) {
			$data_array[] = $row;
		}
		
		mysql_free_result($query_results);
		return $data_array;
	}

	/**
	* Adds the message stored in $message to the mail queue, and sets the sent flag to true in the posts table. From this point on,
	* the message is in the hands of the mailer, and out of the domain of the forum proper.
	*
	* @param [type]  $message: ...
	* @return [type]  ...
	*/
	function addPostToQueue($message) {
		$subject = addslashes($message['post_subject']);
		$text = addslashes($message['post_text']);
		$author = addslashes($message['post_author']);
		$pid = $message['pid'];
		
		$tstamp = time();
		
		$query = "
		INSERT INTO tx_chcforum_mail_queue
			(uid,
			pid,
			conf_uid,
			thread_uid,
			post_uid,
			post_author,
			post_subject,
			post_text,
			post_tstamp,
			tstamp, sent_flag)
			VALUES (
			'',
			'" . $pid . "',
			'" . $message['conference_id'] . "',
			'" . $message['thread_id'] . "',
			'" . $message['uid'] . "',
			'" . $author . "',
			'" . $subject . "',
			'" . $text . "',
			'" . $message['tstamp'] . "',
			'" . $tstamp . "',
			 '0')
		 ";
		if ($this->debug) {
			print "<strong>addPostToQueue:</strong> Message is added to the queue with the following query:<br>";
			print $query;
			print "<br><br>";
		}
		$query_results = mysql_query($query,$this->db_connection);
		
		$query_update = "UPDATE tx_chcforum_post SET post_sent_flag = 1 WHERE uid = ".$message['uid'];
		mysql_query($query_update,$this->db_connection);
	}

	/**
	* Check the log to make sure it's empty. If it's not, process whatever is in it.
	*
	* @return [type]  ...
	*/
	function checkLog() {
		$query = 'SELECT * FROM tx_chcforum_mail_log';
		$query_results = mysql_query($query,$this->db_connection);

		while ($row = mysql_fetch_assoc($query_results)) {
			$this->processLogRow($row);
		}
		
		mysql_free_result($query_results);
	}
       
	/**
	* Check the log to make sure it's empty. If it's not, process whatever is in it.
	* 
	* @param array $log_data this array contains a row from the log table (user uid and message uid).
	* @return [type]  ...
	*/
	function processLogRow($log_data) {
		$recip = $log_data['recipient_uid']; // user receiving a message
		$queue = $log_data['message_uid']; // queue entry being sent

		$query = 'SELECT * FROM tx_chcforum_mail_queue WHERE uid = ' . $queue;
		$results = mysql_query($query,$this->db_connection);
		
		// If queue entry not found return
		if (!$results) return;
		
		$message = mysql_fetch_assoc($results);

		// Only send message if recipient is not author of post
		if($recip != $message['post_author']) {			
			// build the message
			$text = $message['post_text'];
			$subject = $message['post_subject'];
			$author = $this->getAuthor($message['post_author']);
			$author_email = $author['email'];
			$author_name = $author['username'];
			$day_time = strftime('%b %d %Y',$message['post_tstamp']);
			$name = $email = '';
			
			$this_message = $this->makeMessage($text, $author_name, $author_email, $day_time, $name, $email, $message['conf_uid'], $message['thread_uid']);
			$this_recipient = $this->getOneRecipient($recip);
			
			// Get the email and name.
			$email = $this_recipient['email'];
			$name = $this_recipient['name'];
			
			// Send the message
			$this->sendMessage($email, $subject, $this_message);
		} else if($this->debug) {
			print "<br><br><strong>processLogRow:</strong> Skipping mail as recipient is post author:<br>";
		}
		echo "recipient: " . $recip	. "<br>" . "author:" . $message['post_author'] . "<br>";
		// Delete this line from the log
		$query = 'DELETE FROM tx_chcforum_mail_log WHERE recipient_uid = ' . $recip  . ' AND message_uid = ' . $message['uid'];
		$query_results = mysql_query($query,$this->db_connection);
		
		mysql_free_result($query_results);
	}

	/**
	* [Describe function...]
	*
	* @return [type]  ...
	
	
	*/
	function getMessages() {
		$data_array=array();
		
		$query = 'SELECT * FROM tx_chcforum_post WHERE post_sent_flag = 0 and hidden = 0 and deleted = 0';
		$query_results = mysql_query($query,$this->db_connection);
		
		while ($row = @mysql_fetch_assoc($query_results)) {
			$data_array[] = $row;	
		}
		if ($this->debug && $row) {
			print "<br><br><strong>getMessages:</strong> Gets the information for message, puts it in array that looks like this:<br>";
			print_r($data_array);
			print "<br><br>";
		}

		mysql_free_result($query_results);
		return $data_array;
	}

	/**
	* [Describe function...]
	*
	* @param [type]  $pid: ...
	* @return [type]  ...
	*/
	function getFconf($pid) {
		// get the forum plugin record where starting pages value is the same
		// as the pid for this message
		$fields = 'tt_content.pid as forum_instance_pid,tt_content.pi_flexform AS flex';
		$tables = 'tt_content';
		$where = 'tt_content.list_type = "chc_forum_pi1" AND tt_content.deleted=0 AND tt_content.pages=' . $pid;
		$query = 'SELECT ' . $fields . ' FROM ' . $tables . ' WHERE ' . $where;
		$query_results = mysql_query($query,$this->db_connection);
		$row = @mysql_fetch_assoc($query_results);
		
		// if starting point didn't return any records, look for general records
		// storage page.
		if (!$row) {
			$tables = 'tt_content LEFT JOIN pages ON tt_content.pid = pages.uid';
			$where = 'tt_content.list_type="chc_forum_pi1" AND tt_content.deleted=0 AND pages.storage_pid='.$pid;
			$query = 'SELECT ' . $fields . ' FROM ' . $tables . ' WHERE ' . $where;
			$query_results = mysql_query($query,$this->db_connection);
			// note: it is convievable that someone would set up 2 forums, both pointing
			// at the same storage folder. And it's possible that these forums will have
			// different settings. However, in this case, we'll just use the first one
			// that the mailer finds... I'm not sure how else to deal with this.
			$row = @mysql_fetch_assoc($query_results);
		}
		mysql_free_result($query_results);
		
		if ($row['flex']) $flex_arr = t3lib_div::xml2array($row['flex']);
		$flex_arr['data']['s_mailer']['lDEF']['forum_instance_pid'] = $row['forum_instance_pid'];
		return $flex_arr['data']['s_mailer']['lDEF'];
	}
}
?>