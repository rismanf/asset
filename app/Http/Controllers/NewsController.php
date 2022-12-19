<?php
    
namespace App\Http\Controllers;

use App\Models\News;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
    
class NewsController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:news-list|news-create|news-edit|news-delete', ['only' => ['index','show']]);
         $this->middleware('permission:news-create', ['only' => ['create','store']]);
         $this->middleware('permission:news-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:news-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = News::latest()->paginate(5);
        return view('news.index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('news.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
    
        News::create($request->all());
    
        return redirect()->route('news.index')
                        ->with('success','News created successfully.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\News  $News
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return view('news.show',compact('news'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\News  $News
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        return view('news.edit',compact('news'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\News  $News
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $News)
    {
         request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
    
        $News->update($request->all());
    
        return redirect()->route('news.index')
                        ->with('success','News updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\News  $News
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $News)
    {
        $News->delete();
    
        return redirect()->route('news.index')
                        ->with('success','News deleted successfully');
    }
}