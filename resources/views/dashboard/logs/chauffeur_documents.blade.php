@extends('dashboard.layouts.index')

@section('content')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
            </div>

        </div>
        <div class="card">
            <div class="row" id="basic-table">
                <div class="col-12">
                    <div class="row p-4">
                        <h4 class="w-50 font-weight-bold">Logs</h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">

                            <!-- Table with outer spacing -->
                            <div class="container">
                                <div class="row">
                                    @foreach ($chauffeurDocuments as $document)
                                    @php
                                        $dataAttributes = json_decode($document->data, true);
                                    @endphp
                                    <div class="col-md-4">
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                Document ID: {{ $document->id }}
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">Driver Name: {{ $document->user->first_name." ". $document->user->last_name}}</h5>
                                                <h6 class="card-title">Driver ID: {{ $document->driver_id }}</h6>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                @foreach ($dataAttributes as $attribute)
                                                <li class="list-group-item">
                                                    <strong>Required:</strong> {{ $attribute['required'] == 0 ? 'required' : 'not required' }}<br>
                                                    <strong>Type:</strong> {{ $attribute['type'] }}<br>
                                                    <strong>Label:</strong> {{ $attribute['label'] }}<br>
                                                    <strong>Text:</strong> {{ $attribute['text'] }}<br>
                                                    <strong>Value:</strong> {{ $attribute['value'] }}<br>
                                                    <strong>Name:</strong> {{ $attribute['name'] }}<br>
                                                    @if (isset($attribute['file']))
                                                        <strong>File:</strong> <a href="{{ $attribute['file'] }}" target="_blank">View File</a><br>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            @endsection
