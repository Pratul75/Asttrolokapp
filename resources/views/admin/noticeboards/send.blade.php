@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ getAdminPanelUrl() }}/{{ (!empty($isCourseNotice) and $isCourseNotice) ? 'course-noticeboards' : 'noticeboards' }}/{{ !empty($noticeboard) ? $noticeboard->id .'/update' : 'store' }}" class="form-horizontal form-bordered mt-4">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label" for="inputDefault">{!! trans('admin/main.title') !!}</label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ !empty($noticeboard) ? $noticeboard->title : old('title') }}">
                                    <div class="invalid-feedback">@error('title') {{ $message }} @enderror</div>
                                </div>

                                @if(!empty($isCourseNotice) and $isCourseNotice)
                                    <div class="form-group">
                                        <label class="input-label control-label">{!! trans('product.course') !!}</label>
                                        <select name="webinar_id" class="form-control search-webinar-select2 @error('webinar_id') is-invalid @enderror">
                                            <option value="" selected disabled>{{ trans('panel.select_course') }}</option>

                                            @if(!empty($noticeboard) and !empty($noticeboard->webinar))
                                                <option value="{{ $noticeboard->webinar->id }}" selected>{{ $noticeboard->webinar->title }}</option>
                                            @endif
                                        </select>
                                        <div class="invalid-feedback">@error('webinar_id') {{ $message }} @enderror</div>
                                    </div>


                                    <div class="form-group">
                                        <label class="input-label control-label">{!! trans('update.color') !!}</label>
                                        <select name="color" id="colorSelect" class="form-control @error('color') is-invalid @enderror">
                                            <option value="" selected disabled>{{ trans('update.select_a_color') }}</option>

                                            @foreach(\App\Models\CourseNoticeboard::$colors as $color)
                                                <option value="{{ $color }}" @if(!empty($noticeboard) and $noticeboard->color == $color) selected @endif>{{ trans('update.course_noticeboard_color_'.$color) }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">@error('color') {{ $message }} @enderror</div>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label class="control-label">{!! trans('admin/main.type') !!}</label>
                                        <select name="type" id="typeSelect" class="form-control @error('type') is-invalid @enderror">
                                            <option value="" selected disabled></option>

                                            <option value="all" @if(!empty($noticeboard) and $noticeboard->type == 'all') selected @endif>{{ trans('admin/main.all') }}</option>
                                            @foreach(\App\Models\Noticeboard::$adminTypes as $type)
                                                <option value="{{ $type }}" @if(!empty($noticeboard) and $noticeboard->type == $type) selected @endif>{{ trans('admin/main.notification_'.$type) }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">@error('type') {{ $message }} @enderror</div>
                                        <div class="text-muted text-small mt-1">{{ trans('admin/main.new_noticeboards_hint') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">{{ trans('admin/main.message') }}</label>
                            <textarea name="message" class="summernote form-control text-left  @error('message') is-invalid @enderror">{{ (!empty($noticeboard)) ? $noticeboard->message :'' }}</textarea>
                            <div class="invalid-feedback">@error('message') {{ $message }} @enderror</div>
                        </div>


                        <div class="form-group">
                            <div>
                                <button class="btn btn-primary" type="submit">{{ trans('admin/main.send_noticeboard') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
@endpush
