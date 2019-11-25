<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Major;
// use Illuminate\Http\Request;

use App\Http\Requests\Auth\ProfileRequest;
// use App\Http\Controllers\Controller;
use Repositories\Contracts\ProfileRepositoryInterface;
use Repositories\Contracts\ImageRepositoryInterface;


class ProfileController extends Controller
{
    protected $profiles;
    protected $images;

    public function __construct(ProfileRepositoryInterface $profiles, ImageRepositoryInterface $images)
    {
        $this->middleware('auth', ['except' => 'getShowClub']);
        $this->profiles = $profiles;
        $this->images = $images;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEditProfile()
    {
        $profile = $this->profiles->find(Auth::id());
        //return $major = $profile->major()->get();
        $majors = Major::all()->lists('name', 'id');
        //return dd($majors);
        return View('users.profile', compact('profile'), compact('majors'));
        //return dd($profile);
    }


    public function patchUpdateProfile(ProfileRequest $request){

        //
        //$request->replace(['password' => bcrypt($request->password)]);
        //$request->password = bcrypt($request->password);
        //$request->input('password') = bcrypt($request->input('password'));
        if(Auth::user()->username!=null)
            $profile = $this->profiles->update($request->except('user_name','_method', '_token', 'password_confirmation','colleage','gender','username'), Auth::id());
        else
            $profile = $this->profiles->update($request->except('user_name','_method', '_token', 'password_confirmation','colleage','gender'), Auth::id());
        
        return redirect()->back();
        //return dd($request->is('profile'));
        //return dd($request->password);
    }

    public function patchUpdateProfilePassword(ProfileRequest $request){

        if(\Hash::check($request->old_password, Auth::user()->password)){
            $request->replace(['password' => bcrypt($request->password)]);
            $profile = $this->profiles->update($request->except('_method', '_token', 'password_confirmation'), Auth::id());
            Auth::logout();
        }

        return redirect()->back()->withErrors(['現在密碼錯誤']);

    }


    public function getEditAccount()
    {
        $profile = $this->profiles->find(Auth::id());
        //return $major = $profile->major()->get();

        return View('users.account', compact('profile'));
        //return dd($profile);
    }

    public function getEditFB()
    {
        $profile = $this->profiles->find(Auth::id());
        //return $major = $profile->major()->get();

        return View('users.fb', compact('profile'));
        //return dd($profile);
    }

}
