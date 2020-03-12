<?php

namespace App\Http\Controllers;

use App\Eloquent\FlightsModel;
use App\Jobs\getAirspaceData;
use App\Jobs\getWeather;
use App\Rules\FlightTimeValidation;
use http\Client\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

/**
 * Class FlightsController
 * @package App\Http\Controllers
 */
class FlightsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //
        $data = FlightsModel::all();
        $headers = [];
        foreach($data->toArray() as $row){
            $headers = array_keys($row);
            break;
        }
        return view('flights.list', ['data' => $data, 'headers' => $headers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('flights.add');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $request->validate([
            'flight_time' => 'required|date|after:'.date('m/d/y h:ia'),
            'duration_in_seconds' => 'required|int',
            'notes' => 'required|string',
            'lat' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'long' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/']
        ]);

        $saved = FlightsModel::create($request->toArray());

        // Adding new job
        getWeather::dispatchNow($saved->id);
        getAirspaceData::dispatchNow($saved->id);

       return redirect('/')->with('success', 'Record has been added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = FlightsModel::find($id);

        // Adding new job
        //getWeather::dispatchNow($id);
        getAirspaceData::dispatchNow($id);

        return view('flights.show', ['data' => $data->toArray(), 'advisoriesData' => $data->advisories->toArray()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = FlightsModel::find($id);

        return view('flights.edit', ['data' => $data->toArray()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'flight_time' => 'required|date|after:'.date('m/d/y h:ia'),
            'duration_in_seconds' => 'required|int',
            'notes' => 'required|string',
            'lat' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'long' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/']
        ]);

        $data = FlightsModel::find($id);
        $data->fill($request->toArray());
        $data->save();

        // Adding new job
        getWeather::dispatchNow($id);
        getAirspaceData::dispatchNow($id);

        return redirect('/')->with('success', 'Record has been added!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $data = FlightsModel::find($id);
        $data->delete();

        return redirect('/')->with('success', 'Record has been deleted Successfully');
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiGetListAll(){
        $data = FlightsModel::all();

        return response()->json($data);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiGetFlight($id){
        $data = FlightsModel::find($id);

        return response()->json($data);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function apiCreateFlight(Request $request){

       $requestArray = json_decode($request->getContent(), true);

       // Check if request is present and correct
       if(!is_array($requestArray))  return response()->json(array('status' => 'Error', 'message' => 'Missing/Incorrect Post Values'));

       // validate request data
        $validation = Validator::make($requestArray,[
            'flight_time' => 'required|date|after:'.date('m/d/y h:ia'),
            'duration_in_seconds' => 'required|int',
            'notes' => 'required|string',
            'lat' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'long' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/']
        ]);

        if($validation->fails()){
            $errors = $validation->errors();
            return $errors->toJson();

        } else{
            $saved = FlightsModel::create($requestArray);

            // Starting new job/s
            getWeather::dispatchNow($saved->id);
            getAirspaceData::dispatchNow($saved->id);

            return response()->json(array('status' => 'Success', 'message' => 'Added Successfully'));
        }

    }
}
