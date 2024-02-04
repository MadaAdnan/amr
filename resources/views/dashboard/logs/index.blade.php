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
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Causer</th>
                                            <th class="text-center">Res#</th>
                                            <th class="text-center">Attribute</th>
                                            <th class="text-center">Old Value</th>
                                            <th class="text-center">New Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($changesData as $change)
                                            @php $firstChange = true; @endphp
                                            @foreach ($change['changes'] as $changeDetail)
                                                <tr>
                                                    <th scope="row" class="text-bold-500 text-center">
                                                        @if ($firstChange)
                                                            {{ $change['causer'] }}
                                                        @endif
                                                    </th>
                                                    <td class="text-bold-500 text-center">
                                                        @if ($firstChange)
                                                        {{ $change['subject_id'] }}
                                                        @php $firstChange = false; @endphp
                                                        @endif
                                                    </td>
                                                    <td class="text-bold-500 text-center">{{ $changeDetail['attribute'] }}
                                                    </td>
                                                    <td class="text-bold-500 text-center">
                                                        @if ($changeDetail['attribute'] == 'pick_up_date')
                                                            {{ \Carbon\Carbon::parse($changeDetail['old'])->format('d/m/Y') }}
                                                        @else
                                                            {{ $changeDetail['old'] }}
                                                        @endif

                                                    </td>
                                                    <td class="text-bold-500 text-center">
                                                        @if ($changeDetail['attribute'] == 'pick_up_date')
                                                            {{ \Carbon\Carbon::parse($changeDetail['new'])->format('d/m/Y') }}
                                                        @else
                                                            {{ $changeDetail['new'] }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="float-right">
                                {{ $activities->links() }}
                            </div>
                        </div>

                    </div>
                @endsection
