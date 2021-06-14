@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{ route('product.search') }}" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="" id="" class="form-control">
                        <optgroup label="Color">
                            @foreach ($colors as $color)
                            <option value="{{$color->id}}">{{ $color->variant}}</option>
                            @endforeach
                        
                        </optgroup>
                        <optgroup label="Size">
                            @foreach ($sizes as $size)
                            <option value="{{$size->id}}">{{ $size->variant}}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>


                        @foreach ($products as $product)
                        <tr>
                            <td>#</td>
                            <td>{{ $product->title }} <br> Created at : 25-Aug-2020</td>
                            <td>{{ Str::limit($product->description, 50) }}</td>
                            <td>
                                <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">
    
                                    <dt class="col-sm-3 pb-0">


                                        @foreach ($pvprice as $pvc)
                                            @if ($product->id == $pvc->product_id )

                                            @foreach ($pvariants as $pv)
                                            @if ($pvc->product_variant_one == $pv->id)
                                                {{ $pv->variant}}/
                                            @endif
                                            @endforeach
                                            @foreach ($pvariants as $pv)
                                            @if ($pvc->product_variant_two == $pv->id)
                                                {{ $pv->variant}}
                                            @endif
                                            @endforeach 
                                             <br>
                                             
                                            @endif
                                        @endforeach


                                    </dt>
                                    <dd class="col-sm-9">
                                        <dl class="row mb-0">
                                        
                                        @foreach ($pvprice as $pvc)
                                            
                                            @if ($product->id == $pvc->product_id )
                                            <dt class="col-sm-4 pb-0">Price : {{ $pvc->price}}</dt>
                                            <dd class="col-sm-8 pb-0">InStock : {{ $pvc->stock}}</dd>
                                        
                                            @endif
                                                                                  
                                        @endforeach

                                        </dl>
                                    </dd>
                                </dl>
                                <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                                </div>
                            </td>
                        </tr>
    
                            @endforeach

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p> Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} out of {{$products->total()}}</p>
                </div>
                <div class="col-md-2">
                 {{  ($products->appends(request()->input())->links()) }}
                   
                </div>
            </div>
        </div>
    </div>

@endsection
