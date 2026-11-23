<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\ImageManager;
use Throwable;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('id')->get();
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.form', ['user' => new User()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'full_name' => ['required', 'string', 'max:255'],
        ], [], ['full_name' => 'full name']);

        User::create($data);
        return redirect()->route('users.index')->with('success', 'User added.');
    }

    public function edit(User $user): View
    {
        return view('users.form', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user)],
            'full_name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048', 'dimensions:max_width=4096,max_height=4096'],
        ], [
            'avatar.image' => 'Choose a valid image.',
            'avatar.mimes' => 'Use a JPG, PNG, WEBP or GIF image.',
            'avatar.max' => 'The image must be 2 MB or smaller.',
            'avatar.dimensions' => 'The image must be no larger than 4096 by 4096 pixels.',
        ], ['full_name' => 'full name']);

        unset($data['avatar']);
        $oldAvatar = $user->avatar;
        $newAvatar = null;

        if ($request->hasFile('avatar')) {
            try {
                $image = ImageManager::gd()->read($request->file('avatar')->getRealPath())
                    ->cover(160, 160)->toJpeg(85);
            } catch (DecoderException $e) {
                throw ValidationException::withMessages(['avatar' => 'This image could not be read. Choose another image.']);
            }

            $newAvatar = 'avatars/'.Str::uuid().'.jpg';
            Storage::disk('public')->put($newAvatar, (string) $image);
            $data['avatar'] = $newAvatar;
        }

        try {
            $user->update($data);
        } catch (Throwable $e) {
            if ($newAvatar) {
                Storage::disk('public')->delete($newAvatar);
            }
            throw $e;
        }

        if ($newAvatar && $oldAvatar) {
            Storage::disk('public')->delete($oldAvatar);
        }

        return redirect()->route('users.index')->with('success', 'User updated.');
    }
}
