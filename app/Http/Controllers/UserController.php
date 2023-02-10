<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Http\Requests\Profile\PhotoDeleteRequest;
use App\Http\Requests\Profile\PhotoStoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\StoreRequest;
use App\Models\User;
use App\Services\Photo\Upload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('users.index');
    }
    public function show(User $user): JsonResponse
    {
        $data = $user->only('id', 'name', 'email');
        $data['photo'] = $user->photoUrl();

        return response()->json($data);
    }
    public function store(StoreRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($request->filled('file')) {
            Upload::crop(Storage::path($request->file), $request->points);
            $user->photo_path = $request->file;
        }

        $user->save();

        return response()->json($user);
    }
    public function update(User $user, UpdateRequest $request)
    {
        $file = $user->photo_path ?? false;
        if ($request->filled('file')) {
            Upload::crop(Storage::path($request->file), $request->points);
            $user->photo_path = $request->file;
        }
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function destroy(User $user)
    {
        $file = $user->photo_path;
        $user->delete();

        if ($file) {
            Storage::delete($file);
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function photoStore(PhotoStoreRequest $request): JsonResponse
    {
        $path = Upload::handle($request->file('file'));

        return response()->json(
            ['file' => $path]
        );
    }
    public function photoDestroy(PhotoDeleteRequest $request): JsonResponse
    {
        Storage::delete($request->file);
        return response()->json(['success' => true]);
    }
}
