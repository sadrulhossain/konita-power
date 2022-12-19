<?php

namespace App\Http\Controllers;

use Validator;
use App\ProductCategory;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller {

    private $controller = 'ProductCategory';
    private $parentArr = [];

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $targetArr = ProductCategory::select('product_category.*')->orderBy('order', 'asc');
        $nameArr = ProductCategory::select('name')->orderBy('order', 'asc')->get();
//begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $target) {

                if (!empty($target->parent_id)) {
                    //calling recursive function findParentCategory
                    $this->findParentCategory($target->parent_id, $target->id);
                }
            }
        }
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/productCategory?page=' . $page);
        }

        $parentArr = $this->parentArr;
        //echo '<pre>';print_r($parentArr);exit;

        return view('productCategory.index')->with(compact('targetArr', 'qpArr', 'parentArr', 'nameArr'));
    }

    public function findParentCategory($parentId = null, $id = null) {
        $dataArr = ProductCategory::find($parentId);

        $this->parentArr[$id] = isset($this->parentArr[$id]) ? $this->parentArr[$id] : '';
        $this->parentArr[$id] = $dataArr['name'] . ' &raquo; ' . $this->parentArr[$id];


        if (!empty($dataArr['parent_id'])) {
            $this->findParentCategory($dataArr['parent_id'], $id);
        }

        //exclude last &raquo; sign
        $this->parentArr[$id] = trim($this->parentArr[$id], ' &raquo; ');
        return true;
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $categoryArr = ProductCategory::where('status', 1)->orderBy('name', 'asc')->select('name', 'id', 'parent_id')->get();
        $parentArr = [];

        if (!$categoryArr->isEmpty()) {

            foreach ($categoryArr as $category) {
                //calling recursive function findParentCategory
                $this->findParentCategory($category->parent_id, $category->id);
                $parentArr[$category->id] = trim($this->parentArr[$category->id] . ' &raquo; ' . $category->name, ' &raquo; ');
            }
        }
        return view('productCategory.create')->with(compact('qpArr', 'parentArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:product_category,name',
                    'code' => 'required|unique:product_category,code',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('productCategory/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new ProductCategory;
        $target->name = $request->name;
        $target->code = $request->code;
        $target->parent_id = $request->parent_id;
        $target->order = 0;
        $target->has_machine = !empty($request->has_machine) ? $request->has_machine : '0';
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.PRODUCT_CATEGORY_CREATED_SUCCESSFULLY'));
            return redirect('productCategory');
        } else {
            Session::flash('error', __('label.PRODUCT_CATEGORY_COULD_NOT_BE_CREATED'));
            return redirect('productCategory/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = ProductCategory::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('productCategory');
        }
        //calling recursive function findParentCategory

        $categoryArr = ProductCategory::where('status', 1)->orderBy('name', 'asc')->select('id', 'parent_id', 'name')->where('id', '!=', $id)->get();
        //echo '<pre>';print_r($categoryArr);exit;
        $parentArr = [];

        if (!$categoryArr->isEmpty()) {
            foreach ($categoryArr as $category) {
                //calling recursive function findParentCategory
                $this->findParentCategory($category->parent_id, $category->id);
                $parentArr[$category->id] = trim($this->parentArr[$category->id] . ' &raquo; ' . $category->name, ' &raquo; ');
            }
        }
        //passing param for custom function
        $qpArr = $request->all();

        return view('productCategory.edit')->with(compact('target', 'qpArr', 'parentArr', 'orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = ProductCategory::find($id);
        $presentOrder = $target->order;
        //echo '<pre>';print_r($target);exit;
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:product_category,name,' . $id,
                    'code' => 'required|unique:product_category,code,' . $id,
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('productCategory/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->code = $request->code;
        $target->parent_id = $request->parent_id;
        $target->order = $request->order;
        $target->has_machine = !empty($request->has_machine) ? $request->has_machine : '0';
        $target->status = $request->status;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.PRODUCT_CATEGORY_UPDATED_SUCCESSFULLY'));
            return redirect('productCategory' . $pageNumber);
        } else {
            Session::flash('error', __('label.PRODUCT_CATEGORY_COULD_NOT_BE_UPDATED'));
            return redirect('productCategory/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = ProductCategory::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

//        //Dependency
        $dependencyArr = [
            'ProductCategory' => ['1' => 'parent_id'],
            'Product' => ['1' => 'product_category_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL', ['model' => $model]));
                    return redirect('productCategory' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.PRODUCT_CATEGORY_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PRODUCT_CATEGORY_COULD_NOT_BE_DELETED'));
        }
        return redirect('productCategory' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . urlencode($request->search);
        return Redirect::to('productCategory?' . $url);
    }

}
