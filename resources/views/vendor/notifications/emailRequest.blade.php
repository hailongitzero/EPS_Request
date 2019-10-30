@component('mail::message')

{{-- Intro Lines --}}
@foreach ($introLines as $line)
    {!! $line !!}

@endforeach

@component('mail::status', ['ma_trang_thai' => $ma_trang_thai, 'trang_thai'=>$trang_thai, 'tieu_de' => $tieu_de])
@endcomponent


{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::buttonCustom', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@isset($outroLines)
<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td align="center">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background: #e8edf5;">
                            @foreach ($outroLines as $key=>$line)
                                @if($key % 2 == 0)
                                <tr style="height: 50px;">
                                    <td width="30%" style="color: #00204d; font-weight: 700; padding-left: 20px; {{ $key < count($outroLines)-2 ? 'border-bottom: 1px solid #d1dcec;' : '' }}">
                                        {!! $line !!}
                                    </td>
                                @else
                                    <td style="color: #00204d; font-weight: 600; {{ $key < count($outroLines)-1 ? 'border-bottom: 1px solid #d1dcec;' : '' }}">
                                        {!! $line !!}
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endisset

@component('mail::subcopyCustom')
@endcomponent
@endcomponent
