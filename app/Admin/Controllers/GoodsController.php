<?php

namespace App\Admin\Controllers;

use App\Model\p_goods;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GoodsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
        
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new p_goods);

        $grid->id('Id');
        $grid->goods_id('Goods id');
        $grid->goods_name('Goods name');
        $grid->goods_price('Goods price');
        $grid->goods_list('Goods list');
        $grid->goods_num('Goods num');
        $grid->goods_url('Goods url');
        $grid->is_goods('Is goods');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(p_goods::findOrFail($id));

        $show->id('Id');
        $show->goods_id('Goods id');
        $show->goods_name('Goods name');
        $show->goods_price('Goods price');
        $show->goods_list('Goods list');
        $show->goods_num('Goods num');
        $show->goods_url('Goods url');
        $show->is_goods('Is goods');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new p_goods);

        $form->number('goods_id', 'Goods id');
        $form->text('goods_name', 'Goods name');
        $form->switch('goods_price', 'Goods price');
        $form->text('goods_list', 'Goods list');
        $form->number('goods_num', 'Goods num');
        $form->text('goods_url', 'Goods url');
        $form->switch('is_goods', 'Is goods')->default(1);

        return $form;
    }
}
