@extends('backend.layouts.master')

@section('main-content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Facility inquiry by customers</h4>
                    </div>
                    <div class="table-responsive m-t-20">
                        <table class="table table-bordered table-responsive-lg">
                            <thead>
                            <tr>

                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Contact Number</th>
                                <th scope="col">Listing</th>
                                <th scope="col">Username</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($inquiries as $inquiry)
                                <tr id="row_{{$inquiry->id}}">
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $inquiry -> name }}</td>
                                    <td>{{ $inquiry -> email }}</td>
                                    <td>{{ $inquiry -> contact_number }}</td>
                                    <td>{{ $inquiry -> listing-> title }}</td>
                                    <td>{{ $inquiry -> user-> username }}</td>
                                    <td>
                                        <a href="{{ route('inquiry-detail', $inquiry -> id ) }}"><span
                                                class="btn btn-sm btn-rounded btn-success">View</span></a>
                                    </td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
