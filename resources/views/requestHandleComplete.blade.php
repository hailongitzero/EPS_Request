<?php
/**
 * Created by PhpStorm.

 */

?>
@extends('layouts.master',$masterData)
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
            <li class="active">Hoàn Thành</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="row main-layout">
        <div class="col-md-12">
            <div class="box">
                <div class="box-title">
                    <h3><i class="fa fa-table"></i>Danh sách Yêu cầu Hoàn thành</h3>
                </div>
                <div class="box-content">
                    <div class="clearfix"></div>
                    <div class="table-responsive" style="border:0">
                        <table class="table table-advance" id="quan-ly-yeu-cau">
                            <thead>
                            <tr>
                                <th>STT</th>
                                {{--<th style="width:18px"><input type="checkbox" /></th>--}}
                                <th class="text-center" style="width:120px">Ngày tạo</th>
                                <th class="text-center">Tiêu đề</th>
                                <th>Người tạo</th>
                                <th>Phòng ban</th>
                                <th class="text-center" style="width:120px">Ngày xử lý</th>
                                <th class="text-center" style="width:114px">Độ ưu tiên</th>
                                <th class="text-center">Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (isset($dsRequest))
                                @foreach($dsRequest as $key=>$val)
                                    <tr class="table-flag-blue">
                                        <td class="{{ $val->class }}">{{$key+1}}</td>
                                        {{--<td><input type="checkbox" /></td>--}}
                                        <td class="text-center {{ $val->class }}">{{ date('d-m-Y', strtotime($val->ngay_tao)) }}</td>
                                        <td><a href='#' data-content="{{ $val->ma_yeu_cau }}" data-toggle="modal" data-target="#requestDetail"  class="{{ $val->class }}">{{$val->tieu_de}}</a></td>
                                        <td class="{{ $val->class }}">{{$val->user['name']}}</td>
                                        <td class="{{ $val->class }}">{{$val->phong_ban['ten_phong_ban']}}</td>
                                        <td class="text-center {{ $val->class }}">{{date('d-m-Y', strtotime($val->ngay_xu_ly ))}}</td>
                                        <td class="text-center"><span class="label {{ $val->prioClass }}">{{ $val->prioMn }}</span></td>
                                        <td class="text-center">
                                            <span class="label {{$val->statusClass }}">
                                                {{$val->statusMn }}
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
    <div class="modal fade" id="requestDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Họ Tên</b></label>
                                        <div class="col-xs-8 col-sm-9 col-md-4 col-sm-mb-1 controls">
                                            <p id="ho_ten" class="content-label"></p>
                                        </div>
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Điện Thoại</b></label>
                                        <div class="col-xs-8 col-sm-9 col-md-4 controls">
                                            <p id="so_dien_thoai" class="content-label"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Phòng Ban</b></label>
                                        <div class="col-xs-8 col-sm-9 col-md-4 col-sm-mb-1 controls">
                                            <p id="phong_ban" class="content-label"></p>
                                        </div>
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Ưu Tiên</b></label>
                                        <div class="col-xs-8 col-sm-9 col-md-4 controls">
                                            <p id="do_uu_tien" class="content-label"></p>
                                        </div>
                                    </div>
                                    <!-- tesst cc Email-->
                                    <div class="form-group">
                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Cc mail</b></label>
                                        <div class="col-xs-12 col-sm-9 col-md-10 controls">
                                            <p id="ccMaiList" class="content-label"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Nội Dung</b></label>
                                        <div class="col-xs-12 col-sm-9 col-md-10 controls">
                                            <p id="noi_dung" class="content-label auto-overflow"></p>
                                            <p id="ma_yeu_cau" class="content-label hidden"></p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Loại yêu cầu</b></label>
                                        <div class="col-xs-8 col-sm-9 col-md-4 col-sm-mb-1 controls">
                                            <p id="loai_yeu_cau" class="content-label"></p>
                                        </div>
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Ngày tạo</b></label>
                                        <div class="col-xs-7 col-sm-9 col-md-3 controls">
                                            <div id="ngay_tao_yc" class="input-group date col-sm-5 col-md-12">
                                                <input id="ngay_tao" name="ngay_tao" type="text" class="form-control" data-date-format="dd/mm/yyyy" disabled="disabled">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Người xử lý</b></label>
                                        <div class="col-xs-8 col-sm-9 col-md-4 col-sm-mb-1 controls">
                                            <p id="nguoi_xu_ly" class="content-label"></p>
                                            <p id="subPerson" class="content-label"></p>
                                        </div>
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Hạn xử lý</b></label>
                                        <div class="col-xs-7 col-sm-9 col-md-3 controls">
                                            <div class="input-group date col-sm-5 col-md-12" id="han_xu_ly_yc">
                                                <input id="han_xu_ly" name="han_xu_ly" type="text" class="form-control" data-date-format="dd/mm/yyyy">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Trạng Thái</b></label>
                                        <div class="col-xs-8 col-sm-9 col-md-4 col-sm-mb-1 controls">
                                            <p id="trang_thai"></p>
                                        </div>
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Ngày xử lý</b></label>
                                        <div class="col-xs-7 col-sm-9 col-md-3 controls">
                                            <div class="input-group date col-sm-5 col-md-12" id="ngay_xu_ly_yc">
                                                <input id="ngay_xu_ly" name="ngay_xu_ly" type="text" class="form-control" data-date-format="dd/mm/yyyy">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>File đính kèm</b></label>
                                        <div class="col-xs-12 col-sm-9 col-md-10 controls">
                                            <div class="attach-file"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Yêu Cầu</b></label>
                                        <div class="col-xs-12 col-sm-9 col-md-10 controls">
                                            <p id="yeu_cau_xu_ly" name="yeu_cau_xu_ly" class="content-label auto-overflow"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Nội Dung Xử Lý</b></label>
                                        <div class="col-xs-12 col-sm-9 col-md-10 controls">
                                            <p id="thong_tin_xu_ly" name="thong_tin_xu_ly" class="content-label"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="button" id="updateHandleRequest" class="btn btn-primary">Cập Nhật</button>
                </div>
            </div>
        </div>
    </div>
@endsection
