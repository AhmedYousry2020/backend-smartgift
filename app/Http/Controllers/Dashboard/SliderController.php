<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use  App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SliderController extends Controller
{

    public function index()
    {
        $SliderMedia = Slider::get();
        return view('dashboard.slider.index',compact('SliderMedia'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'media' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480', // 20MB limit
        ]);

        if ($request->hasFile('media')) {
            $media = $request->file('media');
            $filename = time() . '.' . $media->getClientOriginalExtension();

            // Determine if it's an image or video
            $folder = in_array($media->getClientOriginalExtension(), ['jpg', 'jpeg', 'PNG', 'gif']) ? 'images' : 'videos';

            // Move the file to the correct directory
            $media->move(public_path("uploads/slider_media/{$folder}/"), $filename);

            // Save the path in the database
            $request_data['image'] = "uploads/slider_media/{$folder}/" . $filename;
        }
        Slider::create($request_data);
        session()->flash('success', __('site.added_successfully'));

        return redirect()->route('dashboard.slider.media.index');
    }
    public function destroy($id)
    {
        $media = Slider::findOrFail($id);
        // Delete record
        $media->delete();

        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->back();
    }

}
