<?php

namespace App\Admin\Controllers;

use App\Models\Ads\ProductCarousel;
use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductCarouselController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Слайдер товаров';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProductCarousel());

        $grid->sortable();

        $grid->column('title', 'Заголовок');
        $grid->column('categories', 'Категории')->display(function ($categories) {
            return implode(' ', array_map(function ($role) {
                return "<span class='label label-success'>$role[title]</span>";
            }, $categories));
        });
        $grid->column('only_sale', 'Только со скидкой')->switch();
        $grid->column('only_new', 'Только новинки')->switch();
        $grid->column('count', 'Количество товаров');

        $grid->model()->where('is_imidj', false)->whereNull('enum_type_id');

        $grid->disablePagination();
        $grid->disableFilter();
        $grid->disableExport();
        $grid->disableColumnSelector();
        $grid->disableRowSelector();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param  mixed  $id
     * @return Show
     */
    protected function detail($id)
    {
        return back();
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ProductCarousel());

        $form->text('title', 'Заголовок');
        $form->multipleSelect('categories_list', 'Категории')->options(Category::getFormatedTree())->required();
        $form->switch('only_sale', 'Только товары со скидкой');
        $form->switch('only_new', 'Только новинки');
        $form->number('count', 'Количество выводимых товаров')->default(15);
        $form->hidden('speed', 'Скорость (мс)')->default(3000);

        return $form;
    }
}
