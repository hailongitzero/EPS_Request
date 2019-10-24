<?php
/**
 * Created by PhpStorm.
 * User: HaiLong
 * Date: 10/24/2019
 * Time: 2:15 PM
 */
?>
@extends('layouts.master')
@section('page-title')
    {{--<h1><i class="fa fa-file-o"></i> Trang Chủ</h1>--}}
    {{--<h4>Thống kê số liệu</h4>--}}
    <img src="/img/logo.png" alt="EPS Genco 3">
@endsection

@section('breadcrumb')
    <ul class="breadcrumb">
        <li class="active"><i class="fa fa-home"></i> Hướng dẫn sử dụng</li>
    </ul>
@endsection

@section('content')
    @include('ckfinder::setup')
    <div id="ckfinder-widget">
    </div>
    <script>
        CKFinder.widget( 'ckfinder-widget', {
            width: '100%',
            height: 700
        } );
    </script>
@endsection