<?php 

namespace Collejo\App\Modules\Employees\Http\Controllers;

use Collejo\App\Http\Controllers\Controller as BaseController;
use Collejo\App\Repository\EmployeeRepository;
use Collejo\App\Modules\Employees\Http\Requests\CreateEmployeePositionRequest;
use Collejo\App\Modules\Employees\Http\Requests\UpdateEmployeePositionRequest;

class EmployeePositionController extends BaseController
{

	protected $employeeRepository;

	public function getEmployeePositionNew()
	{
		return $this->printModal(view('employees::modals.edit_employee_position', [
						'employee_position' => null,
						'position_form_validator' => $this->jsValidator(CreateEmployeePositionRequest::class),
						'employee_categories' => $this->employeeRepository->getEmployeeCategories()->all()
					]));
	}

	public function postEmployeePositionNew(CreateEmployeePositionRequest $request)
	{
		return $this->printPartial(view('employees::partials.employee_position', [
						'employee_position' => $this->employeeRepository->createEmployeePosition($request->all()),
					]), trans('employees::employee_position.employee_position_created'));
	}

	public function getEmployeePositionEdit($id)
	{
		return $this->printModal(view('employees::modals.edit_employee_position', [
						'employee_position' => $this->employeeRepository->findEmployeePosition($id),
						'position_form_validator' => $this->jsValidator(UpdateEmployeePositionRequest::class),
						'employee_categories' => $this->employeeRepository->getEmployeeCategories()->all()
					]));
	}

	public function postEmployeePositionEdit(UpdateEmployeePositionRequest $request, $id)
	{
		return $this->printPartial(view('employees::partials.employee_position', [
						'employee_position' => $this->employeeRepository->updateEmployeePosition($request->all(), $id),
					]), trans('employees::employee_position.employee_position_updated'));
	}

	public function getEmployeePositionList()
	{
		return view('employees::employee_position_list', [
						'employee_positions' => $this->employeeRepository->getEmployeePositions()->paginate(config('collejo.pagination.perpage'))
					]);
	}

	public function __construct(EmployeeRepository $employeeRepository)
	{
		$this->employeeRepository = $employeeRepository;
	}
}