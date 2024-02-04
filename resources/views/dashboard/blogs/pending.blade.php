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
                    <h4 class="w-50 font-weight-bold">Pending Blogs</h4>
                </div>
                <div class="card-content">
                    <div class="row">
                        <div class="form-group col p-4">
                            <h6>
                                Title
                            </h6>
                            <input value='{{ $query }}' id="searchInput" class="form-control" type="text">
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
                                <th class="text-center">Image</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">User</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td class="text-bold-500 text-center"><img onerror="this.onerror=null;this.src='https://heuft.com/upload/image/400x267/no_image_placeholder.png';"
                                        src="{{ $item->avatar }}" style="width: 150px;"
                                        alt="Avatar" />
                                      </td>
                                    <td class="text-bold-500 text-center">{{ $item->title??'N/A' }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->user->full_name }}</td>
                                    <td class="text-bold-500 text-center">
                                        @if(count($item->categories()->get()) == 0)
                                        N/A
                                        @endif
                                       @foreach ($item->categories()->get()->take(3) as $cat)
                                           <span class="badge primary-badge m-2 category-badge">{{ $cat->title }}</span>
                                       @endforeach
                                    </td>
                                    <td class="text-bold-500 text-center">{{ $item->date?$item->date->format('Y/m/d'):'N/A' }}</td>
                                    <td class="text-bold-500 text-center">
                                        <a  href="{{ route('dashboard.blogs.review',$item->id) }}" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit" width="20"></i></a>
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
    const filter_input = document.getElementById('searchInput').value;
    const status_input = null;
    let url = '{{ route("dashboard.blogs.pending") }}'

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

   $('.select2').select2({
        width: '100%',
        allowClear: true,
        multiple: true,
        maximumSelectionSize: 1,
        placeholder: "Start typing",
        data: [
            { id: 1, text: "Ford"     },
            { id: 2, text: "Dodge"    },
            { id: 3, text: "Mercedes" },
            { id: 4, text: "Jaguar"   }
        ]    
   });

   $('.select2').change(function(e){
        // $('#searchTitle').val('');
        $('#searchTitle').val(e.target.value)
        console.log(e.target.value)
        // let url = '{{ route("dashboard.blogs.pending") }}';
        // url = url+'?query='+document.getElementById('searchTitle').value;
        // window.location.href = url;
   });

</script>
@endsection

