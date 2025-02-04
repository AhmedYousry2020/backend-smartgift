<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use  App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PortfolioController extends Controller
{

    public function index()
    {
        $portfolioMedia = Portfolio::get();
        return view('dashboard.portfolio.index',compact('portfolioMedia'));
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
            $media->move(public_path("uploads/portfolio_media/{$folder}/"), $filename);

            // Save the path in the database
            $request_data['media_path'] = "uploads/portfolio_media/{$folder}/" . $filename;
            $request_data['type'] = $folder === 'images' ? 'image' : 'video';
        }
        Portfolio::create($request_data);
        session()->flash('success', __('site.added_successfully'));

        return redirect()->route('dashboard.portfolio.media.index');
    }
    public function destroy($id)
    {
        $media = Portfolio::findOrFail($id);

        // Delete file from public storage
        if (file_exists(public_path($media->media_path))) {
            unlink(public_path($media->media_path));
        }

        // Delete record
        $media->delete();

        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->back();
    }

}
