<?php

namespace App\Libraries\Translator;

use Phalcon\Mvc\User\Component;
use Phalcon\Translate\Adapter\NativeArray;

class Translator extends Component
{
    public function getTranslation()
    {
        $client_lang = $this->session->get('lang');

        if (!$client_lang) {
            switch ($client_lang) {
                case 'es':
                    $client_lang = 'es';
                    break;
                case 'en':
                    $client_lang = 'en';
                    break;
                default:
                    $client_lang = 'en';
                    break;
            }
        }

        $langs_dir = $this->config->application->langsDir;
        $lang_folder = $client_lang;

        $translation_files = glob($langs_dir . $lang_folder .   '/*.php');
        $strings = [];

        foreach ($translation_files as $translation_file) {
           include $translation_file;
        }

        $strings = array_merge(
            $menu,
            $alerts,
            $footer,
            $forms,
            $actions,
            $responses,
            $index,
            $faqs,
            $profile
        );
        return new NativeArray(['content' => $strings]);
    }
}
