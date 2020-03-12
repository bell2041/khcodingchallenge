@extends('layout.mainlayout')

@section('title', 'Adding a Flight')

@section('content')


    <div class="container">
        <div class="py-5 text-center">
            <h2>Flight# {{$data['id']}}</h2>
            <p class="lead">Nice and Informative Description Here! </p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br />
        @endif

        <div class="row">

            <div class="col-md-12 order-md-1">

                <div class="row">
                    <div class="col-md-8 text-center">
                        <img src="https://api.mapbox.com/styles/v1/mapbox/light-v10/static/{{$data['long']}},{{$data['lat']}},12/700x400?access_token=pk.eyJ1IjoiYmVsbDMiLCJhIjoiY2s3bnF6OHhlMDFqMzNncGgxZWoxcTJ1YiJ9.WbGTuYvgGqWq_z_vhJe-sA">
                    </div>

                    <div class="col-md-4">

                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Flight Start Time</h6>
                                    <small class="text-muted"></small>
                                </div>
                                <span class="text-muted">{{date('m/d/Y h:ma',strtotime($data['flight_time']))}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Duration</h6>
                                    <small class="text-muted">in seconds</small>
                                </div>
                                <span class="text-muted">{{ $data['duration_in_seconds']  }}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Notes</h6>
                                    <small class="text-muted">{{ $data['notes'] }}</small>
                                </div>

                            </li>

                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Weather</h6>
                                    <small class="text-muted">{{ $data['weatherSummary'] }}</small>
                                </div>
                                <span class="text-muted">{{ $data['weatherTemperature']  }}F</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Advisory</h6>
                                    <small class="text-muted">{{ $data['advisoryColor'] }}</small>
                                </div>

                            </li>
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">No Fly Zone?</h6>
                                    <small class="text-muted">{{ $data['warning'] ? "Yes" : "No" }}</small>
                                </div>

                            </li>

                        </ul>


                    </div>
                </div>

                <hr>
                <h2>Near Advisories</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Color</th>
                            <th>Distance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($advisoriesData as $advisory)
                            <tr >
                                <td style="background-color: {{$advisory['color']}}"></td>
                                <td>{{$advisory['type']}}</td>
                                <td>{{$advisory['name']}}</td>
                                <td>{{$advisory['color']}}</td>
                                <td>{{$advisory['distance']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection

