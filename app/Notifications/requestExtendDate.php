<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class requestExtendDate extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::getFromJson('Thông báo yêu cầu gia hạn xử lý.'))
            ->action(Lang::getFromJson('Chi tiết'), url(config('app.url') . '/request-update/' . $this->data['ma_yeu_cau']))
            ->line(Lang::getFromJson('Người tạo: '))
            ->line(Lang::getFromJson($this->data['nguoi_gui']))
            ->line(Lang::getFromJson('Phòng ban: '))
            ->line(Lang::getFromJson($this->data['phong_ban']))
            ->line(Lang::getFromJson('Người chuyển xử lý: '))
            ->line(Lang::getFromJson($this->data['nguoi_xu_ly']))
            ->line(Lang::getFromJson('Ngày gửi: '))
            ->line(Lang::getFromJson($this->data['ngay_tao']))
            ->line(Lang::getFromJson('Ngày chuyển xử lý: '))
            ->line(date('d/m/Y h:i:s'))
            ->markdown('vendor.notifications.emailRequest', ['ma_trang_thai' => $this->data['ma_trang_thai'], 'trang_thai' => $this->data['trang_thai'], 'tieu_de' => $this->data['tieu_de']]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
