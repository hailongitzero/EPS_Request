<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class requestComplete extends Notification implements ShouldQueue
{
    use Queueable;
    public $tries = 3; // Max tries
    public $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
            ->subject(Lang::getFromJson('Thông báo hoàn thành xử lý yêu cầu.'))
            ->action(Lang::getFromJson('Chi tiết'), url(config('app.url') . '/request-detail/' . $this->data['ma_yeu_cau']))
            ->line(Lang::getFromJson('Người tạo '))
            ->line(Lang::getFromJson($this->data['nguoi_gui']))
            ->line(Lang::getFromJson('Phòng/PX '))
            ->line(Lang::getFromJson($this->data['phong_ban']))
            ->line(Lang::getFromJson('Người xử lý '))
            ->line(Lang::getFromJson($this->data['nguoi_xu_ly']))
            ->line(Lang::getFromJson('Thông tin XL'))
            ->line(Lang::getFromJson($this->data['thong_tin_xu_ly']))
            ->cc($this->data['cc_email'])
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
