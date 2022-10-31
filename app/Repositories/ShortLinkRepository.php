<?php

namespace App\Repositories;

use App\Models\ShortLink;

class ShortLinkRepository
{

    public function getShortLinks()
    {
        return ShortLink::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
    }

    public function createShortLink(array $data)
    {
        return ShortLink::create($data);
    }

    public function getShortenLink($code)
    {
        return ShortLink::where('code', $code)->first(['link']);
    }

    public function getLinks()
    {
        return ShortLink::with('user')->orderBy('created_at', 'desc')->get();
    }
}
