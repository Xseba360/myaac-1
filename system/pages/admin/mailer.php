<?php
/**
 * Mailer
 *
 * @package   MyAAC
 * @author    Slawkens <slawkens@gmail.com>
 * @copyright 2017 MyAAC
 * @version   0.6.6
 * @link      http://my-aac.org
 */
defined('MYAAC') or die('Direct access not allowed!');
$title = 'Mailer';

if(!hasFlag(FLAG_CONTENT_MAILER) && !superAdmin())
{
	echo 'Access denied.';
	return;
}

if(!$config['mail_enabled'])
{
	echo 'Mail support disabled.';
	return;
}

$mail_content = isset($_POST['mail_content']) ? stripslashes($_POST['mail_content']) : NULL;
$mail_subject = isset($_POST['mail_subject']) ? stripslashes($_POST['mail_subject']) : NULL;
$preview = isset($_REQUEST['preview']);

$preview_done = false;
if($preview) {
	if(!empty($mail_content) && !empty($mail_subject)) {
		$preview_done = _mail($account_logged->getCustomField('email'), $mail_subject, $mail_content);
		
		if(!$preview_done)
			error('Error while sending preview mail: ' . $mailer->ErrorInfo);
	}
}


echo $twig->render('admin.mailer.html.twig', array(
	'mail_subject' => $mail_subject,
	'mail_content' => $mail_content,
	'preview_done' => $preview_done
));

if(empty($mail_content) || empty($mail_subject) || $preview)
	return;

$success = 0;
$failed = 0;

$add = '';
if($config['account_mail_verify'])
	$add = ' AND ' . $db->fieldName('email_verified') . ' = 1';

$query = $db->query('SELECT ' . $db->fieldName('email') . ' FROM ' . $db->tableName('accounts') . ' WHERE ' . $db->fieldName('email') . ' != ""' . $add);
foreach($query  as $email)
{
	if(_mail($email['email'], $mail_subject, $mail_content))
		$success++;
	else
	{
		$failed++;
		echo '<br />';
		error('An error occorred while sending email to <b>' . $email['email'] . '</b>. Error: ' . $mailer->ErrorInfo);
	}
}
?>
	Mailing finished.<br/>
	<p class="success"><?php echo $success; ?> emails delivered.</p><br/>
	<p class="warning"><?php echo $failed; ?> emails failed.</p></br>
