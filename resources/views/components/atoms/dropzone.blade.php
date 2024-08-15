@props([
    'accept' => 'image/*',
    'maxSize' => 2,
    'readonly' => false,
    'name' => 'file',
    'id' => 'dropzone',
    'values' => [],
])

@if (count($values) > 0)
    <div class="dropzone" data-plugin="dropzone" id="{{ $id }}" data-name="{{ $name }}">
        <div class="dz-message">
            @if (!$attributes->has('readonly'))
                <a href="javascript:void(0)" class="btn btn-outline-primary" id="btn_select">Add File</a>
            @endif
            <span class="mt-2">Or Drag Or Drop Files Here</span>
        </div>
        <div class="dz-preview">
            <span><i class="fal fa-file  fa-2x"></i></span>
            <div class="dz-details">
                <div class="dz-details__info">
                    <a class="dz-info__name" target="_blank" href="javascript:void(0)" data-dz-name></a>
                </div>
                <span class="dz-size" data-dz-size></span>
            </div>
            <div class="dz-actions">
                <a class="dz-action dz-action__remove" href="javascript:void(0)" data-dz-remove>Remove</a>
            </div>
        </div>
        @foreach ($values as $value)
            <div class="dz-preview dz-existing">
                <span><i class="fal fa-file  fa-2x"></i></span>
                <div class="dz-details">
                    <div class="dz-details__info">
                        <a class="dz-info__name" target="_blank" href="{{ $value['preview'] }}"
                            data-dz-name>{{ $value['filename'] }}</a>
                    </div>
                    <span class="dz-size" data-dz-size>{{ $value['size'] }}</span>
                </div>
                <div class="dz-actions">
                    <a class="dz-action dz-action__remove" href="{{ $value['preview'] }}" data-dz-remove>Remove</a>
                </div>
            </div>
        @endforeach
    </div>
    <small class="invalid-feedback"></small>
@else
    <div class="dropzone" data-plugin="dropzone" id="{{ $id }}" data-name="{{ $name }}">
        <div class="dz-message">
            @if (!$attributes->has('readonly'))
                <a href="javascript:void(0)" class="btn btn-outline-primary" id="btn_select">Add File</a>
            @endif
            <span class="mt-2">Or Drag Or Drop Files Here</span>
        </div>
        <div class="dz-preview">
            <span><i class="fal fa-file  fa-2x"></i></span>
            <div class="dz-details">
                <div class="dz-details__info">
                    <a class="dz-info__name" target="_blank" href="javascript:void(0)" data-dz-name></a>
                </div>
                <span class="dz-size" data-dz-size></span>
            </div>
            <div class="dz-actions">
                <a class="dz-action dz-action__remove" href="javascript:void(0)" data-dz-remove>Remove</a>
            </div>
        </div>
    </div>
    <small class="invalid-feedback"></small>
@endif
