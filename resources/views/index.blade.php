<?php
?>
@extends('layouts.master')
@section('page-title')
{{--<h1><i class="fa fa-file-o"></i> Trang Chủ</h1>--}}
{{--<h4>Thống kê số liệu</h4>--}}
<img src="/img/logo.png" alt="EPS Genco 3">
@endsection

@section('breadcrumb')
<ul class="breadcrumb">
    <li class="active"><i class="fa fa-home"></i> Trang Chủ</li>
</ul>
@endsection

@section('content')
<div class="row main-layout">
    <div class="col-md-7">
        <div class="row">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tile tile-blue">
                            <div class="img">
                                <i class="fa fa-archive"></i>
                            </div>
                            <div class="content">
                                <p class="big">{{ $totalReq }}</p>
                                <p class="title">Tổng số yêu cầu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tile tile-light-blue">
                            <div class="img">
                                <i class="fa fa-warning"></i>
                            </div>
                            <div class="content">
                                <p class="big">{{ $totalNewReq }}</p>
                                <p class="title">Yêu cầu mới</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="row">
            <div class="col-md-6 tile-active">
                <div class="tile tile-orange">
                    <div class="img">
                        <i class="fa fa-reply"></i>
                    </div>
                    <div class="content">
                        <p class="big">{{ $totalRecpReq }}</p>
                        <p class="title">Đã tiếp nhận</p>
                    </div>
                </div>

                <div class="tile tile-pink">
                    <div class="img">
                        <i class="fa fa-pencil-square-o"></i>
                    </div>
                    <div class="content">
                        <p class="big">{{ $totalHandleReq }}</p>
                        <p class="title">Đang xử lý</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 tile-active">
                <div class="tile tile-green">
                    <div class="img">
                        <i class="fa fa-check"></i>
                    </div>
                    <div class="content">
                        <p class="big">{{ $totalCompReq }}</p>
                        <p class="title">Đã hoàn thành</p>
                    </div>
                </div>

                <div class="tile tile-red">
                    <div class="img">
                        <i class="fa fa-ban"></i>
                    </div>
                    <div class="content">
                        <p class="big">{{ $totalRejReq }}</p>
                        <p class="title">Đã từ chối</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="box">
            <div class="box-title">
                <h3><i class="fa fa-bar-chart-o"></i> Thống kê yêu cầu theo tháng</h3>
                <div class="box-tool">
                    <a data-action="collapse" href="flaty.html#"><i class="fa fa-chevron-up"></i></a>
                    <a data-action="close" href="flaty.html#"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div id="requestAnaly" style="margin-top:20px; position:relative; height: 290px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="box">
            <div class="box-title">
                <h3><i class="fa fa-bar-chart-o"></i> Thống kê yêu cầu theo tuần</h3>
                <div class="box-tool">
                    <a data-action="collapse" href="flaty.html#"><i class="fa fa-chevron-up"></i></a>
                    <a data-action="close" href="flaty.html#"><i class="fa fa-times"></i></a>
                </div>
            </div>
            @if(isset($WeeklyAnalyData))
            <div class="box-content">
                <ul class="weekly-stats">
                    @foreach($WeeklyAnalyData as $key=>$val)
                    <li>
                        <span class="inline-sparkline">{{ $val->CNT }}</span>
                        {{ $val->STS == 0 ? "Yêu cầu mới" : ($val->STS == 1 ? "Tiếp nhận yêu cầu" : ($val->STS == 2 ? "Đang xử lý" : ($val->STS == 3 ? "Hoàn thành" : "Từ chối"))) }}: <span class="value">{{ $val->TOT }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-7">
        <div class="box box-orange">
            <div class="box-title">
                <h3><i class="fa fa-bar-chart-o"></i> Tổng số yêu cầu theo phòng ban</h3>
                <div class="box-tool">
                    <a data-action="collapse" href="flaty.html#"><i class="fa fa-chevron-up"></i></a>
                    <a data-action="close" href="flaty.html#"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <div class="box-content">
                <form action="form_layout.html#" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12 col-lg-2 controls text-right">
                            <div id="switch_search_1" class="make-switch has-switch" data-label-icon="" data-on-label="<i class='fa fa-check fa fa-white'></i>" data-off-label="<i class='fa fa-times'></i>">
                                <input type="checkbox">
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-3 controls">
                            <div class="input-group date" id="tu_ngay_1">
                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" disabled>
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-3 controls">
                            <div class="input-group date" id="den_ngay_1">
                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" disabled>
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-3 controls">
                            <select id="cbx_loai_yeu_cau" name="cbx_loai_yeu_cau" class="form-control chosen" data-placeholder="Chọn phòng ban" tabindex="1" disabled>
                                <option value=""> Tất cả </option>
                                @if(isset($loai_yc))
                                    @foreach($loai_yc as $key=>$val)
                                        <option value="{{ $val->loai_yeu_cau }}">{{ $val->ten_loai_yeu_cau }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </form>
                <table id="tongYeuCauTheoPhongBan" class="table table-bordered">
                    <tbody>
                    @foreach( $getTotalReqByDepartment as $key=>$val)
                        @if( ($key+1) % 2 == 1)
                            <tr>
                                <td width="25%">{{ $val->TEN_PHONG_BAN }}</td>
                                <td width="25%">{{ $val->TOTAL }}</td>
                        @else
                                <td width="25%">{{ $val->TEN_PHONG_BAN }}</td>
                                <td width="25%">{{ $val->TOTAL }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="box box-orange">
            <div class="box-title">
                <h3><i class="fa fa-bar-chart-o"></i> Tổng số yêu cầu theo loại yêu cầu</h3>
                <div class="box-tool">
                    <a data-action="collapse" href="flaty.html#"><i class="fa fa-chevron-up"></i></a>
                    <a data-action="close" href="flaty.html#"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <div class="box-content">
                <form action="form_layout.html#" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12 col-lg-4 controls text-right">
                            <div id="switch_search_2" class="make-switch has-switch" data-label-icon="" data-on-label="<i class='fa fa-check fa fa-white'></i>" data-off-label="<i class='fa fa-times'></i>">
                                <input type="checkbox">
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-4 controls">
                            <div class="input-group date" id="tu_ngay_2">
                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" disabled>
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-4 controls">
                            <div class="input-group date" id="den_ngay_2">
                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" disabled>
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <table id="tb_tongYeuCauTheoLoai" class="table table-bordered">
                    <tbody>
                    @foreach( $totalReqByStatus as $key=>$val)
                        <tr>
                            <td width="50%">{{ $val->TEN_LOAI_YEU_CAU }}</td>
                            <td>{{ $val->TOTAL }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 