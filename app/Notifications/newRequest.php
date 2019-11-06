<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class newRequest extends Notification implements ShouldQueue
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
            ->subject(Lang::getFromJson('Thông báo tiếp nhận yêu cầu mới.'))
            ->action(Lang::getFromJson('Chi tiết'), url(config('app.url').'/request-assign/'.$this->data['ma_yeu_cau']))
            ->line(Lang::getFromJson('Họ tên: '))
            ->line(Lang::getFromJson($this->data['nguoi_gui']))
            ->line(Lang::getFromJson('Phòng ban: '))
            ->line(Lang::getFromJson($this->data['phong_ban']))
            ->line(Lang::getFromJson('Ngày gửi: '))
            ->line(Lang::getFromJson($this->data['ngay_tao']))
            ->cc($this->data['cc_email'])
            ->markdown('vendor.notifications.emailRequest', ['ma_trang_thai' => $this->data['ma_trang_thai'], 'trang_thai'=> 'Yêu cầu mới', 'tieu_de' => $this->data['tieu_de']]);
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
