<?php

namespace App\Services;

use App\Repositories\ShortLinkRepository;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ShortLinkService
{
    public ShortLinkRepository $shortLinkRepository;

    public function __construct(ShortLinkRepository $shortLinkRepository)
    {
        $this->shortLinkRepository = $shortLinkRepository;
    }

    public function getShortLinks()
    {
        return $this->shortLinkRepository->getShortLinks();
    }

    public function generateShortLink(Request $request)
    {
        $request->validate([
            'link' => 'required|url'
        ]);

        $data['user_id'] = auth()->id();
        $data['link'] = $request->link;
        $data['code'] = substr(sha1($request->link), 0, 7);
        $pathname = "/images/qr_" . $data['code'] . ".svg";
        $data['qr_code'] = $pathname;
        $this->generateQRcode($request->link, $pathname);

        $this->shortLinkRepository->createShortLink($data);

        return redirect('generate-shorten-link')
            ->with('success', 'Shorten Link Generated Successfully!');
    }

    public function getShortenLink($code)
    {
         $link = $this->shortLinkRepository->getShortenLink($code);
        return redirect($link->link);
    }

    private function generateQRcode($link, string $pathname)
    {
        return QrCode::generate($link, public_path($pathname));
    }

    public function getLinks()
    {
        return $this->shortLinkRepository->getLinks();
    }

    public function exportCSV()
    {
        $fileName = 'report.csv';
        $links = $this->shortLinkRepository->getLinks();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $columns = array('Name', 'Email', 'Date', 'Short Link', 'Link', 'QR Code');

        $callback = function () use ($links, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($links as $link) {
                $row['Name'] = $link->user->name;
                $row['Email'] = $link->user->email;
                $row['Date'] = $link->created_at;
                $row['Link'] = $link->link;
                $row['Short Link'] = $link->code;
                $row['QR code'] = $link->qr_code;

                fputcsv($file, array($row['Name'], $row['Email'],
                    $row['Date'], $row['Link'], $row['Short Link'], $row['QR code']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
