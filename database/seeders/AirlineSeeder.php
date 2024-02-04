<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airline;

class AirlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $airlines = [
            ["name" => "3M - Silver Airways"],
            ["name" => "4M - LATAM"],
            ["name" => "7F - First Air - 7F"],
            ["name" => "8P - Pacific Coastal Airlines"],
            ["name" => "9A - Air Atlantic - 9A"],
            ["name" => "AA - American Airlines - AA"],
            ["name" => "AC - Air Canada - AC"],
            ["name" => "AD - Azul Brazilian Airlines"],
            ["name" => "AF - Air France - AF"],
            ["name" => "AM - Aeromexico - AM"],
            ["name" => "AN - Ansett Australia - AN"],
            ["name" => "AQ - Aloha Airlines - AQ"],
            ["name" => "AR - Aerolineas Argentinas - AR"],
            ["name" => "AS - Alaska Airlines"],
            ["name" => "AV - Avianca - AV"],
            ["name" => "AY - Finn Air - AY"],
            ["name" => "AZ - Alitalia - AZ"],
            ["name" => "B0 - La Compagnie"],
            ["name" => "B6 - JetBlue Airways - B6"],
            ["name" => "BA - British Airways - BA"],
            ["name" => "BD - British Midland -] BD"],
            ["name" => "BR - EVA Airways - BR"],
            ["name" => "BU - Braathens - BU"],
            ["name" => "C6 - CanJet - C6"],
            ["name" => "CA - Air China - CA"],
            ["name" => "CI - China Airlines"],
            ["name" => "CM - Copa Airlines"],
            ["name" => "CO - Continental Airlines - CO"],
            ["name" => "CZ - China Southern Airlines - CZ"],
            ["name" => "DH - Independence Air - DH"],
            ["name" => "DI - Norwegian Air"],
            ["name" => "DL - Delta Air Lines - DL"],
            ["name" => "DY - Norwegian Air Shuttle"],
            ["name" => "ED - CCAir - ED"],
            ["name" => "EI - Aer Lingus - EI"],
            ["name" => "EK - Emirates"],
            ["name" => "ET - Ethiopian Airlines"],
            ["name" => "F9 - Frontier Airlines - F9"],
            ["name" => "FI - Icelandair - FI"],
            ["name" => "FJ - Fiji Airways"],
            ["name" => "FL - Air Tran - FL"],
            ["name" => "FR - Ryanair - FR"],
            ["name" => "G4 - Allegiant Air - G4"],
            ["name" => "GA - Garuda - GA"],
            ["name" => "GB - Airborne Express - GB"],
            ["name" => "GL - Miami Air Intl. - GL"],
            ["name" => "GQ - Big Sky Airways - GQ"],
            ["name" => "H1 - Hahn Air Systems"],
            ["name" => "HA - Hawaiian Airlines - HA"],
            ["name" => "HP - America West Airlines - HP"],
            ["name" => "HQ - Harmony Airways - HQ"],
            ["name" => "IB - Iberia - IB"],
            ["name" => "IC - Indian Airlines - IC"],
            ["name" => "IG - Air Italy"],
            ["name" => "IJ - Air Liberte - IJ"],
            ["name" => "IR - Iran Air - IR"],
            ["name" => "JD - Japan Air System - JD"],
            ["name" => "JJ - LATAM Brasil"],
            ["name" => "JK - Spanair - JK"],
            ["name" => "JL - Japan Airlines - JL"],
            ["name" => "JQ - Jetstar"],
            ["name" => "JV - Bearskin Airlines - JV"],
            ["name" => "KE - Korean Air Lines - KE"],
            ["name" => "KL - KLM Royal Dutch Airlines - KL"],
            ["name" => "LA - LAN Airlines - LA"],
            ["name" => "LAN - LATAM Chile"],
            ["name" => "LF - Contour Airlines"],
            ["name" => "LH - Lufthansa - LH"],
            ["name" => "LO - Polish Airlines"],
            ["name" => "LX - Swiss Intl Airllines - LX"],
            ["name" => "LY - El Al Israel Airlines - LY"],
            ["name" => "MF - Xiamen Airlines - MF"],
            ["name" => "MH - Malaysian Airline - MH"],
            ["name" => "MS - EgyptAir - MS"],
            ["name" => "MT - Thomas Cook Airlines"],
            ["name" => "MU - China Eastern Airlines"],
            ["name" => "MW - Mokulele Airlines"],
            ["name" => "MX - Mexicana - MX"],
            ["name" => "MXY - Breeze Airways"],
            ["name" => "NH - All Nippon Airways - NH"],
            ["name" => "NK - Spirit Airlines - NK"],
            ["name" => "NW - Northwest Airlines - NW"],
            ["name" => "NZ - Air New Zealand - NZ"],
            ["name" => "OA - Olympic Airways - OA"],
            ["name" => "OB - Boliviana Aviacion"],
            ["name" => "OR - TUI Airways"],
            ["name" => "OS - Austrian Airlines"],
            ["name" => "OZ - Asiana Airlines"],
            ["name" => "PD - Porter Airlines"],
            ["name" => "PO - Polar Air - PO"],
            ["name" => "PR - Philippine Airlines - PR"],
            ["name" => "QF - Qantas Airways"],
            ["name" => "QJ - Jet Airways - QJ"],
            ["name" => "QR - Qatar Airways"],
            ["name" => "RF - Florida West Airlines - RF"],
            ["name" => "RG - Varig - RG"],
            ["name" => "S5 - Shuttle America - S5"],
            ["name" => "S6 - Salmon Air - S6"],
            ["name" => "SA - South African Airways- SA"],
            ["name" => "SK - Scandinavian Airlines (SAS) - SK"],
            ["name" => "UA - United Airlines"]
        ];
        
        foreach( $airlines as $airline )
        {
            Airline::create([
                "name" => $airline["name"]
            ]);
        }
    }
}

