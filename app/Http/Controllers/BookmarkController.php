<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookmarkResource;
use App\Http\Resources\UserMeResource;
use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{

    public function index()
    {
        try {
            /** @var $user \App\Models\User */
            $user = auth()->user();
            $bookmarks = $user->bookmarks()->orderBy('created_at', 'desc')->get();

            if ($bookmarks->isEmpty()) {
                return response()->json([
                    'error' => false,
                    'message' => 'No bookmarks found for this user',
                ]);
            }

            return response()->json([
                'error' => false,
                'message' => 'Success',
                'bookmark_list' => BookmarkResource::collection($bookmarks),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to fetch bookmarks: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'skripsi_id' => 'required|exists:skripsi,id',
            ]);

            $existingBookmark = Bookmark::where('user_id', auth()->id())
                ->where('skripsi_id', $request->skripsi_id)
                ->first();

            if ($existingBookmark) {
                return response()->json([
                    'error' => true,
                    'message' => 'Bookmark already exists for this skripsi',
                ], 400);
            }

            $bookmark = Bookmark::create([
                'user_id' => auth()->id(),
                'skripsi_id' => $request->skripsi_id,
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Bookmark created successfully',
                'data' => [
                    'id' => $bookmark->id,
                    'skripsi' => BookmarkResource::make($bookmark)->toArray($request),
                ],
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create bookmark: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create bookmark: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $userId = auth()->id();
            $bookmark = Bookmark::where('skripsi_id', $id)
                ->whereHas('skripsi', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->firstOrFail();

            $bookmark->delete();

            return response()->json([
                'error' => false,
                'message' => 'Bookmark deleted successfully',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Bookmark not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete bookmark: ' . $e->getMessage(),
            ], 500);
        }
    }
}
