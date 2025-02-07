<?php

namespace App\Http\Controllers;

use App\Models\Api;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        $apis = Api::withCount('products')->get();
        return view('admin.apis.index', compact('apis'));
    }

    public function create()
    {
        return view('admin.apis.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required",
            "slug" => "required",
            "warning_threshold_status" => "nullable",
            "warning_threshold" => "nullable",
            "status" => "required",
            "api_key" => "nullable",
            "sandbox_base_url" => "nullable",
            "live_base_url" => "nullable",
            "secret_key" => "nullable",
            "public_key" => "nullable"
        ]);
        
        Api::updateOrCreate([
            "name" => $request->name,
            "slug" => $request->slug,
            "warning_threshold_status" => $request->warning_threshold_status,
            "warning_threshold" => $request->warning_threshold,
            "status" => $request->status,
            "api_key" => $request->api_key,
            "secret_key" => $request->secret_key,
            "public_key" => $request->public_key,
            "sandbox_base_url" => $request->sandbox_base_url,
            "live_base_url" => $request->live_base_url,
        ]);

        return redirect(route('api.index'))->with('message', 'Added successfully');
    }

    public function edit(Api $api)
    {
        return view('admin.apis.edit', compact('api'));
    }

    public function update(Request $request, Api $api)
    {
        $this->validate($request, [
            "name" => "required",
            "slug" => "required",
            "warning_threshold_status" => "nullable",
            "warning_threshold" => "nullable",
            "status" => "required",
            "api_key" => "nullable",
            "secret_key" => "nullable",
            "public_key" => "nullable",
            "sandbox_base_url" => "nullable",
            "live_base_url" => "nullable",
            "meta_data" => "nullable"
        ]);

        $meta_data = [
            'nin_verification_api_version' => $request->nin_verification_api_version ?? $api->meta_data->nin_verification_api_version,
            'bvn_verification_api_version' => $request->bvn_verification_api_version ?? $api->meta_data->bvn_verification_api_version,
        ];

        $api->update([
            "name" => $request->name,
            "slug" => $request->slug,
            "warning_threshold_status" => $request->warning_threshold_status,
            "warning_threshold" => $request->warning_threshold,
            "status" => $request->status,
            "api_key" => $request->api_key,
            "secret_key" => $request->secret_key,
            "public_key" => $request->public_key,
            "sandbox_base_url" => $request->sandbox_base_url,
            "live_base_url" => $request->live_base_url,
            "meta_data" => $meta_data
        ]);

        return back()->with('message', 'Updated successfully');
    }

    public function getBalance(Api $api)
    {
        $file_name = $this->getApiFilename($api);
        $res = app("App\Http\Controllers\Providers\\" . $file_name)->balance($api);

        return response()->json($res);
    }
}
