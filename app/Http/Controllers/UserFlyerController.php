<?php

namespace App\Http\Controllers;

use App\Models\Flyer;
use App\Models\FlyerTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserFlyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = Flyer::with('layers')->where('user_id', auth()->user()->id)->where('flyer_type', 'edited')->get();
        return view('user-flyer.index', compact('templates'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $templates = Flyer::with('layers')->where('flyer_type', 'real')->get();
        return view('user-flyer.create', compact('templates'));
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
            'flyer_type' => 'edited',
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
        return redirect()->route('menu-designer.index')->with('status', 'Menu Template Created Successfully!');

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $template = Flyer::with('layers')->where('id', $id)->where('flyer_type', 'real')->first();
        if (!$template) {
            return abort(404);
        }
        return view('user-flyer.show', compact('template'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = Flyer::with('layers')->where('id', $id)->where('user_id', auth()->user()->id)->where('flyer_type', 'edited')->first();
        if (is_null($template)) {
            return abort(404);
        }
        return view('user-flyer.edit', compact('template'));

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
        $this->validate($request, [
            'template_name' => 'required|unique:flyers,template_name,' . $id,
            'layout_width' => 'required',
            'layout_height' => 'required',
            'theme_type' => 'required',
            'theme_value' => 'required',
            'template_cover' => 'required',
        ]);

        $flyer = Flyer::findorfail($id);

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

        if ($request->layers) {
            foreach (json_decode($request->layers) as $layer) {
                FlyerTemplate::updateOrCreate([
                    'flyer_id' => $flyer->id,
                    'id' => $layer->id,
                ],
                    [
                        'flyer_id' => $flyer->id,
                        'type' => $layer->type,
                        'value' => $layer->type == "image" ? $this->base64ToDisk($layer->value) : $layer->value,
                        'width' => $layer->width,
                        'position' => json_encode($layer->position),
                        'style' => json_encode($layer->style),
                    ]);
            }
        }

        return redirect()->route('menu-designer.index')->with('status', 'Menu Template Updated Successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $flyer = Flyer::findorfail($id);
        $flyer_templates = FlyerTemplate::where('flyer_id', $flyer->id)->get();
        foreach ($flyer_templates as $template) {
            $template->delete();
        }
        $imgName = str_replace("/storage/templates/", "", $flyer->template_cover);
        Storage::disk('public')->delete('/templates/' . $imgName);
        $flyer->delete();
        return redirect()->back()->withStatus(__('Menu Template was deleted.'));

    }
}
