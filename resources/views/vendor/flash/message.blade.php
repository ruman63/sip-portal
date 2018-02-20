@php
    if(count($errors)) {
        flash('Validation Failed!')->error();
    }
@endphp
<flash :data-messages="{{ 
    session('flash_notification', collect())->each(function($item, $key) {
        return $item['id'] = $key + 1;
    })->toJson() 
}}"></flash>
{{ session()->forget('flash_notification') }}
