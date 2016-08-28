<?php

namespace Collejo\App\Modules\Employees\Http\Requests;

use Collejo\App\Http\Requests\Request;

class UpdateEmployeePositionRequest extends Request
{

	public function rules()
	{
		$createRequest = new CreateEmployeePositionRequest();

	    return array_merge($createRequest->rules(), [
	    		'name' => 'required|unique:employee_positions,name,' . $this->get('epid')
	    	]);
	}

	public function attributes()
	{
		$createRequest = new CreateEmployeePositionRequest();

		return $createRequest->attributes();
	}
}