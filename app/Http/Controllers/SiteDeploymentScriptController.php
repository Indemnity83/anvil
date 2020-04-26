<?php

namespace App\Http\Controllers;

use App\Site;
use Illuminate\Http\Request;

class SiteDeploymentScriptController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Site $site
     * @return mixed|string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Site $site)
    {
        $this->validate($request, [
            'content' => ['required'],
        ]);

        $site->deploy_script = $request->get('content');
        $site->save();

        return $site->deploy_script;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Site  $site
     * @return string
     */
    public function show(Site $site)
    {
        return $site->deploy_script;
    }
}
