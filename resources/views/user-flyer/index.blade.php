@extends('layouts.app', ['title' => __('Restaurants')])
@section('admin_title')
    {{__('Restaurants')}}
@endsection
@section('content')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>

<style>
    #db-table_filter input[type="search"] {
        margin-right: 1rem;
        margin-top: 0.5rem;
    }
    
    
    #db-table_paginate {
        margin-right: 1rem;
        margin-top: 0.5rem;
        margin-bottom: 1rem;
    }
</style>


<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Menu Templates') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('menu-designer.create') }}" class="btn btn-sm btn-primary">{{ __('Create Menu Template') }}</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        @include('partials.flash')
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="db-table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Template Name') }}</th>
                                    <th scope="col">{{ __('Preview') }}</th>
                                    <th scope="col">{{ __('Original Size') }}</th>
                                    <th scope="col">{{ __('Creation Date') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $template)
                                <tr>
                                <td>
                                    <div class="print font-weight-bold" title="click to print" style="cursor: pointer" data-id="{{$template->id}}">{{ $template->template_name }}</div>
                                </td>
                                <td>
                                    <img src="{{asset($template->template_cover)}}" class="img-fluid open-image img-thumbnail print-image-{{$template->id}}" style="cursor: pointer" width="75" height="75" />
                                </td>
                                <td>{{ $template->layout_width."px x ".$template->layout_height."px" }}</td>
                                <td>{{ $template->created_at->format(config('settings.datetime_display_format')) }}</td>
                                <td class="d-flex">
                                    @if ($template->user_id == auth()->id())
                                        <a href="{{ route('menu-designer.edit', $template->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('menu-designer.destroy', $template) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirm('{{ __("Are you sure you want to delete this template?") }}') ? this.parentElement.submit() : ''">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>    
                                    @endif
                                </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
            
        @include('layouts.footers.auth')
    </div>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    $(document).ready(function () {
        $('#db-table').DataTable({
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            scrollX: true,
            "language": {
            "paginate": {
                "next": "˃",
                "previous": "˂"
            }
        }
        });
    });
    function removeFromStorage(key) {
        localStorage.removeItem(key)
    }
    var success_message = {!! json_encode(session()->get('status')) !!}
    if(success_message) {
        removeFromStorage('template')
        removeFromStorage('edit_template')
    }

   
    function PrintImage(source)
    {
        var popup;
        function closePrint () {
            if ( popup ) {
                popup.close();
            }
        }
        popup = window.open( source );
        popup.onbeforeunload = closePrint;
        popup.onafterprint = closePrint;
        popup.focus(); // Required for IE
        popup.print();
    }


    $('.print').on('click', function(){
        var id = $(this).attr('data-id')
        var myImage = $(`.print-image-${id}`).attr('src')     
        PrintImage(myImage) 
        //getImageFromUrl(myImage, createPDF)
    })

    $('.open-image').on('click', function() {
        var src = $(this).attr('src')
        window.open(src, "_blank");
    })
</script>

@endsection