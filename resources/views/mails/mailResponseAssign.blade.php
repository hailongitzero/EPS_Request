<p>Xin chào</p>
<p>Yêu cầu của bạn đã được chuyển đến người xử lý: {{ $data['nguoi_xu_ly'] }}</p>
<p><b>{{ $data['tieu_de'] }}</b></p><br/>
<p><b>Thông tin yêu cầu xử lý</b></p>
<p>{!! $data['yeu_cau_xu_ly'] !!}</p><br/>
<p><b>Nội dung yêu cầu</b></p>
<p>{!! $data['noi_dung'] !!}</p>
<p>Click vào <a href="{{ url('request-detail/'.$data['ma_yeu_cau']) }}">đây</a> để xem thông tin chi tiết.</p>
<p>Đây là email hệ thống, vui lòng không trả lời mail này.</p>
<p>Cảm ơn</p>