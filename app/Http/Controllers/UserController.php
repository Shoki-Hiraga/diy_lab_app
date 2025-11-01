<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\SocialPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show($id)
    {
        if (Auth::id() != $id) {
            abort(403, 'アクセス権がありません');
        }

        $user = User::with(['profile', 'socialLinks.platform'])->findOrFail($id);
        $platforms = SocialPlatform::all();

        return view('users.show', compact('user', 'platforms'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::id() != $id) {
            abort(403, 'アクセス権がありません');
        }

        $user = User::findOrFail($id);
        $user->update($request->only('name', 'email'));

        // プロフィールを取得 or 作成
        $profile = UserProfile::firstOrCreate(['user_id' => $id]);

        // バリデーション
        $validated = $request->validate([
            'profile' => 'nullable|string|max:500',
            'icon' => 'nullable|image|max:2048',
        ]);

        // ✅ アイコン画像の再アップロード処理
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = uniqid().'_'.$file->getClientOriginalName();

            // 🔹 以前の画像を削除（存在すれば）
            if ($profile->profile_image_url && Storage::disk('public')->exists('icons/'.$profile->profile_image_url)) {
                Storage::disk('public')->delete('icons/'.$profile->profile_image_url);
            }

            // 🔹 新しい画像を保存
            $file->storeAs('icons', $filename, 'public');
            $validated['profile_image_url'] = $filename;
        }

        // 更新
        $profile->update($validated);

        return redirect()->route('users.show', $id)
            ->with('success', 'プロフィールを更新しました！');
    }
}
