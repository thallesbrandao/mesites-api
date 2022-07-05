<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Umbler\Umbler;

class EmailController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::where('token', $request->token)->with('site')->first();

        if (isset($user->site->domain)) {
            $umblerApi = new Umbler;
            $umblerApi->setCredentials('61b911c37b3b5018ecb3d3df', '9f8fac50b5f04566a3fb314f6def8a29');
            $umblerApi->setDomain($user->site->domain);

            $emails = $umblerApi->getEmailAccounts();
        } else {
            $emails = [];
        }

        return response()->json([
            'status' => $user->site->emails,
            'emails' => $emails
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function plan(Request $request)
    {
        $user = User::where('token', $request->token)->with('site')->first();

        $umblerApi = new Umbler;
        $umblerApi->setCredentials('61b911c37b3b5018ecb3d3df', '9f8fac50b5f04566a3fb314f6def8a29');
        $umblerApi->setDomain($user->site->domain);

        $plans = $umblerApi->getAvailableEmailPlans();

        return response()->json(['plans' => $plans]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::where('token', $request->token)->with('site')->first();

        $umblerApi = new Umbler;
        $umblerApi->setCredentials('61b911c37b3b5018ecb3d3df', '9f8fac50b5f04566a3fb314f6def8a29');
        $umblerApi->setDomain($user->site->domain);

        $email = $umblerApi->createEmailAccount([
            "emailAccount" => $request->email,
            "fullName" => $request->name,
            "password" => $request->password,
            "emailType" => $request->type
        ]);

        return response()->json($this->statusResponse($email));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = User::where('token', $request->token)->with('site')->first();

        $umblerApi = new Umbler;
        $umblerApi->setCredentials('61b911c37b3b5018ecb3d3df', '9f8fac50b5f04566a3fb314f6def8a29');
        $umblerApi->setDomain($user->site->domain);

        $email = $umblerApi->getEmailAccount($request->email);

        return response()->json($email);
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
    public function update(Request $request, $email)
    {
        $user = User::where('token', $request->token)->with('site')->first();

        $umblerApi = new Umbler;
        $umblerApi->setCredentials('61b911c37b3b5018ecb3d3df', '9f8fac50b5f04566a3fb314f6def8a29');
        $umblerApi->setDomain($user->site->domain);

        $email = $umblerApi->updateEmailAccount($email, [
            "id" => $request->id,
            "fullName" => $request->name,
            "password" => $request->password,
            "emailType" => $request->type
        ]);

        return response()->json($this->statusResponse($email));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::where('token', $request->token)->with('site')->first();

        $umblerApi = new Umbler;
        $umblerApi->setCredentials('61b911c37b3b5018ecb3d3df', '9f8fac50b5f04566a3fb314f6def8a29');
        $umblerApi->setDomain($user->site->domain);

        $response = $umblerApi->deleteEmailAccount($request->email);

        return response()->json(['email' => $response]);
    }

    public function statusResponse($array)
    {
        if (isset($array['EmailAccount'])) {
            return ['status' => false, 'msg' => 'Email inválido, ajuste e tente novamente!'];
        } elseif (isset($array['errors']) and isset($array['errors']['Password']) and $array['errors']['Password'][0] == 'EmailAccountPasswordLength' or isset($array['Password'])) {
            return ['status' => false, 'msg' => 'Senha muito pequena, ajuste e tente novamente!'];
        } elseif (isset($array['errors']) and isset($array['errors']['FullName']) and $array['errors']['FullName'][0] == 'EmailAccountFullNameLength' or isset($array['FullName'])) {
            return ['status' => false, 'msg' => 'Nome muito pequeno, ajuste e tente novamente!'];
        } elseif (isset($array['errors']) and isset($array['errors']['Password']) and $array['errors']['Password'][0] == 'EmailAccountPasswordLetter') {
            return ['status' => false, 'msg' => 'Senha deve ter ao menos 1 letra, ajuste e tente novamente!'];
        } elseif (isset($array['errors']) and isset($array['errors']['Password']) and $array['errors']['Password'][0] == 'EmailAccountPasswordSpecialChars') {
            return ['status' => false, 'msg' => 'Senha deve ter ao menos 1 caracter especial, ajuste e tente novamente!'];
        } elseif (isset($array['errors']) and isset($array['errors']['FullName']) and $array['errors']['FullName'][0] == 'EmailAccountFullNameAlreadyInUse') {
            return ['status' => false, 'msg' => 'O nome já está em uso, ajuste e tente novamente!'];
        } elseif (isset($array['errors']) and isset($array['errors']['AccountName']) and $array['errors']['AccountName'][0] == 'EmailAccountNameAlreadExists') {
            return ['status' => false, 'msg' => 'O email já está em uso, ajuste e tente novamente!'];
        } elseif (isset($array['id'])) {
            return ['status' => true, 'msg' => 'Sucesso!'];
        } else {
            return ['status' => false, 'msg' => 'Erro desconhecido'];
        }
    }
}
