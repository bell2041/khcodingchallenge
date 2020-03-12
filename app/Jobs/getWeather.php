<?php

namespace App\Jobs;

use App\Eloquent\FlightsModel;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;
use Naughtonium\LaravelDarkSky\Facades\DarkSky;


class getWeather
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
        $this->getWeatherByID($this->flightID);
    }

    public function getWeatherByID($flightID){

        $data = FlightsModel::find($flightID);

        $weatherData= DarkSky::location($data->lat, $data->long)->atTime(strtotime($data->flight_time))->get();

        if(isset($weatherData->currently->summary) && isset($weatherData->currently->temperature))
        {
            $data->weatherSummary     = $weatherData->currently->summary;
            $data->weatherTemperature = $weatherData->currently->temperature;

            $data->save();
        }

        // Dump data as asked in instructions
        echo "<pre>Job just updated the weather!<div align='left'>";
        print_r($data->toArray());
        echo "</div></pre>";


        return $flightID;
    }
}
