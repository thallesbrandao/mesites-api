<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Projects;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::where('token', $request->token)->first();

        return response()->json([
            'data' => $user->projects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::where('token', $request->token)->first();

        $project = Projects::create([
            'user_id' => $user->id,
            'name' =>  $request->name,
            'config_logo' => $request->logo,
            'config_name' => $request->title,
            'config_email' => $request->email,
            'config_description' => $request->description,
        ]);

        return response()->json([
            'url' => 'https://builder.meeventos.com.br/?token=' . $user->token . '&edit=' . $project->id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $project = Projects::where('id', $request->project)->firstOrFail();
        $project->name = $request->name;
        $project->save();

        return response()->json([
            'project' => $project
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::where('token', $request->token)->first();

        $project = Projects::where('id', $request->project)->where('user_id', $user->id)->firstOrFail();

        $project->delete();

        return response()->json(['project' => $project]);
    }
}
