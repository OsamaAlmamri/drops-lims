@extends('pdf/base')

@section('title')
    {{ trans('protocols.worksheet_for_protocol') }} #{{ $protocol->id }}
@endsection
@section('style')
    <style>
        /* dompdf: reserve space for header via @page (not body margin) */
        @page { margin: 170px 20px 50px 20px; }

        /*body { font-family: "DejaVu Sans", sans-serif; }*/

        /* fixed header sits in the negative top offset ~= header height */
        header.lab-header{
            position: fixed;
            top: -160px;              /* ≈ header height */
            left: 0; right: 0;
            height: 160px;
        }
        /*.header.lab-header { top: -120px; height: 120px; }*/

        /* layout tables */
        .hdr   { width:100%; border-collapse:collapse; }
        .hdr td{ vertical-align:top; }

        /* BOX: use <td> border (more reliable in dompdf than div borders) */
        .boxcell{
            border:1px solid #000;      /* the visible box */
            padding:6px;
        }

        /* inner detail table */
        .sub       { width:100%; border-collapse:collapse; table-layout:fixed; }
        .sub th,
        .sub td    { padding:4px 6px; font-size:12px; line-height:1.2; border-top:1px solid #cfcfcf;
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .sub tr:first-child th, .sub tr:first-child td { border-top:none; }
        .sub th    { width:120px; text-align:left; font-weight:bold; }
        .sub td    { text-align:left; }

        .lab-title { text-align:right; font-weight:bold; font-size:20px; margin-bottom:6px; }
    </style>
@endsection

@section('header')
    @php
        $p   = $protocol->internalPatient;
        $doc = optional($protocol->prescriber)->full_name;
        $fileNo = $p->identification_number ?? $p->id;

        $when = \Carbon\Carbon::parse($protocol->completion_date ?? now());
        $date = $when->format('d/m/Y');
        $time = $when->format('h:i A');

        $sex = match ($p->sex ?? null) {
            'M' => trans('patients.male'),
            'F' => trans('patients.female'),
            default => trans('patients.undefined'),
        };

        $ageArr = method_exists($p,'age') ? $p->age() : null;
        $ageStr = '';
        if ($ageArr) {
            $y = (int)($ageArr['year']??0);
            $m = (int)($ageArr['month']??0);
            $d = (int)($ageArr['day']??0);
            $ageStr = $y>0 ? $y.' '.__('patients.years') : ($m>0 ? $m.' '.__('patients.months') : $d.' '.__('patients.days'));
        }

        $ward = $protocol->ward ?? '—';
        $room = $protocol->room ?? '—';
    @endphp

    <header class="lab-header">
        <div class="lab-title">قسم المختبر</div>

        <table class="hdr">
            <tr>
                <!-- LEFT BOX -->
                <td style="width:58%; padding-right:8px;">
                    <table class="sub">
                        <tr>
                            <td class="boxcell">
                                <table class="sub">
                                    <tr><th>Lab No</th>       <td>{{ str_pad($protocol->id, 4, '0', STR_PAD_LEFT) }}</td></tr>
                                    <tr><th>File No</th>      <td>{{ $fileNo }}</td></tr>
                                    <tr><th>Patient Name</th> <td>{{ $p->full_name }}</td></tr>
                                    <tr><th>Doctor Name</th>  <td>{{ $doc }}</td></tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>

                <!-- RIGHT BOX -->
                <td style="width:42%; padding-left:8px;">
                    <table class="sub">
                        <tr>
                            <td class="boxcell">
                                <table class="sub">
                                    <tr><th>Date</th><td>{{ $date }} &nbsp; {{ $time }}</td></tr>
                                    <tr><th>Sex</th> <td>{{ $sex }}</td></tr>
                                    <tr><th>Age</th> <td>{{ $ageStr }}</td></tr>
                                    <tr><th>Ward</th><td>{{ $ward }} &nbsp;&nbsp; <b>Room</b> {{ $room }}</td></tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </header>
@endsection


@section('content')
    @foreach ($protocol->internalPractices as $practice)
            <div class="page-break-inside">
                {!! $practice->result_template !!} <br />
                ============================================ <br />
            </div>
    @endforeach
@endsection
