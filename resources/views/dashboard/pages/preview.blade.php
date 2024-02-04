@extends('layouts.app')
@section('content')
<style>
    header#header {
        display: none;
    }
    .widget.cp-md-margin-bottom-80 {
        display: none;
    }
    .cta-bar.stick {
        display: none;
    }
    .comment-form {
        display: none;
    }
    h4.post-title {
        color: #000;
    }
    /* section.banner-section {
        display: none;
    } */
    .reply-comment-input {
    box-shadow: 0 0 10px rgb(197 31 31 / 90%) !important;
    }
    p.text-success {
        color: #13a311;
    }

</style>
<div class="white-section section-block">
    <div class="cp-md-margin-bottom-80 cp-md-margin-top-80">
        <div class="cta-bar">
            <ul>
                <li><a href="#"><i class="fa fa-facebook"></i> Share on facebook</a></li>
                <li><a href="#"><i class="fa fa-whatsapp"></i> Share on Whatsapp</a></li>
                <li><a href="#"><i class="fa fa-instagram"></i> Share on Instagram</a></li>
                <li><a href="#"><i class="fa fa-twitter"></i> Share on Twitter</a></li>
                <li><a href="/test" class="back-to-top" title="Back to top"><i class="fa fa-angle-up"></i> Back to top</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-lg-3 sidebar-blog">
                <div class="widget cp-md-margin-bottom-80">
                    <h4 class="widget-title">Similar Posts</h4>
                    <div class="sidebar-recent-post">
                        <ul>
                            <li>
                                <div class="recent-post-thumb">
                                    <a href="blog-details.html">
                                        <img src="assets/img/post/post4.jpg" alt="">
                                    </a>
                                </div>
                                <div class="recent-post-content">
                                    <h4>
                                        <a href="blog-details.html">Quality and the New Perspective</a>
                                    </h4>
                                    <div class="widget-date">12 June 2018</div>
                                </div>
                            </li>
                            <li>
                                <div class="recent-post-thumb">
                                    <a href="blog-details.html">
                                        <img src="assets/img/post/post2.jpg" alt="">
                                    </a>
                                </div>
                                <div class="recent-post-content">
                                    <h4>
                                        <a href="blog-details.html">Quality and the New Perspective</a>
                                    </h4>
                                    <div class="widget-date">12 June 2018</div>
                                </div>
                            </li>
                            <li>
                                <div class="recent-post-thumb">
                                    <a href="blog-details.html">
                                        <img src="assets/img/post/post3.jpg" alt="">
                                    </a>
                                </div>
                                <div class="recent-post-content">
                                    <h4>
                                        <a href="blog-details.html">Quality and the New Perspective</a>
                                    </h4>
                                    <div class="widget-date">12 June 2018</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="widget stick">
                    <a href="{{ route('dashboard.pages.edit',$data->id) }}" class="widget-title">Back to Edit</a>
                </div>
                <div class="widget stick">
                        <h4 class="widget-title">Table Content</h4>
                        <div class="sidebar-recent-post">
                            <ul id="table-content-list">
                            </ul>
                        </div>
                    </div>
            </div>
            <div class="col-lg-7 blog-single">
                <article class="post-entry">
                    <div class="post-image wow fadeIn animated">
                        <a href="#">
                           
                            <img width="600" height="430" src="{{ $data->avatar }}" onerror="this.onerror=null;this.src='{{ asset('no-image.png') }}';">
                            <span class="filter-grayscale"></span>
                        </a>
                    </div>
                    <div id="section1" class="post-content">
                        <h4 class="post-title">{{ $data->title??'No title avalibale' }}</h4>
                        <ul class="post-tags">
                            <li>{{ $data->date ? $data->date->format('Y-m-d') : 'No date avalibale' }}</li>
                            <li class="pull-right"><a href="#">Admin</a></li>
                        </ul>
                        <hr/>
                        <div id="blog-content" class="description">
                            @if ($data->content)
                                {!! json_decode($data->content) !!}
                                @else 
                                No Content
                            @endif
                        </div>
                        <div class="tagcloud">
                            <span>Tags:</span>
                            @foreach ($data->keywords as $item)
                                <a href="#">{{ $item->title }}</a>
                            @endforeach
                        </div>
                    </div>
                </article>

            
              
            </div>
        </div>
    </div>
</div>

<script>
    var contentDiv = document.getElementById("blog-content");
    var h2Elements = contentDiv.getElementsByTagName("h2");
    for (var i = 0; i < h2Elements.length; i++) {
        h2Elements[i].id = h2Elements[i].textContent;
        $('#table-content-list').append(`<li><a href="#${h2Elements[i].textContent}"> ${h2Elements[i].textContent.replace(/<[^>]+>/g, '')}</a><li/>`)
    }
    var contentTableList = document.getElementById("table-content-list");
    var liElements = contentTableList.getElementsByTagName("li");
    for (var i = liElements.length - 1; i >= 0; i--) {
        var li = liElements[i];
        
        // Check if the <li> element is empty (contains no child nodes or only whitespace)
        if (!li.firstChild || li.firstChild.nodeType === Node.TEXT_NODE && li.firstChild.textContent.trim() === '') {
            // Remove the empty <li> element
            li.parentNode.removeChild(li);
        }
    }

    function showReply(id)
    {
        let comment_id = '#comment-reply-form-'+id;
       
        $(comment_id).css('animation-name','fadeIn');
        $(comment_id).css('webkit-animation-name','fadeIn');
        $(comment_id).css('animation-duration','0.2s');
        $(comment_id).css('webkit-animation-duration','ease-in-out');
        $(comment_id).css('webkit-animation-timing-function','ease-in-out');
        $(comment_id).css('visibility','visible !important');
        $(comment_id).toggle();
    }

    function commentAction(status,comment_id)
    {
        let request = '{{ route("dashboard.comments.commentAction",":id") }}'
        request = request.replace(':id',comment_id);
        $.ajax({
            url:request,
            type:'POST',
            data:{
                '_token':'{{ csrf_token() }}',
                'status':status
            },
            success:(res)=>{
                if(res.status == 201)
                {
                    window.location.hash = '#comment-num-'+res.data.id
                    location.reload();
                }
                else
                {
                    Swal.fire({
                        title: 'Something wrong!',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                }
            },
            error:(err)=>{
                console.log('error sending the reply');
            }
        });
    }


    function send_reply(id)
    {
        let InputId = 'add-reply-input-'+id;
        let text = document.getElementById(InputId).value;
        if(text == '')
        {
            alert('no data')
        };
        
        let request = '{{ route("dashboard.comments.reply",":id") }}';
        request = request.replace(":id",id)
        $.ajax({
            url:request,
            type:'POST',
            data:{
                '_token':'{{ csrf_token() }}',
                'content':text,
                'comment_id':id
            },
            success:(res)=>{
                let url = '{{ route("dashboard.comments.preview",$data->id) }}'
              
                window.location.hash = '#comment-num-'+res.data.id
                location.reload();

             
            },
            error:(err)=>{
                console.log('error sending the reply');
            }
        });

    }



</script>

@endsection