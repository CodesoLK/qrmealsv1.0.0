<?php

namespace App\Http\Controllers;

use App\Models\Flyer;
use App\Models\FlyerTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FlyerController extends Controller
{

    public function __construct()
    {
        $this->middleware(['isAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = Flyer::with('layers')->where('user_id', auth()->user()->id)->where('flyer_type', 'real')->get();
        return view('flyer.list', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('flyer.design');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'template_name' => 'required|unique:flyers,template_name',
            'layout_width' => 'required',
            'layout_height' => 'required',
            'theme_type' => 'required',
            'theme_value' => 'required',
            'template_cover' => 'required',
        ]);

        $template_cover = $this->base64ToDisk($request->template_cover);

        $flyer = Flyer::create([
            'user_id' => auth()->user()->id,
            'template_name' => $request->template_name,
            'layout_width' => $request->layout_width,
            'layout_height' => $request->layout_height,
            'theme_type' => $request->theme_type,
            'theme_value' => $request->theme_type == "image" ? $this->base64ToDisk($request->theme_value) : $request->theme_value,
            'template_cover' => $template_cover,
        ]);
        if ($request->layers) {
            foreach (json_decode($request->layers) as $layer) {
                FlyerTemplate::create([
                    'flyer_id' => $flyer->id,
                    'type' => $layer->type,
                    'value' => $layer->type == "image" ? $this->base64ToDisk($layer->value) : $layer->value,
                    'width' => $layer->width,
                    'position' => json_encode($layer->position),
                    'style' => json_encode($layer->style),
                ]);
            }
        }
        return redirect()->route('flyer.index')->with('status', 'Template Created Successfully!');
    }

    public function base64ToDisk($image_64)
    {
        try {
            //code...
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1]; // .jpg .png .pdf
        } catch (\Throwable$th) {
            $extension = pathinfo($image_64)['extension'];

            $oldImg = str_replace("/storage/templates/", "", $image_64);
            $oldPath = storage_path() . "/app/public/templates/" . $oldImg;
            $imageName = Str::random(10) . '.' . $extension;
            $newpath = storage_path() . "/app/public/templates/" . $imageName;
            File::copy($oldPath, $newpath);
            return '/storage/templates/' . $imageName;
        }

        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1]; // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
        // find substring fro replace here eg: data:image/png;base64,
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(10) . '.' . $extension;
        Storage::disk('public')->put('/templates/' . $imageName, base64_decode($image));
        return '/storage/templates/' . $imageName;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Flyer  $flyer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $template = Flyer::with('layers')->where('user_id', auth()->user()->id)->where('id', $id)->where('flyer_type', 'real')->first();
        if (is_null($template)) {
            return abort(404);
        }
        return view('flyer.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Flyer  $flyer
     * @return \Illuminate\Http\Response
     */
    public function edit(Flyer $flyer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Flyer  $flyer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Flyer $flyer)
    {
        $this->validate($request, [
            'template_name' => 'required|unique:flyers,template_name,' . $flyer->id,
            'layout_width' => 'required',
            'layout_height' => 'required',
            'theme_type' => 'required',
            'theme_value' => 'required',
            'template_cover' => 'required',
        ]);

        $template_cover = $this->base64ToDisk($request->template_cover);

        $imgName = str_replace("/storage/templates/", "", $flyer->template_cover);
        Storage::disk('public')->delete('/templates/' . $imgName);

        $flyer->user_id = auth()->user()->id;
        $flyer->template_name = $request->template_name;
        $flyer->layout_width = $request->layout_width;
        $flyer->layout_height = $request->layout_height;
        $flyer->theme_type = $request->theme_type;
        $flyer->theme_value = $request->theme_type == "image" ? $this->base64ToDisk($request->theme_value) : $request->theme_value;
        $flyer->template_cover = $template_cover;
        $flyer->save();

        return redirect()->route('flyer.index')->with('status', 'Template Updated Successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Flyer  $flyer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Flyer $flyer)
    {
        $flyer_templates = FlyerTemplate::where('flyer_id', $flyer->id)->get();
        foreach ($flyer_templates as $template) {
            $template->delete();
        }
        $imgName = str_replace("/storage/templates/", "", $flyer->template_cover);
        Storage::disk('public')->delete('/templates/' . $imgName);
        $flyer->delete();
        return redirect()->back()->withStatus(__('Template was deleted.'));

    }
}
