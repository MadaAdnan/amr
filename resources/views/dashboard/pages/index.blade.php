@extends('dashboard.layouts.index')

@section('content')
@php
    $statusQuery =  request()->get('status') ;
    $typeParameter =  request()->route('type');
    $route = Route::currentRouteName();
    $status = [
        'Published'=>[
            'name'=>'Published',
            'color'=>'#58f000'
        ],
        'Draft'=>[
            'name'=>'Draft',
            'color'=>'#fbe73b'
        ],
        'Rejected'=>[
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
                    <h4 class="w-50 font-weight-bold">Web Pages</h4>
                    <div class="w-50 text-right">
                        {{-- <a href="{{ route('dashboard.pages.create') }}" type="button" class="btn btn-primary create-hover remove-border">Create</a> --}}
                    </div>
                </div>
                <nav class="navbar navbar-expand-lg navbar-light bg-white card w-75 align-self-center mx-auto card-shadow-nav">
                    <!-- Container wrapper -->
                    <div class="container-fluid">
                      <!-- Toggle button -->
                      <button
                        class="navbar-toggler"
                        type="button"
                        data-mdb-toggle="collapse"
                        data-mdb-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                      >
                        <i class="fas fa-bars"></i>
                      </button>
                  
                      <!-- Collapsible wrapper -->
                      <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Navbar brand -->
                       
                        <!-- Left links -->
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100">
                          @foreach ($navs->take(6) as $item)
                            @if ($item->title != 'Help')
                              <li class="nav-item">
                                <a class="nav-link web-pages {{ $item->title == $typeParameter?'active':'' }}" href="{{ route('dashboard.pages.index',$item->title) }}">{{ $item->title }}</a>
                              </li>
                            @endif
                          @endforeach
                         
                          <li class="nav-item">
                            <a class="nav-link web-pages {{ $typeParameter ==  'Services Type'?'active':''}}" href="{{ route('dashboard.pages.index','Services Type') }}">Services Slider</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link web-pages {{ $typeParameter ==  'Fleet'?'active':''}}" href="{{ route('dashboard.pages.index','Fleet') }}">Fleet</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link web-pages {{ $typeParameter ==  'Faq'?'active':''}}" href="{{ route('dashboard.pages.index','Faq') }}">Faq</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link web-pages {{ $typeParameter ==  'Terms'?'active':''}}" href="{{ route('dashboard.pages.index','Terms') }}">Legal</a>
                          </li>
                          {{-- <li class="nav-item add-border ml-5">
                              <a class="nav-link web-pages {{ $typeParameter ==  'Orphan'?'active':''}}" href="{{ route('dashboard.pages.index','Orphan') }}">Orphan</a>
                          </li> --}}
                        </ul>
                        <!-- Left links -->
                      </div>
                      <!-- Collapsible wrapper -->
                  
                      <!-- Right elements -->
                      
                      <!-- Right elements -->
                    </div>
                    <!-- Container wrapper -->
                  </nav>
                  
                <div class="card-content card-shadow-nav m-4">
                   
                  <div class="card-body">

                    @if ($typeParameter == 'Home')
                      @include('dashboard.pages.types.home')                        
                    @endif
                    @if ($typeParameter == 'Services Type')
                      @include('dashboard.pages.types.slider_services')                        
                    @endif
                    @if ($typeParameter == 'Countries')
                      @include('dashboard.pages.types.countries')                        
                    @endif
                    @if ($typeParameter == 'About')
                      @include('dashboard.pages.types.about_us')                        
                    @endif
                    @if ($typeParameter == 'Fleet Category')
                      @include('dashboard.pages.types.fleet_category')                        
                    @endif
                    @if ($typeParameter == 'Fleet')
                      @include('dashboard.pages.types.fleet') 
                    @endif
                    @if ($typeParameter == 'Services')
                      @include('dashboard.pages.types.services') 
                    @endif
                    @if ($typeParameter == 'Faq')
                      @include('dashboard.pages.types.faq') 
                    @endif
                    @if ($typeParameter == 'Terms')
                      @include('dashboard.pages.types.terms') 
                    @endif
                    

                  </div>

                    {{-- <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th class="text-center w-25">Image</th>
                                <th class="pl-5">Title</th>
                                <th class="pl-5">Status</th>
                                <th class="text-right fix-padding-size-custom">Action</th>
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
                                    <td class="text-bold-500 text-center"><span style="background-color: {{ $status[$item->status]['color'] }}; !important" class="badge badge-success {{ $item->status == 'publish'?'success-badge':'danger-badge' }} text-uppercase">{{$status[$item->status]['name'] }}</span></td>
                                    <td class="text-bold-500 text-right">
                                      @permission('delete-web-page')
                                        <button onclick="deletePage('{{ $item->id }}')" class="btn btn-danger btn-sm custom-button delete-red"> <i data-feather="trash" width="20"></i> </button>
                                        @endpermission
                                        <a href="{{ route("dashboard.pages.edit",$item->id) }}" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit" width="20"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            
                            </tbody>
                            </table>
                           
                    </div>
                    <div class="float-right">
                        {{$data->links()}}
                    </div> --}}
    </div>
    
    <div id="deleteModal" class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete this page?</p>
          </div>
          <div class="modal-footer">
            <button onclick="closeButton()" type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancel</button>
            <button onclick="confirmDelete()" type="button" class="btn btn-danger">Delete</button>
          </div>
        </div>
      </div>
    </div> 



</div>

@endsection

@section('scripts')
<script>
  let current_item;
   function deletePage(id)
    {
        current_item = id;
        $('#deleteModal').modal('show');
    }
    function closeButton()
    {
        $('#deleteModal').modal('hide');
    }
    function confirmDelete()
    {
        let url = '{{ route("dashboard.pages.delete",":id") }}';
        url = url.replace(':id',current_item)
        $.ajax({
            url:url,
            type:'DELETE',
            data:{
                '_token':'{{ csrf_token() }}'
            },
            success:(res)=>{
                location.reload();
            },
            error:(err)=>{
                console.log('error: ',err)
            }
        })
    }

</script>
@endsection

