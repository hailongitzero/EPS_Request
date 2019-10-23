<?php
//dd($dsYeuCau);
?>
@extends('layouts.master')
@section('page-title')
    <img src="/img/logo.png" alt="EPS Genco 3">
@endsection
@section('breadcrumb')
<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="index.html">Trang Chủ</a>
            <span class="divider"><i class="fa fa-angle-right"></i></span>
        </li>
        <li class="active">Danh sách yêu cầu của tôi</li>
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
                            {{--<th style="width:18px"><input type="checkbox" /></th>--}}
                            <th class="text-center">Ngày Tạo</th>
                            <th>Tiêu Đề</th>
                            <th>Người xử lý</th>
                            <th class="text-center">Ngày xử lý</th>
                            <th class="text-center">Độ Ưu Tiên</th>
                            <th class="text-center">Trạng Thái</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (isset($dsYeuCau))
                            @foreach($dsYeuCau as $key=>$val)
                                <tr class="table-flag-blue">
                                    <td class="{{ $val->ngay_xu_ly != null ? (date("Y-m-d",strtotime($val->ngay_xu_ly)) > date("Y-m-d",strtotime($val->han_xu_ly)) ? "important-red" : "") : (date("Y-m-d",strtotime($val->han_xu_ly)) < date("Y-m-d") && $val->han_xu_ly != null ? "important-red" : "") }}">{{$key+1}}</td>
                                    {{--<td><input type="checkbox" /></td>--}}
                                    <td class="text-center {{ $val->ngay_xu_ly != null ? (date("Y-m-d",strtotime($val->ngay_xu_ly)) > date("Y-m-d",strtotime($val->han_xu_ly)) ? "important-red" : "") : (date("Y-m-d",strtotime($val->han_xu_ly)) < date("Y-m-d") && $val->han_xu_ly != null ? "important-red" : "") }}">{{ date('d-m-Y', strtotime($val->ngay_tao ))}}</td>
                                    <td><a href='#' data-content="{{ $val->ma_yeu_cau }}" data-toggle="modal" data-target="#myRequestDetail"  class="{{ $val->ngay_xu_ly != null ? (date("Y-m-d",strtotime($val->ngay_xu_ly)) > date("Y-m-d",strtotime($val->han_xu_ly)) ? "important-red" : "") : (date("Y-m-d",strtotime($val->han_xu_ly)) < date("Y-m-d") && $val->han_xu_ly != null ? "important-red" : "") }}">{{$val->tieu_de}}</a></td>
                                    <td class="{{ $val->ngay_xu_ly != null ? (date("Y-m-d",strtotime($val->ngay_xu_ly)) > date("Y-m-d",strtotime($val->han_xu_ly)) ? "important-red" : "") : (date("Y-m-d",strtotime($val->han_xu_ly)) < date("Y-m-d") && $val->han_xu_ly != null ? "important-red" : "") }}">{{$val->xu_ly['name']}}</td>
                                    <td class="text-center {{ $val->ngay_xu_ly != null ? (date("Y-m-d",strtotime($val->ngay_xu_ly)) > date("Y-m-d",strtotime($val->han_xu_ly)) ? "important-red" : "") : (date("Y-m-d",strtotime($val->han_xu_ly)) < date("Y-m-d") && $val->han_xu_ly != null ? "important-red" : "") }}">{{date('d-m-Y', strtotime($val->ngay_xu_ly ))}}</td>
                                    <td class="text-center"><span class="label {{ $val->do_uu_tien == 0 ? "label-info": $val->do_uu_tien == 1 ? "label-success" : "label-important" }}">{{ $val->do_uu_tien == 0 ? "Thấp": $val->do_uu_tien == 1 ? "Trung Bình" : "Cao"  }}</span></td>
                                    <td class="text-center">
                                    <span class="label {{$val->trang_thai == 0 ? "label-info" : ($val->trang_thai == 1 ? "label-warning" : ($val->trang_thai == 2 ? "label-magenta" : ($val->trang_thai == 3 ? "label-success" : "label-important"))) }}">
                                        {{$val->trang_thai == 0 ? "Yêu cầu mới" : ($val->trang_thai == 1 ? "Tiếp nhận" : ($val->trang_thai == 2 ? "Đang xử lý" : ($val->trang_thai == 3 ? "Hoàn thành" : "Từ chối"))) }}
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
                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>File đính kèm</b></label>
                                    <div class="col-xs-12 col-sm-9 col-md-10 controls">
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