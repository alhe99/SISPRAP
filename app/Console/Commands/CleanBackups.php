<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class CleanBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia las copias de seguridad locales y en Dropbox';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $local = new Filesystem;
        $local->cleanDirectory(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix());

        $dropbox = Storage::disk('dropbox')->allFiles();
        Storage::disk('dropbox')->delete($dropbox);
    }
}
