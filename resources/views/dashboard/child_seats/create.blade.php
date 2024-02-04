@extends('dashboard.layouts.index')


@section('content')
    {{-- ============================================== --}}
    {{-- ================== Header ==================== --}}
    {{-- ============================================== --}}

    <style>

    </style>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
            </div>

        </div>
        <div class="row p-2 card">
            <div class="row p-4">
                <h4 class="w-50 font-weight-bold">Add Child seat</h4>
                <div class="w-50 text-right">
                </div>
            </div>

            <form enctype="multipart/form-data" action="{{ route('dashboard.childSeats.store') }}" id="AddForm"
                method="post">

                @csrf



                {{--  Title --}}
                <div class="row  flex-row">
                    <div class="col-md-6">

                        <div class="form-group  p-3">
                            <label for="title">Title
                                <strong class="text-danger">
                                    *
                                    @error('title')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ old('title') }}">
                        </div>
                    </div>
                    {{-- Price --}}
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="price"> Price
                                <strong class="text-danger">
                                    *
                                    @error('price')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="number" id="price" class="form-control" name="price"
                                value="{{ old('price') }}" min="1">
                        </div>
                        </div>

                    {{-- Status --}}
                    <div class="row  flex-row">

                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="status"> Status
                                <strong class="text-danger">
                                    *
                                    @error('status')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <select id="status" class="form-select" name='status'>
                                <option value="">Select</option>
                                <option value="Published">Publish</option>
                                <option value="Disabled">Disable</option>
                            </select>
                        </div>
                    </div>
                </div>

                </div>


                


                <div class="col-md-12">
                    <div class="form-group  p-3">
                        <label for="description">Discription
                            <strong class="text-danger">
                                *
                                @error('description')
                                    -
                                    {{ $message }}
                                @enderror
                            </strong>
                        </label>
                        <textarea id="description" name="description" class="form-control" id="w3review" name="w3review" rows="4"
                            cols="50">{{ old('description') }}</textarea>


                    </div>
                </div>






                <div class="form-group  col-4 p-3">

                    <button type="submit" onclick="sendActionToBlogger('Accepted')"
                        class="btn btn-primary remove-border w-50">
                        create
                    </button>
                </div>
            </form>
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
@endsection

@section('scripts')
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
    </script>
@endsection
