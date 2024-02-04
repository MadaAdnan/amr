@extends('dashboard.layouts.index')

@section('content')
@php
    $statusQuery =  request()->get('status') ;
    $query =  request()->query('query') ;
    $route = Route::currentRouteName();
    $nowDate = \Carbon\Carbon::now();
    $id;
    $status = [
        'publish'=>[
            'name'=>'Published',
            'color'=>'#58f000'
        ],
   
       
        'Scheduled'=>[
            'name'=>'Scheduled',
            'color'=>'#58f000'
        ],
        'draft'=>[
            'name'=>'Draft',
            'color'=>'#fbe73b'
        ],
        'in-progress'=>[
            'name'=>'In Progress',
            'color'=>'#FFAC1C'
        ],
        'admin_reject'=>[
            'name'=>'Admin Reject',
            'color'=>'#fe3738'
        ],
        'rejected'=>[
            'name'=>'Rejected',
            'color'=>'#fe3738'
        ]
    ];

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
                    <h4 class="w-50 font-weight-bold">Blogs</h4>
                    <div class="w-50 text-right">
                        <a href="{{ route('dashboard.blogs.create') }}" type="button" class="btn btn-primary remove-border w-50">Create</a>
                    </div>
                </div>
                <div class="card-content">
                    <div class="row">
                        <div class="form-group col p-4">
                            <h6>
                                Title
                            </h6>
                            <input onkeypress="handle(event)"  value='{{ $query }}' id="searchInput" class="form-control" type="text">
                        </div>
                        <div class="form-group col-2 p-4">
                            <h6>
                                Status
                            </h6>
                           <select id="statusInput" class="form-control">
                            <option  {{ $statusQuery == ''?'selected':'' }} value="">All</option>
                            <option  {{ $statusQuery == 'publish'?'selected':'' }} value="publish">Published</option>
                            <option  {{ $statusQuery == 'scheduled'?'selected':'' }} value="scheduled">Scheduled</option>
                            <option  {{ $statusQuery == 'draft'?'selected':'' }} value="draft">Draft</option>
                            <option  {{ $statusQuery == 'rejected'?'selected':'' }} value="rejected">Rejected</option>
                            <option  {{ $statusQuery == 'in-progress'?'selected':'' }} value="in-progress">In Progress</option>
                            <option  {{ $statusQuery == 'admin_reject'?'selected':'' }} value="admin_reject">Admin Reject</option>
                           </select>
                        </div>
                        <div class="form-group col p-4">
                            @if($statusQuery||$query)
                            <a href="{{ route($route) }}" class="btn btn-danger mt-4">Clear</a>
                            @endif
                            <button class="btn btn-primary mt-4" onclick="filter()"> <i class="fa fa-search"></i> </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th class="text-center" >Image</th>
                                <th class="text-center" >Title</th>
                                <th class="text-center" >User</th>
                                <th class="text-center" >Category</th>
                                <th class="text-center" >Status</th>
                                <th class="text-center" >Date</th>
                                <th class="text-center" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td class="text-bold-500 text-center"><img onerror="this.onerror=null;this.width='150px';this.src='https://content.hostgator.com/img/weebly_image_sample.png';"
                                        src="{{ $item->avatar }}" style="width: 150px;"
                                        alt="Avatar" />
                                      </td>
                                    <td class="text-bold-500 text-center">{{ $item->title??'No Data' }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->user->full_name??'No Data' }}</td>
                                    <td class="text-bold-500 text-center">
                                       @foreach ($item->categories()->get()->take(3) as $cat)
                                           <span class="badge primary-badge m-2 category-badge">{{ $cat->title }}</span>
                                       @endforeach
                                    </td>
                                    <td class="text-bold-500 text-center"><span style="background-color: {{ $status[$item->status]['color'] }}; !important" class="badge badge-success {{ $item->status == 'publish'?'success-badge':'danger-badge' }} text-uppercase">{{$item->status == 'publish'&& $item->date&&$item->date->gt($nowDate)?$status['Scheduled']['name']:$status[$item->status]['name'] }}</span></td>
                                    <td class="text-bold-500 text-center">{{ $item->date?$item->date->format('Y/m/d'):'No Data' }}</td>
                                    <td class="text-bold-500">
                                        @permission('delete-blogs')
                                            <button onclick="deleteItem('{{ $item->id }}')" class="btn btn-danger btn-sm custom-button delete-red mb-2 mt-2"> <i data-feather="trash" width="20"></i> </button>
                                        @endpermission
                                        <a href="{{ route('dashboard.blogs.edit',$item->id) }}" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit" width="20"></i></a>
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


<div id="deleteModal" class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this blog?</p>
        </div>
        <div class="modal-footer">
          <button onclick="closeButton()" type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancel</button>
          <button onclick="confirmDelete()" type="button" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>  


@endsection

@section('scripts')
<script>
    let current_item_id = 0;

   function filter()
   {
    const filter_input = document.getElementById('searchInput').value;
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

   function deleteItem(id)
   {
    current_item_id = id;
    $('#deleteModal').modal('show');
   }

   function closeButton()
    {
        $('#deleteModal').modal('hide');
    }

    function confirmDelete()
    {
        let request = '{{ route("dashboard.blogs.delete",":id") }}';
        request = request.replace(':id',current_item_id);
        $.ajax({
            url:request,
            type:'GET',
            success:(res)=>{
                console.log('response: ',res)
                location.reload();
            },
            error:(err)=>{
                console.log('err: ',res)
                console.log(err)
            }
        })
    }

     function handle(e){
        if(e.keyCode === 13){
            e.preventDefault(); // Ensure it is only this code that runs
            filter();
        }
    }


</script>
@endsection

