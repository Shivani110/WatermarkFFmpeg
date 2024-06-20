<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function watermarkImage(){
        return view('image_upload');
    }

    public function addWatermark(Request $request){
        $image = $request->file('image');
        $watermark = $request->file('watermark');

        $filename = time().'.'.$image->getClientOriginalExtension();
        $watermarkname = time().'.'.$watermark->getClientOriginalExtension();

        $outputName = 'watermarked_' . $filename;
        $imagePath = $image->storeAs('images', $filename);
        $watermarkPath = $watermark->storeAs('watermarks', $watermarkname);

        $inputImagePath = storage_path('app/' . $imagePath);
        $inputWatermarkPath = storage_path('app/' . $watermarkPath);
        $outputImagePath = storage_path('app/images/' . $outputName);

        // $cmd = "ffmpeg -i $inputImagePath -i $inputWatermarkPath -filter_complex \"overlay=20:20\" $outputImagePath";
        // shell_exec($cmd);

        // $cmd = "ffmpeg -i $inputImagePath -i $inputWatermarkPath -filter_complex \"[1][0]scale2ref=w=120:h=120[wm][base];[base][wm]overlay=50:50\" $outputImagePath";
        // shell_exec($cmd);
        // $cmd = "ffmpeg -i $inputImagePath -i $inputWatermarkPath -filter_complex \"[1]scale=320:320[wm];[0][wm]overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2\" $outputImagePath";
        // shell_exec($cmd);

        $cmd = "ffmpeg -i $inputImagePath -i $inputWatermarkPath -filter_complex \"[1]scale=350:180[wm];[0][wm]overlay=(main_w-overlay_w)/2:10\" $outputImagePath";
        shell_exec($cmd);

        return response()->json(['message' => 'Watermark added successfully', 'path' => Storage::url('images/' . $outputName)], 200);
    }

    public function addTextwatermark(Request $request){
        $image = $request->file('image');
        $watermarktext = $request->watermark;

        $imageName = time().'.'.$image->getClientOriginalExtension();
        $outputName = 'watermarked_'.$imageName;

        $imagePath = $image->storeAs('images',$imageName);

        $inputImagePath = storage_path('app/' . $imagePath);
        $outputImagePath = storage_path('app/images/' . $outputName);

        $fontfile = public_path('fonts/Noteworthy-Lt.ttf');

        $cmd = "ffmpeg -i $inputImagePath -vf \"drawtext=text='$watermarktext':fontfile=$fontfile:fontcolor=white:fontsize=24:x=10:y=H-th-10\" $outputImagePath";
    
        shell_exec($cmd);

        return response()->json(['message' => 'Watermark added successfully', 'path' => Storage::url('images/' . $outputName)], 200);
    }
}
