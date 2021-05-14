@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(Session::has('message'))
    <div class="alert {{ Session::get('alert-class', 'alert-info') }}">
        {{ Session::get('message') }}
    </div>
@endif