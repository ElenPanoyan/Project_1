<?php

namespace App\Http\Controllers;

use App\Services\BulkLinkService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BulkLinkController extends Controller
{
    /**
     * @var BulkLinkService
     */
    public BulkLinkService $bulkLinkService;

    /**
     * @param BulkLinkService $bulkLinkService
     */
    public function __construct(BulkLinkService $bulkLinkService)
    {
        $this->bulkLinkService = $bulkLinkService;
    }

    /**
     * @return Application|Factory|View
     */
    public function view()
    {
        return view('importCSV');
    }

    /**
     * @param Request $request
     * @return JsonResponse|string
     */
    public function getBulkLinksCSV(Request $request)
    {
        try {
            return $this->bulkLinkService->getBulkLinksCSV($request);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
