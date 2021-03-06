<?php

namespace Collejo\App\Modules\Classes\Providers;

use Collejo\App\Modules\Classes\Contracts\ClassRepository as ClassRepositoryContract;
use Collejo\App\Modules\Classes\Criteria\BatchListCriteria;
use Collejo\App\Modules\Classes\Repositories\ClassRepository;
use Collejo\Foundation\Modules\BaseModuleServiceProvider as ModuleServiceProvider;

class ClassesModuleServiceProvider extends ModuleServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ClassRepositoryContract::class, ClassRepository::class);
        $this->app->bind(BatchListCriteria::class);
    }

    /**
     * Returns an array of permissions for the current module.
     *
     * @return array
     */
    public function getPermissions()
    {
        return [
            'view_batch_details' => ['add_edit_batch', 'list_batches'],
            'list_batches'       => ['transfer_batch', 'graduate_batch'],
            'view_grade_details' => ['add_edit_grade', 'list_grades', 'view_class_details'],
            'view_class_details' => ['add_edit_class', 'list_classes', 'list_class_students'],
        ];
    }
}
