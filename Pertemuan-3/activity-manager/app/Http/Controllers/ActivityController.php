<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest; // Pastikan Request diimpor
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\Category;
use App\Services\ActivityService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        $activities = Activity::query()
            ->search($request->query('search'))
            ->filterCategory($request->query('category_id'))
            ->filterStatus($request->query('status'))
            ->sortByDate($request->query('sort'))
            ->paginate(10)
            ->withQueryString();

        return view('activities.index', compact('activities', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('activities.create', compact('categories'));
    }

    public function store(
        StoreActivityRequest $request,
        ActivityService $service
    ): RedirectResponse {
        $activity = $service->create($request->validated());

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Kegiatan berhasil dibuat dengan status Draft.');
    }

    public function show(Activity $activity): View
    {
        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity): View
    {
        $categories = Category::orderBy('name')->get();

        return view('activities.edit', compact('activity', 'categories'));
    }

    public function update(
        UpdateActivityRequest $request,
        Activity $activity,
        ActivityService $service
    ): RedirectResponse {
        try {
            $service->update($activity, $request->validated());
        } catch (DomainException $exception) {
            return back()
                ->withErrors(['status' => $exception->getMessage()])
                ->withInput();
        }

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function publish(
        Activity $activity,
        ActivityService $service
    ): RedirectResponse {
        try {
            $service->publish($activity);

            return back()->with('success', 'Kegiatan berhasil dipublikasikan.');
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function complete(
        Activity $activity,
        ActivityService $service
    ): RedirectResponse {
        try {
            $service->complete($activity);

            return back()->with('success', 'Kegiatan berhasil diselesaikan.');
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
