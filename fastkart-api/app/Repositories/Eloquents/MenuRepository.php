<?php

namespace App\Repositories\Eloquents;

use Exception;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Exceptions\ExceptionHandler;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

class MenuRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'title' => 'like',
        'child.title' => 'like'
    ];

    public function boot()
    {
        try {

            $this->pushCriteria(app(RequestCriteria::class));

        } catch (ExceptionHandler $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    function model()
    {
       return Menu::class;
    }

    public function show($id)
    {
        try {

            return $this->model->with('child')->findOrFail($id);

        } catch (Exception $e){

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {

            $menu = $this->model->create([
                'title' => $request->title,
                'path' => $request->path,
                'link_type' => $request->link_type,
                'mega_menu' => $request->mega_menu,
                'mega_menu_type' => $request->mega_menu_type,
                'badge_text' => $request->badge_text,
                'badge_type' => $request->badge_type,
                'parent_id' => $request->parent_id,
                'item_image_id' => $request->item_image_id,
                'is_target_blank' => $request->is_target_blank,
                'banner_image_id'   => $request->banner_image_id,
            ]);

            $menu->item_image;
            $menu->banner_image;

            if (isset($request->product_ids)){
                $menu->products()->attach($request->product_ids);
            }

            if (isset($request->blog_ids)){
                $menu->blogs()->attach($request->blog_ids);
            }

            DB::commit();
            return $menu;

        } catch (Exception $e) {

            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {

            $menu = $this->model->findOrFail($id);
            $menu->update($request);

            if (isset($request['product_ids'])){
                $menu->products()->sync($request['product_ids']);
            }

            if (isset($request['blog_ids'])){
                $menu->blogs()->sync($request['blog_ids']);
            }

            DB::commit();
            return $menu;

        } catch (Exception $e) {

            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function updateMenuPosition($menu, $sort)
    {

        if ($menu['id']) {
            $menu = $this->model->findOrFail($menu['id']);
            $menu->update([
                'sort' => $sort
            ]);
        }

        if (count($menu['child'])) {
            $sortIndex = 0;
            foreach ($menu['child'] as $childMenu) {
                $this->updateMenuPosition($childMenu, $sortIndex);
                ++$sortIndex;
            }
        }

        return $menu;
    }

    public function sort($request)
    {
        DB::beginTransaction();
        try {

            $sort = 0;
            $menus = $request->menus;
            foreach ($menus as $menu) {
                $menu = $this->updateMenuPosition($menu, $sort);
                ++$sort;
            }

            $menus = $this->model->whereNull('parent_id')->orderBy('sort')->with('child')->get();
            DB::commit();
            return $menus;

        } catch (Exception $e) {

            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {

            return $this->model->findOrFail($id)?->destroy($id);

        } catch (Exception $e){

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }
}
