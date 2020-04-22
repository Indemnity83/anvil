<?php

namespace App\Http\Controllers;

use App\Jobs\InstallSite;
use App\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            'port' => ['required', 'unique:sites', 'integer', 'min:1025'],
            'directory' => ['required'],
        ]);

        $site = Site::create($validated);

        $this->dispatch(new InstallSite($site));

        return response()->json($site);
    }

    /**
     * Display the specified resource.
     *
     * @param Site $site
     * @return JsonResponse
     */
    public function show(Site $site)
    {
        //
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
