<div id="{{$itemId}}}" class="col-12 mt-4 draggable">
    <hr/>
    <div class="d-flex justify-content-between mb-3">
            <h3>
                <i class="fa fa-arrows-v h3"></i>
                Drag
            </h3>
            <div>
                <button onclick="deleteItem('{{ $itemId }}','{{ $itemId }}',true)" type="button" class="btn btn-danger">Delete</button>
            </div>
        </div>
            <div class="row flex-row-reverse cp-md-margin-bottom-80 cp-margin-bottom-20">
                @if (isset($isRight))
                    <div class="col-md-6 col-12 wow fadeIn {{ $isRight == false ? 'Left' : 'Right' }} animated" data-wow-duration=".5s" data-wow-delay=".3s">
                        <div class="about-img">
                            <img src="{{ $imageUrl }}" class="img-fluid" alt="${imageAlt}" loading="lazy">
                            <figcaption>{{$imageCaption}}</figcaption>
                        </div>
                    </div>
                @endif
                <div class="col-md-6 col-12 cp-margin-top-15">
                    <div class="content-area">
                        <div class="col-6 padding-0 padding-bottom-15 border-bottom-main-1">
                            <h2 class="section-title">{{ $titleInput }}</h2>
                        </div>
                        <div class="margin-top-25">
                            {{$descriptionInput}}
                        </div>
                    </div>
                </div>
                @if (isset($isRight))
                    <div class="col-md-6 col-12 wow fadeIn${isRight == false?'Left':'Right'} animated" data-wow-duration=".5s" data-wow-delay=".3s">
                        <div class="about-img">
                            <img src="{{ $imageUrl }}" class="img-fluid" alt="${imageAlt}" loading="lazy">
                            <figcaption>{{$imageCaption}}</figcaption>
                        </div>
                    </div>
                @endif
            </div>
</div>