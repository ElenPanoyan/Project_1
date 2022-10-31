<?php

namespace App\Http\Controllers;

use App\Services\ShortLinkService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShortLinkController extends Controller
{
    /**
     * @var ShortLinkService
     */
    public ShortLinkService $linkService;

    /**
     * @param ShortLinkService $linkService
     */
    public function __construct(ShortLinkService $linkService)
    {
        $this->linkService = $linkService;
    }

    /**
     * @return Application|Factory|View|string
     */
    public function index()
    {
        try {
            $shortLinks = $this->linkService->getShortLinks();
            return view('shorten-link', compact('shortLinks'));
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector|string
     */
    public function generateShortLink(Request $request)
    {
        try {
            return $this->linkService->generateShortLink($request);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param $code
     * @return Application|RedirectResponse|Redirector
     */
    public function getShortenLink($code)
    {
        return $this->linkService->getShortenLink($code);
    }

    /**
     * @return Application|Factory|View|string
     */
    public function getAllData()
    {
        try {
            $links = $this->linkService->getLinks();
            return view('all-links', compact('links'));
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @return StreamedResponse
     */
    public function exportCSV()
    {
        return $this->linkService->exportCSV();
    }
}
