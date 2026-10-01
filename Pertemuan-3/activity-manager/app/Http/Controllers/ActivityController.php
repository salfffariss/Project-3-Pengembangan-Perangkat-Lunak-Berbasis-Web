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
        $activities = Activity::query()
            ->filterStatus($request->query('status'))
            ->orderBy('activity_date', 'asc')
            ->get();

        return view('activities.index', compact('activities'));
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
            ->with('success', 'Kegiatan berhasil dibuat.');
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
        ActivityService $service // Gunakan service untuk update data
    ): RedirectResponse {
        try {
            // Serahkan validasi aturan bisnis dan penyimpanan ke service
            $service->update($activity, $request->validated());
        } catch (DomainException $exception) {
            // Jika status mencoba mundur, tangkap error dan kembalikan ke form
            return back()
                ->withErrors(['status' => $exception->getMessage()])
                ->withInput();
        }

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
