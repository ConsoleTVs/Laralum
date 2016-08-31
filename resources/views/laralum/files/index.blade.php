@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{  trans('laralum.files_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.files_title'))
@section('icon', "file")
@section('subtitle', trans('laralum.files_subtitle'))
@section('content')
<div class="ui doubling stackable one column grid container">
    <div class="column">
        <div class="ui very padded segment">
            @if(count($files) == 0)
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{ trans('laralum.missing_title') }}
                        </div>
                        <p>{{ trans('laralum.missing_subtitle', ['element'  =>  "files"]) }}</p>
                    </div>
                </div>
            @else
                <div class="ui doubling stackable three column grid">
                    @foreach($files as $file)
                        <div class="column">
                            @if(Laralum::isDocument($file))

                                <?php $doc = Laralum::document('name', $file); $slug = $doc->slug; ?>

                                <div class="ui fluid blue card">
                                  <div class="content">
                                    <div class="header">{{ $file }}</div>
                                    <div class="meta">{{ trans('laralum.documents_document') }}</div>
                                  </div>
                                  <div class="description">
                                      <center>
                                          <a href="{{ route('Laralum::files_download', ['file' => $file]) }}" class="ui no-disable button download">
                                              {{ trans('laralum.download') }}
                                          </a>
                                      </center>
                                  </div><br>
                                  <div class="extra content">
                                    <i class="download icon"></i>
                                    {{ $doc->downloads }}
                                    &nbsp;
                                    <i class="configure icon"></i>
                                    <div class="ui dropdown">
                                      {{ trans('laralum.options') }}
                                      <div class="menu">
                                        <a href="{{ Laralum::downloadLink($file) }}" class="no-disable item">{{ trans('laralum.download_link') }}</a>
                                        <a href="{{ route('Laralum::documents_edit', ['slug' => $slug]) }}" class="item">{{ trans('laralum.documents_edit') }}</a>
                                        <a href="{{ route('Laralum::documents_delete', ['slug' => $slug]) }}" class="item">{{ trans('laralum.documents_delete_document') }}</a>
                                        <a href="{{ route('Laralum::files_delete', ['file' => $file]) }}" class="item">{{ trans('laralum.files_delete_file') }}</a>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            @else
                                <div class="ui fluid orange card">
                                  <div class="content">
                                    <div class="header">{{ $file }}</div>
                                    <div class="meta">{{ trans('laralum.files_file') }}</div>
                                  </div>
                                  <div class="description">
                                      <center>
                                          <a href="{{ route('Laralum::files_download', ['file' => $file]) }}" class="ui no-disable button download">
                                              {{ trans('laralum.download') }}
                                          </a>
                                      </center>
                                  </div><br>
                                  <div class="extra content">
                                    <i class="configure icon"></i>
                                    <div class="ui dropdown">
                                      {{ trans('laralum.options') }}
                                      <div class="menu">
                                        <a href="{{ route('Laralum::documents_create', ['file' => $file]) }}" class="item">{{ trans('laralum.files_create_document') }}</a>
                                        <a href="{{ route('Laralum::files_delete', ['file' => $file]) }}" class="item">{{ trans('laralum.files_delete_file') }}</a>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $('.no-disable.button.download').click(function(){
        swal({
			title: "{{ trans('laralum.downloaded') }}",
			text: "{{ trans('laralum.downloaded_desc') }}",
			type: "success",
			confirmButtonText: "{{ trans('laralum.okai') }}"
		});
    });
</script>
@endsection
