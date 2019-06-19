<?php

require __DIR__ . '/vendor/autoload.php';

use Swoft\DocBuild\TranslatorCommand;

$app = Swoft\DocBuild\App::create();
$tr  = new TranslatorCommand();

$app->addCommand($tr->name, $tr, $tr->desc);
$app->run();
