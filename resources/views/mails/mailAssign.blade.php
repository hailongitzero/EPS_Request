<p>Xin chào</p>
<p>Bạn có 1 yêu cầu xử lý từ {{ $data['nguoi_gui'] }}</p>
<p><b>{{ $data['tieu_de'] }}</b></p><br/>
<p><b>Thông tin yêu cầu xử lý</b></p>
<p>{!! $data['yeu_cau_xu_ly'] !!}</p><br/>
<p><b>Nội dung yêu cầu</b></p>
<p>{!! $data['noi_dung'] !!}</p>
<p>Click vào <a href="{{ url('request-update/'.$data['ma_yeu_cau']) }}">đây</a> để xem thông tin chi tiết.</p>
<p>Đây là email hệ thống, vui lòng không trả lời mail này.</p>
<p>Cảm ơn</p>