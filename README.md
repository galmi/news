Test project News Portal
========================

Installation
------------

1.Clone project
 
 ~~~
 git clone git@github.com:galmi/news.git
 ~~~
 
2. Update dependences
 ~~~
 php composer.phar install
 ~~~

3. Create database *news_yii*

4. Update config/db.php, set *username* and *password*

  ```php
  return [
      'class' => 'yii\db\Connection',
      'dsn' => 'mysql:host=localhost;dbname=news_yii',
      'username' => 'root',
      'password' => '123',
      'charset' => 'utf8',
  ];
  ```
  
5. Run migration script from *news* directory or manual install from *createDatabase.sql* file
  
  ~~~
  php yii migrate/up
  ~~~

6. Use this command for start local server on http://localhost:8000
  
  ~~~
  php -S localhost:8000 -t ./web
  ~~~
 
7. Open in browser http://localhost:8000

Configure mailer
----------------

1. Change config/mailer.php for sending emails

2. Fake sending email

  ```php
  return [
  	'class' => 'yii\swiftmailer\Mailer',
  	// send all mails to a file if true.
  	'useFileTransport' => true
  ];
  ```
3. Using php mail

  ```php
  return [
  	'class' => 'yii\swiftmailer\Mailer',
  	'transport' => [
  		'class' => 'Swift_SendmailTransport',
  	]
  ];
  ```
4. Using SMTP server

  ```php
  return [
     'class' => 'yii\swiftmailer\Mailer',
     'transport' => [
         'class' => 'Swift_SmtpTransport',
         'host' => 'localhost',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
         'username' => 'username',
         'password' => 'password',
         'port' => '587', // Port 25 is a very common port too
         'encryption' => 'tls', // It is often used, check your provider or mail server specs
     ]
  ]
  ```
