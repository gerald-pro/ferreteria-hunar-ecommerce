<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Page;
use App\Models\SidebarGroup;
use App\Models\SidebarItem;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdministrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function statistics()
    {
        return view('statistics');
    }

    /**
     * Display the view.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function userIndex(Request $request)
    {
        if ($request->user) {
            $users = User::where('id', $request->user)->orderBy('name')->get();
        } else {
            $users = User::orderBy('name')->get();
        }

        $roles = Role::all();

        return view('users', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    /**
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function pages(Request $request)
    {
        if (Page::count()) {
            $pages = Page::all();
        }

        return view('administration.pages', [
            'pages' => $pages ?? false,
        ]);
    }

    /**
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function sidebar()
    {
        if (SidebarGroup::count()) {
            $sidebarGroups = SidebarGroup::all()->sort();
        }

        if (SidebarItem::count()) {
            $sidebarItems = SidebarItem::all()->sort();
        }

        $permissions = Permission::all();

        return view('administration.sidebar', [
            'sidebarGroups' => $sidebarGroups ?? false,
            'sidebarItems' => $sidebarItems ?? false,
            'permissions' => $permissions,
        ]);
    }
}
