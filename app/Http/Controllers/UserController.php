<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '';
                    if ($row->deleted_at) {
                        // Soft deleted
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="restore btn btn-warning btn-sm">Restore</a>';
                    } else {
                        // Not deleted
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="delete btn btn-danger btn-sm">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = User::withTrashed()->find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        if ($request->input('soft') === 'true') {
            // Soft delete
            if ($user->trashed()) {
                return response()->json(['success' => false, 'message' => 'User already deleted.'], 400);
            }
            $user->delete();
        } else {
            // Hard delete
            if ($user->trashed()) {
                return response()->json(['success' => false, 'message' => 'User is not soft deleted.'], 400);
            }
            $user->forceDelete();
        }

        return response()->json(['success' => true]);
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return response()->json(['success' => true, 'message' => 'User restored successfully.']);
    }
}