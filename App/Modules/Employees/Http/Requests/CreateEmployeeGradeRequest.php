<?php

namespace Collejo\App\Modules\Employees\Http\Requests;

use Collejo\Foundation\Http\Requests\Request;

class CreateEmployeeGradeRequest extends Request
{
    public function rules()
    {
        return [
            'name'                  => 'required|unique:employee_grades',
            'code'                  => 'unique:employee_grades|max:5',
            'priority'              => 'numeric',
            'max_sessions_per_day'  => 'numeric',
            'max_sessions_per_week' => 'numeric',
        ];
    }

    public function attributes()
    {
        return [
            'name'                  => trans('students::employee_grade.name'),
            'code'                  => trans('students::employee_grade.code'),
            'priority'              => trans('students::employee_grade.priority'),
            'max_sessions_per_day'  => trans('students::employee_grade.max_sessions_per_day'),
            'max_sessions_per_week' => trans('students::employee_grade.max_sessions_per_week'),
        ];
    }
}
