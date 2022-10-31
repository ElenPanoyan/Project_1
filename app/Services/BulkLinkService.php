<?php

namespace App\Services;

use App\Repositories\ShortLinkRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BulkLinkService
{
    public ShortLinkRepository $shortLinkRepository;

    /**
     * @param ShortLinkRepository $shortLinkRepository
     */
    public function __construct(ShortLinkRepository $shortLinkRepository)
    {
        $this->shortLinkRepository = $shortLinkRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getBulkLinksCSV(Request $request)
    {
        $file = $request->file('file');
        $csvData = file_get_contents($file);
        $rows = array_map("str_getcsv", explode("\n", $csvData));

        foreach ($rows as $row) {
            $row = array_shift($row);
            if ($row != '') {
                $data['user_id'] = auth()->id();
                $data['link'] = $row;
                $data['code'] = substr(sha1($row), 0, 7);
                $pathname = "/images/qr_" . $data['code'] . ".svg";
                $data['qr_code'] = $pathname;
                $this->generateQRcode($row, $pathname);
                $this->shortLinkRepository->createShortLink($data);
            }
        }
        return response()->json(['message' => ' Bulk link csv imported successfully!']);
    }

    /**
     * @param $link
     * @param string $pathname
     * @return mixed
     */
    private function generateQRcode($link, string $pathname)
    {
        return QrCode::generate($link, public_path($pathname));
    }
}
