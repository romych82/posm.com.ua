<?php
# Include the Autoloader (see "Libraries" for install instructions)
require 'vendor/autoload.php';
use Mailgun\Mailgun;

# Instantiate the client.
$mgClient = new Mailgun('key-04626bc1c83765b495081d9f471cda8f');
$domain = "sandbox880c08f8dbad44b6bededbd7915aa55b.mailgun.org";

# Make the call to the client.
$result = $mgClient->sendMessage("$domain",
                  array('from'    => 'Mailgun Sandbox <postmaster@sandbox880c08f8dbad44b6bededbd7915aa55b.mailgun.org>',
                        'to'      => 'Roman <rtemchenko@gmail.com>',
                        'subject' => 'Hello Roman',
                        'text'    => 'Congratulations Roman, you just sent an email with Mailgun!  You are truly awesome!  You can see a record of this email in your logs: https://mailgun.com/cp/log .  You can send up to 300 emails/day from this sandbox server.  Next, you should add your own domain so you can send 10,000 emails/month for free.'));
?>