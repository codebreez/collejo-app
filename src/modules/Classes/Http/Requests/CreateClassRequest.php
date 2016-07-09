<?php

namespace Collejo\App\Modules\Classes\Http\Requests;

use Collejo\App\Http\Requests\Request;

class CreateClassRequest extends Request
{

	public function rules()
	{
	    return [
	        'name' => 'required'
	    ];
	}

	public function attributes()
	{
		return [
	        'name' => 'Class Name'
	    ];
	}
}