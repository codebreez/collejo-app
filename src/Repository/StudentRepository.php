<?php

namespace Collejo\App\Repository;

use Collejo\Core\Foundation\Repository\BaseRepository;
use Collejo\Core\Contracts\Repository\StudentRepository as StudentRepositoryContract;
use Collejo\Core\Contracts\Repository\ClassRepository as ClassRepositoryContract;
use Collejo\Core\Contracts\Repository\GuardianRepository as GuardianRepositoryContract;
use Collejo\Core\Contracts\Repository\UserRepository as UserRepositoryContract;
use Collejo\App\Models\Student;
use Collejo\App\Models\Address;
use Collejo\App\Models\StudentCategory;
use DB;
use Carbon;

class StudentRepository extends BaseRepository implements StudentRepositoryContract {

	protected $userRepository;
	
	protected $classRepository;

	public function updateStudentCategory(array $attributes, $studentCategoryId)
	{
		$this->findStudentCategory($studentCategoryId)->update($attributes);

		return $this->findStudentCategory($studentCategoryId);
	}

	public function createStudentCategory(array $attributes)
	{
		return StudentCategory::create($attributes);
	}

	public function findStudentCategory($studentCategoryId)
	{
		return StudentCategory::findOrFail($studentCategoryId);
	}

	public function assignGuardian($guardianId, $studentId)
	{
		if (!$this->findStudent($studentId)->guardians->contains($guardianId)) {
			$this->findStudent($studentId)->guardians()->attach($this->guardiansRepository->findGuardian($guardianId), [
					'id' => $this->newUUID(),
					'updated_at' => Carbon::now()
				]);
		}
	}

	public function assignToClass($batchId, $gradeId, $classId, $studentId)
	{
		if (!$this->findStudent($studentId)->classes->contains($classId)) {
			$this->findStudent($studentId)->classes()->attach($this->classRepository->findClass($classId, $gradeId), [
					'batch_id' => $this->classRepository->findBatch($batchId)->id,
					'id' => $this->newUUID(),
					'updated_at' => Carbon::now()
				]);
		}
	}

	public function deleteAddress($addressId, $studentId)
	{
		$this->findAddress($addressId, $studentId)->delete();
	}

	public function updateAddress(array $attributes, $addressId, $studentId)
	{
		$attributes['is_emergency'] = isset($attributes['is_emergency']);

		$this->findAddress($addressId, $studentId)->update($attributes);

		return $this->findAddress($addressId, $studentId);
	}

	public function createAddress(array $attributes, $studentId)
	{
		$address = null;

		$student = $this->findStudent($studentId);

		$attributes['user_id'] = $student->user->id;
		$attributes['is_emergency'] = isset($attributes['is_emergency']);

		DB::transaction(function () use ($attributes, &$address) {
			$address = Address::create($attributes);
		});

		return $address;
	}

	public function findAddress($addressId, $studentId)
	{
		return Address::where(['user_id' => $this->findStudent($studentId)->user->id, 'id' => $addressId])->firstOrFail();
	}

	public function findStudent($id)
	{
		return Student::findOrFail($id);
	}

	public function getStudents($criteria)
	{
		return $this->search($criteria);
	}

	public function update(array $attributes, $studentId)
	{
		$student = null;

		if (isset($attributes['admitted_on'])) {
			$attributes['admitted_on'] = toUTC($attributes['admitted_on']);
		}

		if (!isset($attributes['image_id'])) {
			$attributes['image_id'] = null;
		}

		$studentAttributes = $this->parseFillable($attributes, Student::class);

		DB::transaction(function () use ($attributes, $studentAttributes, &$student, $studentId) {
			$student = parent::update($studentAttributes, $studentId);

			$user = $this->userRepository->update($attributes, $student->user->id);
		});

		return $student;
	}

	public function create(array $attributes)
	{
		$student = null;
		
		$attributes['admitted_on'] = toUTC($attributes['admitted_on']);

		if (!isset($attributes['image_id'])) {
			$attributes['image_id'] = null;
		}
		
		$studentAttributes = $this->parseFillable($attributes, Student::class);

		DB::transaction(function () use ($attributes, $studentAttributes, &$student) {
			$user = $this->userRepository->create($attributes);

			$student = parent::create(array_merge($studentAttributes, ['user_id' => $user->id]));

			$this->userRepository->addRoleToUser($user, $this->userRepository->getRoleByName('student'));
		});


		return $student;
	}

	public function getStudentCategories()
	{
		return $this->search(StudentCategory::class);
	}

    public function boot()
    {
    	parent::boot();

    	$this->userRepository = app()->make(UserRepositoryContract::class);
    	$this->classRepository = app()->make(ClassRepositoryContract::class);
    	$this->guardiansRepository = app()->make(GuardianRepositoryContract::class);
    }
}