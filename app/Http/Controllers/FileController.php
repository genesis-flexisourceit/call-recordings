<?php

namespace App\Http\Controllers;

use App\Recording;
use App\Traits\Renameable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    use Renameable;

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
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimetypes:audio/*',
        ]);

        if($validator->fails()){
            return response(['message' => 'Invalid data given.', 'errors'=> $validator->errors()], 422);
        }

        $originalCleanName = $this->removeExtension($request->file('file')->getClientOriginalName());
        $path = $request->file('file')->storeAs('', uniqid().'.mp3', 'gcs');

        if($path){
            $recording = new Recording();
            $recording->file_name = $originalCleanName;
            $recording->path = $path;
            $recording->type = $request->file('file')->getClientOriginalExtension();
            $recording->file_size = $request->file('file')->getSize();
            $recording->status = 'Complete';
            $recording->save();

            return response(['message' => 'File has been uploaded.', 'recording' => $recording], 200);
        }

        return response(['message' => 'An error occurred.'], 400);
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
     * @param  array  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ids = explode(",", $id);

        $recordings = Recording::whereIn('id', $ids)->pluck('path')->toArray();
        Recording::whereIn('id', $ids)->delete();

        Storage::disk('gcs')->delete($recordings);

        return response(null, 204);
    }
}
