<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BaseModel extends Model
{
    /**
     * Define relationships that should always be loaded
     */
    protected $autoRelations = [];

    protected static function booted()
    {
        static::addGlobalScope('autoRelations', function (Builder $builder) {

            $model = $builder->getModel();

            if (!empty($model->autoRelations)) {
                $builder->with($model->autoRelations);
            }

        });
    }
}