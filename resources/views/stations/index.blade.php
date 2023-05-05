@extends('stations.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="pull-left">
                <h2>All stations</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('stations.create') }}"> Create New Station</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Company id</th>
            <th>Address</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($stations as $station)
        <tr>
            <td>{{ $station->id }}</td>
            <td>{{ $station->name }}</td>
            <td>{{ $station->latitude }}</td>
            <td>{{ $station->longitude }}</td>
            <td>{{ $station->company_id }}</td>
            <td>{{ $station->address }}</td>
            <td>
                <form action="{{ route('stations.destroy',$station->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('stations.show',$station->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('stations.edit',$station->id) }}">Edit</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach

    </table>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Select stations in range:</strong>
                <input type="text" name="stations_in_range" class="form-control" placeholder="Number of km">
            </div>
        </div>
    </div>

    <div class="pull-right">
        <a class="btn btn-primary" href="{{ url()->previous() }}"> Back</a>
    </div>
    
    {{ $stations->links() }}


@endsection