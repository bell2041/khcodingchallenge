<?php

namespace App\Jobs;

use App\Eloquent\FlightsModel;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Naughtonium\LaravelDarkSky\Facades\DarkSky;


class getAirspaceData
{
    use Dispatchable, Queueable;
    private $flightID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($flightID)
    {
        //
        $this->flightID = $flightID;
    }

     /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->getAirspaceData($this->flightID);
    }

    public function getAirspaceData($flightID){

        $data = FlightsModel::find($flightID);


        $endpoint = "https://app.kittyhawk.io/api/atlas/advisories";
        $data_string = ['geometry' => [
            'format' => 'geojson',
            'data'   => json_encode([
                'type' => "Point",
                'coordinates' => [(float)$data->long,(float)$data->lat]
            ]),
        ]];
        $advisoryColor = '';
        $advisoryTFRDistance = 0;


        $process = curl_init($endpoint);
        curl_setopt($process, CURLOPT_HTTPHEADER, ['Content-type: application/json']);

        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($process, CURLOPT_POSTFIELDS, json_encode($data_string));

        curl_setopt($process, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);

        $return     = curl_exec($process);
        $response = json_decode($return, true);


        if (Arr::has($response,  "data.color.name")){

            // Deleting All Old Ones
            $data->advisories()->delete();

            // Main Color
            $advisoryColor = $response['data']['color']['name'];

            // Saving All Advisories into separate table
            if(is_array($response['data']['advisories'])){
                foreach($response['data']['advisories'] as $advisory){

                   // Checking for No Fly zone
                   if($advisory['type'] == "tfr")
                   {
                       if (Arr::has($advisory,  "distance.value"))
                       {
                           $advisoryTFRDistance = $advisory['distance']['value'] == 0 ? 1 : 0;
                       }
                   }

                   $data->advisories()->create([
                       'type' => $advisory['type'],
                       'name' => $advisory['name'],
                       'color' => $advisory['color']['name'],
                       'distance' => $advisory['distance']['value']
                   ]);
               }
            }


            $data->advisoryColor = $advisoryColor;
            $data->warning = $advisoryTFRDistance;

            $data->save();

        }else{
          Log::notice('Cannot connect to https://app.kittyhawk.io/api : ' . $return);
        }

        return $flightID;
    }
}
