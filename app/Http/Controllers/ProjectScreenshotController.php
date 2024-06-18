<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectScreenshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectScreenshotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        return view('admin.project_screenshots.create',[
            'project' => $project
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        //
        $validated = $request->validate([
            'screenshot' => 'required|image|mimes:png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('screenshot')) {
                $path = $request->file('screenshot')->store('projects', 'public');
                $validated['screenshot'] = $path;
            }
            $validated['project_id'] = $project->id;

            $newScreenshot = ProjectScreenshot::create($validated);

            DB::commit();

            return redirect()->back()->with('success', 'Project Screenshot Added Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'System error!'.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectScreenshot $projectScreenshot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectScreenshot $projectScreenshot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectScreenshot $projectScreenshot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectScreenshot $projectScreenshot)
    {
        try {
            $projectScreenshot->delete();
            return redirect()->route('admin.project_screenshots.create')->with('success', 'Screenshot Deleted Successfully');
        } catch (\Exception $e) {
            //throw $th;
            return redirect()->back()->with('error', 'System error!'.$e->getMessage());
        }
    }
}
