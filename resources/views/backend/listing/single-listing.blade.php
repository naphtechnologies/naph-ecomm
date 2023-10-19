@extends('backend.layouts.master')

@section('main-content')

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">

            </div>
            <div class="col-7 align-self-center">
                <div class="d-flex align-items-center justify-content-end">

                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <h4 class="page-title">Listing Details</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <br/>
        @endif
        <div class="row">
            <!-- Column -->
            <div class="col-lg-4 col-xlg-3 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <center class="m-t-30">
                            <img src="{{ url($listing->thumbnail_0) }}" class="mb-5" width="300" height="150"/>
                            @if($listing->thumbnail_1)
                                <img src="{{ url($listing->thumbnail_1) }}" class="mb-5" width="300" height="150"/>
                            @endif
                            @if($listing->thumbnail_2)
                                <img src="{{ url($listing->thumbnail_2) }}" class="mb-5" width="300" height="150"/>
                            @endif
                            @if($listing->thumbnail_3)
                                <img src="{{ url($listing->thumbnail_3) }}" class="mb-5" width="300" height="150"/>
                            @endif
                            @if($listing->thumbnail_4)
                                <img src="{{ url($listing->thumbnail_4) }}" class="mb-5" width="300" height="150"/>
                            @endif
                            @if($listing->thumbnail_5)
                                <img src="{{ url($listing->thumbnail_5) }}" class="mb-5" width="300" height="150"/>
                            @endif
                            @if($listing->thumbnail_6)
                                <img src="{{ url($listing->thumbnail_6) }}" class="mb-5" width="300" height="150"/>
                            @endif

                        </center>
                    </div>
                    <div>
                        <hr>
                    </div>

                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-8 col-xlg-9 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('listing.update', $listing -> id) }}" method="POST"
                              class="form-horizontal m-t-30" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="form-group">
                                <label>Property Title :</label>
                                <input type="text" name="title" class="form-control" value="{{ $listing -> title }}"
                                       placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label>Price :</label>
                                <input type="number" name="price" class="form-control" value="{{ $listing -> price }}"
                                       placeholder="Price">
                            </div>
                            <div class="form-group">
                                <label>Square Feet :</label>
                                <input type="number" name="square_feet" class="form-control"
                                       value="{{ $listing -> square_feet }}" placeholder="Square Feet">
                            </div>
                            <div class="form-group">
                                <label>Lot Size :</label>
                                <input type="number" name="lot_size" class="form-control"
                                       value="{{ $listing -> square_feet }}" placeholder="Lot Size">
                            </div>
                            <div class="form-group">
                                <label>Bedroom :</label>
                                <input type="number" name="bedroom" class="form-control"
                                       value="{{ $listing -> bedroom }}" placeholder="Bedroom">
                            </div>
                            <div class="form-group">
                                <label>Bathroom :</label>
                                <input type="number" name="bathroom" class="form-control"
                                       value="{{ $listing -> bathroom }}" placeholder="Bathroom">
                            </div>
                            <div class="form-group">
                                <label>Garage :</label>
                                <input type="number" name="garage" class="form-control" value="{{ $listing -> garage }}"
                                       placeholder="Garage">
                            </div>

                            <div class="form-group">
                                <label>City :</label>
                                <input type="text" name="city" class="form-control" value="{{ $listing -> city }}"
                                       placeholder="City">
                            </div>
                            <div class="form-group">
                                <label>Country :</label>
                                <input type="text" name="country" class="form-control" value="{{ $listing -> country }}"
                                       placeholder="Country">
                            </div>
                            <div class="form-group">
                                <label>Description :</label>
                                <textarea name="description" class="form-control"
                                          rows="5">{{ $listing -> description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Thumbnail :</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image" class="custom-file-input">
                                        <label for="image" class="custom-file-label">Choose Image</label>

                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <label>Other Image :</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image_one" class="custom-file-input">
                                        <label for="image_one" class="custom-file-label">Choose Image</label>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Other Image :</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image_two" class="custom-file-input">
                                        <label for="image_two" class="custom-file-label">Choose Image</label>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Other Image :</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image_three" class="custom-file-input">
                                        <label for="image_three" class="custom-file-label">Choose Image</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Other Image :</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image_four" class="custom-file-input">
                                        <label for="image_four" class="custom-file-label">Choose Image</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Other Image :</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image_five" class="custom-file-input">
                                        <label for="image_five" class="custom-file-label">Choose Image</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Other Image :</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image_six" class="custom-file-input">
                                        <label for="image_six" class="custom-file-label">Choose Image</label>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-12">Is Publish :</label>
                                <div class="col-sm-12">
                                    <select name="is_published" class="form-control form-control-line" required>
                                        <option selected style="display:none">Select Publish/Draft</option>
                                        <option value="1"
                                                @if($listing -> is_published == '1')
                                                    selected
                                            @endif >Publish
                                        </option>

                                        <option value="0"
                                                @if($listing -> is_published == '0')
                                                    selected
                                            @endif
                                        >Draft
                                        </option>

                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>

    </div>

@endsection
