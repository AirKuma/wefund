<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Auth;
use Socialite;
use App\College;
use App\Major;
use App\Facebook;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ActivateFormRequest;
use Repositories\Contracts\NotificationRepositoryInterface;
use Repositories\Contracts\BillboardRepositoryInterface;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    protected $redirectPath = '/home';
    protected $notifications;
    protected $billboards;

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(NotificationRepositoryInterface $notifications,BillboardRepositoryInterface $billboards)
    {
        //$this->middleware('auth', ['except' => ['getActivate', 'anotherMethod']]);
        $this->middleware('guest', ['except' => ['getLogout', 'getActivateAccount', 'activateAccount', 'postIntegrateFB', 'handleProviderCallback','findOrCreateUser', 'redirectToProvider','postActivateEmail']]);
        
        $this->notifications = $notifications;
        $this->billboards = $billboards;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = array(
            'email.regex'=>'E-mail必須符合學校格式',
            'email.required'=>'請輸入E-mail',
            'email.email'=>'E-mail必須符合E-mail格式',
            'email.max'=>'E-mail最多不能超過255個字',
            'email.unique'=>'該帳號已有人使用',
            'password.min'=>'密碼不能小於6個字',
            'password.required'=>'請輸入新密碼',
            'password.confirmed'=>'確認密碼錯誤',
            'firstname.required'=>'請輸入名字',
            'firstname.max'=>'名字至多10個字',
            'lastname.required'=>'請輸入姓氏',
            'lastname.max'=>'姓氏至多10個字',
            'major_id.required'=>'請選擇科系',
            'major_id.integer'=>'請選擇科系',
            'gender.required'=>'請選擇性別',
            'gender.integer'=>'只能選擇選擇男或女',
            'gender.max'=>'只能選擇選擇男或女',
            'gender.min'=>'只能選擇選擇男或女',
            'birthday.required'=>'請輸入生日',
            'birthday.date'=>'生日必須符合日期格式',
            'birthday.max'=>'生日至多255個字',
             );

        return Validator::make($data, [
            'firstname' => 'required|max:10',
            'lastname' => 'required|max:10',
            'email' => 'regex:/^[a-z0-9._%+-]+@mail.fju.edu.tw$/|required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'birthday' => 'required|max:255|date',
            'gender' => 'required|integer|min:0|max:1',
            'major_id' => 'required|integer',

        ],$messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'birthday' => $data['birthday'],
            'gender' => $data['gender'],
        ]);
    }


    protected function getIndex(){
        $majors = Major::where('id', '>', 1)->orderBy('id')->lists('name', 'id');
        //return dd($majors);
        return view('index', compact('majors'));
    }


    protected function getLogin(){
        return view('users.login');
    }

    protected function getRegister(){
        $majors = Major::where('id', '>', 1)->orderBy('id')->lists('name', 'id');
        //return dd($majors);
        return view('users.register', compact('majors'));
    }

    protected function postRegister(Request $request){
        $validator = $this->validator($request->all());


        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $activation_code = str_random(60) . $request->input('email');

        $email_domain = strstr($request->input('email'), '@');
        $college = College::where('email', $email_domain)->first();
        
        $user = new User;
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->birthday = $request->input('birthday');
        $user->gender = $request->input('gender');
        $user->college_id = $college->id;
        $user->major_id = $request->input('major_id');
        $user->activation_code = $activation_code; 



        if ($user->save()) {
            $data = array(
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'code' => $activation_code,
            );
            // Create a Personal Albums
            $user->albums()->create(['user_id' => $user->id, 'name' => 'Profile Pictures']);
            //訂閱討論版
            $collegebillboard_id = $this->billboards->where('domain',$user->college()->first()->acronym)->first()->id;
            $majorbillboard_id = $this->billboards->where('domain',$user->major()->first()->acronym)->first()->id;
            $billboards = $this->billboards->whereIn('id',[1,$collegebillboard_id,$majorbillboard_id])->get();
            foreach($billboards as $billboard) {
                $subscription = $billboard->subscriptions()->create(['user_id' => $user->id]);
            }

            // Send Active Code to User's Email
            $activate_url = route('user.activate.code', ['code' => $user->activation_code]);

            $array = array(
                    'contents' => $activate_url,
                );
            $email = array(
                    'email' => $user->email,
                    'name' => $user->lastname.''.$user->firstname,
                    'from' => 'loyaus@loyaus.com',
                    'subject' => 'Loyaus帳號認證'
                );
            
            $sendmail = $this->notifications->sendEmail('emails.activate',$array,$email);
            
            // Redirect to Active Code Page
            \Auth::login($user);
            return redirect('/home'); 

        }else
        {
            \Session::flash('message', 'Your account couldn\'t be create please try again');
            return redirect()->back()->withInput();
        }



    }

    public function activateAccount($code, User $user)
    {
        $userdata = User::where('activation_code', $code)->first();

       //$user = $user->with('users')->get();

       if($user->accountIsActive($code)) {
            //\Session::flash('message', 'Success, your account has been activated.');
            //return "Success, your account has been activated.";
            //return dd($userdata);

             $array = array(
                    'contents' => '您已成功認證Loyaus帳號，歡迎加入Loyaus！'."\n\n".

'Loyaus平台提供學生二手拍賣、競投與資訊交流服務，希望讓學生更快融入大學生活圈。'."\n\n".

'祝您有個美好的校園生活！',
                    'link' => route('get.post.index', ['billboard' => 'loyaus','category' => 'all']),
                );
            $email = array(
                    'email' => $userdata->email,
                    'name' => $userdata->lastname.''.$userdata->firstname,
                    'from' => 'loyaus@loyaus.com',
                    'subject' => 'Loyaus帳號認證成功！'
                );
            
            $sendmail = $this->notifications->sendEmail('emails.authenticated',$array,$email);

            return redirect('/');
        }else{
            //\Session::flash('message', 'Your account couldn\'t be activated, please try again');
            //return redirect('home');
            return "message', 'Your account couldn\'t be activated, please try again";
        }
    }


    public function getActivateAccount()
    {
        if(Auth::check()){
            if(Auth::user()->actived == 0){
                return view('users.activate');

            }
        }
        //return dd(Auth::user()->actived);
        return redirect('/home');
    }


    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    //http://goodheads.io/2015/08/24/using-facebook-authentication-for-login-in-laravel-5/
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
        } catch (Exception $e) {
            return redirect('auth/facebook');
        }
        //return dd($user);
        //return $user;
        $authUser = $this->findOrCreateUser($user);


        if(!$authUser && !Auth::check()){
            return redirect()->route('users.login')->withErrors('Facebook帳號登入只方便學生快速登入。同學註冊帳號之後，再進帳號管理平台，自行綁定Facebook帳號。');
        }
        //return dd($authUser);
        if(!Auth::check()){
            Auth::login($authUser, true);
        }
        

        return redirect('/home');
    }

    private function findOrCreateUser($facebookUser)
    {


        $facebook = Facebook::where('facebook_id', $facebookUser->id)->first();
        //return $facebook;
        if(!$facebook && Auth::check()){
            if(Auth::user()->facebook()->first()!=null)
                Facebook::destroy(Auth::user()->facebook()->first()->id);

            $facebook = $this->createFacebook($facebookUser);
            //return redirect()->back();
        }

        if($facebook && Auth::check()){
            if(Auth::user()->facebook()->first()!=null && $facebook->id!=Auth::user()->facebook()->first()->id)
                Facebook::destroy(Auth::user()->facebook()->first()->id);
            //$user = User::find(Auth::id());
            $facebook->user_id = Auth::id();
            //$user = Auth::user()->facebook();
            $facebook->save();
            //return dd($facebook);
            //return dd($user->facebook()->save($facebook));
        }
        if($facebook){
            return $facebook->user()->first();
        }
        
        return false;
        //$facebook = Auth::user()->facebook()->createFacebook($facebookUser);
        
        //$facebook->user($facebook)->save();

        


        // $db_user = User::where('facebook_id', $facebookUser->id)->first();
        // $facebook = Facebook::where('facebook_id', $facebookUser->id)->first();
        // if($facebook){
        //     $db_user = User::where('facebook_id', $facebookUser->id)->first();
        //         if($db_user){
        //             return $db_user;
        //         }else{
        //             return view('users.integrate', compact('facebook'));
        //         }
        // }else{

        // }

        // $user = $db_user->facebook();
        // return dd($user->facebook()->get());

        // if ($fb_user){
        //     return $fb_user;
        // }
        //return dd($facebookUser->id);


            //return dd($facebook);
            $college = College::find(1);
            $user = new User;
            $user->facebook_id = $fb_user->user['id'];
            $user->firstname = $fb_user->user['first_name'];
            $user->lastname = $fb_user->user['last_name'];
            $user->other_email = $fb_user->email;
            $user->avatar = $fb_user->avatar;
            if($fb_user->user['gender'] = 'male'){
                $user->gender = 1;
            }else{
                $user->gender = 0;
            }
            $user->college_id = 1;
            $user->major_id = 1;
            $user->save();
            //$college->users()->save($user);
            // Create a Personal Albums
            $user->albums()->create(['user_id' => $user->id, 'name' => 'Profile Pictures']);
            //$albums = $this->$albums->store(['user_id' =>  1, 'name' => 'Personal']);
            Auth::loginUsingId($user->id);
            //return redirect('club.index');   
            return dd($user->albums()->get()->toArray());    


        // return User::create([
        //     'name' => $facebookUser->name,
        //     'email' => $facebookUser->email,
        //     'facebook_id' => $facebookUser->id,
        //     'avatar' => $facebookUser->avatar
        // ]);
    }

    public function createFacebook($facebookUser)
    {

            $facebook = new Facebook;
            $facebook->user_id = Auth::id();
            $facebook->facebook_id = $facebookUser->id;
            $facebook->name = $facebookUser->name;
            if($facebookUser->email != null){
		$facebook->email = $facebookUser->email;
	    }
	    //$facebook->email = $facebookUser->email;
            $facebook->avatar = $facebookUser->avatar;
            $facebook->avatar_original = $facebookUser->avatar_original;
            $facebook->save();
            return $facebook;
    }
    public function postIntegrateFB(Request $request)
    {
        //return dd($request);
        if(\Hash::check($request->password, Auth::user()->password)){

        // // try {
        // //     $user = Socialite::driver('facebook')->user();
        // // } catch (Exception $e) {
        // //     return redirect('auth/facebook');
        // // }
        return redirect('auth/facebook');
        // return dd($this->handleProviderCallback());


        // $facebook = Facebook::where('facebook_id', $user->id)->first();
        // if(!$facebook){
        //     $facebook = $this->createFacebook($user);
        // }

        // $facebook = Auth::user()->facebook()->createFacebook($user);
        // return $facebook;
        // //$facebook->user($facebook)->save();

        // return redirect()->back();
            
        }else{
            return redirect()->back()->withErrors('密碼錯誤');
        }

        // return view('users.integrate',);
    }

    public function postActivateEmail(ActivateFormRequest $request,$id)
    {
        $user = User::findOrFail($id);
        if($user->email!= $request->email)
            return redirect()->back()->withErrors('Email輸入錯誤');
        $activate_url = route('user.activate.code', ['code' => $user->activation_code]);

        $array = array(
                'contents' => $activate_url,
            );
        $email = array(
                'email' => $request->email,
                'name' => $user->lastname.''.$user->firstname,
                'from' => 'loyaus@loyaus.com',
                'subject' => 'Loyaus帳號認證'
            );
        
        $sendmail = $this->notifications->sendEmail('emails.activate',$array,$email);
        \Session::flash('flash_message','成功發送帳號鏈結, 請檢查您的電郵信箱!'); //<--FLASH MESSAGE

        return redirect()->back();
    }

}

// if User V & Facebook V -> login
// if User X & Facebook V -> Create User and Bind facebook
// if User V & Facebook X -> Create Facebook and Bind User
// if User X & Facebook X -> Create Facebook or User


// Facebook Login -> V get Form -> User Created -> redirect ->facebook Login -> Created Facebook bind user
