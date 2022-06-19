<?php


use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

include 'vendor/autoload.php';

// value data yang di perlukan
$filepath = __DIR__ . '/asset/docx/masterdata.docx';
$data = [
    'nama' => 'Syarifah Putri Sevila',
    'nim' => '3042021006',
    'keperluan' => 'TTD Kip',
    'angkatan' => '2021',
];
$ttd = 'Eka Wahyudi';
$output_file = 'Syrifah';
$filename = 'Syarifah.docx';

// call function
$qrlogo = generateQrcode($output_file , $data , $ttd);
getDocument($filepath,$filename, $data , $qrlogo);
echo 'done';
// function create generate qrcode

function generateQrcode($output_file , $data , $ttd)
{
    $logo = 'asset/logo/politap.png';
    $isi_text = "
    Memberikan Pengesahan Tandda tangan ke :
    Nama : " . $data['nama'] . "
    Nim : " . $data['nim'] . "
    Keperluan : " . $data['keperluan'] . "
    Angakatan : " . $data['angkatan'];

    $writer = new PngWriter();

    // Create QR code
    $qrCode = QrCode::create($isi_text);

    // Create generic logo
    $logo = Logo::create($logo)
        ->setResizeToWidth(50);

    // Create generic label
    $label = Label::create($ttd)
        ->setTextColor(new Color(0, 0, 0));


    $result = $writer->write($qrCode, $logo, $label);
    $result->saveToFile(__DIR__ . "/asset/qr/$output_file.png");

    return "asset/qr/$output_file.png";
}

// function create document

function getDocument($filepath, $filename, $data , $qrlogo)
{
    $template = new PhpOffice\PhpWord\TemplateProcessor($filepath);
    foreach ($data as $key => $value) {
        $template->setValue($key, $value);
    }
    $qrdata = [ 'path' => $qrlogo, 'width' => 100 , 'height' => 100];
    $template->setImageValue('qrcode', $qrdata);
    $template->saveAs("asset/docx/$filename");
}


