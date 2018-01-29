<?php

namespace Collejo\App\Modules\Classes\Repository;

use Collejo\Foundation\Repository\BaseRepository;
use Collejo\App\Modules\Classes\Contracts\ClassRepository as ClassRepositoryContract;
use Collejo\App\Modules\Classes\Models\Batch;
use Collejo\App\Modules\Classes\Models\Grade;
use Collejo\App\Modules\Classes\Models\Clasis;
use Collejo\App\Modules\Classes\Models\Term;

class ClassRepository extends BaseRepository implements ClassRepositoryContract
{
    public function deleteClass($classId, $gradeId)
    {
        $this->findClass($classId, $gradeId)->delete();
    }

    public function updateGrade(array $attributes, $id)
    {
        $this->findGrade($id)->update($attributes);

        return $this->findGrade($id);
    }

    public function findGrade($id)
    {
        return Grade::findOrFail($id);
    }

    public function createGrade(array $attributes)
    {
        return Grade::create($attributes);
    }

    public function getGrades()
    {
        return $this->search(Grade::class);
    }

    public function updateClass(array $attributes, $classId, $gradeId)
    {
        $this->findClass($classId, $gradeId)->update($attributes);

        return $this->findClass($classId, $gradeId);
    }

    public function createClass(array $attributes, $gradeId)
    {
        $attributes['grade_id'] = $this->findGrade($gradeId)->id;

        return Clasis::create($attributes);
    }

    public function findClass($classId, $gradeId)
    {
        return Clasis::where(['grade_id' => $gradeId, 'id' => $classId])->firstOrFail();
    }

    public function getClasses()
    {
        return $this->search(Clasis::class);
    }

    public function updateBatch(array $attributes, $batchId)
    {
        $this->findBatch($batchId)->update($attributes);

        return $this->findBatch($batchId);
    }

    public function deleteTerm($termId, $batchId)
    {
        $this->findTerm($termId, $batchId)->delete();
    }

    public function updateTerm(array $attributes, $termId, $batchId)
    {
        $this->findTerm($termId, $batchId)->update($attributes);

        return $this->findTerm($termId, $batchId);
    }

    public function findTerm($termId, $batchId)
    {
        return Term::where(['batch_id' => $batchId, 'id' => $termId])->firstOrFail();
    }

    public function assignGradesToBatch(array $gradeIds, $batchId)
    {
        $this->findBatch($batchId)->grades()->sync($this->createPrivotIds($gradeIds));
    }

    public function createTerm(array $attributes, $batchId)
    {
        $batch = $this->findBatch($batchId);

        $attributes['start_date'] = toUTC($attributes['start_date']);
        $attributes['end_date'] = toUTC($attributes['end_date']);
        $attributes['batch_id'] = $batch->id;

        return Term::create($attributes);
    }

    public function activeBatches()
    {
        return Batch::active();
    }

    public function findBatch($id)
    {
        return Batch::findOrFail($id);
    }

    public function createBatch(array $attributes)
    {
        return Batch::create($attributes);
    }

    public function getBatches()
    {
        return $this->search(Batch::class);
    }
}