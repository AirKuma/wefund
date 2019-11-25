<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;

//use App\Http\Requests;
use App\Http\Controllers\Controller;

use Dingo\Api\Routing\Helpers;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Illuminate\Support\Collection;

use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Repositories\Contracts\NotificationRepositoryInterface;
use Repositories\Contracts\BillboardRepositoryInterface;
use Auth;
use App\User;
use App\College;
use App\Major;
use App\Http\Requests\Auth\ActivateFormRequest;
use App\Transformers\Auth\UserTransformers;
use Repositories\Contracts\UserRepositoryInterface;

class AuthController extends Controller
{
    use Helpers;

    protected $billboards;
    protected $notifications;
    protected $users;

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    
    public function __construct(NotificationRepositoryInterface $notifications,BillboardRepositoryInterface $billboards, UserRepositoryInterface $users)
    {
        $this->notifications = $notifications;
        $this->billboards = $billboards;
        $this->users = $users;
    }

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            //$activate_url = route('user.activate.code', ['code' => $user->activation_code]);
            $activate_url = 'https://www.loyaus.com/activate/'.$user->activation_code;

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
            //\Auth::login($user);
            return $this->response->array($user)->setStatusCode(200);

        }else
        {
            //\Session::flash('message', 'Your account couldn\'t be create please try again');
            //return redirect()->back()->withInput();
        }



    }

    public function activateAccount($code, User $user)
    {
        $userdata = User::where('activation_code', $code)->first();
        $tuserdata = User::where('activation_code', $code)->get();

       //$user = $user->with('users')->get();

       if($user->accountIsActiveAPI($code)) {
            //\Session::flash('message', 'Success, your account has been activated.');
            //return "Success, your account has been activated.";
            //return dd($userdata);

             $array = array(
                    'contents' => '您已成功認證Loyaus帳號，歡迎加入Loyaus！'."\n\n".

'Loyaus平台提供學生二手拍賣、競投與資訊交流服務，希望讓學生更快融入大學生活圈。'."\n\n".

'祝您有個美好的校園生活！

此信為系統自動發送，請勿回覆。'."\n".'
歡迎至https://www.facebook.com/loyaus Loyaus粉絲專頁關注最新消息，也歡迎訂閱Loyaus粉專機器人，我們將於每晚10點為您送上當日拍賣項目。'."\n".'
歡迎加入我們社團https://www.facebook.com/groups/180008392428214輔大二手拍平台 Loyaus，所有新發起的拍賣/競投項目將會自動發至這裡。',
                    'link' => route('get.post.index', ['billboard' => 'loyaus','category' => 'all']),
                );
            $email = array(
                    'email' => $userdata->email,
                    'name' => $userdata->lastname.''.$userdata->firstname,
                    'from' => 'loyaus@loyaus.com',
                    'subject' => 'Loyaus帳號認證成功！'
                );
            
            $sendmail = $this->notifications->sendEmail('emails.authenticated',$array,$email);


            return $this->response->collection($tuserdata, new UserTransformers())->setStatusCode(200);
        }else{
            //\Session::flash('message', 'Your account couldn\'t be activated, please try again');
            //return redirect('home');
            return $this->response->errorBadRequest();
        }
    }

    public function postActivateEmail(ActivateFormRequest $request)
    {
        $user = app('Dingo\Api\Auth\Auth')->user();
        if($user->email!= $request->email)
            return $this->response->errorBadRequest();
            //return redirect()->back()->withErrors('Email輸入錯誤');
        //$activate_url = route('user.activate.code', ['code' => $user->activation_code]);
        $activate_url = 'https://www.loyaus.com/activate/'.$user->activation_code;

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
        //\Session::flash('flash_message','成功發送帳號鏈結, 請檢查您的電郵信箱!'); //<--FLASH MESSAGE

        return "";
    }

    public function getIfEmailUnique($email)
    {
        $emailcount = $this->users->where('email',$email)->count();

        return $emailcount;
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
