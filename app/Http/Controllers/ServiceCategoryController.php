<?php

namespace App\Http\Controllers;

use App\Http\Requests\serviceCategoryRequest;
use App\Models\servicecategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ServiceCategoryController extends Controller
{

    public function index()
    {
        $All = ServiceCategory::get()->toFlatTree();
        return view('backend.servicecategory.index', compact('All'));
    }

    public function create()
    {
        $Kategori = ServiceCategory::all();
        return view('backend.servicecategory.create', compact('Kategori'));
    }

    public function store(Request $request)
    {
        $New = ServiceCategory::create($request->except('_token', 'image', 'gallery'));

        if ($request->image) {
            $New->addMedia($request->image)->toMediaCollection();
        }

        if ($request->parent_id) {
            $node = ServiceCategory::find($request->parent_id);
            $node->appendNode($New);
        }

        toast(SWEETALERT_MESSAGE_CREATE, 'success');
        return redirect()->route('servicecategory.index');

    }


    public function show($id)
    {
        $Show = ServiceCategory::findOrFail($id);
        return view('frontend.servicecategory.index', compact('Show'));
    }

    public function edit($id)
    {
        $Edit = ServiceCategory::findOrFail($id);
        $Kategori = ServiceCategory::all();

        return view('backend.servicecategory.edit', compact('Edit', 'Kategori'));
    }

    public function update(servicecategoryRequest $request, $id)
    {

        //dd($request->all());
        $Update = ServiceCategory::findOrFail($id);

        $Update->title = $request->title;
        $Update->short = $request->short;
        $Update->desc = $request->desc;

        $Update->seo_title = $request->seo_title;
        $Update->seo_desc = $request->seo_desc;
        $Update->seo_key = $request->seo_key;

        $Update->parent_id = $request->parent_id;

        $Update->save();

        if ($request->hasFile('image')) {
            $Update->media()->delete();
            $Update->addMedia($request->image)->toMediaCollection();
        }

        if ($request->parent) {
            $node = ServiceCategory::find($request);
            $node->appendNode($Update);
        }

        toast(SWEETALERT_MESSAGE_UPDATE, 'success');
        return redirect()->route('servicecategory.index');

    }

    public function destroy($id)
    {
        $Delete = ServiceCategory::find($id);
        if ($Delete->getCategoryCount() > 0) {
            alert()->error('Silinemez', 'Kategoriye ait sayfa bulunmaktadır.');
            return Redirect::back();
        }
        $Delete->delete();

        toast(SWEETALERT_MESSAGE_DELETE, 'success');
        return redirect()->route('servicecategory.index');
    }
}
