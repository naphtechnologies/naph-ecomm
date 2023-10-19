@extends('backend.layouts.master')

@section('main-content')


    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                @if(count($unpublished_listings) > 0)
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">New Listings</h4>
                        </div>
                        <div class="table-responsive m-t-20">
                            <table id="" class="table table-bordered table-responsive-lg">
                                <thead>
                                <tr>

                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Realtor</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($unpublished_listings as $listing)
                                    <tr id="row_{{$listing->id}}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $listing -> title }}</td>
                                        <td>{{ $listing -> price }}</td>
                                        <td>{{ $listing -> realtor-> name }}</td>
                                        <td><img src="{{ url($listing -> thumbnail_0) }}" alt="" srcset="" style="width:70px;height:50px"></td>
                                        <td>@if ( $listing -> is_published == '1' )
                                                Published
                                            @else
                                                Un Publish
                                            @endif
                                        </td>
                                        <td>{{ $listing -> created_at->diffForHumans() }}</td>

                                        <td>
                                            <a href="{{ route('listings.show', $listing -> id ) }}"><span class="btn btn-sm btn-rounded btn-success">View</span></a>

                                            {{-- <form style="display:inline-block" method="POST" action="{{ route('listings.destroy', $listing -> id) }}">
                                            @csrf
                                            @method('DELETE')

                                        <button  type="submit" class="btn btn-sm btn-rounded btn-danger">Delete</button>
                                            </form> --}}
                                            <button onclick="deleteData('{{ route('listings.destroy', $listing -> id) }}','{{ $listing -> id }}')" type="submit" class="btn btn-sm btn-rounded btn-danger">Delete</button>

                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                @if(count($published_listings) > 0)
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">All Listings</h4>
                        </div>
                        <div class="table-responsive m-t-20">
                            <table id="" class="table table-bordered table-responsive-lg">
                                <thead>
                                <tr>

                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($published_listings as $listing)
                                    <tr id="row_{{$listing->id}}">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $listing -> title }}</td>
                                        <td>{{ $listing -> price }}</td>
                                        {{--                                            <td>{{ $listing -> realtor-> name }}</td>--}}
                                        <td><img src="{{ url($listing -> thumbnail_0) }}" alt="" srcset="" style="width:70px;height:50px"></td>
                                        <td>@if ( $listing -> is_published == '1' )
                                                Published
                                            @else
                                                Un Publish
                                            @endif
                                        </td>
                                        <td>{{ $listing -> created_at->diffForHumans() }}</td>

                                        <td>
                                            <a href="{{ route('listing.show', $listing -> id ) }}"><span class="btn btn-sm btn-rounded btn-success">View</span></a>

                                            <button onclick="deleteData('{{ route('listing.destroy', $listing -> id) }}','{{ $listing -> id }}')" type="submit" class="btn btn-sm btn-rounded btn-danger">Delete</button>

                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>

@endsection
