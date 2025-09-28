<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class CrudController extends Controller
{
    protected $model;
    protected $viewPath;
    protected $routePrefix;
    protected $columns = [];

    public function index()
    {
        $records = $this->model::all();
        $title = ucfirst(str_replace('-', ' ', $this->routePrefix));
        
        return view('admin.crud.index', [
            'records' => $records,
            'title' => $title,
            'columns' => $this->columns,
            'createRoute' => $this->routePrefix ? route($this->routePrefix . '.create') : null,
            'editRoute' => $this->routePrefix ? $this->routePrefix . '.edit' : null,
            'deleteRoute' => $this->routePrefix ? $this->routePrefix . '.destroy' : null,
        ]);
    }

    public function create()
    {
        $title = 'Create ' . ucfirst(str_replace('-', ' ', $this->routePrefix));
        
        return view('admin.crud.create', [
            'title' => $title,
            'columns' => $this->columns,
            'storeRoute' => $this->routePrefix ? route($this->routePrefix . '.store') : null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->getValidationRules());
        
        $this->model::create($request->all());
        
        return redirect()->route($this->routePrefix . '.index')
            ->with('success', ucfirst(str_replace('-', ' ', $this->routePrefix)) . ' created successfully.');
    }

    public function show($id)
    {
        $record = $this->model::findOrFail($id);
        $title = 'View ' . ucfirst(str_replace('-', ' ', $this->routePrefix));
        
        return view('admin.crud.show', [
            'record' => $record,
            'title' => $title,
            'columns' => $this->columns,
        ]);
    }

    public function edit($id)
    {
        $record = $this->model::findOrFail($id);
        $title = 'Edit ' . ucfirst(str_replace('-', ' ', $this->routePrefix));
        
        return view('admin.crud.edit', [
            'record' => $record,
            'title' => $title,
            'columns' => $this->columns,
            'updateRoute' => $this->routePrefix ? route($this->routePrefix . '.update', $record->id) : null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate($this->getValidationRules());
        
        $record = $this->model::findOrFail($id);
        $record->update($request->all());
        
        return redirect()->route($this->routePrefix . '.index')
            ->with('success', ucfirst(str_replace('-', ' ', $this->routePrefix)) . ' updated successfully.');
    }

    public function destroy($id)
    {
        $record = $this->model::findOrFail($id);
        $record->delete();
        
        return redirect()->route($this->routePrefix . '.index')
            ->with('success', ucfirst(str_replace('-', ' ', $this->routePrefix)) . ' deleted successfully.');
    }

    protected function getValidationRules()
    {
        return [];
    }
}