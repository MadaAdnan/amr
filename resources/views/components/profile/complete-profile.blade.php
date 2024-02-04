
<div class="bg-light rounded p-3">
    <!-- Progress bar -->
    <div class="overflow-hidden">
        <h6>Complete Your Profile</h6>
        <div class="progress progress-sm bg-{{ $percentage == '0%' ? 'danger' : 'success' }} bg-opacity-10">
            <div class="progress-bar bg-{{ $percentage == '0%' ? 'danger' : 'success' }} aos" role="progressbar" data-aos="slide-right" data-aos-delay="200" data-aos-duration="1000" data-aos-easing="ease-in-out" style="width: {{ $percentage }}" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
            <span class="progress-percent-simple h6 fw-light ms-auto">{{ $percentage }}</span>
        </div>
    </div>
    <p class="mb-0">Get the best out of booking by adding the remaining details!</p>
    </div>
    <!-- Content -->
    <div class="bg-body rounded p-3 mt-3">
        <ul class="list-inline hstack flex-wrap gap-2 justify-content-between mb-0">
            <li class="list-inline-item h6 fw-normal mb-0"><a href="#updateEmailSectionDivider"><i class="{{ $isEmailVerified == true ? 'bi bi-check-circle-fill text-success' : 'bi bi-plus-circle-fill text-primary' }}  me-2"></i>Verified Email</a></li>
            <li class="list-inline-item h6 fw-normal mb-0"><a href="#updatePhoneNumberSectionDivider" class="text-black"><i class="{{ $isPhoneNumberVerified == true ? 'bi bi-check-circle-fill text-success' : 'bi bi-plus-circle-fill text-primary' }} me-2"></i>Verified Mobile Number</a></li>
        </ul>
    </div>
</div>