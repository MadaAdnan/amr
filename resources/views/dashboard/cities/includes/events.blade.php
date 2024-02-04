<h5 class="font-weight-bold hourley-{{ $item->id }}">Events</h5>

<div class="table-responsive mb-5">
    <table class="table">
    <thead>
        <tr>
        <th class="text-center" >Name</th>
        <th class="text-center col-3" >Date</th>
        <th class="text-center" >Value</th>
        <th class="text-center" >Address</th>
        <th class="text-center" >Status</th>
        <th class="text-center" >Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($events as $item)
        <tr>
           
            <td class="text-bold-500 text-center">{{ $item->name??'No Data' }}</td>
            <td class="text-bold-500 text-center">{{ $item->start_date->format('d-m-Y')}} / {{ $item->end_date->format('d-m-Y') }}</td>
            <td class="text-bold-500 text-center">
                @if ($item->discount_type == 'Price')
                    ${{ number_format($item->price, 2, '.', ',')  }}
                    @else
                    %{{$item->price}}
                @endif
            </td>
            <td class="text-bold-500 text-center">{{  $item->address  }}</td>
           
            <td class="text-bold-500 text-center"><span style="background-color: {{ $status[$item->status]['color'] }}; !important" class="badge badge-success {{ $item->status == 'Active'?'success-badge':'danger-badge' }} text-uppercase">{{$status[$item->status]['name'] }}</span></td>
            <td class="text-bold-500">
                <a target="_blank" href="{{ route('dashboard.events.edit',$item->id) }}" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit" width="20"></i></a>
            </td>
        </tr>
        @endforeach
    
    </tbody>
    </table>



</div>