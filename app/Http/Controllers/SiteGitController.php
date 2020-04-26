<?php

namespace App\Http\Controllers;

use App\Events\SiteUpdated;
use App\Jobs\RepoInstall;
use App\Jobs\RepoRemove;
use App\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SiteGitController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Site $site
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request, Site $site)
    {
        $validated = $this->validate($request, [
            'provider' => ['required', 'in:custom'],
            'repository' => ['required', 'regex:/(git@|https?:\/\/)([a-zA-Z0-9\.\-_]+)(\/|:)([a-zA-Z0-9\-]+)\/([a-zA-Z0-9\-]+)\.git/'],
            'branch' => ['required'],
        ]);

        $site->repository = $request->get('repository');
        $site->repository_provider = $request->get('provider');
        $site->repository_branch = $request->get('branch');
        $site->repository_status = 'installing';

        $site->save();

        broadcast(new SiteUpdated($site))->toOthers();

        dispatch(new RepoInstall($site));

        return response()->json($site->fresh());
        return response()->json($site);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Site $site
     * @return JsonResponse
     */
    public function destroy(Site $site)
    {
        $site->repository = null;
        $site->repository_provider = null;
        $site->repository_branch = null;
        $site->repository_status = 'uninstalling';

        $site->save();

        broadcast(new SiteUpdated($site))->toOthers();

        dispatch(new RepoRemove($site));

        return response()->json($site->fresh());
    }
}
