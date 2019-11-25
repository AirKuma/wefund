<?php

namespace App\Http\Controllers\Discuss;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\Contracts\BillboardCategoryRepositoryInterface;
use Repositories\Contracts\BillboardRepositoryInterface;
use App\Http\Requests\Discuss\BillboardCategoryRequest;


class BillboardCategoryController extends Controller
{
    protected $categories;
    protected $billboards;

    public function __construct(BillboardCategoryRepositoryInterface $categories,BillboardRepositoryInterface $billboards)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        
        $this->categories = $categories;
        $this->billboards = $billboards;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategory($id)
    {
        $billboard = $this->billboards->find($id);
        $categories = $billboard->categories()->get();
        return View('discuss.billboards.category',compact('id','categories','billboard'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCreateCategory(BillboardCategoryRequest $request,$id)
    {
        $billboard = $this->billboards->find($id);
        $billboard = $billboard->categories()->create($request->all());

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function getEditCategory($id)
    {
        $billboard = $this->billboards->find($id);

        return View('discuss.billboards.edit', compact('billboard'));
    }*/

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function patchUpdateCategory(BillboardCategoryRequest $request, $id)
    {
        $category = $this->categories->find($id);
        $category = $this->categories->update($request->except('_method', '_token'), $id);
        
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyCategory($id)
    {
        $category = $this->categories->delete($id);

         return redirect()->back();
    }
}
