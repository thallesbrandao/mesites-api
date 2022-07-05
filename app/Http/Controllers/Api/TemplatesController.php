<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Templates;
use App\Models\User;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
    public function index($token)
    {
        $templates = Templates::get();

        $user = User::where('token', $token)->with('site')->first();

        $user->site()->updateOrCreate([
            'ftp' => $user->site->ftp
        ]);

        foreach ($templates as $key => $template) {
            $templates[$key]->thumbnail = "//builder.meeventos.com.br/templates/{$template['id']}/thumbnail.png";
            $templates[$key]->edit = "//builder.meeventos.com.br/?token={$user->token}&template={$template['id']}";
        }

        if (isset($user->site->project_id) and $user->site->project_id) {
            $templates->prepend(['id' => 0, 'name' => 'Site ativo', 'thumbnail' => "//image.thum.io/get/auth/54404-imgindex/width/350/noanimate/viewportWidth/1650/https://builder.meeventos.com.br/preview/{$user->token}?preview={$user->site->preview}", 'edit' => "//builder.meeventos.com.br/?token={$user->token}&edit={$user->site->project_id}"]);
        } elseif (!empty($user->site->preview) and empty($user->site->ftp)) {
            $templates->prepend(['id' => 0, 'name' => 'Site ativo', 'thumbnail' => "//image.thum.io/get/auth/54404-imgindex/width/350/noanimate/viewportWidth/1650/https://builder.meeventos.com.br/preview/{$user->token}?preview={$user->site->preview}", 'edit' => "//builder.meeventos.com.br/?token={$user->token}&template=0"]);
        } elseif (!empty($user->site->preview) and !empty($user->site->ftp)) {
            $templates->prepend(['id' => 0, 'name' => 'Site ativo', 'thumbnail' => "//image.thum.io/get/auth/54404-imgindex/width/350/noanimate/viewportWidth/1650/{$user->site->http}{$user->site->domain}/?preview={$user->site->preview}", 'edit' => "//builder.meeventos.com.br/?token={$user->token}&template=0"]);
        } else {
            $templates->prepend(['id' => 0, 'name' => 'Site em branco', 'thumbnail' => url('/images/sem-layout.jpg'), 'edit' => "//builder.meeventos.com.br/?token={$user->token}&create=project"]);
        }

        return response()->json([
            'data' => $templates,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $token)
    {
        $user = User::where('token', $token)->with('site')->first();

        $user->site()->update([
            'config_logo' => $request->logo,
            'config_name' => $request->name,
            'config_email' => $request->email,
            'config_description' => $request->description,
        ]);

        return response()->json([
            'status' => true
        ]);
    }
}
