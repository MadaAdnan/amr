<div class="text-right">
    <a href="{{ route('dashboard.sliderServices.create') }}" class="btn btn-primary">Create</a>
</div>
<hr/>
<div class="row d-flex justify-content-center">
    @foreach ($services as $item)
        <div class="col-4 p-2">
            <div class="card">
                <img class="thumb-nail" src="{{ $item->image }}" onerror="this.onerror=null;this.src='{{ asset('no-image.png') }}';" alt="Card image cap">
                <div class="card-body">
                <h5 class="card-title">{{ $item->title }}</h5>
                <p class="card-text">{{ $item->caption ?? 'No Data' }}</p>
                <div class="text-center">
                    <a target="_blank" href="{{ $item->link }}" class="btn btn-primary">Go somewhere</a>
                    <button onclick="deleteServices('{{ $item->id }}')" class="btn btn-danger">Delete</button>
                    <a  href="{{ route('dashboard.sliderServices.edit',$item->id) }}" class="btn btn-success">Edit</a>
                </div>
                </div>
            </div>          
        </div>        
    @endforeach
</div>

<div id="deleteServices" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this ?</p>
        </div>
        <div class="modal-footer">
          <button onclick="deleteRequest()" type="button" class="btn btn-danger">Delete</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  

<script>
    let current_id = null;
    function deleteServices(id)
    {
        current_id = id;
        $('#deleteServices').modal('show')
    }

    function deleteRequest()
    {
        let request = '{{ route("dashboard.sliderServices.delete",":id") }}';
        request = request.replace(':id',current_id);
            $.ajax({
            url:request,
            type:'POST',
            data:{
                '_token':'{{ csrf_token() }}'
            },
            success:(res)=>{
                location.reload();
            },
            error:(err)=>{

            }
        });
    }
</script>