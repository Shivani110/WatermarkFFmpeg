<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function watermarkImage(){
        return view('image_upload');
    }

    // ADD IMAGE WATERMARK ON IMAGE
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

    // ADD TEXT WATERMARK ON IMAGE
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

    public function addRepeatedWatermark(Request $request){
        dd($request->all());

        $image = $request->file('image');
        $watermark = $request->file('watermark');
        $watermarkWidth = $request->watermark_width;
        $watermarkHeight = $request->watermark_height;
        $distanceX = $request->distance_x;
        $distanceY = $request->distance_y;

        $imageName = time().'.'.$image->getClientOriginalExtension();
        $watermarkName = time().'.'.$watermark->getClientOriginalExtension();
        $outputName = 'watermarked_' . $imageName;

        $imagePath = $image->storeAs('images', $imageName);
        $watermarkPath = $watermark->storeAs('watermarks', $watermarkName);

        $inputImagePath = storage_path('app/' . $imagePath);
        $inputWatermarkPath = storage_path('app/' . $watermarkPath);
        $outputImagePath = storage_path('app/images/' . $outputName);

        $imageDimensions = getimagesize($inputImagePath);
        $imageWidth = $imageDimensions[0];
        $imageHeight = $imageDimensions[1];

        $ffmpegCmd = "ffmpeg -i $inputImagePath -i $inputWatermarkPath";

        $filter = "[1]scale=$watermarkWidth:$watermarkHeight[wm];";
        $overlayBase = '[0][wm]';
        $index = 0;

        $numX = floor($imageWidth / ($watermarkWidth + $distanceX));
        $numY = floor($imageHeight / ($watermarkHeight + $distanceY));

        for ($y = 0; $y < $numY; $y++) {
            for ($x = 0; $x < $numX; $x++) {
                $xPos = $x * ($watermarkWidth + $distanceX);
                $yPos = $y * ($watermarkHeight + $distanceY);

                if ($index > 0) {
                    $overlayBase = "[v" . ($index - 1) . "][wm]";
                }
                $filter .= "$overlayBase overlay=$xPos:$yPos\[v$index];";
                // overlay=(main_w-overlay_w)/2:10\" $outputImagePath"
                $index++;
            }
        }

        $filter = rtrim($filter, ';');
        $ffmpegCmd .= " -filter_complex \"$filter\" $outputImagePath";

        // Execute the FFmpeg command
        shell_exec($ffmpegCmd);

        return response()->json(['message' => 'Watermarks added successfully', 'path' => Storage::url('images/' . $outputName)], 200);
    }

    public function addOverlayImage(Request $request){
        // dd($request->all());
        // $request->validate([
        //     'image' => 'required|image|mimes:png',
        //     // 'overlay' => 'required|image|mimes:png',
        //     'x_position' => 'required|integer',
        //     'y_position' => 'required|integer'
        // ]);

        // Get the uploaded image and overlay parameters
        $image = $request->file('image');
      
        // $overlay = $request->file('overlay');
        $xPosition = $request->input('x_position');

        $yPosition = $request->input('y_position');
       
        // Generate unique file names
        $imageName = time(). '.' . $image->getClientOriginalExtension();
       
        // $overlayName = time(). '.' . $overlay->getClientOriginalExtension();
        $outputName = 'overlayed_' . $imageName;
        
        // Save the uploaded files to storage
        $imagePath = $image->storeAs('images', $imageName);
        // $overlayPath = $overlay->storeAs('overlays', $overlayName);
       
        // Define paths
        $inputImagePath = storage_path('app/' . $imagePath);
        
        // $inputOverlayPath = storage_path('app/' . $overlayPath);
        $inputOverlayPath = storage_path('app/overlays/1718949756.png');
   
        $outputImagePath = storage_path('app/images/' . $outputName);
        // dd($outputImagePath);
        // Build the FFmpeg command
        // $ffmpegCmd = "ffmpeg -i $inputImagePath -i $inputOverlayPath -filter_complex \"[0][1] overlay=$xPosition:$yPosition\" -y $outputImagePath";
 
        // FOR REDUCE OPACITY BETWEEN 0 to 1 //
        $ffmpegCmd = "ffmpeg -i $inputImagePath -i $inputOverlayPath -filter_complex \"[1]format=rgba,colorchannelmixer=aa=0.4[wm];[0][wm]overlay=$xPosition:$yPosition\" -y $outputImagePath";

        // Execute the FFmpeg command and capture output
       
        exec($ffmpegCmd . " 2>&1", $output, $return_var);

        // Check if the command was executed successfully
        if ($return_var === 0) {
            return response()->json([
                'message' => 'Image overlay added successfully',
                'path' => Storage::url('images/' . $outputName),
                'ffmpeg_output' => $output
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to overlay image',
                'error' => $output
            ], 500);
        }
       
    }

    public function addStaticImage(Request $request){
        $xPosition = $request->input('x_position');
        $yPosition = $request->input('y_position');

        $inputImagePath = storage_path('app/images/1718883839.png');
        $inputOverlayPath = storage_path('app/overlays/1718978261.png');

        $outputName = 'overlayed_1718883839.png';
        $outputImagePath = storage_path('app/images/' . $outputName);

        $ffmpegCmd = "ffmpeg -i $inputImagePath -i $inputOverlayPath -filter_complex \"[1]format=rgba,colorchannelmixer=aa=0.4[wm];[0][wm]overlay=$xPosition:$yPosition\" -y $outputImagePath";

        exec($ffmpegCmd . " 2>&1", $output, $return_var);

        if ($return_var === 0) {
            return response()->json([
                'message' => 'Image overlay added successfully',
                'path' => Storage::url('images/' . $outputName),
                'ffmpeg_output' => $output
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to overlay image',
                'error' => $output
            ], 500);
        }

    }
}
