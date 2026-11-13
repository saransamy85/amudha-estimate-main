
@php

if(Auth::user()->Role == 'Admin'){
    $layout = 'layouts.adminbase';
}
elseif(Auth::user()->Role == 'Sales'){
    $layout = 'layouts.salesbase';
}
elseif(Auth::user()->Role == 'Purchase'){
    $layout = 'layouts.purchasebase';
}
elseif(Auth::user()->Role == 'Accounts'){
    $layout = 'layouts.accountsbase';
}
elseif(Auth::user()->Role == 'Receptionist'){
    $layout = 'layouts.receptionbase';
}
else{
    $layout = 'layouts.adminbase';
}

@endphp

@extends($layout)

@section('title','Dashboard')

@section('content')

<h1>Welcome Accounts Ma'am</h1>
@endsection
