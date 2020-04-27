<?php

namespace App\Http\Controllers;

use App\Jobs\SiteDeploy;
use App\Site;
use Illuminate\Http\JsonResponse;

class SiteDeploymentController extends Controller
{
    /**
     * Trigger a deployment of the site.
     *
     * @param Site $site
     * @return JsonResponse
     */
    public function __invoke(Site $site)
    {
        $site->deploy_status = 'deploying';
        $site->save();

        dispatch(new SiteDeploy($site));

        return response()->json($site->fresh());
    }
}
