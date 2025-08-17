<div class="display-messages {{ isset($extra_class) && !empty($extra_class) ? $extra_class : '' }}">
    @if(session('error_message'))
    <div class="alert alert-danger" role="alert">
        {!! __(session('error_message')) !!}
    </div>
    @elseif(session('message'))
    <div class="alert alert-success" role="alert">
        {!! __(session('message')) !!}
    </div>
    @endif
</div> 