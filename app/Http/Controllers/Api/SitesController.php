<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sites;
use Illuminate\Http\Request;
use Umbler\Umbler;

class SitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = Sites::get();

        return response()->json([
            'sites' => $sites
        ]);
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
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $site = Sites::where('id', $id)->firstOrFail();
        $site->preview = $request->preview;
        $site->project_id = $request->project;
        $site->save();

        return response()->json([
            'site' => $site
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function domain($domain)
    {
        $umblerApi = new Umbler;
        $umblerApi->setCredentials('61b911c37b3b5018ecb3d3df', '9f8fac50b5f04566a3fb314f6def8a29');

        $domain = $umblerApi->getDomainAvailable($domain);

        return response()->json($domain);
    }
}
