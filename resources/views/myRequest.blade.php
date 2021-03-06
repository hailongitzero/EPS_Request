<?php
// dd($dsYeuCau);
?>
@extends('layouts.master')
@section('page-title')
    <img src="/img/logo.png" alt="EPS Genco 3" alt width="15%">
@endsection
@section('breadcrumb')
<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="index.html">Trang Chủ</a>
            <span class="divider"><i class="fa fa-angle-right"></i></span>
        </li>
        <li>
                <a>Cá nhân</a>
                <span class="divider"><i class="fa fa-angle-right"></i></span>
            </li>
            <li class="active">Danh Sách Yêu Cầu Của Tôi</li>
    </ul>
</div>
@endsection

@section('content')
<div class="row main-layout">
    <div class="col-md-12">
        <div class="box">
            <div class="box-title">
                <h3><i class="fa fa-table"></i> Yêu Cầu Của Tôi</h3>
            </div>
            <div class="box-content">
                <div class="clearfix"></div>
                <div class="table-responsive" style="border:0">
                    <table class="table table-advance" id="my-request">
                        <thead>
                        <tr>
                            <th style="width:30px">STT</th>
                            <th class="text-center">Ngày Tạo</th>
                            <th>Tiêu Đề</th>
                            <th class="text-center">Người xử lý</th>
                            <th class="text-center">Ngày xử lý</th>
                            <th class="text-center">Độ Ưu Tiên</th>
                            <th class="text-center">Trạng Thái</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (isset($dsYeuCau))
                            @foreach($dsYeuCau as $key=>$val)
                                <tr class="table-flag-blue">
                                    <td class="{{ $val->class }}">{{$key+1}}</td>
                                    <td class="text-center {{ $val->class }}">{{ date('d-m-Y', strtotime($val->ngay_tao ))}}</td>
                                    <td><a href='#' data-content="{{ $val->ma_yeu_cau }}" data-toggle="modal" data-target="#myRequestDetail"  class="{{ $val->class }}">{{$val->tieu_de}}</a></td>
                                    <td class="text-center {{ $val->class }}">{{$val->xu_ly != null ? $val->xu_ly['name'] : "-" }}</td>
                                    <td class="text-center {{ $val->class }}">{{ $val->ngay_xu_ly != null ? date('d-m-Y', strtotime($val->ngay_xu_ly )) : "-" }}</td>
                                    <td class="text-center"><span class="label {{ $val->prioClass }}">{{ $val->prioMn }}</span></td>
                                    <td class="text-center">
                                    <span class="label {{ $val->statusClass }}">
                                        {{ $val->statusMn }}
                                    </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myRequestDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-title">
                                <h3 id="tieu_de"></h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="box-content form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label"><b>Trạng Thái</b></label>
                                    <div class="col-sm-3 col-lg-4 controls">
                                        <p id="trang_thai" class="content-label"></p>
                                    </div>
                                    <label class="col-sm-3 col-lg-2 control-label"><b>Ưu Tiên</b></label>
                                    <div class="col-sm-3 col-lg-4 controls">
                                        <p id="do_uu_tien" class="content-label"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label"><b>Nội Dung</b></label>
                                    <div class="col-sm-9 col-lg-10 controls">
                                        <p id="noi_dung" class="content-label auto-overflow"></p>
                                        <p id="ma_yeu_cau" class="content-label hidden"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label"><b>Yêu cầu</b></label>
                                    <div class="col-sm-9 col-lg-10 controls">
                                        <p id="yeu_cau_xu_ly" class="content-label auto-overflow"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label"><b>Ngày tạo</b></label>
                                    <div class="col-sm-3 col-lg-4 controls">
                                        <p id="ngay_tao" class="content-label"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label"><b>Hạn xử lý</b></label>
                                    <div class="col-sm-3 col-lg-4 controls">
                                        <p id="han_xu_ly" class="content-label"></p>
                                    </div>
                                    <label class="col-sm-3 col-lg-2 control-label"><b>Người xử lý</b></label>
                                    <div class="col-sm-3 col-lg-4 controls">
                                        <p id="nguoi_xu_ly" name="nguoi_xu_ly" class="content-label"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label"><b>Loại yêu cầu</b></label>
                                    <div class="col-sm-3 col-lg-4 controls">
                                        <p id="loai_yeu_cau" name="loai_yeu_cau" class="content-label"></p>
                                    </div>
                                    <label class="col-sm-3 col-lg-2 control-label"><b>Ngày hoàn thành</b></label>
                                    <div class="col-sm-3 col-lg-4 controls">
                                        <p id="ngay_xu_ly" class="content-label"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label"><b>File đính kèm</b></label>
                                    <div class="col-sm-9 col-lg-10 controls">
                                        <div class="attach-file"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label"><b>Phản hồi</b></label>
                                    <div class="col-sm-9 col-lg-10 controls">
                                        <p id="thong_tin_xu_ly" name="thong_tin_xu_ly" class="content-label auto-overflow"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>
@endsection
