<?php

namespace Ben182\AutoTranslate\Commands;

use Ben182\AutoTranslate\AutoTranslate;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autotrans:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translates all source translations to target translations';

    protected $autoTranslator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AutoTranslate $autoTranslator)
    {
        parent::__construct();
        $this->autoTranslator = $autoTranslator;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $targetLanguages = Arr::wrap(config('auto-translate.target_language'));

        $foundLanguages = count($targetLanguages);
        $this->line('Found '.$foundLanguages.' '.Str::plural('language', $foundLanguages).' to translate');

        $availableTranslations = 0;
        $sourceTranslations = $this->autoTranslator->getSourceTranslations();
        $availableTranslations = count($dottedTranslations = Arr::dot($sourceTranslations)) * count($targetLanguages);

        if (empty($dottedTranslations)) {
            $this->line('0 keys found...aborting');

            return;
        }

        $strLen = collect($dottedTranslations)->map(function ($value) {
            return strlen($value);
        })->sum() * count($targetLanguages);

        $this->line($strLen.' characters will be translated');

        if (! $this->confirm('Continue?', true)) {
            return;
        }

        $bar = $this->output->createProgressBar($availableTranslations);
        $bar->start();

        foreach ($targetLanguages as $targetLanguage) {
            $dottedSource = Arr::dot($sourceTranslations);

            $translated = $this->autoTranslator->translate($targetLanguage, $dottedSource, function () use ($bar) {
                $bar->advance();
            });

            $this->autoTranslator->fillLanguageFiles($targetLanguage, $translated);
        }

        $bar->finish();

        $this->info("\nTranslated ".$availableTranslations.' language keys.');
    }
}
