@extends('dashboard.layouts.index')

@section('content')
@php
    $statusQuery =  request()->get('status') ;
    $query =  request()->query('query') ;
    $route = Route::currentRouteName();
@endphp
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
      
    </div>
    <div class="card">
        <div class="row" id="basic-table">
            <div class="col-12">
                <div class="row p-4">
                    <h4 class="w-50 font-weight-bold">Pending Pages</h4>
                </div>
                <div class="card-content">
                    <div class="row">
                        <div class="form-group col p-4">
                            <h6>
                                Title
                            </h6>
                            <input value='{{ $query }}' id="titleInput" class="form-control" type="text">
                        </div>

                        <div class="form-group col p-4">
                            <button class="btn btn-primary mt-4" onclick="filter()">Filter</button>
                            @if($statusQuery||$query)
                            <a href="{{ route($route) }}" class="btn btn-danger mt-4">Clear</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th class="text-center w-25">Image</th>
                                <th class="pl-5">Title</th>
                                <th class="text-right fix-padding-size">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td class="text-bold-500 text-center"><img onerror="this.onerror=null;this.src='https://hips.hearstapps.com/hmg-prod/images/legacy-fre-image-placeholder-1642515930.png?crop=1.00xw:0.501xh;0,0.234xh&resize=1200:*';"
                                        src="{{ $item->avatar }}" style="width: 150px;"
                                        alt="Avatar" />
                                      </td>
                                    <td class="text-bold-500 text-left pl-5">{{ $item->title??'No Data' }}</td>
                                    <td class="text-bold-500 text-right">
                                        <a href="{{ route('dashboard.pages.review',$item->id) }}" class="btn btn-dark text-white btn-sm action-button-place"><i data-feather="edit" width="20"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            
                            </tbody>
                            </table>
                           
                    </div>
                    <div class="float-right">
                        {{$data->links()}}
                    </div>
    </div>
    
</div>

@endsection

@section('scripts')
<script>
   function filter()
   {
    const filter_input = document.getElementById('titleInput').value;
    const status_input = document.getElementById('statusInput').value;
    let url = '{{ route("dashboard.blogs.index") }}'

    if(!filter_input&&status_input)
    {
        url = url+'?status='+status_input
    }
    if(filter_input&&!status_input)
    {
        url = url+'?query='+filter_input
    }
    if(filter_input&&status_input)
    {
        url = url+'?query='+filter_input+'&&status='+status_input
    }

    window.location.href = url;
   }
</script>
@endsection

