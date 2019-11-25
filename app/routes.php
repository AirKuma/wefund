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
Route::get('a', function () {
    return Auth::id();
});
Route::get('/', ['middleware' => 'auth', function () {
     return Redirect::to('fju/auction/bid/all');
}]);

Route::get('/home', function()
{
    return Redirect::to('fju/auction/bid/all');
});//Re
// Route::get('/', ['as' => 'user.logout', 'uses' => 'Auth\AuthController@getIndex']); 
// Route::get('/', function () {
//     return view('index');
// });
// Route::get('/', ['as' => 'get.auction.index', 'uses' => 'Auction\ItemController@getIndex']);

//Auth
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

//discuss
//billboard
Route::get('discuss/billboard', ['as' => 'get.billboard.index', 'uses' => 'Discuss\BillboardController@getIndex']);
Route::get('discuss/billboard/create',['as' => 'get.discuss.billboard', 'uses' => 'Discuss\BillboardController@getCreateBillboard']);
Route::post('discuss/billboard/create',['as' => 'post.discuss.billboard', 'uses' => 'Discuss\BillboardController@postCreateBillboard']);
Route::get('discuss/billboard/edit/{id}',['as' => 'get.discuss.billboard.edit', 'uses' => 'Discuss\BillboardController@getEditBillboard']);
Route::patch('discuss/billboard/{id}',['as' => 'patch.discuss.billboard.update', 'uses' => 'Discuss\BillboardController@patchUpdateBillboard']);
Route::delete('discuss/billboard/{id}',['as' => 'delete.discuss.billboard.destroy', 'uses' => 'Discuss\BillboardController@destroyBillboard']);
Route::post('billboard/block/{id}',['as' => 'get.billboard.block', 'uses' => 'Discuss\BillboardController@postBlock']);

//post
Route::get('discuss/post/{billboard}', ['as' => 'get.post.index', 'uses' => 'Discuss\PostController@getIndex']);
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

//comment
Route::get('comment/vote/{votetype}/{id}',['as' => 'get.comment.vote', 'uses' => 'Comment\CommentController@getVote']);

//test
Route::get('api/auction/admin', ['as' => 'get.api.auction.admin', 'uses' => 'Auction\ItemController@getAdminAPI']);
//Message
Route::get('messages/{id}', ['as' => 'get.messages.inbox', 'uses' => 'Message\MessageController@getMessageInbox']);
Route::post('messages/send',['as' => 'post.messages.send', 'uses' => 'Message\MessageController@postSendMessage']);



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
