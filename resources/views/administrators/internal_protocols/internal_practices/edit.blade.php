@extends('administrators/default-template')

@section('title')
    {{ trans('practices.edit_practice') }}
@endsection

@section('active_protocols', 'active')

@section('css')
    <style>
        .pdf-container {
            font-family: monospace, system-ui;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 5px;
        }
    </style>
@endsection

@section('js')
    {{-- تجهيز تنسيقات الإدخال وتحميل القيم المحفوظة --}}
    <script type="module">
        $(document).ready(function () {
            $('#template').find('input').each(function () {
                $(this).addClass("form-control");
            });
            $('#template').find('select').each(function () {
                $(this).addClass("form-select");
            });
            loadResult();
        });
    </script>

    <script type="text/javascript">
        function loadResult() {
            var parameters = { "id": '{{ $practice->id }}' };

            $.ajax({
                data: parameters,
                url: '{{ route("administrators/protocols/practices/get_result", ["id" => $practice->id]) }}',
                type: 'post',
                beforeSend: function () {
                    $("#messages").html('<div class="spinner-border text-info mt-3"> </div> {{ trans("forms.please_wait") }}');
                },
                dataType: 'json',
                success: function (response) {
                    @if ($practice->internalProtocol->isOpen())
                    @if ($practice->signInternalPractices->isNotEmpty())
                    $("#messages").html('<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert"> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> </button> <strong> {{ trans("forms.danger") }}!</strong> {{ trans("practices.practice_already_signed")}} </div>');
                    @else
                    $("#messages").html('<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert"> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> </button> <strong> {{ trans("forms.warning") }}!</strong> {{ trans("practices.modified_practice")}} </div>');
                    @endif
                    @else
                    $("#messages").html('');
                    @endif

                    // يعبّي الحقول حسب الـ id (لا يكتب فوق قيم افتراضية)
                    for (var key in response) {
                        $('#'+key).val(response[key]);
                    }
                }
            }).fail(function () {
                $("#messages").html('<div class="alert alert-danger fade show mt-3" role="alert"> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> </button> <strong> {{ trans("forms.danger") }}! </strong> {{ trans("forms.failed_transaction") }} </div>');
            });
        }

        function signPractice() {
            if (! confirm("{{ Lang::get('forms.confirm')}}")) return false;
            let practice_signature_form = $("#practice_signature_form");
            practice_signature_form.submit();
        }
    </script>

    {{-- كود الجافاسكربت الخاص بالـ Determination (مغلّف داخل <script>) --}}
    @if ($practice->determination->javascript)
        <script>
{{--            {!! $practice->determination->javascript !!}--}}
        </script>
    @endif
@endsection

@section('menu')
    <nav class="navbar">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link @if ($practice->internalProtocol->isClosed() || ! auth()->user()->can('sign practices')) disabled @endif"
                   href="#"
                   onclick="return signPractice();">
                    {{ trans('practices.sign_practice') }}
                </a>
            </li>

            <form method="post" action="{{ route('administrators/protocols/practices/sign', ['id' => $practice->id]) }}" id="practice_signature_form">
                @csrf
                {{ method_field('PUT') }}
            </form>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('administrators/protocols/practices/index', ['internal_protocol_id' => $practice->internal_protocol_id]) }}">
                    {{ trans('forms.go_back') }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content-title')
    <i class="fas fa-file-medical"></i> {{ trans('practices.edit_practice') }}
@endsection

@section('content-message')
    <p class="text-justify pe-5">
        {{ trans('practices.practices_edit_message') }}
    </p>
@endsection

@section('content')
    <div id="messages"></div>

    <div class="form-group mt-3 col-md-6">
        <label class="fs-4" for="determination"> {{ trans('determinations.determination') }} </label>
        <input type="text" class="form-control" id="determination" value="{{ $practice->determination->name }}" disabled>
    </div>

    <div class="form-group mt-3">
        <label class="fs-4" for="determination"> {{ trans('practices.result') }} </label>
    </div>

    <div class="pdf-container">
        <form method="post" action="{{ route('administrators/protocols/practices/inform_result', ['id' => $practice->id]) }}" onsubmit="return confirm('{{ Lang::get('forms.confirm') }}')">
            @csrf
            {{ method_field('PUT') }}

            @php
                $det = $practice->determination;

                // قابلية التعديل = البروتوكول مفتوح ولم يتم توقيع هذا الـ practice
                $editable = $practice->internalProtocol->isOpen()
                    && $practice->signInternalPractices->isEmpty();

                // قيم النتائج المحفوظة (لو موجودة) لملء الحقول عند الفتح
                $values = (array) (
                    $practice->result
                    ?? $practice->results   // احتياط لأسماء أعمدة مختلفة
                    ?? $practice->variables
                    ?? $practice->values
                    ?? []
                );

                // تجهيز الاستبدال في قالب العرض
                $tpl = $det->template ?? '';
                $map = (array) ($det->getReplacementVariables() ?? []);
                // Fallback لو ما في getReplacementVariables
                if (empty($map) && is_array($det->template_variables)) {
                    foreach (array_keys((array)$det->template_variables) as $id) {
                        $map['${'.$id.'}'] = $id;
                    }
                }
                $search = $replace = [];
                foreach ($map as $ph => $varId) {
                    $search[]  = $ph;
                    $replace[] = (string) (\Illuminate\Support\Arr::get($values, $varId, ''));
                }

                // ورقة الإدخال أو بديل بسيط إذا مفقودة
                $worksheet = $det->worksheet_template;
                if (!$worksheet) {
                    $vars = array_keys((array) ($det->template_variables ?? []));
                    $fallback = '';
                    foreach ($vars as $v) {
                        $label = e($v);
                        $fallback .= "<div class=\"mb-2\"><label class=\"form-label\">{$label}</label><input id=\"{$v}\" name=\"{$v}\" class=\"form-control\"></div>";
                    }
                    $worksheet = $fallback ?: '<div class="alert alert-info">No worksheet defined.</div>';
                }
            @endphp

            <div id="template">
                @if ($editable)
                    {{-- نعرض حقول الإدخال طالما لم يُوقَّع الفحص --}}
                    {!! $worksheet !!}
                @else
                    {{-- بعد التوقيع (أو لمنع التعديل) نعرض القالب النهائي --}}
                    {!! str_replace($search, $replace, $tpl) !!}
                @endif
            </div>


            <div class="row row-cols-md-auto mt-4">
                <div class="col-12">
                    <input type="submit" class="btn btn-primary @if ($practice->internalProtocol->isClosed()) disabled @endif" value="{{ trans('forms.save') }}">
                </div>

                <div class="col-12 mt-2">
                    <div class="form-check">
                        <input class="form-check-input @if ($practice->internalProtocol->isClosed()) disabled @endif" type="checkbox" name="stay_on_this_page" value="1" id="stayOnThisPage">
                        <label class="form-check-label" for="stayOnThisPage">
                            {{ trans('forms.stay_on_this_page') }}
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
