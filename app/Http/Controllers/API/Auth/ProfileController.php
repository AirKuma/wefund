<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Illuminate\Support\Collection;
use App\Transformers\Auth\MajorTransformers;
use App\Major;
use App\Transformers\Auth\UserTransformers;
use Repositories\Contracts\ProfileRepositoryInterface;

use App\Http\Requests\Auth\API\ProfileRequest;
use App\Http\Requests\Auth\API\PasswordRequest;
use Validator;

class ProfileController extends Controller
{
    use Helpers;

    protected $profiles;

    public function __construct(ProfileRepositoryInterface $profiles)
    {
        $this->middleware('apiauth', ['except' => ['getUser','getUserContact','getMajor','getIfUsernameUnique','getCurrentPassword']]);
        $this->middleware('cors');
	$this->profiles = $profiles;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getUser()
    {
        $user_id = app('Dingo\Api\Auth\Auth')->user()->id;
        $user = \App\User::where('id', '=', $user_id)->get();

        //return $this->response->array($user)->setStatusCode(200);
        return $this->response->collection($user, new UserTransformers())->setStatusCode(200);
    }

    public function getUserContact($id)
    {
        $user = \App\User::where('id', '=', $id)->get();
        if($user=='[]'){;
            return $this->response->errorBadRequest();
        }

        //return $this->response->array($user)->setStatusCode(200);
        return $this->response->collection($user, new UserTransformers())->setStatusCode(200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMajor()
    {
        $majors = Major::all();

        return $this->response->collection($majors, new MajorTransformers())->setStatusCode(200);
    }

    public function patchUpdateProfile(ProfileRequest $request)
    {

        $user = app('Dingo\Api\Auth\Auth')->user();

        if($user->username!=null)
            $profile = $this->profiles->update($request->except('username'), $user->id);
        else{
            $validator = Validator::make($request->all(), [
                'username' => 'unique:users|regex:/^[a-zA-Z0-9\s-]+$/|max:20',
            ]);

            if ($validator->fails()) 
                return $this->response->errorBadRequest();
            
            $profile = $this->profiles->update($request->all(), $user->id);
        }

        return $this->response->array($profile)->setStatusCode(200);
    }

    public function patchUpdateProfilePassword(PasswordRequest $request)
    {
        $user = app('Dingo\Api\Auth\Auth')->user();

        if(\Hash::check($request->old_password, $user->password)){
            $request->replace(['password' => bcrypt($request->password)]);
            $profile = $this->profiles->update($request->except('password_confirmation','old_password'), $user->id);
            //Auth::logout();
            return $this->response->array($profile)->setStatusCode(200);
        }
        //return redirect()->back()->withErrors(['現在密碼錯誤']);
    }

    public function getCurrentPassword(Request $request)
    {
        $user = app('Dingo\Api\Auth\Auth')->user();
        
        if(\Hash::check($request->old_password, $user->password))
            return 1;
        else
            return 0;
    }

    public function getIfUsernameUnique($username)
    {
        $usernamecount = $this->profiles->where('username',$username)->count();

        return $usernamecount;
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
