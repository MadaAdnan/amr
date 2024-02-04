@extends('dashboard.layouts.index')

@section('content')
@php
    $statusQuery =  request()->get('status') ;
    $query =  request()->query('query') ;
    $route = Route::currentRouteName();
    $id;
    $myRole = Auth::user()->roles[0]->name;

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
                    <h4 class="w-50 font-weight-bold">Users</h4>
                    <div class="w-50 text-right">
                    </div>
                </div>
                <div class="card-content">
                    
                    <div class="card-body">
                       

                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th class="text-center" >Name</th>
                                <th class="text-center" >email</th>
                                <th class="text-center" >phone</th>
                                <th class="text-center" >Role</th>
                                <th class="text-center" >Verifide</th>
                                <th class="text-center" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $item)
                                <tr>
                                    <td class="text-bold-500 text-center">{{ $item->full_name??'No Data' }} {{ $item->id == Auth::user()->id ? '(You)':'' }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->email??'No Data' }} {{ $item->id == Auth::user()->id ? '(You)':'' }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->phone }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->roles[0]->name??'No Data' }}</td>
                                    <td class="text-bold-500 text-center">{{ isset($item->email_verified_at)?'Verifide':'Un-verified' }}</td>
                                    
                                    <td class="text-bold-500 text-center">
                                        @if ($item->roles[0]->name != 'Seo-admin'||$myRole == 'Super-admin')
                                            @if ($item->roles[0]->name != 'Blogger'&&$item->roles[0]->name != 'super_admin'&&Auth::user()->id != $item->id)
                                                <button onclick="delete_user_modal('{{ $item->id }}')" class="btn btn-danger btn-sm custom-button delete-red mb-2 mt-2"> <i data-feather="trash" width="20"></i> </button>                                                                                           
                                            @endif
                                                                             
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            
                            </tbody>
                            </table>
                           
                    </div>
                    <div class="float-right">
                        {{$customers->links()}}
                    </div>
    </div>
    
</div>

@endsection
