@extends('dashboard.layouts.index')

@section('content')
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
  rel="stylesheet"
/>
<!-- Google Fonts -->
<link
  href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
  rel="stylesheet"
/>
<!-- MDB -->

    <div class="card p-3">


        <table class="table">
            <thead>
              <tr>
                <th scope="col">Country</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                @foreach ($data as $item)
                <th
                    class="btn btn-primary text-left"
                    type="button"
                    data-mdb-toggle="collapse"
                    data-mdb-target="#countryCollapes-num-{{$item->id}}"
                    aria-expanded="false"
                    aria-controls="countryCollapes-num-{{$item->id}}"
                    style="color: #000; width:100%;"
                >
                    <div class="d-flex justify-content-between">
                        {{ $item->name }}
                        <i class="fa fa-eye"></i>
                    </div>

                    <ul id="countryCollapes-num-{{$item->id}}" class="list-group list-group-light collapse mt-3 text-left">
                        @foreach ($item->states as $state)
                            <li 
                            class="list-group-item border-0 p-2"
                            data-mdb-toggle="collapse"
                            data-mdb-target="#stateCollapesNum-{{ $state->id }}"
                            aria-expanded="false"
                            aria-controls="stateCollapesNum-{{ $state->id }}"
                            >
                            {{ $state->name }}
                                <div class="collapse mt-3" id="stateCollapesNum-{{ $state->id }}">
                                    @foreach ($state->cities as $city)
                                        <p>{{ $city->title }}</p>
                                    @endforeach
                                </div>
                            </li>
                            
                        @endforeach
                    </ul>
                </th>
                @endforeach
               
                
              </tr>
            
            </tbody>
          </table>



       
                  
                <!-- Collapsed content -->
                   
    
    </div>


    <script
  type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.js"
></script>

<script>
    const collapseElementList = [].slice.call(document.querySelectorAll('.collapse'))
        const collapseList = collapseElementList.map((collapseEl) => {
        return new mdb.Collapse(collapseEl, {
            toggle: false,
        });
    });

</script>

@endsection

