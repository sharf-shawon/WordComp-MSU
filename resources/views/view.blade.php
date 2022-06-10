@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <a class="btn btn-primary mb-5" href="/json">Get JSON</a>
            @if (isset($output) && count($output))
            <div class="table-responsive">
                <table class="table table-striped table-hover table-condensed" id="example">
                    <thead>
                        <tr>
                            <td>&Delta;</td>
                            <td>Live#</td>
                            <td>Staging#</td>
                            <td>Live</td>
                            <td>Staging</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($output as $item)
                        <tr>
                            <td>
                                {{$item['diff']}}
                            </td>
                            <td>
                                <a href="{{$item['live_url']}}">{{$item['live_count']}}</a>
                            </td>
                            <td>
                                <a href="{{$item['staging_url']}}">{{$item['staging_count']}}</a>
                            </td>
                            <td>
                                {{$item['live']}}
                            </td>
                            <td>
                                {{$item['staging']}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <script>
                    $(document).ready(function () {
                        $('#example').DataTable();
                    });
                </script>
            </div>
            <hr>
            @endif
            @if (isset($not_found) && count($not_found))
            <p>The following files were found on the live server but could not be found on the staging server</p>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td>File</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($not_found as $item)
                        <tr>
                            <td>
                                {{$item}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
    </div>
</div>
@endsection
