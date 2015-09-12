<?php

return [
	'class' => 'yii\swiftmailer\Mailer',
	// send all mails to a file if true.
	'useFileTransport' => false,
	'transport' => [
		'class' => 'Swift_SendmailTransport',
	]
];

?>