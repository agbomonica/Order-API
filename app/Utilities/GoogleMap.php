<?php

namespace App\Utilities;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\OrderResource;

class GoogleMap {

    public $sourceAddress;
    public $sourceLat;
    public $sourceLng;
    public $destinationAddress;
    public $destinationLat;
    public $destinationLng;
    public $err;

    public function __construct($sourceAddress, $sourceLat, $sourceLng, $destinationAddress, $destinationLat, $destinationLng, $err){
        $this->sourceAddress = $sourceAddress;
        $this->destinationAddress = $destinationAddress;
        $this->sourceLat = $sourceLat;
        $this->sourceLng = $sourceLng;
        $this->destinationLat = $destinationLat;
        $this->destinationLng = $destinationLng;
        $this->err = $err;

    }

    public static function geocoding($addressFrom, $addressTo) {


        $addressFrom = request('origin');
        $addressTo = request('destination');

        $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
        $formattedAddrTo    = str_replace(' ', '+', $addressTo);


        $geocodeFromOutput = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => urlencode($formattedAddrFrom),
            'key' => env('GOOGLE_MAPS_KEY'),
        ])->json();
        $geocodeOrigin = collect($geocodeFromOutput);

        $geocodeToOutput = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => urlencode($formattedAddrTo),
            'key' => env('GOOGLE_MAPS_KEY'),
        ])->json();
        $geocodeDestination = collect($geocodeToOutput);


        if (
            (count($geocodeOrigin['results']) > 0)
            &&  ($geocodeOrigin['status'] != 'ZERO_RESULTS')
            && (count($geocodeDestination['results']) > 0)
            &&  ($geocodeDestination['status'] != 'ZERO_RESULTS')) {
                $sourceAddress = $geocodeOrigin['results'][0]['formatted_address'];
        $sourceLat = $geocodeOrigin['results'][0]['geometry']['location']['lat'];
        $sourceLng = $geocodeOrigin['results'][0]['geometry']['location']['lng'];

        $destinationAddress = $geocodeDestination['results'][0]['formatted_address'];
        $destinationLat = $geocodeDestination['results'][0]['geometry']['location']['lat'];
        $destinationLng = $geocodeDestination['results'][0]['geometry']['location']['lng'];

        $theta    = $sourceLng - $destinationLng;
        $dist    = sin(deg2rad($sourceLat)) * sin(deg2rad($destinationLat)) +  cos(deg2rad($sourceLat)) * cos(deg2rad($destinationLat)) * cos(deg2rad($theta));
        $dist    = acos($dist);
        $dist    = rad2deg($dist);
        $miles    = $dist * 60 * 1.1515;
        $distance = round($miles * 1609.344, 2);

        $coordinates = [

                'origin' => ['address' => $sourceAddress, 'coordinates' => ['lat' => $sourceLat, 'lng' => $sourceLng]],
                'destination' => ['address' => $destinationAddress, 'coordinates' => ['lat' => $destinationLat, 'lng' => $destinationLng]],
                'distance' => $distance,

                    ];
            return $coordinates;

        }

        else {
            $err = "Address not found";
            return ['error' => $err];
        }

}
    }


?>
