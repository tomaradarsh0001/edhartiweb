@extends('layouts.app')

@section('title', 'Applicant Property History')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
<style>
    .flatpickr-disabled:not(.nextMonthDay,.taken-by-other){
        background: #6c757d40 !important;
        color: #0000004f !important;
    }
    .flatpickr-day:not(.nextMonthDay){
        background: #1c8b36; 
        color: white;
    }
    .holiday:not(.nextMonthDay){
        background: #dc3545ba !important;
        color:white !important;
    }
    .flatpickr-day.selected{
        background: #fb0 !important;
        color:white !important;
    }

    /* Legend ----*/

    .flatpickr-legend{
        display: flex;
        flex-wrap: wrap;
    }
     .legend-label{
        padding: 5px
    }
    .legend-item{
        width: 20px;
        height: 20px;
        margin-right: 8px;
    }
    .selected{
        background: #fb0 !important;
    }
    .available-date{
        background: #1c8b36;
    }

    /*css copied from public/style.ccs*/

    /* Appointment Date - Passed date */

    span.taken-by-other::before {
        position: absolute;
        content: '';
        top: 50%;
        left: 50%;
        width: 1px;
        height: 80%;
        background: black;
        transform: translate(-50%, -50%) rotate(45deg);
    }

    span.taken-by-other::after {
        position: absolute;
        content: '';
        top: 50%;
        left: 50%;
        width: 1px;
        height: 80%;
        background: black;
        transform: translate(-50%, -50%) rotate(-45deg);
    }

    span.taken-by-other {
        position: relative;
    }
</style>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Proof Reading Appointment</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Proof Reading Appointment</li>
                </ol>
            </nav>
        </div>
    </div>
    <hr>
    <div class="card">
        <div class="card-body">
            <form>
                <input type="hidden" name="appointmentId" id="appointmentId" value="{{$appointmentData->id}}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="appointmentDate">Appointment Date</label>
                        <input type="text" name="appointmentDate" id="appointmentDate" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-success" style="margin-top:20px" onclick="bookAppointment()">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('include.alerts.ajax-alert')
@endsection

@section('footerScript')

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function(){
        let calendarData = @json($calendarData);
        $("#appointmentDate").flatpickr({...calendarData, 
        onDayCreate: function(dObj, dStr, fp, dayElem) {
            // Dates to add custom class
            const holidays = calendarData.holidays;
            const bookedDates = calendarData.bookedDates
            
            // Get the date of the current cell
            const dateObj = new Date(dayElem.dateObj);
            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, "0");
            const day = String(dateObj.getDate()).padStart(2, "0");
            const localDate = `${year}-${month}-${day}`;

            // Add a custom class if the date matches
            if (holidays.includes(localDate)) {
                dayElem.classList.add("holiday");
                console.log(dayElem)
            }
            if (bookedDates.includes(localDate)) {
                dayElem.classList.add("taken-by-other");
            }
        },
        onReady: function(selectedDates, dateStr, instance){
            const legend = `
                <div class="flatpickr-legend">
                    <div class="d-flex legend-label"><span class="legend-item selected"></span> <label>Present appointment</label></div>
                    <div class="d-flex legend-label"><span class="legend-item available-date"></span> <label>Available</label></div>
                    <div class="d-flex legend-label"><span class="legend-item holiday"></span> <label>Holiday</label></div>
                    <div class="d-flex legend-label"><span class="legend-item taken-by-other"></span><label>Booked by others</label></div>
                    <div class="d-flex legend-label"><span class="legend-item flatpickr-disabled"></span><label>Disabled</label></div>
                </div>
            `;

            // Append to the flatpickr-calendar container
            const calendarContainer = instance.calendarContainer;
            $(calendarContainer).append(legend);
        }
     });
    });
    function bookAppointment(){
        var dateString = $('#appointmentDate').val();
        var appointmentId = $('#appointmentId').val();
        var appointmentDate = dateString;
        $.ajax({
            type: "POST",
            url: "{{route('applicant.bookAppointment')}}",
            data:{
                _token  :   "{{csrf_token()}}",
                appointmentId   :   appointmentId,
                appointmentDate :   appointmentDate
            },
            success:function(response){
                if(response.status){
                    showSuccess(response.message);
                }
                else{
                    showError(response.message);
                }
            },
            error:function(response){
                showError(response.resposnseJson.error)
            }
        })
    }
</script>
@endsection