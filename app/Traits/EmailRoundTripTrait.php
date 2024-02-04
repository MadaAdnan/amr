<?php

namespace App\Traits;
use App\Models\Reservation;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;

trait EmailRoundTripTrait 
{
    public function sendRoundTripEmail($trip1Id, $trip2Id)
    {
        // Retrieve trip data from the database
        $trip1Data = Reservation::find($trip1Id);
        $trip2Data = Reservation::find($trip2Id);

        // Generate PDFs for each trip
        $pdf1 = PDF::loadView('emails.invoice_pdf_new', ['trip' => $trip1Data]);
        $pdf2 = PDF::loadView('emails.invoice_pdf_new', ['trip' => $trip2Data]);

        // Combine PDFs
        $combinedPdf = $this->combinePDFs([$pdf1, $pdf2]);

        // Send email with the combined PDF
        $this->sendEmail($combinedPdf);
    }

    private function combinePDFs($pdfs)
    {
        $outputPdf = new \PDFMerger;

        foreach ($pdfs as $pdf) {
            $outputPdf->addPDF($pdf->output());
        }

        return $outputPdf;
    }

    private function sendEmail($pdf)
    {
        $customer_data = [
            "subject" => "LR Trip #" . $trip->id. " - Create",
            "title" => 'Thank you for choosing LR!',
            "message" => '
                 Your trip #' . $trip->id . ' has been created successfully and will be reviewed and accepted shortly. For inquiries, do not hesitate to contact us at  info@lavishride.com or call us at 
                +1 (888) 816-1015.',
            'cc' => ["info@lavishride.com", "lavishride"],

            "url" => "",
            "url_msg" => "",
        ];

        Mail::send('emails.round-trip', $data, function ($message) use ($pdf) {
            $message->to('recipient@example.com')->subject('Round Trip Booking');
            $message->attachData($pdf->output(), 'round_trip_details.pdf');
        });
    }
    
}