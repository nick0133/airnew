<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;

class CategoryImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($row[0] == 'ID элемента {IE_ID}') {
            return;
        }
        return new Category([
            'name' => $row[1]
        ]);
    }

    public function getCategories(){

    }
}
