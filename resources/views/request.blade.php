<?php
?>
@extends('layouts.master')
@section('page-title')
{{--<h1><i class="fa fa-file-o"></i> Yêu Cầu</h1>--}}
{{--<h4>Tạo yêu cầu</h4>--}}
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
        <li class="active"> Tạo Yêu Cầu</li>
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
                <form id="request_form" method="POST" action="/request-receipt" class="form-horizontal" enctype="multipart/form-data">
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
                    <div class="form-group">
                        <label class="col-sm-4 col-md-3 col-lg-2 md-pb-1 control-label">Phòng Ban(*)</label>
                        <div class="col-sm-8 col-md-9 col-lg-2 md-pb-1 controls">
                            <select id="phong_ban" name="phong_ban" class="form-control chosen" data-placeholder="Chọn phòng ban" tabindex="1" {{ isset(\Illuminate\Support\Facades\Auth::user()->ma_phong_ban) ? "disabled" : "" }}>
                                <option value=""> </option>
                                @if(isset($phongBan))
                                    @foreach($phongBan as $key=>$val)
                                        <option value="{{ $val->ma_phong_ban }}" {{ \Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->ma_phong_ban == $val->ma_phong_ban ? "selected" : "" : ""}}>{{ $val->ten_phong_ban }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-sm-4 col-md-3 col-lg-1 md-pb-1 control-label">Họ tên(*)</label>
                        <div class="col-sm-8 col-md-9 col-lg-2 md-pb-1 controls">
                            <select id="ho_ten" name="ho_ten" class="form-control chosen" data-placeholder="Người yêu cầu" tabindex="1" {{ isset(\Illuminate\Support\Facades\Auth::user()->username) ? "disabled" : "" }}>
                                <option value="{{ \Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->username : "" }}">{{ \Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->name : "" }}</option>
                            </select>
                        </div>
                        <label class="col-sm-4 col-md-3 col-lg-1 control-label">Điện Thoại(*)</label>
                        <div class="col-sm-8 col-md-9 col-lg-2 controls">
                            <input id="dien_thoai" name="dien_thoai" type="text" value="{{ \Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->dien_thoai : "" }}" placeholder="Điện Thoại" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 col-md-3 col-lg-2 md-pb-1 control-label">Ưu Tiên</label>
                        <div class="col-sm-8 col-md-9 col-lg-2 md-pb-1 controls">
                            <select id="uu_tien" name="uu_tien" class="form-control" tabindex="1">
                                <option value="0">Thấp</option>
                                <option value="1" selected>Trung Bình</option>
                                <option value="2">Cao</option>
                            </select>
                        </div>
                        <label class="col-sm-4 col-md-3 col-lg-1 md-pb-1 control-label">Loại yêu cầu</label>
                        <div class="col-sm-8 col-md-9 col-lg-2 md-pb-1 controls">
                            <select id="loai_yeu_cau" name="loai_yeu_cau" class="form-control" tabindex="1">
                                @if(isset($loai_yc))
                                    @foreach($loai_yc as $key=>$val)
                                        <option value="{{ $val->loai_yeu_cau }}" data-cc-mail-check="{{ $val->cc_mail_check }}">{{ $val->ten_loai_yeu_cau }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-sm-4 col-md-3 col-lg-1 control-label">Thời gian</label>
                        <div class="col-sm-8 col-md-9 col-lg-2 controls">
                            <div class="input-group date" id="han_xu_ly_yc_moi">
                                <input id="han_xu_ly" name="han_xu_ly" type="text" class="form-control" data-date-format="dd/mm/yyyy">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                    <label class="col-sm-4 col-md-3 col-lg-2 md-pb-1 control-label">Đính kèm file</label>
                        <div class="col-sm-8 col-md-9 col-lg-5 md-pb-1 controls"> 
                            <input id="dinh_kem" type="file" name="dinh_kem[]" class="form-control" accept=".pdf, .jpg, .png, .xls, .xlsx, .doc, .docx, .ppt, .pptx" multiple>
                        </div>
                        <label class="col-sm-4 col-md-3 col-lg-1 control-label">Email</label>
                        <div class="col-sm-8 col-md-9 col-lg-2 controls">
                            <input id="email" name="email" type="text" placeholder="Email" class="form-control" value="{{ \Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->email : "" }}" {{ \Illuminate\Support\Facades\Auth::user() ? (isset(\Illuminate\Support\Facades\Auth::user()->email) ? "disabled" : "") : "" }}/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 col-md-3 col-lg-2 md-pb-1 control-label">Tiêu Đề</label>
                        <div class="col-sm-8 col-md-10 col-lg-8 controls">
                            <input id="tieu_de" name="tieu_de" type="text" placeholder="Tiêu Đề" class="form-control" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 col-md-3 col-lg-2 md-pb-1 control-label">Người phê duyệt</label>
                        <div class="col-sm-8 col-md-10 col-lg-8 controls">
                            <select id="cc_email" name="cc_email[]" data-placeholder="Email phê duyệt" class="form-control chosen" multiple="multiple" tabindex="6">
                                <option value=""> </option>
                                @if( isset($userPb) )
                                    @foreach( $userPb as $key=>$val)
                                        <optgroup label="{{ $val->ten_phong_ban }}">
                                            @foreach( $val->user as $cKey=>$cVal)
                                                <option value="{{ $cVal->email }}">{{ $cVal->name . ' < '. $cVal->email . ' >' }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 col-md-3 col-lg-2  control-label">Nội Dung</label>
                        <div class="col-sm-8 col-md-9 col-lg-8 controls">
                            <textarea id="noi_dung" name="noi_dung" class="form-control col-md-12 ckeditor" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                            <button id="btn_submit_request" type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Gửi Yêu Cầu</button>
                            <button type="button" class="btn">Xóa</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 