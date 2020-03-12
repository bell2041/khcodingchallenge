@extends('layout.mainlayout')

@section('title', 'List of Flight')

@section('content')


    <div class="container">
        <div class="py-5 text-center">
            <h2>List of Flight</h2>
            <p class="lead"> Each required form group has a validation state that can be triggered by attempting to submit the form without completing it.</p>
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

        @if(session()->get('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div><br />
        @endif

        <div class="row clearfix">

            <div class="col-lg-12 col-md-12">

                <div class="card">

                    <div class="body" >
                        <table class="table table-striped">
                            <thead>
                            <tr style="font-size: 10px">
                                <td>id</td>
                                <td>Latitude</td>
                                <td>Longitude</td>
                                <td>Flight Start Time</td>
                                <td>Duration</td>
                                <td>Notes</td>
                                <td colspan="3">Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $row)
                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td>{{$row->lat}}</td>
                                    <td>{{$row->long}}</td>
                                    <td>{{date('m/d/y h:ia', strtotime($row->flight_time))}}</td>
                                    <td>{{$row->duration_in_seconds}}</td>
                                    <td>{{$row->notes}}</td>
                                    <td><a href="{{ route('flights.show',$row->id)}}" class="btn btn-secondary">View</a></td>
                                    <td><a href="{{ route('flights.edit',$row->id)}}" class="btn btn-primary">Edit</a></td>
                                    <td>
                                        <form action="{{ route('flights.destroy', $row->id)}}" method="post" onsubmit="return confirm('Do you really want to delete this record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit" >Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <a href="{{ route('flights.create') }}" class="btn btn-success">+Add New</a>

                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@section('page-css')
@stop

@section('page-script')
@stop
