<?php

namespace Swoft\DocBuild;

use Google\Cloud\Translate\TranslateClient;
use Toolkit\Cli\Color;

/**
 * class TranslatorCommand
 */
class TranslatorCommand
{
    public $name = 'trans';

    public $desc = 'translate text by google translate API';
/*
curl -X POST \
     -H "Authorization: Bearer AIzaSyBpp4wiVQtlCUDqNNJKXSryjmhjt6qQf-A" \
     -H 'Content-Type: application/json' --data "{
     target_language_code: 'ru',
     contents: ['Dr. Watson, come here!']
}" "https://translation.googleapis.com/v3beta1/projects/transtool/locations/global:translateText"
 */

    private $source;
    private $target;

    /**
     * showHelp
     */
    public function showHelp(App $app): void
    {
        $bin  = $app->getScript();
        $desc = ucfirst($this->desc);
        $help = <<<HELP
{$desc}

Usage:
  php $bin [args ...] [--opt ...]

Options:
  -s, --sl      The source language name. eg: zh-CN, en
  -t, --tl      The traget language name. eg: zh-CN, en
  --file        Translate the input file contents
  --text        Translate the input words text
  -d, --driver  Set the translate driver. Allow:
                 ms - microsoft translate
                 gg - google translate
Example:
  php $bin -s=zh-CN -t=en -d=ms --file=some/file.txt

HELP;
        echo $help;
    }

    public function __invoke(App $app): void
    {
        if ($app->getOpt('h', false) || $app->getOpt('help', false)) {
            $this->showHelp($app);
            return;
        }

        if ($file = $app->getOpt('file')) {
            if (!file_exists($file)) {
                throw new \InvalidArgumentException('the option file is not exist');
            }

            $text = file_get_contents($file);
            echo Color::info('translate contents from the input file: ' . $file) . PHP_EOL;
        } else {
            $text = $app->getOpt('text');
        }

        if (!$text) {
            throw new \InvalidArgumentException("please set 'file' or 'text' option to translate");
        }

        # The text to translate
        // $text = '欢迎您';

        # The target language
        $this->source = $app->getOpt('sl', 'zh-CN');
        $this->target = $app->getOpt('tl', 'en');

        $driver = $app->getOpt('driver', $app->getOpt('d', 'ms'));

        if ($driver === 'ms') {
            $textType = $file && strpos($file, '.html') ? 'html' : 'plain';
            $this->transByMicrosoft($text, $textType);
        } elseif ($driver === 'gg') {
            $this->transByGoogle($text);
        } else {
            throw new \InvalidArgumentException("invalid 'driver' option value");
        }
    }

    public function transByGoogle(string $text)
    {
        # Your Google Cloud Platform project ID
        $projectId = 'transtool';

        # Instantiates a client
        $translate = new TranslateClient([
            'projectId' => $projectId,
            'key'       => 'AIzaSyBpp4wiVQtlCUDqNNJKXSryjmhjt6qQf-A'
        ]);

        echo Color::info("Begin translate contents by google API\n");

        # Translates some text into Russian
        $translation = $translate->translate($text, [
            'source' => $this->source,
            'target' => $this->target
        ]);

        printf("Raw Contents:\n\n %s\nTranslation Result:\n\n %s\n", $text, $translation['text']);
    }

    public function transByMicrosoft(string $text, string $textType = 'plain')
    {
        $mtr = new MicrosoftTranslator([
            'key' => '122854f25f0c457f8af926d747e718ac',
        ]);

        $ret = $mtr->translate($text, [
            'from'  => $this->source,
            'to'    => $this->target,
            'textType' => $textType,
        ]);

        printf("Raw Contents:\n\n %s\nTranslation Result:\n\n %s\n", $text, $ret['translations'][0]['text']);
    }
}
