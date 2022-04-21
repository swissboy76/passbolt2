#!/usr/bin/php -q
<?php
// Check platform requirements
require '/etc/passbolt/requirements.php';
require dirname(__DIR__) . '/vendor/autoload.php';
include '/etc/passbolt/bootstrap.php';

use App\Application;
use Cake\Console\CommandRunner;

// Build the runner with an application and root executable name.
$runner = new CommandRunner(new Application('/etc/passbolt'), 'cake');
exit($runner->run($argv));
