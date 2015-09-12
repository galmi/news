<?php

return [
	'class' => 'yii\swiftmailer\Mailer',
	// send all mails to a file if true.
	'useFileTransport' => true,
	'transport' => [
		'class' => 'Swift_SendmailTransport',
	]
];

?>