<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('activities')->orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        // Aturan Bisnis (BR-08): Kategori yang masih digunakan tidak dapat dihapus
        if ($category->activities()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh kegiatan.');
        }

        try {
            $category->delete();

            return back()->with('success', 'Kategori berhasil dihapus.');
        } catch (QueryException $e) {
            return back()->with('error', 'Penghapusan kategori ditolak oleh database constraint.');
        }
    }
}
