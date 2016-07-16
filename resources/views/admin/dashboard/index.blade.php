@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Site totals</div>
                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <th width="30%">Overall</th>
                                <th>Views</th>
                                <th>Unique IP</th>
                                <th>Unique Cookie</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>{{ $site['totals']['views'] or '0' }}</td>
                                    <td>{{ $site['totals']['viewsip'] or '0' }}</td>
                                    <td>{{ $site['totals']['viewscookie'] or '0' }}</td>
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
                            @foreach ($site['browser'] as $name => $arr)
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
                            @foreach ($site['os'] as $name => $arr)
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
                            @foreach ($site['country'] as $name => $arr)
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
                            @foreach ($site['ref'] as $name => $arr)
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

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Pages Overview</div>
                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead>
                            <th width="30%">Page</th>
                            <th>Views</th>
                            <th>Unique IP</th>
                            <th>Unique Cookie</th>
                            </thead>
                            <tbody>
                            @foreach ($pages as $name => $arr)
                            <tr>
                                <td><a href="{{ url('/admin/dashboard') }}/{{ $name }}">{{ $name  }}</a></td>
                                <td>{{ $arr['totals']['views'] }}</td>
                                <td>{{ $arr['totals']['viewsip'] }}</td>
                                <td>{{ $arr['totals']['viewscookie'] }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
