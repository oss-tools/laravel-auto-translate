<?php

namespace OSSTools\AutoTranslate\Translators;

use OSSTools\AutoTranslate\Exceptions\LanguageCodeNotExist;
use OSSTools\LibreTranslate\Client;
use Exception;

class LibreTranslateTranslator implements TranslatorInterface
{
    protected $translator;
    protected $source;
    protected $target;

    public function __construct()
    {
        $this->translator = new Client(config('auto-translate.libretranslate.host'), config('auto-translate.libretranslate.api_key'), config('auto-translate.libretranslate.default_source'));
    }

    public function setSource(string $source): self
    {
        $this->source = strtolower($source);

        $this->translator = new Client(config('auto-translate.libretranslate.host'), config('auto-translate.libretranslate.api_key'), $this->source);

        return $this;
    }

    public function setTarget(string $target): self
    {
        $this->target = strtolower($target);

        return $this;
    }

    public function translate(string $string) : string
    {
        try {
            return $this->translator->translate($string, $this->target, $this->source)->first()->getText() ?? '';
        } catch (Exception $th) {
            if ($th->getMessage() === 'The supplied target is invalid') {
                throw LanguageCodeNotExist::throw($this->source, $this->target);
            }

            throw $th;
        }
    }
}
