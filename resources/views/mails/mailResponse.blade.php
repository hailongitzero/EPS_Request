<p>Xin chào</p>
<p>Yêu cầu đã được xử lý - {{ $data['trang_thai']}}</p>
<p><b>{{ $data['tieu_de'] }}</b></p><br/>
<p><b>Thông tin trả lời</b></p>
<p>{!! $data['thong_tin_xu_ly'] !!}</p><br/>
<p><b>Nội dung yêu cầu</b></p>
<p>{!! $data['noi_dung'] !!}</p><br/>
<p>Click vào <a href="{{ url('request-detail/'.$data['ma_yeu_cau']) }}">đây</a> để xem thông tin chi tiết.</p>
<p>Đây là email hệ thống, vui lòng không trả lời mail này.</p>
<p>Cảm ơn</p>