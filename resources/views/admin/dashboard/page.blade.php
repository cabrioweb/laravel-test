@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <a href="{{ url('/admin/dashboard') }}" class="btn btn-default">Back to dashboard</a>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Page {{ $page }} stats</div>
                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead>
                            <th width="30%">&nbsp;</th>
                            <th>Views</th>
                            <th>Unique IP</th>
                            <th>Unique Cookie</th>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Overall</td>
                                <td>{{ $totals['views'] or '0' }}</td>
                                <td>{{ $totals['viewsip'] or '0' }}</td>
                                <td>{{ $totals['viewscookie'] or '0' }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Browsers</div>
                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead>
                            <th width="30%">Name</th>
                            <th>Views</th>
                            <th>Unique IP</th>
                            <th>Unique Cookie</th>
                            </thead>
                            <tbody>
                            @foreach ($browser as $name => $arr)
                                <tr>
                                    <td>{{ $name  }}</td>
                                    <td>{{ $arr['views'] or '0' }}</td>
                                    <td>{{ $arr['viewsip'] or '0' }}</td>
                                    <td>{{ $arr['viewscookie'] or '0' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">OS</div>
                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead>
                            <th width="30%">Name</th>
                            <th>Views</th>
                            <th>Unique IP</th>
                            <th>Unique Cookie</th>
                            </thead>
                            <tbody>
                            @foreach ($os as $name => $arr)
                                <tr>
                                    <td>{{ $name  }}</td>
                                    <td>{{ $arr['views'] or '0' }}</td>
                                    <td>{{ $arr['viewsip'] or '0' }}</td>
                                    <td>{{ $arr['viewscookie'] or '0' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Countries</div>
                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead>
                            <th width="30%">Name</th>
                            <th>Views</th>
                            <th>Unique IP</th>
                            <th>Unique Cookie</th>
                            </thead>
                            <tbody>
                            @foreach ($country as $name => $arr)
                                <tr>
                                    <td>{{ $name  }}</td>
                                    <td>{{ $arr['views'] or '0' }}</td>
                                    <td>{{ $arr['viewsip'] or '0' }}</td>
                                    <td>{{ $arr['viewscookie'] or '0' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Referer</div>
                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead>
                            <th width="30%">Name</th>
                            <th>Views</th>
                            <th>Unique IP</th>
                            <th>Unique Cookie</th>
                            </thead>
                            <tbody>
                            @foreach ($ref as $name => $arr)
                                @if ($name != 'No referer')
                                    <tr>
                                        <td>{{ $name  }}</td>
                                        <td>{{ $arr['views'] or '0' }}</td>
                                        <td>{{ $arr['viewsip'] or '0' }}</td>
                                        <td>{{ $arr['viewscookie'] or '0' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
