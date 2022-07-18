<?php

namespace App\Http\Controllers;

use App\Models\Hadeeth;
use File;
use Illuminate\Http\Request;
use ZipArchive;

//use Illuminate\Support\Facades\File;

class HadeethController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Hadeeth::all();
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Hadeeth $hadeeth
     * @return \Illuminate\Http\Response
     */
    public function show(Hadeeth $hadeeth)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Hadeeth $hadeeth
     * @return \Illuminate\Http\Response
     */
    public function edit(Hadeeth $hadeeth)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Hadeeth $hadeeth
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hadeeth $hadeeth)
    {
        $request->validate([
            'content_ar' => 'required_without_all:content_sw,content_en',
            'content_en' => 'required_without_all:content_sw,content_ar',
            'content_sw' => 'required_without_all:content_ar,content_en'
        ]);
        $hadeeth = Hadeeth::find($hadeeth->id);
        $hadeeth->content_ar = $request->content_ar;
        $hadeeth->content_en = $request->content_en;
        $hadeeth->content_sw = $request->content_sw;
        return $hadeeth->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Hadeeth $hadeeth
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hadeeth $hadeeth)
    {
        //
    }

    /**
     * Upload the hadeeth audio to the app
     */

    public function upload(Request $request)
    {
        $request->validate([
            'id' => 'integer',
            'audio' => 'required|mimes:mp3,wav,mp4|max:40000'
        ]);

        $file = $request->file('audio');
        $hasFile = $request->hasFile('audio');
        if ($hasFile) {
            $fileName = strtolower($request->file('audio')->getClientOriginalName());
            return $file->move(public_path() . '/audio/', $fileName);
        }
    }

    public function getAudioFiles()
    {
        $zip = new ZipArchive();
        $zipFile = "audio_files.zip";
        if (File::exists(public_path() . '/audio/' . $zipFile)) {
            File::delete(public_path() . '/audio/' . $zipFile);
            echo "deleted0";
        }
        if ($zip->open(public_path() . '/audio/' . $zipFile, ZipArchive::CREATE)) {
            $files = File::files(public_path() . '/audio/');
            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }
        }
        $zip->close();
        $response = response()->download(public_path('/audio/' . $zipFile));
        ob_end_clean();
        return $response;
    }
}
