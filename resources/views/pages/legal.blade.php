@extends('layouts.app')
@section('title','Legal & demo disclaimer')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">LEGAL INFORMATION</span><h1>Terms & demo disclaimer</h1><p>Informasi penting sebelum menggunakan FlyHigh.</p></div></section>
<section class="section container"><article class="card content-article"><h2>Demonstration, not an airline</h2><p>FlyHigh v2.0 is a configurable reservation-software demonstration. Demo bookings, prices, payment confirmations and tickets do not grant travel rights and are not issued by an airline.</p><h2>Privacy</h2><p>Demo users should use fictional personal details. The software stores account and booking information in the deployment operator's database. Production operators must publish their own legally reviewed privacy notice, refund policy, and terms before enabling real payments.</p><h2>Software ownership</h2><p>{{ config('flyhigh.copyright') }} · <a class="text-link" href="{{ config('flyhigh.origin') }}">Original source repository</a>. Product licensing is governed by the repository LICENSE and applicable third-party licenses.</p></article></section>
@endsection
