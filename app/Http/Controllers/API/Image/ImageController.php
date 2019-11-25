<?php

namespace App\Http\Controllers\API\Image;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
//use App\Http\Requests\Image\ImageRequest;

use Cloudder;

use Validator;
use Dingo\Api\Routing\Helpers;

class ImageController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->middleware('apiauth');

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

    public function postUpload(Request $request){
        //return $request->file;

        $rules['file'] = 'required|image';
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->response->errorBadRequest();
        }

        $tag = $request->tag;

        $filename = $request->file('file')->getRealPath();

        Cloudder::upload($filename, null, [], [$tag]);

       // $imageID = Cloudder::getPublicId();
        //$fileUrl = Cloudder::secureShow($imageID, ["width" => 800, "height" => 600]);


        return Cloudder::getResult();
   }

   public function destroyUpload(Request $request, $id){

        $publicId = $id;

        Cloudder::delete($publicId, null);

        return $id;
   }



   // public function getUpload(Request $request){

   //      return dd($request);

   //      $imageID = $request->imageid;

   //      $fileUrl = Cloudder::secureShow($imageID, ["width" => 800, "height" => 600]);
        
   //      return $fileUrl;
   // }

}
