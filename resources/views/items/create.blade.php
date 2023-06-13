@extends('layouts.master')
@section('content')
    <div class="container">
        <form action="{{ route('item.store') }}" method="POST">
            @csrf

            <div class="form-group row">
                <label for="name">Item Name</label>
                <input type="text" class="form-control" id="name" name="title" placeholder="item Name">

            </div>

            <div class="form-group row">
                <label for="description">Description</label>
                <textarea class="form-control" id="desciption" name="description" cols="30" rows="10"></textarea>
            </div>
            <div class="form-group row">
                <label for="cost">Item cost</label>
                <input type="text" class="form-control" id="cost" name="cost" placeholder="item cost">
            </div>
            <div class="form-group row">
                <label for="sell">Item selling price</label>
                <input type="number" class="form-control" id="cost" name="sell" placeholder="item selling price" step="0.5">
            </div>
            <div class="form-group">
                <label for="document">Attachments</label>
                <div class="needsclick dropzone" id="document-dropzone"></div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script>
        var uploadedDocumentMap = {}
        Dropzone.options.documentDropzone = {
            url: '{{ route('item.storeMedia') }}',
            maxFilesize: 2, // MB
            addRemoveLinks: true,
            // acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(file, response) {
                $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
                uploadedDocumentMap[file.name] = response.name
            },
            removedfile: function(file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }
                $('form').find('input[name="document[]"][value="' + name + '"]').remove()
            },
            init: function() {
                @if (isset($project) && $project->document)
                    var files =
                        {!! json_encode($project->document) !!}
                    for (var i in files) {
                        var file = files[i]
                        this.options.addedfile.call(this, file)
                        file.previewElement.classList.add('dz-complete')
                        $('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
                    }
                @endif
            }
        }
    </script>
@endsection
