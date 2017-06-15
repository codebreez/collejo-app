<?php

/**
 * Copyright (C) 2017 Anuradha Jauayathilaka <astroanu2004@gmail.com>
 */

namespace Collejo\App\Console\Commands;

use Illuminate\Console\Command;
use Collejo\App\Contracts\Repository\UserRepository;

/**
 * Class AdminCreate
 * @package Collejo\App\Console\Commands
 */
class AdminCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new super user account and assigns administrative roles';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->ask('Enter name');
        $email = false;

        do{
            $email = $this->ask('Enter email');

            if (!$this->isValidEmail($email)) {
                $this->error('Enter a valid email address');
            }

        } while (!$this->isValidEmail($email));

        do{
            if ($this->accountExists($email)) {
                $this->error('There is already an account by this email');
                $email = $this->ask('Enter email');
            }

        } while ($this->accountExists($email));

        $password = $this->secret('Enter password');

        $this->userRepository->createAdminUser($name, $email, $password);
    }

    /**
     * Checks if a given email is valid
     *
     * @param $email
     *
     * @return bool
     */
    private function isValidEmail($email)
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Checks if an account is already there in the database for the given email
     *
     * @param $email
     *
     * @return bool
     */
    private function accountExists($email)
    {
        return (bool) $this->userRepository->findByEmail($email);
    }

    /**
     * AdminCreate constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }
}
