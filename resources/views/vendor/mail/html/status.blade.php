<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td align="center">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td>
                                    @if( $ma_trang_thai == '0')
                                        <img src="../img/icon_plus.png" alt="">
                                    @elseif($ma_trang_thai == '1' || $ma_trang_thai == '2')
                                        <img src="../img/icon_handle.png" alt="">
                                    @elseif($ma_trang_thai == '3')
                                        <img src="../img/icon_success.png" alt="">
                                    @elseif($ma_trang_thai == '4')
                                        <img src="../img/icon_fail.png" alt="">
                                    @elseif($ma_trang_thai == '5')
                                        <img src="../img/icon_success.png" alt="">
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
