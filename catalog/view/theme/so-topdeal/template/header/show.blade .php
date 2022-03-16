@extends('product.layout')

@section('content')
<br><br><br>
<div class="row">
<div class="col-lg-12 margin-tb">

        <div class="pull-left">
          <h2>Product Description</h2>
        </div>


            <div class="pull-right">
                <a class="btn btn-info" href="{{route('product.index')}}">Back</a>

            </div>


</div>

</div>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Product Name:</strong>
            {{$data->product_name}}
        

        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
        <strong>Product code:</strong>
        {{$data->product_code}}
    

    </div>
</div>


<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
        <strong> Details:</strong>
        {{$data->details}}
    

    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
        <strong> Image:</strong>
        <img src="{{URL::to($data->logo)}}" height="150px;" width="200px;">
    

    </div>
</div>


</div>






@endsection