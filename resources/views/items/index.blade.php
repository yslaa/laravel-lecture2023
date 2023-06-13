@extends('layouts.master')
@section('content')
    <div class="container">
      @include('layouts.flash-messages')
      <a href="{{route('item.create')}}" class="btn btn-primary btn-lg " role="button" aria-disabled="true">Add Item</a>
      <div class="col-xs-6">
        <form method="post" enctype="multipart/form-data" action="{{ route('item-import') }}">
           {{ csrf_field() }}
         <input type="file" id="uploadName" name="item_upload" required>
         <button type="submit" class="btn btn-info btn-primary " >Import Excel File</button>
          
          </form>
          {{-- {{ link_to_route('item.export', 'Export to Excel')}} --}}
        </div>
     </div>
        <div class="modal" tabindex="-1" role="dialog" id="createItem">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Modal body text goes here.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-5">
        <h1 class="mb-5">Laravel 9 with Yajra Datatables</h1>

        {{ $dataTable->table() }}
        {{ $dataTable->scripts() }}

    </div>
@endsection
