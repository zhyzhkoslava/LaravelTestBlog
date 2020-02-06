<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BlogCategoryRepository
 *
 * @package App\Repositories
 */
class BlogCategoryRepository extends CoreRepository
{
    /**
     * @return string
     */
    public function getModelClass()
    {
        return Model::class; //   App\Models\BlogCategory::class;
    }
    /**
     * Get model for edit in admin
     *
     * @param int $id
     * @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * Get list of categories for output in option list
     *
     * @return mixed
     */
    public function getForComboBox()
    {
//        return $this->startConditions()->all();
        $columns = implode(', ', [
            'id',
            'CONCAT (id, ". ", title) AS id_title',
        ]);

//        $result[] = $this
//            ->startConditions()
//            ->selectRaw($columns)
//            ->toBase()
//            ->get();

        return $this
            ->startConditions()
            ->selectRaw($columns)
            ->toBase()
            ->get();

//        return $result;
    }

    /**
     * Get categories for paginator
     *
     * @param int/null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate($perPage = null)
    {
        $columns = ['id', 'title', 'parent_id'];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->with('parentCategory:id,title')
            ->paginate($perPage);

//        dd($result);
        return $result;
    }
}
