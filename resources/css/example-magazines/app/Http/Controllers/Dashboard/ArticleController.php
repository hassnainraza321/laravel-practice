<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Token;
use GuzzleHttp\Client;
// use Carbon\Carbon;
use App\Models\User;
use Helper;
use DataTables;

class ArticleController extends Controller
{

    public function all()
    {
        $articles = DB::table('articles')->get();

        return response()->json(['articles' => $articles], 200);
    }

    public function list(Request $request)
    {

        $user_id = Auth::id();

        if (Auth::user()->is_admin === 1) {
            
            $data = DB::table('articles');
        }else{
            $data = DB::table('articles')->where('user_id', $user_id);
        }

        $articles = $data->get();

        if ($request->ajax())
        {

            if (!empty($request->keyword))
            {
                $keyword = trim($request->keyword);
                
                $data = $data->where(function($qry) use ($keyword) {
                                $qry->orWhereRaw("title like ?", ["%{$keyword}%"])
                                ->orWhereRaw("slug like ?", ["%{$keyword}%"]);
                        });
            }

            if ($request->status_id !== null)
            {
                $data = $data->where('articles.status', $request->status_id);
            }

            $data = $data->select('articles.*', 'articles.status');

            return DataTables::of($data)
                ->addColumn('status', function ($row) {
                    $status_class = $row->status === 1 ? 'bg-success' : 'bg-danger';
                    $status_text = $row->status === 1 ? 'Publish' : 'Draft';
                    return '<span class="badge ' . $status_class . '">' . $status_text . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $edit_url = route('articles.edit', $row->id);
                    $delete_url = route('articles.remove', $row->id);
                    $publish = route('articles.publish', $row->id);
                    $draft = route('articles.publish', $row->id);

                    $article_status = DB::table('articles')->where('id', $row->id)->first();

                    $publish_link = '';
                    $draft_link = '';

                    if ($article_status->status === 0){
                        $publish_link = '<a href="'.$publish.'" class="dropdown-item edit_magazine"> <i data-feather="check"></i> Publish</a>';
                    }
                    else{
                        $draft_link = '<a href="'.$draft.'" class="dropdown-item edit_magazine"> <i data-feather="x"></i> Draft</a>';
                    }

                    return '<div class="btn-group">
                                <button type="button" class="btn border-0 p-1 m-0 bg-danger text-white rounded" data-bs-toggle="dropdown" data-bs-target="#dropdown">. . .</button>
                                <div id="dropdown" class="dropdown-menu">
                                    <a href="' . $edit_url . '" class="dropdown-item edit_magazine"><i data-feather="edit"></i> Edit</a>
                                    '.$publish_link .$draft_link.'
                                    <a class="dropdown-item show_alert" data-url="' . $delete_url . '"><i data-feather="trash-2"></i> Delete</a>
                                </div>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);

        }

        return view('dashboard.articles.list', compact('articles'));

        if ($request->expectsJson()) {

            return response()->json(['articles' => $articles],200);
        }

    }

    public function add(Request $request, $dashboard = null)
    {
        if (request()->method() === 'GET') {

            $magazines = DB::table('magazines')->get();
            
            return view('dashboard.articles.add', compact('magazines'));
        }

        if (Auth::user()->account_status === 1) {
            if ($request->expectsJson()) {

                return response()->json(['error' => 'Please Contact Admin Your Account Is Suspended !'], 500);
            }

            return redirect()->back()->with('error', 'Please Contact Admin Your Account Is Suspended !');
        }
        
        $Validator = $request->validate([

            'title' => 'required',
            'magazine_id' => 'required|array',
            'content' => 'required',
            'featured_image' => 'image|mimes:jpeg,png,jpg,gif',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',
            'featured_video' => 'mimes:mp4,avi,mov,wmv|max:40480', // Example: Max size of 20MB
            'videos.*' => 'mimes:mp4,avi,mov,wmv|max:40480',

        ]);

        if (!is_null($request->slug)) {
            $slug = Str::slug($request->slug);
        }else{

            $title = substr($request->title, 0, 40);
            $slug = Str::slug($title);
        }

        $user_id = Auth::id();
        

        $_request = $request->expectsJson();

        if (is_null($dashboard)) {
            
            $_request = 0;

        } else {
            $_request = 1;
        }

        if (is_null($request->status)) {
            
            $status = 0;

        } else {
            $status = 1;
        }

        $articles = DB::table('articles')->where('user_id', $user_id)->count();

        if (is_null(Auth::user()->package_id)) {

            $package = DB::table('packages')->where('amount', 0)->value('article_limit');

        }else{

            $package = DB::table('packages')->where('id', Auth::user()->package_id)->value('article_limit');
        }

        if (Auth::user()->is_admin === 1 || $articles <= $package) {

            $article_id = DB::table('articles')->insertGetId([

                'user_id' => $user_id,
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'status' => $status,
                'request' => $_request,
                'created_at' => now(),
                'updated_at' => now(),

            ]);

            $_mag_id = [];

            foreach ($request->magazine_id as $key => $mag_id) {
            
                $_mag_id[] = [

                    'magazine_id' => $mag_id,
                    'article_id' => $article_id,
                ];
            }

            $mag_article = DB::table('mag_articles')->insert($_mag_id);

            if ($request->file('featured_image')) {

                $imagePath = $request->file('featured_image')->store('public/images');

                $image = str_replace('public/', '', $imagePath); // Remove 'public/' from the path

                $images = DB::table('images')->insertGetId([

                    'article_id' => $article_id,
                    'image' => $image,
                    'featured_image' => 1,

                ]);
            }

            if ($request->file('featured_video')) {
                
                $videoPath = $request->file('featured_video')->store('public/images');

                $video = str_replace('public/', '', $videoPath);

                $videos = DB::table('videos')->insertGetId([

                    'article_id' => $article_id,
                    'video' => $video,
                    'featured_video' => 1,

                ]);
            }

            if ($request->file('images')) {

                $_images = [];

                foreach ($request->file('images') as $key => $image) {
                    
                    $imagePath = $image->store('public/images');

                    $image_path = str_replace('public/', '', $imagePath);

                    $_images[] = [

                        'article_id' => $article_id,
                        'image' => $image_path,
                    ];


                }

                $images = DB::table('images')->insert($_images);
            }

            if ($request->file('videos')) {

                $_videos = [];

                foreach ($request->file('videos') as $key => $video) {
                    
                    $videoPath = $video->store('public/images');

                    $video_path = str_replace('public/', '', $videoPath);

                    $_videos[] = [

                        'article_id' => $article_id,
                        'video' => $video_path,
                    ];


                }

                $videos = DB::table('videos')->insert($_videos);
            }

            Helper::alert_notification($request->title, $request->content, $slug);

            if ($request->expectsJson()) {

                if ($article_id) {
                    return response()->json(['success' => 'Data inserted successfully'], 200);
                }
                return response()->json(['error' => 'Please Try Again !'], 500);
            }

            if ($article_id) {

                return redirect()->back()->with('success', 'Data inserted successfully');
            }

            return redirect()->back()->with('error', 'Please Try Again !');
        }
        else
        {
            
            if ($request->expectsJson()) {

                return response()->json(['error' => 'Please Subscripe Higher Package In Order To Genrate More Articles !'], 500);
            }

            return redirect()->back()->with('error', 'Please Subscripe Higher Package In Order To Genrate More Articles !');
        }

    }

    public function edit(Request $request, $id, $type = null)
    {
        if (request()->method() === 'GET') {
            
            $articles = DB::table('articles')->where('id', $id)->first();
            $magazines = DB::table('magazines')->get();
            $magazine_id = DB::table('mag_articles')->where('article_id', $id)->pluck('magazine_id');
            $images = DB::table('images')->where('article_id', $id)->where('featured_image', 0)->get();
            $featured_image = DB::table('images')->where('article_id', $id)->where('featured_image', 1)->value('image');
            $videos = DB::table('videos')->where('article_id', $id)->where('featured_video', 0)->get();
            $featured_video = DB::table('videos')->where('article_id', $id)->where('featured_video', 1)->value('video');

            if ($request->expectsJson()) {

                return response()->json(['articles' => $articles, 'magazines' => $magazines, 'magazine_id' => $magazine_id, 'images' => $images, 'featured_image' => $featured_image, 'videos' => $videos, 'featured_video' => $featured_video], 200);
            }

            return view('dashboard.articles.add', compact('articles', 'magazines', 'magazine_id', 'images', 'featured_image', 'videos', 'featured_video'));

        }

        $Validator = $request->validate([

            'title' => 'required',
            'magazine_id' => 'required|array',
            'content' => 'required',
            'featured_image' => 'image|mimes:jpeg,png,jpg,gif',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',
            'featured_video' => 'mimes:mp4,avi,mov,wmv|max:40480', // Example: Max size of 20MB
            'videos.*' => 'mimes:mp4,avi,mov,wmv|max:40480',

        ]);

        if (!is_null($request->slug)) {
            $slug = Str::slug($request->slug);
        }else{
            $title = substr($request->title, 0, 40);
            $slug = Str::slug($title);
        }

        $user_id = Auth::id();
        

        $_request = $request->expectsJson();

        // if (is_null($dashboard)) {
            
        //     $_request = 0;

        // } else {
        //     $_request = 1;
        // }

        if (is_null($request->status)) {
            
            $status = 0;

        } else {
            $status = 1;
        }

        $update_article = DB::table('articles')->where('id', $id)->update([

            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'status' => $status,
            'updated_at' => now(),

        ]);

        $mag_article = DB::table('mag_articles')->where('article_id', $id)->delete();

        $_mag_id = [];

        foreach ($request->magazine_id as $key => $mag_id) {
        
            $_mag_id[] = [

                'magazine_id' => $mag_id,
                'article_id' => $id,
                
            ];
        }

        $mag_article = DB::table('mag_articles')->insert($_mag_id);

        if ($request->file('featured_image')) {

            $img = DB::table('images')->where('article_id', $id)->where('featured_image', 1)->value('image');

            Storage::disk('public')->delete($img);

            $del_image = DB::table('images')->where('article_id', $id)->where('featured_image', 1)->delete();

            $imagePath = $request->file('featured_image')->store('public/images');

            $image = str_replace('public/', '', $imagePath);

            $images = DB::table('images')->insertGetId([

                'article_id' => $id,
                'image' => $image,
                'featured_image' => 1,

            ]);
        }

        if ($request->file('featured_video')) {

            $video = DB::table('videos')->where('article_id', $id)->where('featured_video', 1)->value('video');

            Storage::disk('public')->delete($video);

            $del_video = DB::table('videos')->where('article_id', $id)->where('featured_video', 1)->delete();
            
            $videoPath = $request->file('featured_video')->store('public/images');

            $video = str_replace('public/', '', $videoPath);

            $videos = DB::table('videos')->insertGetId([

                'article_id' => $id,
                'video' => $video,
                'featured_video' => 1,

            ]);
        }

        if ($request->file('images')) {

            // $del_images = DB::table('images')->where('article_id', $id)->where('featured_image', 0)->delete();

            $_images = [];

            foreach ($request->file('images') as $key => $image) {
                
                $imagePath = $image->store('public/images');

                $image_path = str_replace('public/', '', $imagePath);

                $_images[] = [

                    'article_id' => $id,
                    'image' => $image_path,
                ];


            }

            $images = DB::table('images')->insert($_images);
        }

        if ($request->file('videos')) {

            // $del_videos = DB::table('videos')->where('article_id', $id)->where('featured_video', 0)->delete();

            $_videos = [];

            foreach ($request->file('videos') as $key => $video) {
                
                $videoPath = $video->store('public/images');

                $video_path = str_replace('public/', '', $videoPath);

                $_videos[] = [

                    'article_id' => $id,
                    'video' => $video_path,
                ];


            }

            $videos = DB::table('videos')->insert($_videos);
        }

        Helper::alert_notification($request->title, $request->content, $request->slug);

        if (!is_null($type) && $request->expectsJson()) {

            if ($update_article) {
                return response()->json(['success' => 'Data updated successfully'],200);
            }
            return response()->json(['error' => 'Please Try Again !'],500);
        }

        if ($update_article) {

            return redirect()->back()->with('success', 'Data updated successfully');
        }

        return redirect()->back()->with('error', 'Please Try Again !');
    }

    public function image_remove(Request $request,$id , $type = null)
    {
        if ($type === 'image') {

            $img = DB::table('images')->where('id',$id)->value('image');

            Storage::disk('public')->delete($img);

            $del = DB::table('images')->where('id', $id)->delete();

            if ($request->expectsJson()) {

                if ($del) {
                    return response()->json(['success' => 'Image deleted successfully'], 200);
                }
                return response()->json(['error' => 'Please Try Again !'], 500);
            }

            if ($del) {

                return redirect()->back()->with('success', 'Image delete successfully');
            }

            return redirect()->back()->with('error', 'Please Try Again !');

        }elseif($type === 'video'){

            $video = DB::table('videos')->where('id',$id)->value('video');

            Storage::disk('public')->delete($video);

            $del = DB::table('videos')->where('id', $id)->delete();

            if ($request->expectsJson()) {

                if ($del) {
                    return response()->json(['success' => 'video deleted successfully'], 200);
                }
                return response()->json(['error' => 'Please Try Again !'], 500);
            }

            if ($del) {

                return redirect()->back()->with('success', 'video delete successfully');
            }

            return redirect()->back()->with('error', 'Please Try Again !');
        }else{

            return redirect()->back()->with('error', 'Please Try Again !');
        }
    }

    public function remove(Request $request, $id)
    {
        $del_article = DB::table('articles')->where('id', $id)->delete();

        if ($request->expectsJson()) {

            if ($del_article) {
                return response()->json(['success' => 'Article deleted successfully'], 200);
            }
            return response()->json(['error' => 'Please Try Again !'], 500);
        }

        if ($del_article) {
            
            return redirect()->back()->with('success', 'Article deleted successfully');
        }

        return redirect()->back()->with('error', 'Please Try Again !');
    }

    public function publish($id)
    {
        $article = DB::table('articles')->where('id', $id)->first();

        if ($article->status === 0) {

            $article = DB::table('articles')->where('id', $id)->update([

                'status' => 1,

            ]);

            if ($article) {
                return redirect()->back()->with('success', 'Article suspend successfully');
            }
            return redirect()->back()->with('error', 'Please Try Again !');

        }elseif ($article->status === 1){

            $article = DB::table('articles')->where('id', $id)->update([

                'status' => 0,

            ]);

            if ($article) {
                return redirect()->back()->with('success', 'Article active successfully');
            }
            return redirect()->back()->with('error', 'Please Try Again !');

        }else{
            return redirect()->back()->with('error', 'Something wrong, Please Try Again !');
        }
    }
}
