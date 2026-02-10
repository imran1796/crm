<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigurationStoreRequest;
use App\Http\Requests\ConfigurationUpdateRequest;
use App\Models\Configuration;
use App\Services\ConfigurationService;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    private ConfigurationService $configurationService;

    public function __construct(ConfigurationService $configurationService){
        $this->configurationService=$configurationService;

        $this->middleware('permission:configuration', ['only' => ['index','store']]);
        $this->middleware('permission:configuration', ['only' => ['create','store']]);
        $this->middleware('permission:configuration', ['only' => ['edit','update']]);
        $this->middleware('permission:configuration', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configurations = $this->configurationService->getAllConfigurations();
        $departments = $this->configurationService->getData('Department');
        return view('configurations.index',compact('configurations','departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConfigurationStoreRequest $request)
    {
        $configuration = $request->validated();
        return $this->configurationService->createConfiguration($configuration);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function update(ConfigurationUpdateRequest $request, $id)
    {
        $configuration = $request->validated();
        return $this->configurationService->updateConfiguration($configuration, $id);
    }

}
