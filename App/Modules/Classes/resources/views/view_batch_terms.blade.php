@extends('dashboard::layouts.tab_view')

@section('title', $batch->name)

@section('styles')
    @parent
    <link href="{{ mix('/assets/classes/css/module.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('scripts')
    @parent
    <script type="text/javascript" src="{{ mix('/assets/classes/js/viewBatchTerms.js') }}"></script>
@endsection

@section('tools')

    @can('add_edit_batch')
        <a href="{{ route('batch.terms.edit', $batch->id) }}" class="btn btn-primary">
            <i class="fa fa-fw fa-edit"></i> {{ trans('base::common.edit') }}
        </a>
    @endcan

@endsection

@section('tabs')

    @include('classes::partials.view_batch_tabs')

@endsection

@section('tab')

    <div id="viewBatchTerms">
        <view-batch-terms :batch="{{$batch}}"
                          :terms="{{$batch->terms ? $batch->terms : []}}">

        </view-batch-terms>
    </div>

@endsection