@extends('dashboard.layouts.index')

@section('content')

<div class="page-title">
    <div>
       
        <div class="row p-4">
            <h1 class="mb-4">
                Dashboard
            </h1>
            <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0 card-shadow card1">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0 custom-title-card card-custom-text ">Users</h5>
                        <span class="h6 mb-0 card-custom-text">{{ number_format((float)$googleData->users, 2, '.', '') }} </span>
                      </div>

                      <div class="col">
                       
                      </div>
                     
                    </div>
                   
                  </div>
                </div>
              </div>
            <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0 card-shadow card2">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0 custom-title-card card-custom-text ">Sessions</h5>
                        <span class="h6 mb-0 card-custom-text">{{ number_format((float)$googleData->sessions, 2, '.', '') }}</span>
                      </div>
                     
                    </div>
                    
                  </div>
                </div>
              </div>
            <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0 card-shadow card3">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0 custom-title-card card-custom-text ">Session Duration</h5>
                        <span class="h6 mb-0 card-custom-text">{{ number_format((float)$googleData->sessionDuration, 2, '.', '') }} </span>
                      </div>
                  
                    </div>
                    
                  </div>
                </div>
              </div>
            <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0 card-shadow card4">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0 custom-title-card card-custom-text ">Bounce Rate</h5>
                        <span class="h6 mb-0 card-custom-text">{{   number_format((float)$googleData->bounceRate, 2, '.', '') }}%</span>
                      </div>
                      
                    </div>
                   
                  </div>
                </div>
              </div>


            
        </div>
    </div>
    <div class="card p-5">

        <h3>
            Charts
        </h3>

        <div class="row">
            <div class="col-6">
                <div style="width: 100%;">
                    <canvas id="lineChart"></canvas>
                </div>        
            </div>
            <div class="col-6 d-flex justify-content-end">
                <div style="width: 260px">
                    <canvas id="pieChart" width="260px" height="260px"></canvas>
                </div>        
            </div>
        </div>



        <h3 class="mb-5">Calender</h3>
        <div id='calendar'></div>
    </div>



    <div class="modal fade shadow-none" id="blogModal" tabindex="-1" role="dialog" aria-labelledby="blogModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="blogModalLabel">Blog Title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p class="text-muted">Published on May 29, 2023</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    
    <style>
    .swal2-container.swal2-center>.swal2-popup {
    grid-column: 2;
    grid-row: 2;
    align-self: center;
    justify-self: center;
    box-shadow: 4px 5px 12px 6px #b9aeae;
  }
      .swal2-container.swal2-backdrop-show, .swal2-container.swal2-noanimation {
            background: none !important;
      }


    </style>
        
                        

</div>
<!-- Basic Tables start -->


<!-- Responsive tables end -->
@endsection


@section('scripts')
<script>


document.addEventListener('DOMContentLoaded', function () {
  
    /** Full calender ***/
        var calendarEl = document.getElementById('calendar');
        let blogs = JSON.parse('{!! json_encode($blogs) !!}');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            eventDisplay: 'block',
            events: blogs,
            firstDay: 1,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'en',
            editable: false,
            droppable: false,
            eventClick: function (el) {

              /** Time formate with the modal creation **/
              const event = el.event
              let day = event.start.getDate();
              let month = event.start.getMonth();
              let year = event.start.getFullYear();
              let hour = event.start.getHours();
              let min = event.start.getMinutes();
              let dateFormate = day+'/'+month+'/'+year;


              var dt = new Date(event.start);
              var hours = dt.getHours() ;
              var AmOrPm = hours >= 12 ? 'PM' : 'AM';
              hours = (hours % 12) || 12;
              var minutes = dt.getMinutes() ;
              var finalTime = "Time  - " + hours + ":" + minutes + " " + AmOrPm; 


              Swal.fire({
                title: event.title,
                html:`Date: ${dateFormate} </br> Publish Time: ${finalTime}`,
                showDenyButton: false,
                showCancelButton: false,
                confirmButtonColor: '#ae2227',
                confirmButtonText: 'Close',
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
               
              })               
              
            }
        });

        calendar.render();
    /** Chart js **/
        var ctx = document.getElementById('lineChart').getContext('2d');
            const data = {
            labels: @json($viewsLastWeekDate),
                datasets: [{
                    label: 'Views',
                    data:@json($viewsLastWeekVisitor),
                    fill: true,
                    borderColor: '#262221',
                    fillColor: 'red',
                    tension: 0.1
                }]
            };
            const config = {
                type: 'line',
                data: data,
            };

            new Chart(ctx,config);
            var ctxPie = document.getElementById('pieChart').getContext('2d');
            const dataForPie = {
                labels:@json($topBrowsersNames),
                datasets: [{
                    label: 'User',
                    data: @json($topBrowsersSessions),
                    hoverOffset: 4
                }]
            };

            const configForPie = {
                type: 'doughnut',
                data: dataForPie,
            };

            new Chart(ctxPie,configForPie);

    });
  </script>

@endsection