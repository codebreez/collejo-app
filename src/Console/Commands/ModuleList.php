<?php

/**
 * Copyright (C) 2017 Anuradha Jauayathilaka <astroanu2004@gmail.com>
 */
namespace Collejo\App\Console\Commands;

use Illuminate\Console\Command;
use DirectoryIterator;
use Module;

/**
 * Class ModuleList
 * @package Collejo\App\Console\Commands
 */
class ModuleList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all loaded modules';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->table(['Identifier', 'Display Name', 'Provider', 'Path'], Module::all()->toArray());
    }
}
