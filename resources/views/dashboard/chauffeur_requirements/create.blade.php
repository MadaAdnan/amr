@extends('dashboard.layouts.index')


@section('content')
    {{-- ============================================== --}}
    {{-- ================== Header ==================== --}}
    {{-- ============================================== --}}


    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
            </div>

        </div>
        <div class="row p-2 card">
            <div class="row p-4">
                <h4 class="w-50 font-weight-bold">Add Requirments</h4>
                <div class="w-50 text-right">
                    <button type="button" class="btn btn-success plus"> <i class="fa fa-plus"></i> </button>
                </div>


                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            @if ($message = Session::get('error'))
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <div class="alert-icon contrast-alert">
                                        <i class="fa fa-times"></i>
                                    </div>
                                    <div class="alert-message">
                                        <span><strong>Error!</strong> {{ $message }}</span>
                                    </div>
                                </div>
                            @endif
                            {!! Session::forget('error') !!}
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <div class="alert-icon contrast-alert">
                                        <i class="fa fa-times"></i>
                                    </div>
                                    <div class="alert-message">
                                        <span><strong>Success!</strong> {{ $message }}</span>
                                    </div>
                                </div>
                            @endif
                            {!! Session::forget('success') !!}
                        </div>
                    </div>
                    <div class="col-md-12 justify-content-center">
                        <div class="">
                            <div class="">

                                <div class="card-body">
                                    <form method="POST" action="{{ route('dashboard.chauffeur_requirements.store') }}"
                                        id="myForm">
                                        @csrf

                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No.</th>
                                                    <th class="text-center">Title</th>
                                                    <th class="text-center">Type</th>
                                                    <th class="text-center">Required</th>
                                                    <th class="text-center">Action</th>

                                                </tr>
                                            </thead>
                                            <tbody id="document">
                                                @if (isset($chauffeurs) && count($chauffeurs) > 0)
                                                    <div id="countVar"
                                                        data-count=@if (isset($chauffeurs)) {{ count($chauffeurs) }}
                                                            @else
                                                            {{ count(old('document')) }} @endif>
                                                    </div>
                                                    @foreach ($chauffeurs as $key => $value)
                                                        <tr data-id={{ $key == 0 ? $key + 1 : $key }}>
                                                            <th>{{ ++$key }}</th>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control @error('document.' . $key . '.input_title') is-invalid @enderror"
                                                                    name="document[{{ $key }}][input_title]"
                                                                    value="{{ $value->title }}" placeholder="Input Title"
                                                                    autofocus>
                                                                @error('document.' . $key . '.input_title')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </td>

                                                            <td>
                                                                <select
                                                                    class="form-control @error('document.' . $key . '.type') is-invalid @enderror"
                                                                    name="document[{{ $key }}][type]" autofocus>
                                                                    <option value="text"
                                                                        {{ $value->input_type == 'text' ? 'selected' : '' }}>
                                                                        Text</option>
                                                                    <option value="image"
                                                                        {{ $value->input_type == 'image' ? 'selected' : '' }}>
                                                                        Image</option>
                                                                    <option value="file"
                                                                        {{ $value->input_type == 'file' ? 'selected' : '' }}>
                                                                        File</option>
                                                                </select>
                                                                @error('type')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="checkbox"
                                                                    class="text-center @error('document.' . $key . '.doc_required') is-invalid @enderror"
                                                                    name="document[{{ $key }}][doc_required]"
                                                                    value="1"
                                                                    @if ($value->required == 1) checked @endif
                                                                    autofocus>

                                                                @error('document ' . $key . ' doc_required')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </td>
                                                            <td class="text-center">


                                                                <button type="button" class="btn btn-success plus"> <i
                                                                        class="fa fa-plus"></i> </button>
                                                                <a href="{{ route('dashboard.chauffeur_requirements.delete', $value->id) }}"
                                                                    type="button" class="btn btn-danger  delete-item"
                                                                    data-id={{ $value->id }}> <i
                                                                        class="fa fa-minus"></i> </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                @if (old('document') != '')
                                                    <div id="countVar" data-count="{{ count(old('document')) }}"></div>
                                                    @foreach (old('document') as $key => $value)
                                                        <tr data-id={{ $key == 0 ? $key + 1 : $key }}>
                                                            <th>{{ $key == 0 ? $key + 1 : $key }}</th>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control @error('document.' . $key . '.input_title') is-invalid @enderror"
                                                                    name="document[{{ $key }}][input_title]"
                                                                    value="{{ old('document.' . $key . '.input_title') }}"
                                                                    placeholder="Input Title" autofocus>
                                                                @error('document.' . $key . '.input_title')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </td>

                                                            <td>
                                                                <select
                                                                    class="form-control @error('document.' . $key . '.type') is-invalid @enderror"
                                                                    name="document[{{ $key }}][type]" autofocus>
                                                                    <option value="text"
                                                                        {{ old('document.' . $key . '.type') == 'text' ? 'selected' : '' }}>
                                                                        Text</option>
                                                                    <option value="image"
                                                                        {{ old('document.' . $key . '.type') == 'image' ? 'selected' : '' }}>
                                                                        Image</option>
                                                                    <option value="file"
                                                                        {{ old('document.' . $key . '.type') == 'file' ? 'selected' : '' }}>
                                                                        File</option>
                                                                </select>
                                                                @error('type')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="checkbox"
                                                                    class=" text-center @error('document.' . $key . '.doc_required') is-invalid @enderror"
                                                                    name="document[{{ $key }}][doc_required]"
                                                                    value="{{ old('document.$key.doc_required') }}"
                                                                    autofocus>
                                                                @error('document ' . $key . ' doc_required')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($key == 0)
                                                                    <button type="button" class="btn btn-success plus"> <i
                                                                            class="fa fa-plus"></i> </button>
                                                                @else
                                                                    <button type="button" class="btn btn-success plus"> <i
                                                                            class="fa fa-plus"></i> </button>
                                                                    <button type="button" class="btn btn-danger minus"
                                                                        data-id={{ $value->id }}> <i
                                                                            class="fa fa-minus"></i> </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                {{--  @else --}}
                                                <div id="countVar" data-count="0"></div>
                                                {{-- <tr data-id="1"> --}}
                                                {{-- 
                                                        <th scope="row">1</th>
                                                        <td>
                                                            <input type="text" class="form-control text-center"
                                                                name="document[1][input_title]" placeholder="Input Title"
                                                                autofocus>
                                                            @error('document.1.input_title')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <select class="form-control text-center @error('document.1.type') is-invalid @enderror"
                                                                name="document[1][type]" autofocus>
                                                                <option value="text" {{ old('document.1.type') == 'text' ? 'selected' : '' }}>Text</option>
                                                                <option value="image" {{ old('document.1.type') == 'image' ? 'selected' : '' }}>Image</option>
                                                                <option value="file" {{ old('document.1.type') == 'file' ? 'selected' : '' }}>File</option>
                                                            </select>
                                                            @error('document.1.type')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </td>
                                                                                                                
                                                        <td>
                                                            <input type="checkbox" class="text-center"
                                                                name="document[1][type]" placeholder="IP" autofocus>
                                                            @error('document.1.type')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </td>

                                                        
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-success plus"> <i
                                                                    class="fa fa-plus"></i> </button>
                                                        </td>
                                                     --}}
                                                {{-- </tr> --}}
                                                {{-- @endif --}}
                                            </tbody>
                                        </table>
                                        <div class="form-group row mb-0">
                                            <div class="col-md-2 text-center">
                                                <button type="submit" class="btn btn-primary btn-block "
                                                    id="submitButton">
                                                    {{ __('SUBMIT') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


    </div>
    <div id="createTagModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Tag</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="titleInput">Title</label>
                        <input type="text" class="form-control" id="titleInputTag" placeholder="Enter title">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="actionButton" onclick="createTagRequest()" type="button"
                        class="btn btn-primary create-hover">Create</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="createCategorieModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="titleInput">Title</label>
                        <input type="text" class="form-control" id="titleInputCategory" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label for="slugInput">Slug</label>
                        <p>https:{{ env('APP_URL') }}/</p>
                        <input type="text" class="form-control" id="slugInputCategory" placeholder="Enter slug">

                    </div>
                </div>
                <div class="modal-footer">
                    <button id="actionButton" onclick="createCategoryRequest()" type="button"
                        class="btn btn-primary create-hover">Create</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="rejectNoteModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Reject</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <label for="rejectNote">Please add reject note:</label>
                    <textarea class="form-control" id="reject_note" rows="5"></textarea>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button onclick="sendRejectToBlogger()" type="button" class="btn btn-primary">Send</button>
                </div>

            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="chat-content">
                <ul>
                    
                </ul>
            </div>
            <div class="chat-section">
                <div class="chat-box">
                    <div class="chat-input bg-green" id='chatInput' contenteditable=""></div>
                </div>
            </div>
        </div>
    </div>
    









@endsection


@section('scripts')
<script>
    
    $(function(){
        let ip_address = '127.0.0.1';
        let socket_port ='3000';
        let socket = io(ip_address+':'+socket_port);
        let chatInput = $('#chatInput');
        chatInput.keypress(function(e){
            let message = $(this).html();
             console.log(message);
             if(e.which === 13 && !e.shiftKey){
                socket.emit('sendChatToServer',message);//this helps to send message to server
                chatInput.html('');
                return false;
             }
        });
    socket.on('sendChatToCLient',(message)=>{
        $('.chat-content ul').append(`<li class="bg-success text-white">${message}</li>`);
    })
    
    });

</script>
    <script>
        $("#AddForm").validate({
            rules: {
                question: {
                    required: true
                },
                answer: {
                    required: true
                },
                type: {
                    required: true
                },
            }
        });


        <script src = "https://code.jquery.com/jquery-3.4.1.min.js" ></script>
    <script type="text/javascript">
        $('body').on('click', '.plus', function() {



            i = $('#document tr').length;
            // var i = $('#document tr:last').data('id');
            i = i + 1;
            $('#document').append(
                '<tr data-id="' + i + '">\
                                                        <th >' + i +
                '</th>\
                                                        <td>\
                                                            <input placeholder="Input Title" class="form-control text-center" name="document[' +
                i +
                '][input_title]" type="text">\
                                                        </td>\
                                                        <td class="text-center"class="text-center">\
                                                            <select class="form-control" name="document[' + i + '][type]">\
                                                                <option class="text-center" value="text">Text</option>\
                                                                <option class="text-center" value="image">Image</option>\
                                                                <option class="text-center" value="file">File</option>\
                                                                     </select>\
                                                        </td>\
                                                        <td class="text-center">\
                                                            <input  class="" name="document[' + i + '][doc_required]" type="checkbox">\
                                                        </td>\
                                                        <td class="text-center">\
                                                            <button type="button" class="btn btn-success plus"> <i class="fa fa-plus"></i> </button>\
                                                            <button type="button" class="btn btn-danger minus"> <i class="fa fa-minus"></i> </button>\
                                                        </td>\
                                                    </tr>');

        });
        $('body').on('click', '.minus', function() {
            $(this).closest('tr').remove();


        });
    </script>
@endsection
