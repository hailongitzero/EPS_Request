<?php
/**

 */

?>
@extends('layouts.master',$masterData)
@section('page-title')
    <img src="/img/logo.png" alt="EPS Genco 3" alt width="15%">
    <img src="/img/dao.png" alt="EPS Genco" alt width="15%" align="right"/>
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
            <li class="active">Yêu Cầu Được Giao</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="row main-layout">
        <div class="col-md-12">
            <div class="box">
                <div class="box-title">
                    <h3><i class="fa fa-table"></i>Danh sách Yêu cầu Được giao</h3>
                </div>
                <div class="box-content">
                    <div class="clearfix"></div>
                    <div class="table-responsive" style="border:0">
                        <table class="table table-advance" id="danh-sach-yeu-cau">
                            <thead>
                            <tr>
                                <th style="width:30px">STT</th>
                                {{--<th style="width:18px"><input type="checkbox" /></th>--}}
                                <th class="text-center">Ngày tạo</th>
                                <th style="width:450px">Tiêu đề</th>
                                <th>Người tạo</th>
                                <th>Phòng ban</th>
                                <th class="text-center">Hạn xử lý</th>
                                <th class="text-center">Độ ưu tiên</th>
                                <th class="text-center">Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (isset($dsRequest))
                                @foreach($dsRequest as $key=>$val)
                                    <tr class="table-flag-blue">
                                        <td class="{{ $val->class }}">{{$key+1}}</td>
                                        {{--<td><input type="checkbox" /></td>--}}
                                        <td class="text-center {{ $val->class }}">{{date('d-m-Y', strtotime($val->ngay_tao))}}</td>
                                        <td><a href='#' data-content="{{ $val->ma_yeu_cau }}" data-toggle="modal" data-target="#dsRequestDetail"  class="{{ $val->class }}">{{$val->tieu_de}}</a></td>
                                        <td class="{{ $val->class }}">{{$val->user['name']}}</td>
                                        <td class="{{ $val->class }}">{{$val->phong_ban['ten_phong_ban']}}</td>
                                        <td class="text-center {{ $val->class }}">{{ date('d-m-Y', strtotime($val->han_xu_ly )) }}</td>
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
    <div class="modal fade" id="dsRequestDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                <form id="form-request" class="box-content form-horizontal">
                                        {{ csrf_field() }}
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
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Người xử lý</b></label>
                                        <div class="col-xs-12 col-sm-9 col-md-10 controls">
                                            <p id="nguoi_xu_ly" class="content-label"></p>
                                            <p id="subPerson" class="content-label"></p>
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
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Yêu Cầu</b></label>
                                        <div class="col-xs-12 col-sm-9 col-md-10 controls">
                                            <p id="yeu_cau_xu_ly" class="content-label auto-overflow"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Loại yêu cầu</b></label>
                                        <div class="col-xs-8 col-sm-9 col-md-4 col-sm-mb-1 controls">
                                            <p id="loai_yeu_cau" class="content-label "></p>
                                        </div>
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Ngày tạo</b></label>
                                        <div class="col-xs-7 col-sm-9 col-md-3 col-sm-mb-1 controls">
                                            <div id="ngay_tao_yc" class="input-group date col-sm-5 col-md-12">
                                                <input id="ngay_tao" name="ngay_tao" type="text" class="form-control" data-date-format="dd/mm/yyyy" disabled="disabled">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Trạng Thái</b></label>
                                        <div class="col-xs-7 col-sm-9 col-md-3 col-sm-mb-1 controls">
                                            <div class="input-group col-xs-12 col-sm-5 col-md-12">
                                                <select id="trang_thai" name="trang_thai" class="form-control" tabindex="1">
                                                    <option value="0">Chuyển xử lý</option>
                                                    <option value="1">Tiếp nhận</option>
                                                    <option value="2">Đang xử lý</option>
                                                    <option value="3">Hoàn Thành</option>
                                                    <option value="4">Từ chối</option>
                                                </select>
                                            </div>
                                        </div>
                                        <label class="col-xs-4 col-sm-3 col-md-2 col-md-push-1 control-label"><b>Hạn xử lý</b></label>
                                        <div class="col-xs-7 col-sm-9 col-md-3 col-md-push-1 controls">
                                            <div class="input-group date col-sm-5 col-md-12" id="han_xu_ly_yc">
                                                <input id="han_xu_ly" name="han_xu_ly" type="text" class="form-control" data-date-format="dd/mm/yyyy" disabled="disabled">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-4 col-sm-3 col-md-2 control-label"><b>Gia hạn</b></label>
                                        <div class="col-xs-7 col-sm-9 col-md-3 col-sm-mb-1 controls">
                                            <div class="input-group col-xs-12 col-sm-5 col-md-12">
                                                <div id="gia_han_yn" class="make-switch has-switch" data-label-icon="" data-on-label="<i class='fa fa-check fa fa-white'></i>" data-off-label="<i class='fa fa-times'></i>">
                                                    <input id="gia_han" name="gia_han" type="checkbox" disabled="disabled">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="col-xs-4 col-sm-3 col-md-2 col-md-push-1 control-label"><b>Gia hạn tới</b></label>
                                        <div class="col-xs-7 col-sm-9 col-md-3 col-md-push-1 controls">
                                            <div class="input-group date col-sm-5 col-md-12" id="ngay_gia_han_div">
                                                <input id="ngay_gia_han" name="ngay_gia_han" type="text" class="form-control" data-date-format="dd/mm/yyyy" disabled="disabled">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="div_gh_noi_dung" class="form-group" style="display: none;">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Thông tin gia hạn</b></label>
                                        <div class="col-xs-12 col-sm-9 col-md-10 controls">
                                            <textarea id="noi_dung_gia_han" name="noi_dung_gia_han" class="form-control ckeditor" rows="4"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>Phản hồi</b></label>
                                        <div class="col-xs-12 col-sm-9 col-md-10 controls">
                                            <textarea id="thong_tin_xu_ly" name="thong_tin_xu_ly" class="form-control ckeditor" rows="4"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label"><b>File đính kèm</b></label>
                                        <div class="col-xs-12 col-sm-9 col-md-10 controls">
                                            <div class="attach-file"></div>
                                            <input id="attachFile" type="file" name="attachFile[]" class="form-control" accept=".pdf, .jpg, .png, .xls, .xlsx, .doc, .docx, .ppt, .pptx" multiple>
                                        </div>
                                    </div>
                                </form>
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
