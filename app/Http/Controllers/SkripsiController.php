<?php

namespace App\Http\Controllers;

use App\Http\Resources\SkripsiDetailResource;
use App\Http\Resources\SkripsiResource;
use App\Models\Skripsi;
use Illuminate\Http\Request;

class SkripsiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);
            $skripsi = Skripsi::paginate($perPage);

            return response()->json([
                'error' => false,
                'message' => 'success',
                'total_items' => $skripsi->total(),
                'current_page' => $skripsi->currentPage(),
                'per_page' => $skripsi->perPage(),
                'last_page' => $skripsi->lastPage(),
                'data' => SkripsiResource::collection($skripsi)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to fetch skripsi data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $skripsi = Skripsi::findOrFail($id);
            if ($skripsi) {
                return response()->json([
                    'error' => false,
                    'message' => 'success',
                    'data' => new SkripsiDetailResource($skripsi)
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Skripsi not found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to fetch skripsi data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'nama' => 'required|max:100',
            'nim' => 'required|max:7',
            'jurusan' => 'required|max:50',
            'angkatan' => 'required|max:4',
        ]);

        try {
            $skripsi = Skripsi::create($request->all());
            return response()->json([
                'error' => false,
                'message' => 'success',
                'data' => new SkripsiDetailResource($skripsi)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to store skripsi data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'sometimes|max:255',
            'nama' => 'sometimes|max:100',
            'nim' => 'sometimes|max:7',
            'jurusan' => 'sometimes|max:50',
            'angkatan' => 'sometimes|max:4',
        ]);

        try {
            $skripsi = Skripsi::findOrFail($id);
            $skripsi->update($request->all());
            return response()->json([
                'error' => false,
                'message' => 'success',
                'data' => new SkripsiDetailResource($skripsi)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update skripsi data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $skripsi = Skripsi::findOrFail($id);
            $skripsi->delete();
            return response()->json([
                'error' => false,
                'message' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete skripsi data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $keyword = $request->input('keyword');
            $skripsi = Skripsi::where('judul', 'like', '%' . $keyword . '%')
                ->get();

            $foundedCount = $skripsi->count();

            return response()->json([
                'error' => false,
                'founded' => $foundedCount,
                'skripsi' => SkripsiDetailResource::collection($skripsi),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to search skripsi: ' . $e->getMessage(),
            ], 500);
        }
    }
}
