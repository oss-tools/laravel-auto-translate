<?php

namespace Ben182\AutoTranslate\Commands;

use Ben182\AutoTranslate\AutoTranslate;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MissingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autotrans:missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translates all source translations that are not set in your target translations';

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

        $missingCount = 0;
        $strLen = 0;
        foreach ($targetLanguages as $targetLanguage) {
            $missing = $this->autoTranslator->getMissingTranslations($targetLanguage);
            $missingCount += $missing->count();
            $strLen += $missing->map(function ($value) {
                return strlen($value);
            })->sum();
            $this->line('Found '.$missing->count().' missing keys in '.$targetLanguage);
        }

        if ($missingCount === 0) {
            $this->line('0 missing keys found...aborting');

            return;
        }

        $this->line($strLen.' characters will be translated');

        if (! $this->confirm('Continue?', true)) {
            return;
        }

        $bar = $this->output->createProgressBar($missingCount);
        $bar->start();

        foreach ($targetLanguages as $targetLanguage) {
            $missing = $this->autoTranslator->getMissingTranslations($targetLanguage);

            $translated = $this->autoTranslator->translate($targetLanguage, $missing, function () use ($bar) {
                $bar->advance();
            });

            $this->autoTranslator->fillLanguageFiles($targetLanguage, $translated);
        }

        $bar->finish();

        $this->info("\nTranslated ".$missingCount.' missing language keys.');
    }
}
