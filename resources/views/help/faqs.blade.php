@extends('layouts.app')
@section('pageTitle')<title> Frequently Asked Questions - Lavish Ride</title> @endsection

@section('page_name')
    <h1 class="text-white">FAQs</h1>
@endsection

@section('seo')
<meta name="title" content="Frequently Asked Questions - Lavish Ride">
<meta name="description" content="Get answers to common questions about Lavish Ride's chauffeur services. Find information about reservations, rates, vehicle types, and airport transfers.">
<meta name="keywords" content="frequently asked questions, FAQs, reservations, rates, vehicle types, airport transfers, chauffeur service information">
<link rel="canonical" href="{{ route('faqs') }}">

<meta property="og:title" content="Frequently Asked Questions - Lavish Ride" />
<meta property="og:description" content="Get answers to common questions about Lavish Ride's chauffeur services. Find information about reservations, rates, vehicle types, and airport transfers.">
<meta property="og:image" content="{{ asset("assets_new/img/LR-LogoSchema.png") }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('faqs') }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Frequently Asked Questions - Lavish Ride" />
<meta name="twitter:description" content="Get answers to common questions about Lavish Ride's chauffeur services. Find information about reservations, rates, vehicle types, and airport transfers.">
<meta name="twitter:image" content="{{ asset("assets_new/img/LR-LogoSchema.png") }}" />

@endsection


@section('content')
<style>
    .section-bg-coustom{
        background-image: url('{{ asset("assets_new/Lavish-Ride-General.webp") }}') !important;
    }
</style>

    <!-- BEGIN: Terms And Conditions -->
    <section class="white-section section-block">
        <!-- specs-area -->
        <section class="light-section section-block">

            <div class="section-space"></div>
            <div class="container">

                <div aria-multiselectable="true" class="panel-group" id="accordion_reg" role="tablist">

                    <!-- Tabs Container -->
                    <div class="tabs primary">
                        <div class="tabs-header">
                            <ul>
                                <li class="active"><a title="tab-1" href="#tab-1" data-toggle="tab">General</a></li>
                                <li><a title="Professional Chauffeur" href="#tab-2" data-toggle="tab">Professional Chauffeur</a></li>
                                <li><a title="Cancellations & Refunds" href="#tab-3" data-toggle="tab">Cancellations & Refunds</a></li>
                            </ul>
                            <div class="tab-hover"></div>
                            <nav class="tabs-nav">
                                <span class="tab-prev"><i class="icon-material-outline-keyboard-arrow-left"></i></span>
                                <span class="tab-next"><i class="icon-material-outline-keyboard-arrow-right"></i></span>
                            </nav>
                        </div>


                        <!-- Tabs Container / End -->
                        <!-- Tab Content -->
                        <div class="tabs-content">
                            <div class="tab-pane fade in active" id="tab-1">

                                <?php $counter = 0; ?>
                                @foreach ($generalContent as $faqs)
                                    <div class="panel panel-default">
                                        <div class="panel-heading" id="General" role="tab">
                                            <h4 class="panel-title">
                                                <a title="{{ $faqs['title'] }}" aria-controls="collapseOne_reg" aria-expanded="true" class="collapsed"
                                                    data-parent="#accordion_reg" data-toggle="collapse"
                                                    href="#General_view_{{ $counter }}" role="button">
                                                    {{ $faqs['title'] }}
                                                </a>
                                            </h4>
                                        </div>
                                        <div aria-labelledby="General" class="panel-collapse collapse"
                                            id="General_view_{{ $counter }}" role="tabpanel" aria-expanded="true"
                                            style="height: 0px;">
                                            <table class="specs-table">
                                                <tbody>
                                                    <td>{!! $faqs['body'] !!}</td>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php $counter++; ?>
                                @endforeach


                            </div>
                            <div class="tab-pane fade" id="tab-2">


                                <?php $counter = 0; ?>
                                @foreach ($chauffeurContent as $faqs)
                                    <div class="panel panel-default">
                                        <div class="panel-heading" id="Professional_Chauffeur" role="tab">
                                            <h4 class="panel-title">
                                                <a title="{{ $faqs['title'] }}" aria-controls="collapseOne_reg" aria-expanded="true" class="collapsed"
                                                    data-parent="#accordion_reg" data-toggle="collapse"
                                                    href="#Professional_Chauffeur_view_{{ $counter }}" role="button">
                                                    {{ $faqs['title'] }}
                                                </a>
                                            </h4>
                                        </div>
                                        <div aria-labelledby="Engine" class="panel-collapse collapse"
                                            id="Professional_Chauffeur_view_{{ $counter }}" role="tabpanel"
                                            aria-expanded="true" style="height: 0px;">
                                            <table class="specs-table">
                                                <tbody>
                                                    <td>{!! $faqs['body'] !!}</td>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php $counter++; ?>
                                @endforeach

                            </div>
                            <div class="tab-pane fade" id="tab-3">


                                <?php $counter = 0; ?>
                                @foreach ($cancellationsContent as $faqs)
                                    <div class="panel panel-default">
                                        <div class="panel-heading" id="Cancellations_Refunds" role="tab">
                                            <h4 class="panel-title">
                                                <a title="{{ $faqs['title'] }}" aria-controls="collapseOne_reg" aria-expanded="true" class="collapsed"
                                                    data-parent="#accordion_reg" data-toggle="collapse"
                                                    href="#Cancellations_Refunds_view_{{ $counter }}" role="button">
                                                    {{ $faqs['title'] }}
                                                </a>
                                            </h4>
                                        </div>
                                        <div aria-labelledby="Engine" class="panel-collapse collapse"
                                            id="Cancellations_Refunds_view_{{ $counter }}" role="tabpanel"
                                            aria-expanded="true" style="height: 0px;">
                                            <table class="specs-table">
                                                <tbody>
                                                    <td>{!! $faqs['body'] !!}</td>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php $counter++; ?>
                                @endforeach


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-space"></div>
        </section>
    </section>
    <!-- END: Terms And Conditions -->
@endsection
