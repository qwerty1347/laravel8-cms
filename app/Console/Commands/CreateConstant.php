<?php

namespace App\Console\Commands;

use App\Traits\CreateArtisanTrait;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateConstant extends Command
{
    use CreateArtisanTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:constant {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new SampleConstant Class';
    /**
     * @var \App\Console\Commands\Filesystem
     */
    public $files;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = trim($this->argument('name'));
        $this->create('Constants', $name);
    }
}
