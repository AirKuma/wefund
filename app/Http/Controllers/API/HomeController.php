<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Dingo\Api\Routing\Helpers;

class HomeController extends Controller
{
    use Helpers;

    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware('cors');
        $this->middleware('api.auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validateUser(){
        $user = app('Dingo\Api\Auth\Auth')->user();
        if(!$user){
            $responseArray = [
            'message' => 'Not authorized. Please Login again',
            'status' => false
            ];
            return $this->response->array($responseArray)->setStatusCode(403);
        }else{

            $responseArray = [
            'message' => 'User is authorized',
            'status' => true
            ];
            return $this->response->array($responseArray)->setStatusCode(200);
        }
    }

    public function index()
    {
        //
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
        //
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
}
