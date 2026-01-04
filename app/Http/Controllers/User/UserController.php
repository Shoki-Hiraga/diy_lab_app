<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserSocialLink;
use App\Models\SocialPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function update(Request $request, $id)
    {
        if (Auth::id() != $id) {
            abort(403, 'アクセス権がありません');
        }

        DB::transaction(function () use ($request, $id) {

            /* =====================
             * ユーザー基本情報
             * ===================== */
            $user = User::findOrFail($id);
            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            /* =====================
             * プロフィール
             * ===================== */
            $profile = UserProfile::firstOrCreate(['user_id' => $id]);

            $validated = $request->validate([
                'profile' => 'nullable|string|max:500',
                'icon'    => 'nullable|image|max:2048',
            ]);

            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $filename = uniqid() . '_' . $file->getClientOriginalName();

                if (
                    $profile->profile_image_url &&
                    Storage::disk('public_fileassets')->exists('icons/' . $profile->profile_image_url)
                ) {
                    Storage::disk('public_fileassets')->delete('icons/' . $profile->profile_image_url);
                }

                $file->storeAs('icons', $filename, 'public_fileassets');
                $validated['profile_image_url'] = $filename;
            }

            $profile->update($validated);

            /* =====================
             * SNSリンク（★重要）
             * ===================== */
            if ($request->has('social_links')) {
                foreach ($request->social_links as $platformId => $url) {

                    // 空なら削除
                    if (!$url) {
                        UserSocialLink::where('user_id', $id)
                            ->where('social_platforms_id', $platformId)
                            ->delete();
                        continue;
                    }

                    UserSocialLink::updateOrCreate(
                        [
                            'user_id' => $id,
                            'social_platforms_id' => $platformId,
                        ],
                        [
                            'url' => $url,
                        ]
                    );
                }
            }

        });

        return redirect()
            ->route('users.profile.show', $id)
            ->with('success', 'プロフィールを更新しました！');
    }

    public function show($id)
    {
        if (Auth::id() != $id) {
            abort(403, 'アクセス権がありません');
        }

        $user = User::with(['profile', 'socialLinks.platform'])->findOrFail($id);
        $platforms = SocialPlatform::all();

        return view('users.profile.show', compact('user', 'platforms'));
    }

}
