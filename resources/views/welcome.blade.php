@extends('../companies.layout')

@section('content')

    <a class="btn btn-success" href="{{ route('companies.index') }}"> All Companies</a>

    <a class="btn btn-success" href="{{ route('stations.index') }}"> All Stations</a>

@endsection