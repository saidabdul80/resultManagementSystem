<?php

namespace App\Imports;

use App\Course;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportCourses implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    
    public $depa1 = 's';
    public function __construct($depa)
    {
        $this->depa1 = $depa;
    }



    public function model(array $row)
    {

        return new Course([
            'course_title' => @$row[0],
            'course_code' => @$row[1],
            'course_description' => @$row[2],
            'level_id' => @$row[3],
            'credit_unit' => @$row[4],
            'department_id' => $this->depa1,
            'semester' => @$row[5],
            'status' => '0'
        ]);
    }
}
