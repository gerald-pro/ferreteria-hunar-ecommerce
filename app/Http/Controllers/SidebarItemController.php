<?php

namespace App\Http\Controllers;

use App\Models\SidebarItem;
use App\Http\Requests\StoreSidebarItemRequest;
use App\Http\Requests\UpdateSidebarItemRequest;

class SidebarItemController extends Controller
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSidebarItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SidebarItem $sidebarItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SidebarItem $sidebarItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSidebarItemRequest $request, SidebarItem $sidebarItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SidebarItem $sidebarItem)
    {
        //
    }
}
