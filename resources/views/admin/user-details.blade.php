@extends('layouts.adminbase')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <div class="card md-4 p-3">
        <h2>Users</h2>
        <div class="card-body">
            <div class="table table-resposive">
                <table class="table table-bordered">
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Employement Status</th>
                    </tr>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->employee_code }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->Role }}</td>
                        <td>{{ $user->Status }}</td>

                        <td>
                            <form action="#" method="POST">
                                @csrf

                                <div class="form-check form-switch">

                                    <input class="form-check-input employment-toggle" type="checkbox" name="employment_status" value="Active" data-user="{{ $user->id }}" {{ $user->employment_status == 'Active' ? 'checked' : '' }}>

                                    <label class="form-check-label">
                                        {{ $user->employment_status }}
                                    </label>

                                </div>

                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>

        </div>

    </div>

    <hr>
    <div class="card md-4 p-3">
        <div class="table table-resposive">
            <h2>Lead by user</h2>
            <table class="table table-responsive">
                <tr>
                    <th>Lead ID</th>
                    <th>Name</th>
                    <th>phone</th>
                    <th>Product</th>
                    <th>Source</th>
                </tr>
                @foreach($myLeads as $leads)
                <tr>
                    <td>{{ $leads-> id}}</td>
                    <td>{{ $leads-> Name}}</td>
                    <td>{{ $leads-> Mobile}}</td>
                    <td>{{ $leads-> Product}}</td>
                    <td>{{ $leads-> source}}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    <hr>
    <div class="card md-4 p-3">
        <div class="table table-resposive">
            <h2>Estimate by user</h2>
            <table class="table table-responsive">
                <tr>
                    <th>Lead ID</th>
                    <th>Estimate No</th>
                    <th>Customer Name</th>
                    <th>Location</th>
                    <th>Net Total</th>
                    <th>Action</th>
                </tr>
                @foreach($myestimates as $estimates)
                <tr>
                    <td>{{$estimates->lead_id}}</td>
                    <td>{{ $estimates->estimate_no}}</td>
                    <td>{{ $estimates->customer_name}}</td>
                    <td>{{ $estimates->customer_address}}</td>
                    <td>{{ $estimates->net_total}}</td>
                    <td><a href="{{ route('estimates.pdf', $estimates->id) }}" class="btn btn-sm btn-success" target="_blank">
                            PDF
                        </a>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endsection
