<?php

namespace App\Http\Controllers\ContentsManagement;

use App\Services\ContentsManagement\BoardConfigService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class BoardConfigController extends Controller
{
    protected BoardConfigService $boardConfigService;

    public function __construct()
    {
        $this->boardConfigService = new BoardConfigService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request): View
    {
        return $this->boardConfigService->index($request->all());
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
    public function store(Request $request)
    {
        return $this->boardConfigService->store($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BoardConfig  $boardConfig
     * @return \Illuminate\Http\Response
     */
    public function show(BoardConfig $boardConfig)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BoardConfig  $boardConfig
     * @return \Illuminate\Http\Response
     */
    public function edit(BoardConfig $boardConfig)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BoardConfig  $boardConfig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BoardConfig $boardConfig)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BoardConfig  $boardConfig
     * @return \Illuminate\Http\Response
     */
    public function destroy(BoardConfig $boardConfig)
    {
        //
    }
}
