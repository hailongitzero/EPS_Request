<?php
/**
 * Created by PhpStorm.

 * Date: 4/2/2019
 * Time: 2:49 PM
 */
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
                <a href="{{url('')}}">Trang Chủ</a>
                <span class="divider"><i class="fa fa-angle-right"></i></span>
            </li>
            <li>
                <i class="fa fa-home"></i>
                <a href="{{url('my-request')}}"> Danh sách yêu cầu của tôi</a>
                <span class="divider"><i class="fa fa-angle-right"></i></span>
            </li>
            <li class="active"> Chi tiết yêu cầu</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="row main-layout">
        <div class="col-md-12">
            <div class="box">
                <div class="box-title">
                    <h3><i class="fa fa-bars"></i> Thông Tin Yêu Cầu</h3>
                    <!-- <div class="box-tool">
                        <a data-action="collapse" href="form_layout.html#"><i class="fa fa-chevron-up"></i></a>
                        <a data-action="close" href="form_layout.html#"><i class="fa fa-times"></i></a>
                    </div> -->
                </div>
                <div class="box-content">
                    <form method="POST" action="/request-assign-set" class="form-horizontal">
                        {{ csrf_field() }}
                        @if (Session::has('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dissmiss="alert">x</button>
                                <strong>
                                    {{ Session::get('success') }}
                                </strong>
                            </div>
                        @endif
                        @if (Session::has('error'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dissmiss="alert">x</button>
                                <strong>
                                    {{ Session::get('error') }}
                                </strong>
                            </div>
                        @endif
                        @if(isset($request))
                            @foreach($request as $key=>$val)
                                <div class="form-group">
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Trạng Thái</b></label>
                                    <div class="col-xs-8 col-sm-9 col-md-3 col-lg-2 col-sm-mb-1 controls">
                                        <p id="trang_thai" class="content-label"><span class="label label-large {{ $val->trang_thai == 0 ? "label-info" : ($val->trang_thai == 1 ? "label-warning" : ($val->trang_thai == 2 ? "label-magenta" : ($val->trang_thai == 3 ? "label-success" : "label-important"))) }}">{{ $val->trang_thai == 0 ? "Yêu cầu mới" : ($val->trang_thai == 1 ? "Tiếp nhận" : ($val->trang_thai == 2 ? "Đang xử lý" : ($val->trang_thai == 3 ? "Hoàn Thành" : "Từ chối")))}}</span></p>
                                    </div>
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Ưu Tiên</b></label>
                                    <div class="col-xs-8 col-sm-9 col-md-3 col-lg-2 controls">
                                        <p id="do_uu_tien" class="content-label"><span class="label label-large {{ $val->do_uu_tien == 0 ? "label-info": $val->do_uu_tien == 1 ? "label-success" : "label-important" }}"> {{ $val->do_uu_tien == 0 ? "Thấp": $val->do_uu_tien == 1 ? "Trung Bình" : "Cao" }}</span></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Nội Dung</b></label>
                                    <div class="col-xs-12 col-sm-9 col-md-8 controls">
                                        <p id="noi_dung" class="content-label">{!! $val->noi_dung !!}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Yêu Cầu</b></label>
                                    <div class="col-xs-12 col-sm-9 col-md-8 controls">
                                        <p id="yeu_cau_xu_ly" class="content-label">{!! $val->yeu_cau_xu_ly !!}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Ngày tạo</b></label>
                                    <div class="col-xs-8 col-sm-9 col-md-3 col-lg-2 col-sm-mb-1 controls">
                                        <p id="ngay_tao" class="content-label">{{ date_format(date_create($val->ngay_tao), 'd/m/Y') }}</p>
                                    </div>
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Ngày hoàn thành</b></label>
                                    <div class="col-xs-8 col-sm-9 col-md-3 col-lg-2 controls">
                                        <p id="ngay_xu_ly" class="content-label">{{ date_format(date_create($val->ngay_xu_ly), 'd/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Hạn xử lý</b></label>
                                    <div class="col-xs-8 col-sm-9 col-md-3 col-lg-2 col-sm-mb-1 controls">
                                        <p id="han_xu_ly" class="content-label">{{ date_format(date_create($val->han_xu_ly), 'd/m/Y') }}</p>
                                    </div>
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Người xử lý</b></label>
                                    <div class="col-xs-8 col-sm-9 col-md-3 col-lg-2 controls">
                                        <p id="nguoi_xu_ly" name="nguoi_xu_ly" class="content-label">{{ $val->xu_ly->name }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Loại yêu cầu</b></label>
                                    <div class="col-xs-8 col-sm-9 col-md-3 col-lg-2 col-sm-mb-1 controls">
                                        <p id="loai_yeu_cau" name="loai_yeu_cau" class="content-label">{{ $val->loai_yc->ten_loai_yeu_cau }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>File đính kèm</b></label>
                                    <div class="col-xs-12 col-sm-9 col-md-8 controls">
                                        <div class="attach-file">
                                            @foreach($val->files as $key2=>$val2)
                                                <a href="{{ url('fileDownload/'.$val2->store_file_name) }}">{{ $val2->file_name }}</a><br/>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Phản hồi</b></label>
                                    <div class="col-xs-12 col-sm-9 col-md-8 controls">
                                        <p id="thong_tin_xu_ly" class="content-label">{!! $val->thong_tin_xu_ly !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
