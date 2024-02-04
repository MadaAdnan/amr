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
                        <h4 class="w-50 font-weight-bold">Services Logs</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                @foreach ($changesData as $change)
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title mb-0"><strong>Service#:{{ $change['activity']['subject_id'] }}</strong> {{ $change['activity']['description'] }}</h4>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-primary"><strong>Causer:</strong> {{ $change['causerName'] }}</p>
                                                @foreach ($change['changes'] as $changeDetail)
                                                    <p><strong>{{ $changeDetail['attribute'] }}:</strong> 
                                                        <span class="text-danger">{{ $changeDetail['oldValue'] }}</span> 
                                                        <i class="fas fa-arrow-right"></i> 
                                                        <span class="text-success">{{ $changeDetail['newValue'] }}</span>
                                                    </p>
                                                @endforeach
                                            </div>
                                            <div class="card-footer">
                                                <p class="mb-0"><strong>Timestamp:</strong> {{ $change['activity']['created_at'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
