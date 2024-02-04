@php
    $typeQuery = request()->query('faqType');
@endphp

<div class="row">
  <div class="text-left col">
    <select  id="typeSelect" class="form-select mb-3" aria-label="example" onchange="handleSelectChange(event)">
      <option {{ $typeQuery == 'General'?'selected':'' }} value="General">General</option>
      <option {{ $typeQuery == 'Professional Chauffeur'?'selected':'' }} value="Professional Chauffeur">Professional Chauffeur</option>
      <option {{ $typeQuery == 'Cancellations'?'selected':'' }} value="Cancellations">Cancellations & Refunds</option>
    </select>  
  </div>
  <div class="text-right col">
      <a href="{{ route('dashboard.faqs.create') }}" class="btn btn-primary">Create</a>
  </div>
</div>
<hr/>
<div class="row" id="basic-table">
  <div class="col-12">
      
      <div class="card-content">
          <div class="row">
             
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table">
                  <thead>
                      <tr>
                      <th class="text-center" >Drag</th>
                      <th class="text-center" >Question</th>
                      <th class="text-center" >Answer</th>
                      <th class="text-center" >Type</th>
                      <th class="text-center" >Action</th>
                      </tr>
                  </thead>
                  <tbody id="itemsTable">
                      @foreach ($services as $item)
                      <tr id="{{ $item->id }}" class="draggable">
                          <td class="text-bold-500 text-center"> <i class="fa fa-arrows-v"></i>  </td>
                          <td class="text-bold-500 text-center">{{ $item->question}}</td>
                          <td class="text-bold-500 text-center">{{ $item->answer}}</td>
                          <td class="text-bold-500 text-center">{{ $item->type}}</td>
                          <td class="text-bold-500 text-center">
                            <button onclick="deleteServices('{{ $item->id }}')" class="btn btn-danger btn-sm custom-button delete-red mb-2 mt-2"> <i data-feather="trash" width="20"></i> </button>
                              <a href="{{ route('dashboard.faqs.edit',$item->id) }}" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit" width="20"></i></a>
                          </td>
                      </tr>
                      @endforeach
                  
                  </tbody>
                  </table>
                 
          </div>
        
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
          <p>Are you sure you want to delete this faq?</p>
        </div>
        <div class="modal-footer">
          <button onclick="deleteRequest()" type="button" class="btn btn-danger">Delete</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.13.0/Sortable.min.js"></script>

<script>
    let current_id = null;
    const sortRequest = '{{ route("dashboard.faqs.sort") }}';
    function deleteServices(id)
    {
        current_id = id;
        $('#deleteServices').modal('show')
    }

    function deleteRequest()
    {
        let request = '{{ route("dashboard.faqs.delete",":id") }}';
        request = request.replace(':id',current_id);
            $.ajax({
            url:request,
            type:'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
            success:(res)=>{
                location.reload();
            },
            error:(err)=>{
              location.reload();
            }
        });
    }

    var dropZone = document.getElementById('itemsTable');
        var sortable = new Sortable(dropZone, {
        draggable: '.draggable',
        animation: 150,
        onEnd: function(evt) {
            let sortedArray = [];
            var sortedItems = Array.from(dropZone.children);
            var currentItem = sortedItems[evt.newIndex];
            var itemId = currentItem.id;
            sortedItems.forEach((element,index) => {
              const itemId = sortedItems[index].id
              sortedArray.push({
                'id':itemId,
                'sort':index+1
              });
            });

            $.ajax({
              url:sortRequest,
              type:'POST',
              headers:{
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
              },
              data:{
                'items':sortedArray
              },
              success:(res)=>{
                console.log('response: ',res);
                Swal.fire(
                  'Data was Saved',
                  '',
                  'success'
                )
              },
              error:(err)=>{
                Swal.fire(
                  'Someting went weong updating the sort.',
                  '',
                  'error'
                )
              }
            });
        }
        });

        function handleSelectChange(e) {
          var selectedValue = document.getElementById(e.target.id).value;
          let request = '{{ route("dashboard.pages.index","Faq") }}'+'?faqType='+selectedValue;
          window.location.href = request;
      }

</script>