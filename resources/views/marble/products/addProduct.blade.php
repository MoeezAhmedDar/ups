@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
        <h4>Add Product</h4>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
<div class="row">
    <div class="col-md-12 mx-auto">
         
        <form action={{ asset("insert-Product")}} method="post">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Select Category</label>
                        <select class="form-select form-control" value=" {{ old('p_category') }}" id="c_id" onchange=" sub_category_search()" name='p_category' aria-label="Default select example">
                            <option value="">select category</option>
                            @foreach ($category as $cate)
                            <option value=" {{ $cate->id }} ">{{ $cate->name }}</option>
                            @endforeach
                        </select>
                        @error('p_category')
                            <span class="text-danger"> 
                            {{ $message }}    
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Select Company</label>
                        <select class="form-select form-control" id='s_c_id' value=" {{ old('p_subcategory') }} " name='p_subcategory' aria-label="Default select example">
                        <option value="">Select</option>     
                        </select>
                        @error('p_subcategory')
                            <span class="text-danger"> 
                            {{ $message }}    
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value=" {{ old('name') }}" placeholder="Add product name">
                        @error('name')
                            <span class="text-danger"> 
                            {{ $message }}    
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            {{-- <div class="row mt-2">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Product Price</label>
                        <input type="text" name="price" class="form-control" value="{{ old('price') }}" placeholder="Add product price">
                        @error('price')
                            <span class="text-danger"> 
                            {{ $message }}    
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Product Color</label>
                        <input type="text" name="p_color" class="form-control" value="{{ old('p_color') }}" placeholder="Add product color">
                        @error('p_color')
                            <span class="text-danger"> 
                            {{ $message }}    
                            </span>
                        @enderror
                    </div>
                </div>
            </div> --}}
            <div class="mb-3">
                <button type="submit" class="btn btn-outline-success float-right">Add Product</button>
            </div>
        </form>
    </div>
</div>
</div>
<div>
<script>

function sub_category_search(){
 
 var c_id=$("#c_id").val();
 
if(c_id==''){
    $('#s_c_id').html('');
   $('#s_c_id').append( '<option value="">Select</option>'); 
}
else{

    $.ajax({
   url: "{{ route('sub_category_ajax') }}/"+c_id,
   type: "GET",
   dataType: "json",
   success: function(response){
       
     $('#s_c_id').html('');
   $('#s_c_id').append( '<option value="">Select</option>'); 
   $.each(response.subcategories,function(key,item){
 
     $('#s_c_id').append( '<option value='+item.id+'>'+item.name+'</option>'
   ); 
 
   });
     
     }
 });

}
   }

</script>
@endsection