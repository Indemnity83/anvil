<?php

namespace App\Http\Controllers;

use App\Events\SiteAdded;
use App\Jobs\SiteInstall;
use App\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(Site::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => ['required', 'unique:sites'],
            'port' => ['required', 'integer', 'min:1025'],
            'directory' => ['required', 'starts_with:/'],
        ]);

        $site = Site::create($validated);
        broadcast(new SiteAdded($site))->toOthers();

        config(["filesystems.disks.{$site->disk}" => [
            'driver' => 'local',
            'root' => storage_path("sites/{$site->name}"),
        ]]);

        $this->dispatch(new SiteInstall($site));

        // TODO implement a full api resource for models
        return response()->json($site->fresh());
    }

    /**
     * Display the specified resource.
     *
     * @param Site $site
     * @return JsonResponse
     */
    public function show(Site $site)
    {
        return response()->json($site);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Site $site
     * @return JsonResponse
     */
    public function update(Request $request, Site $site)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Site $site
     * @return JsonResponse
     */
    public function destroy(Site $site)
    {
        //
    }
}
