<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\PhotoDeleteRequest;
use App\Http\Requests\Profile\PhotoStoreRequest;
use App\Http\Requests\Profile\PhotoUpdateRequest;
use App\Http\Requests\Profile\UpdateRequest as ProfileUpdateRequest;
use App\Services\Photo\Upload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function photoStore(PhotoStoreRequest $request): JsonResponse
    {

        $user = $request->user();

        if (!$request->hasFile('file')) {
            return response()->json(
                ['error' => 'There is no uploaded Photo'],
                400
            );
        }

        $path = Upload::handle($request->file('file'));

        Cache::put('photo-' . $user->id, $path, now()->addHours(12));

        return response()->json(
            ['file' => $path]
        );
    }
    public function photoUpdate(PhotoUpdateRequest $request)
    {

        $request->validate(['file' => 'required']);
        
        Upload::crop(Storage::path($request->file), $request->points);

        $user = $request->user();
        $file = $user->photo_path ?? false;
        
        $user->photo_path = $request->file;
        $user->save();

        if($file) {
            Storage::delete($file);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    public function photoDestroy(PhotoDeleteRequest $request): JsonResponse
    {
        $user = $request->user();
        $file = $request->file;
        $cached_file = Cache::get('photo-' . $user->id);
        if (!$cached_file == $file) {
            return response()->json(
                ['error' => 'Unable to delete'],
                400
            );
        }

        Storage::delete($file);
        return response()->json(['success' => true]);
    }
}
