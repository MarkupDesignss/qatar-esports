<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserSocialLink;
use Illuminate\Http\Request;

class UserSocialLinkController extends Controller
{
    /**
     * GET social links (auth user)
     */
    public function getSocialLinks(Request $request)
    {
        $userId = $request->user()->id;

        $links = UserSocialLink::where('user_id', $userId)->first();

        return response()->json([
            'status' => true,
            'data'   => $links ?? (object)[]
        ]);
    }

    /**
     * CREATE or UPDATE social links (single API)
     */
    public function saveSocialLinks(Request $request)
    {
        $userId = $request->user()->id;

        $data = $request->validate([
            'facebook'  => 'nullable|url',
            'instagram' => 'nullable|url',
            'twitter'   => 'nullable|url',
            'youtube'   => 'nullable|url',
            'discord'   => 'nullable|url',
            'twitch'    => 'nullable|url',
        ]);

        $links = UserSocialLink::updateOrCreate(
            ['user_id' => $userId],   // condition
            $data                      // values
        );

        return response()->json([
            'status'  => true,
            'message' => 'Social links saved successfully',
            'data'    => $links
        ]);
    }
}
