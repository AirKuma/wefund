<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Credentials: true');

//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
//header('Access-Control-Allow-Headers: *');

Route::get('a', function () {
    return Auth::id();
});

/*Route::get('/', ['middleware' => 'auth', function () {
     return Redirect::to('fju/auction/bid/all');
}]);*/

/*Route::get('/home', function()
{
    return Redirect::to('fju/auction/bid/all');
});*///Re
// Route::get('/', ['as' => 'user.logout', 'uses' => 'Auth\AuthController@getIndex']); 
// Route::get('/', function () {
//     return view('index');
// });
// Route::get('/', ['as' => 'get.auction.index', 'uses' => 'Auction\ItemController@getIndex']);

//home
Route::get('/', ['as' => 'index', 'uses' => 'HomeController@getIndex']);
Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@getIndex']);
Route::post('messengerbot', ['as' => 'fbmessenger.post', 'uses' => 'Notification\FbmessengerController@postFBmessenger']);
//Route::get('messengerbot', ['as' => 'fbmessenger.get', 'uses' => 'FbmessengerController@getFBmessenger']);
//Auth
Route::controllers([
   'password' => 'Auth\PasswordController',
]);
Route::get('auth/login', ['as' => 'users.login', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('auth/login', ['as' => 'users.login', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('auth/logout', ['as' => 'user.logout', 'uses' => 'Auth\AuthController@getLogout']);
Route::get('auth/register',['as' => 'users.register', 'uses' => 'Auth\AuthController@getRegister']);
Route::post('auth/register',['as' => 'users.register', 'uses' => 'Auth\AuthController@postRegister']);
Route::get('activate', 'Auth\AuthController@getActivateAccount');
//Route::get('activate/{code}', 'Auth\AuthController@activateAccount');
Route::get('activate/{code}', ['as' => 'user.activate.code', 'uses' => 'Auth\AuthController@activateAccount']);
Route::post('activate/send/{id}', ['as' => 'activate.send', 'uses' => 'Auth\AuthController@postActivateEmail']);
//Auth Facebook
Route::post('auth/facebook/integrate', ['as' => 'post.facebook.integrate', 'uses' => 'Auth\AuthController@postIntegrateFB']);
Route::get('auth/facebook', 'Auth\AuthController@redirectToProvider');
Route::get('auth/facebook/callback', 'Auth\AuthController@handleProviderCallback');
//Profile
Route::get('profile/edit',['as' => 'get.edit.profile', 'uses' => 'Auth\ProfileController@getEditProfile']);
Route::patch('profile', ['as' => 'patch.update.profile', 'uses' => 'Auth\ProfileController@patchUpdateProfile']);
Route::get('account/edit',['as' => 'get.edit.account', 'uses' => 'Auth\ProfileController@getEditAccount']);
Route::patch('account', ['as' => 'patch.update.account', 'uses' => 'Auth\ProfileController@patchUpdateProfilePassword']);
Route::get('account/fb',['as' => 'get.edit.fb', 'uses' => 'Auth\ProfileController@getEditFB']);

//Items
Route::get('auction/item/show', function()
{
    return view('items.show');
});
Route::get('{college}/auction/{auction}/{type}', ['as' => 'get.auction.index', 'uses' => 'Auction\ItemController@getIndex']);
Route::get('auction/{auction}/create',['as' => 'get.auction.item', 'uses' => 'Auction\ItemController@getCreateItem']);
Route::post('auction/{auction}/create',['as' => 'post.auction.item', 'uses' => 'Auction\ItemController@postCreateItem']);
Route::get('auction/{auction}/item/{id}', ['as' => 'get.auction.item.show', 'uses' => 'Auction\ItemController@getShowItem']);
Route::get('auction/{auction}/admin', ['as' => 'get.auction.admin', 'uses' => 'Auction\ItemController@getAdmin']);
Route::get('auction/{auction}/edit/{id}',['as' => 'get.auction.item.edit', 'uses' => 'Auction\ItemController@getEditItem']);
Route::patch('auction/item/{id}',['as' => 'patch.auction.item.update', 'uses' => 'Auction\ItemController@patchUpdateItem']);
Route::post('auction/item/image/upload/{id}',['as' => 'post.auction.item.image.upload', 'uses' => 'Auction\ItemController@postUploadItemImage']);
Route::delete('auction/item/{id}',['as' => 'delete.auction.item.destroy', 'uses' => 'Auction\ItemController@destroyItem']);
Route::delete('auction/item/image/delete/{id}',['as' => 'delete.auction.item.image.destroy', 'uses' => 'Auction\ItemController@destroyImage']);
Route::post('auction/item/bid/{id}',['as' => 'post.auction.item.bid', 'uses' => 'Auction\ItemController@postBidItem']);
Route::post('auction/item/{id}/comment',['as' => 'post.auction.item.comment', 'uses' => 'Auction\ItemController@postComment']);
Route::post('auction/item/{id}/report',['as' => 'post.auction.item.report', 'uses' => 'Auction\ItemController@postReport']);
Route::post('auction/item/{id}/repost',['as' => 'post.auction.item.repost', 'uses' => 'Auction\ItemController@postRepost']);
Route::post('auction/item/free/{id}',['as' => 'post.auction.item.free', 'uses' => 'Auction\ItemController@postFreeItem']);

//discuss
//billboard
Route::get('discuss/billboard', ['as' => 'get.billboard.index', 'uses' => 'Discuss\BillboardController@getIndex']);
Route::get('discuss/billboard/create',['as' => 'get.discuss.billboard', 'uses' => 'Discuss\BillboardController@getCreateBillboard']);
Route::post('discuss/billboard/create',['as' => 'post.discuss.billboard', 'uses' => 'Discuss\BillboardController@postCreateBillboard']);
Route::get('discuss/billboard/edit/{id}',['as' => 'get.discuss.billboard.edit', 'uses' => 'Discuss\BillboardController@getEditBillboard']);
Route::patch('discuss/billboard/{id}',['as' => 'patch.discuss.billboard.update', 'uses' => 'Discuss\BillboardController@patchUpdateBillboard']);
Route::delete('discuss/billboard/{id}',['as' => 'delete.discuss.billboard.destroy', 'uses' => 'Discuss\BillboardController@destroyBillboard']);
Route::post('billboard/block/{id}',['as' => 'get.billboard.block', 'uses' => 'Discuss\BillboardController@postBlock']);
Route::post('billboard/subscription/{id}',['as' => 'post.billboard.subscription', 'uses' => 'Discuss\BillboardController@postSubscription']);
//admin
Route::get('billboard/admin', ['as' => 'get.billboard.admin', 'uses' => 'Discuss\BillboardController@getAdmin']);
Route::get('billboard/subscriber/{id}', ['as' => 'get.billboard.subscriber', 'uses' => 'Discuss\BillboardController@getSubscriber']);
Route::post('subscriber/setadmin/{id}', ['as' => 'post.subscriber.setadmin', 'uses' => 'Discuss\BillboardController@postSetadmin']);
Route::delete('subscriber/delete/{id}',['as' => 'delete.subscriber.destroy', 'uses' => 'Discuss\BillboardController@destroySubscriber']);
Route::get('billboard/applysubscriber/{id}', ['as' => 'get.billboard.applysubscriber', 'uses' => 'Discuss\BillboardController@getApplySubscriber']);
Route::post('subscriber/respond/{id}', ['as' => 'post.subscriber.respond', 'uses' => 'Discuss\BillboardController@postRespond']);
//category
Route::get('billboard/category/{id}',['as' => 'get.billboard.category', 'uses' => 'Discuss\BillboardCategoryController@getCategory']);
Route::post('billboard/category/create/{id}',['as' => 'post.billboard.category', 'uses' => 'Discuss\BillboardCategoryController@postCreateCategory']);
/*Route::get('billboard/category/edit/{id}',['as' => 'get.billboard.category.edit', 'uses' => 'Discuss\BillboardController@getEditCategory']);*/
Route::patch('billboard/category/{id}',['as' => 'patch.billboard.category.update', 'uses' => 'Discuss\BillboardCategoryController@patchUpdateCategory']);
Route::delete('billboard/category/{id}',['as' => 'delete.billboard.category.destroy', 'uses' => 'Discuss\BillboardCategoryController@destroyCategory']);

//post
Route::get('discuss/post/{billboard}/{category}', ['as' => 'get.post.index', 'uses' => 'Discuss\PostController@getIndex']);
Route::get('discuss/post/create/{type}/{domain}',['as' => 'get.discuss.post', 'uses' => 'Discuss\PostController@getCreatePost']);
Route::post('discuss/post/create',['as' => 'post.discuss.post', 'uses' => 'Discuss\PostController@postCreatePost']);
Route::get('discuss/post/show/{billboard}/{id}', ['as' => 'get.discuss.post.show', 'uses' => 'Discuss\PostController@getShowPost']);
Route::get('discuss/post/edit/{id}/{domain}',['as' => 'get.discuss.post.edit', 'uses' => 'Discuss\PostController@getEditPost']);
Route::patch('discuss/post/{id}',['as' => 'patch.discuss.post.update', 'uses' => 'Discuss\PostController@patchUpdatePost']);
Route::delete('discuss/post/{id}',['as' => 'delete.discuss.post.destroy', 'uses' => 'Discuss\PostController@destroyPost']);
Route::post('discuss/post/{id}/comment',['as' => 'post.discuss.post.comment', 'uses' => 'Discuss\PostController@postComment']);
Route::get('post/vote/{votetype}/{id}',['as' => 'get.post.vote', 'uses' => 'Discuss\PostController@getVote']);
Route::get('post/bookmark/{id}',['as' => 'get.post.bookmark', 'uses' => 'Discuss\PostController@getBookmark']);
Route::post('discuss/post/{id}/report',['as' => 'post.discuss.post.report', 'uses' => 'Discuss\PostController@postReport']);
Route::post('post/block/{id}',['as' => 'get.post.block', 'uses' => 'Discuss\PostController@postBlock']);
Route::get('post/mypost/{type}', ['as' => 'get.post.mypost', 'uses' => 'Discuss\PostController@getMyPost']);
Route::patch('post/setpriority/{id}', ['as' => 'patch.post.setpriority', 'uses' => 'Discuss\PostController@patchSetpriority']);

//comment
Route::get('comment/vote/{votetype}/{id}',['as' => 'get.comment.vote', 'uses' => 'Comment\CommentController@getVote']);
Route::patch('comment/{id}',['as' => 'patch.comment.update', 'uses' => 'Comment\CommentController@patchUpdateComment']);
Route::delete('comment/{id}',['as' => 'delete.comment.destroy', 'uses' => 'Comment\CommentController@destroyComment']);
Route::post('comment/report/{id}',['as' => 'post.comment.report', 'uses' => 'Comment\CommentController@postReport']);
Route::post('comment/block/{id}',['as' => 'get.comment.block', 'uses' => 'Comment\CommentController@postBlock']);

//notification
Route::get('notifications', ['as' => 'get.notification.index', 'uses' => 'Notification\NotificationController@index']);


//vue
//Route::get('/api/bookmark/{id}',['as' => 'get.api.bookmark', 'uses' => 'Discuss\PostController@getBookmark']);
//Route::get('/api/vote/{votetype}/{id}',['as' => 'get.api.vote', 'uses' => 'Discuss\PostController@getVote']);
Route::post('/api/comment/{id}',['as' => 'post.api.post.comment', 'uses' => 'Discuss\PostController@postComment']);
//Route::patch('/api/edit/comment/{id}',['as' => 'api.comment.update', 'uses' => 'Comment\CommentController@patchUpdateComment']);
//Route::get('/api/post/create/{type}/{domain}',['as' => 'api.discuss.post', 'uses' => 'Discuss\PostController@getCreatePost']);
//Route::post('/api/bid/{id}',['as' => 'api.auction.item.bid', 'uses' => 'Auction\ItemController@postBidItem']);
Route::post('/api/free/{id}',['as' => 'api.auction.item.free', 'uses' => 'Auction\ItemController@postFreeItem']);
//Route::post('/api/subscription/{id}',['as' => 'api.billboard.subscription', 'uses' => 'Discuss\BillboardController@postSubscription']);
Route::post('/api/itemcomment/{id}',['as' => 'api.auction.item.comment', 'uses' => 'Auction\ItemController@postComment']);
//Route::get('/api/commentvote/{votetype}/{id}',['as' => 'api.comment.vote', 'uses' => 'Comment\CommentController@getVote']);



//test
//Route::get('api/auction/admin', ['as' => 'get.api.auction.admin', 'uses' => 'Auction\ItemController@getAdminAPI']);
//Message
Route::get('messages/{id}', ['as' => 'get.messages.inbox', 'uses' => 'Message\MessageController@getMessageInbox']);
Route::post('messages/send',['as' => 'post.messages.send', 'uses' => 'Message\MessageController@postSendMessage']);

Route::get('tests', ['as' => 'get.tests.index', 'uses' => 'HomeController@getItemPageScroll']);


// Route::get('test', function()
// {
//     return view('vue');
// });

// Route::get('/api/post/{billboard}/{category}', ['as' => 'get.post.api', 'uses' => 'Discuss\PostController@getIndex']);
// get('/api/users', function(){
// 	return App\User::all();
// });

// post('/api/users', function(){
// 	return App\User::create(Request::all());
// });

// get('/api/users/{id}', function($id){
// 	return App\User::findOrFail($id);
// });

// patch('/api/users/{id}', function($id){
// 	App\User::findOrFail($id)->update(Request::all());
// 	return Response::json(Request::all()); //response()->json()
// });

// delete('/api/users/{id}', function($id){
// 	return App\User::destroy($id);
// });



//Auth
// Route::get('auth/login', ['as' => 'user.login', 'uses' => 'Auth\AuthController@getLogin']);
// Route::post('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);
// Route::get('auth/register',['as' => 'user.register', 'uses' => 'Auth\AuthController@getRegister']);
// Route::post('auth/register',['as' => 'user.register', 'uses' => 'Auth\AuthController@postRegister']);
// Route::get('activate/{code}', 'Auth\AuthController@activateAccount');
// Route::get('auth/logout', ['as' => 'user.logout', 'uses' => 'Auth\AuthController@getLogout']);
// Route::get('auth/facebook', 'Auth\AuthController@redirectToProvider');
// Route::get('auth/facebook/callback', 'Auth\AuthController@handleProviderCallback');

// Route::get('auth',['as' => 'get.edit.account', 'uses' => 'Auth\ProfileController@getProfile']);
// //Profile
// Route::get('profile/{id}/edit',['as' => 'get.edit.profile', 'uses' => 'Auth\ProfileController@getEditProfile']);
// Route::patch('profile/{id}', ['as' => 'patch.update.profile', 'uses' => 'Auth\ProfileController@patchUpdateProfile']);
// Route::get('account/{id}/edit',['as' => 'get.edit.account', 'uses' => 'Auth\ProfileController@getEditAccount']);
// $api = app('Dingo\Api\Routing\Router');

// $api->version('v1',function($api){
// 	$api->get('hello', function(){
// 		 return "HELLO";
// 	});

// 	$api->post('oauth/access_token', function(){
// 		return Authorizer::issueAccessToken();
// 	});

// 	$api->get('authuser', 'App\Http\Controllers\Api\HomeController@validateUser');
// });

//API


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'cors'], function($api){
	$api->get('hello', function(){
		 return "HELLO";
	});

	$api->post('oauth/access_token', function(){
		return Authorizer::issueAccessToken();
	});

	$api->get('authuser', 'App\Http\Controllers\API\HomeController@validateUser');

	//auction
	$api->get('homeitems', 'App\Http\Controllers\API\Auction\ItemController@getHomeItem');
	$api->get('items', 'App\Http\Controllers\API\Auction\ItemController@getIndex');
	$api->post('items', 'App\Http\Controllers\API\Auction\ItemController@postCreateItem');
	$api->post('webitems', 'App\Http\Controllers\API\Auction\ItemController@postCreateItemWeb');
	//$api->get('auction/{limit}/{offset}', 'App\Http\Controllers\API\Auction\ItemController@index');
	//$api->get('items/{auction}/{limit?}/{offset?}', 'App\Http\Controllers\API\Auction\ItemController@getIndex');
	//$api->get('auction/seek/{limit?}/{offset?}', 'App\Http\Controllers\API\Auction\ItemController@getSeekIndex');
	//$api->get('items/{auction}/{id}/refresh', 'App\Http\Controllers\API\Auction\ItemController@Refresh');
	//$api->get('auctions/seek/{id}/refresh', 'App\Http\Controllers\API\Auction\ItemController@seekRefresh');
	$api->get('items/{id}', 'App\Http\Controllers\API\Auction\ItemController@show');
	$api->put('items/{id}', 'App\Http\Controllers\API\Auction\ItemController@putUpdateItem');
	$api->delete('items/{id}','App\Http\Controllers\API\Auction\ItemController@destroyItem');
	$api->get('items/{id}/comment', 'App\Http\Controllers\API\Auction\ItemController@getComment');
	$api->post('items/{id}/comment', 'App\Http\Controllers\API\Auction\ItemController@postComment');

	//category
	$api->get('items/{type}/categories', 'App\Http\Controllers\API\Auction\ItemController@getCategoey');
	$api->get('items/category/count', 'App\Http\Controllers\API\Auction\ItemController@getItemCategoryCount');
	//bid
	$api->get('items/{id}/bid', 'App\Http\Controllers\API\Auction\ItemController@getItemBid');
	$api->post('items/{id}/bid', 'App\Http\Controllers\API\Auction\ItemController@postBid');
	$api->post('items/{id}/free', 'App\Http\Controllers\API\Auction\ItemController@postFree');
	//report
	$api->post('items/{id}/report', 'App\Http\Controllers\API\Auction\ItemController@postReport');
	//repost
	$api->post('items/{id}/repost', 'App\Http\Controllers\API\Auction\ItemController@postRepost');
	//favor
	$api->post('items/{id}/favor', 'App\Http\Controllers\API\Auction\ItemController@postFavor');
	$api->get('items/{id}/iffavor', 'App\Http\Controllers\API\Auction\ItemController@getIfFavor');
	//image
	$api->get('items/{id}/image', 'App\Http\Controllers\API\Auction\ItemController@getImage');
	$api->post('items/uploadimage','App\Http\Controllers\API\Auction\ItemController@postUploadimage');
	$api->delete('items/image/{id}','App\Http\Controllers\API\Auction\ItemController@destroyImage');
	$api->post('items/{id}/image','App\Http\Controllers\API\Auction\ItemController@postUploadItemImageAPP');

	//user
	$api->get('me/items', 'App\Http\Controllers\API\Auction\ItemController@getMyItem');
	$api->get('me/bids', 'App\Http\Controllers\API\Auction\ItemController@getMyBidItem');
	$api->get('me/adminItems', 'App\Http\Controllers\API\Auction\ItemController@getItemAdmin');
	$api->get('me/adminItemscount', 'App\Http\Controllers\API\Auction\ItemController@getItemAdminCount');
	
	//comment
	$api->post('comments/{id}/report', 'App\Http\Controllers\API\Comment\CommentController@postReport');
	$api->delete('comments/{id}', 'App\Http\Controllers\API\Comment\CommentController@destroyComment');
	$api->put('comments/{id}', 'App\Http\Controllers\API\Comment\CommentController@putUpdateComment');

	//auth
	$api->post('users', 'App\Http\Controllers\API\Auth\AuthController@postRegister');
	$api->get('me', 'App\Http\Controllers\API\Auth\ProfileController@getUser');
	$api->put('me', 'App\Http\Controllers\API\Auth\ProfileController@patchUpdateProfile');
	$api->put('me/password', 'App\Http\Controllers\API\Auth\ProfileController@patchUpdateProfilePassword');
	$api->get('me/password', 'App\Http\Controllers\API\Auth\ProfileController@getCurrentPassword');
	$api->get('majors', 'App\Http\Controllers\API\Auth\ProfileController@getMajor');
	$api->get('users/{id}','App\Http\Controllers\API\Auth\ProfileController@getUserContact');
	//activate
	$api->get('activate/{code}', 'App\Http\Controllers\API\Auth\AuthController@activateAccount');
	$api->post('activate/send', 'App\Http\Controllers\API\Auth\AuthController@postActivateEmail');
	//if unique
	$api->get('users/{email}/ifemailunique', 'App\Http\Controllers\API\Auth\AuthController@getIfEmailUnique');
	$api->get('me/{username}/ifusernameunique', 'App\Http\Controllers\API\Auth\ProfileController@getIfUsernameUnique');

	//notification
	$api->get('notifications', 'App\Http\Controllers\API\Notification\NotificationController@getNotification');
	$api->get('notifications/me/count', 'App\Http\Controllers\API\Notification\NotificationController@getUserNotificationCount');
	$api->post('notifications/me/count', 'App\Http\Controllers\API\Notification\NotificationController@postUserNotificationCount');

	//$api->post('testupload', 'App\Http\Controllers\HomeController@postUpload');
	//image
	//$api->post('uploadimage', 'App\Http\Controllers\API\Image\ImageController@postUpload');
	$api->delete('uploadimage/{id}', 'App\Http\Controllers\API\Image\ImageController@destroyUpload');

	//Message
	$api->get('threads', 'App\Http\Controllers\API\Message\MessageController@getThreads');
	//$api->get('threads/{id}', 'App\Http\Controllers\API\Message\MessageController@getThread');
	$api->get('messages/{id}', 'App\Http\Controllers\API\Message\MessageController@getMessageInbox');
	$api->post('messages','App\Http\Controllers\API\Message\MessageController@postSendMessage');
	$api->get('ifthread/{id}', 'App\Http\Controllers\API\Message\MessageController@getIfThread');
	$api->post('threads/{id}', 'App\Http\Controllers\API\Message\MessageController@postCreateThread');
	$api->post('threads/{id}/read', 'App\Http\Controllers\API\Message\MessageController@postThreadRead');
	$api->get('messages/me/count', 'App\Http\Controllers\API\Message\MessageController@getUserMessageCount');
	$api->post('messages/me/count', 'App\Http\Controllers\API\Message\MessageController@postUserMessageCount');

	//forget password
	//$api->get('csrftokens', 'App\Http\Controllers\API\Auth\PasswordController@getCsrf_token');
	$api->post('sendemail', 'App\Http\Controllers\API\Auth\PasswordController@postEmail');
	$api->post('resetpassword', 'App\Http\Controllers\API\Auth\PasswordController@postReset');
	//$api->get('password/email', 'App\Http\Controllers\API\Auth\PasswordController@getEmail');

});

