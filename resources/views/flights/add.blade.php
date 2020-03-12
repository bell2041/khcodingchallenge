@extends('layout.mainlayout')

@section('title', 'Adding a Flight')

@section('content')


    <div class="container">
        <div class="py-5 text-center">
            <h2>Adding a Flight</h2>
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

        <div class="row">

            <div class="col-md-12 order-md-1">

                <form class="needs-validation" novalidate="" action="{{ route('flights.store') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3  ">
                            <label for="lat">Latitude</label>
                            <input type="text" class="form-control @error('lat') is-invalid @enderror" name="lat" placeholder="40.606668"
                                   value="{{ old('lat') ? old('lat') : 40.606668 }}" required="">
                            <div class="invalid-feedback">
                                Valid Latitude is required.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="long">Longitude</label>
                            <input type="text" class="form-control @error('long') is-invalid @enderror" name="long" placeholder="-74.046057"
                                   value="{{ old('long') ? old('long') : -74.046057  }}" required="">
                            <div class="invalid-feedback">
                                Valid Longitude is required.
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="flight_time">Flight Start Time <span class="badge">maximum windows is 24 hours</span>   </label>
                            <input type="text" class="form-control @error('flight_time') is-invalid @enderror" name="flight_time" />
                            <div class="invalid-feedback">
                                Please select Valid Start Time
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="duration_in_seconds">Duration (in seconds)</label>
                            <input type="text" class="form-control"  @error('duration_in_seconds') is-invalid @enderror name="duration_in_seconds" placeholder="600"
                                   value="{{ old('duration_in_seconds') ? old('duration_in_seconds') : 600  }}" required="">
                            <div class="invalid-feedback">
                                Please enter a valid duration
                            </div>
                        </div>
                    </div>





                    <div class="mb-3">
                        <label for="notes">Notes</label>
                        <textarea  class="form-control @error('notes') is-invalid @enderror" name="notes" required=""> {{ old('duration_in_seconds') ? old('duration_in_seconds') : 'some notes'  }} </textarea>
                        <div class="invalid-feedback">
                            Please enter notes.
                        </div>
                    </div>




                    <hr class="mb-4">
                    <button class="btn btn-primary btn-lg btn-block" id="submit" type="submit">Submit a Flight</button>
                </form>
            </div>
        </div>

    </div>
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@stop

@section('page-script')

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>

        $(function() {

            // basic validation, can be modified to match laravel validation
            window.addEventListener('load', function () {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation')

                // Loop over them and prevent submission
                Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
            }, false);

            // Initiate datepicker
            $('input[name="flight_time"]').daterangepicker({
                timePicker: true,
                singleDatePicker: true,
                startDate: moment().startOf('hour'),
                minDate: moment().startOf('hour'),
                locale: {
                    format: 'M/DD/Y hh:mm A'
                }
            });
        });

    </script>

@stop
