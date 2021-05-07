<?php
/**
 * Created by PhpStorm.

 * Date: 4/2/2019
 * Time: 2:49 PM
 */
?>
@extends('layouts.master')
@section('page-title')
    <img src="/img/logo.png" alt="EPS Genco 3" alt width="20%">
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
                <a href="{{url('request-handle')}}">Danh Sách Yêu Cầu Hỗ Trợ</a>
                <span class="divider"><i class="fa fa-angle-right"></i></span>
            </li>
            <li class="active"> Chi tiết - Xử lý yêu cầu</li>
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
                    <form method="POST" id="form-request"  class="form-horizontal">
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
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Họ Tên</b></label>
                                    <div class="col-xs-8 col-sm-9 col-md-3 col-lg-2 col-sm-mb-1 controls">
                                        <p id="ho_ten" class="content-label">{{ $val->user->name }}</p>
                                    </div>
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Điện Thoại</b></label>
                                    <div class="col-xs-8 col-sm-9 col-md-3 col-lg-2 controls">
                                        <p id="so_dien_thoai" class="content-label">{{ $val->user->dien_thoai }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Phòng Ban</b></label>
                                    <div class="col-xs-8 col-sm-9 col-md-3 col-lg-2 col-sm-mb-1 controls">
                                        <p id="phong_ban" class="content-label">{{ $val->phong_ban->ten_phong_ban }}</p>
                                    </div>
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Ưu Tiên</b></label>
                                    <div class="col-xs-8 col-sm-9 col-md-3 controls">
                                        <p id="do_uu_tien" class="content-label">
                                        <span class="label label-large {{ $val->do_uu_tien == 0 ? 'label-info' : ($val->do_uu_tien == 1 ? 'label-success' : 'label-important') }}"> {{ $val->do_uu_tien == 0 ? "Thấp" : ($val->do_uu_tien == 1 ? "Trung Bình" : "Cao") }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Người xử lý</b></label>
                                    <div class="col-xs-12 col-sm-9 col-md-8 controls">
                                        <p id="nguoi_xu_ly" class="content-label">{!! $val->xu_ly->name !!} &lt;chính&gt;</p>
                                        <p id="sub_person" class="content-label">
                                            @foreach( $val->sub_assign as $asg)
                                                {{$asg->user->name}} &lt;phụ&gt;,
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Nội Dung</b></label>
                                    <div class="col-xs-12 col-sm-9 col-md-8 controls">
                                        <p id="noi_dung" class="content-label auto-overflow">{!! $val->noi_dung !!}</p>
                                        <p id="ma_yeu_cau" class="content-label hidden">{{ $val->ma_yeu_cau }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label"><b>Yêu Cầu</b></label>
                                    <div class="col-sm-9 col-lg-10 controls">
                                        <p id="yeu_cau_xu_ly" class="content-label auto-overflow">{!! $val->yeu_cau_xu_ly !!}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Trạng Thái</b></label>
                                    <div class="col-xs-7 col-sm-9 col-md-3 col-lg-2 col-sm-mb-1 controls">
                                        <div class="input-group col-xs-12 col-sm-5 col-md-12">
                                            <select id="trang_thai" name="trang_thai" class="form-control" tabindex="1">
                                                <option value="0" {{ $val->trang_thai == 0 ? "selected" : "" }}>Chuyển yêu cầu</option>
                                                <option value="1" {{ $val->trang_thai == 1 ? "selected" : "" }}>Tiếp nhận</option>
                                                <option value="2" {{ $val->trang_thai == 2 ? "selected" : "" }}>Đang xử lý</option>
                                                <option value="3" {{ $val->trang_thai == 3 ? "selected" : "" }}>Hoàn Thành</option>
                                                <option value="4" {{ $val->trang_thai == 4 ? "selected" : "" }}>Từ chối</option>
                                            </select>
                                        </div>
                                    </div>
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Hạn xử lý</b></label>
                                    <div class="col-xs-7 col-sm-9 col-md-3 col-lg-2 col-sm-mb-1 controls">
                                        <div class="input-group date col-sm-5 col-md-12" id="han_xu_ly_yc">
                                            <input id="han_xu_ly" name="han_xu_ly" type="text" class="form-control" data-date-format="dd/mm/yyyy" value="{{ date_format(date_create($val->han_xu_ly), 'd/m/Y') }}">
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Gia hạn</b></label>
                                    <div class="col-xs-7 col-sm-9 col-md-3 col-lg-2 col-sm-mb-1 controls">
                                        <div class="input-group col-xs-12 col-sm-5 col-md-12">
                                            <div id="gia_han_yn" class="make-switch has-switch" data-label-icon="" data-on-label="<i class='fa fa-check fa fa-white'></i>" data-off-label="<i class='fa fa-times'></i>">
                                                <input id="gia_han" name="gia_han" type="checkbox">
                                            </div>
                                        </div>
                                    </div>
                                    <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Gia hạn tới</b></label>
                                    <div class="col-xs-7 col-sm-9 col-md-3 col-lg-2 col-sm-mb-1 controls">
                                        <div class="input-group date col-sm-5 col-md-12" id="ngay_gia_han_div">
                                            <input id="ngay_gia_han" name="ngay_gia_han" type="text" class="form-control" data-date-format="dd/mm/yyyy">
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="div_gh_noi_dung" class="form-group" style="display: none;">
                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Thông tin gia hạn</b></label>
                                    <div class="col-xs-12 col-sm-9 col-md-8 controls">
                                        <textarea id="noi_dung_gia_han" name="noi_dung_gia_han" class="form-control ckeditor" rows="4"></textarea>
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
                                        <input id="attachFile" type="file" name="attachFile[]" class="form-control" accept=".pdf, .jpg, .png, .xls, .xlsx, .doc, .docx, .ppt, .pptx" multiple>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Phản hồi</b></label>
                                    <div class="col-xs-12 col-sm-9 col-md-8 controls">
                                        <textarea id="thong_tin_xu_ly" name="thong_tin_xu_ly" class="form-control ckeditor" rows="4">{!! $val->thong_tin_xu_ly !!}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                <button id="updateHandleRequest" type="button" class="btn btn-primary"><i class="fa fa-check"></i> Cập Nhật</button>
                                <button type="button" class="btn window-close">Đóng lại</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
