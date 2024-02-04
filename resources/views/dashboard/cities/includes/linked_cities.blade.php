<h5 class="font-weight-bold">Cities</h5>

                    
                    <div class="table-responsive mb-5">
                        <table class="table">
                        <thead>
                            <tr>
                            <th class="text-center" >Title</th>
                            <th class="text-center" >Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->parent_city()->get() as $item)
                            <tr>
                                <td class="text-bold-500 text-center">{{ $item->title??'No Data' }}</td>
                                <td class="text-bold-500 text-center">
                                    <a href="{{ route('dashboard.cities.edit',$item->id) }}" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="eye" width="20"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        
                        </tbody>
                        </table>
                    </div>