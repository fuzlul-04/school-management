<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('module')->orderBy('name')->paginate(15);
        return view('backend.permissions.index', compact('permissions'));
    }

    public function create()
    {
        $modules = Permission::distinct()->pluck('module')->filter()->values();
        return view('backend.permissions.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:permissions,name',
            'module' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Permission::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'module' => $request->module,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function show(Permission $permission)
    {
        $permission->load('roles', 'users');
        return view('backend.permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        $modules = Permission::distinct()->pluck('module')->filter()->values();
        return view('backend.permissions.edit', compact('permission', 'modules'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:permissions,name,' . $permission->id,
            'module' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $permission->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'module' => $request->module,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count() > 0 || $permission->users()->count() > 0) {
            return redirect()->route('permissions.index')->with('error', 'Cannot delete permission assigned to roles or users.');
        }

        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}