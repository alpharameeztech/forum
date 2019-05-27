<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;

class CustomerAnotherSale extends Notification
{
    use Queueable;

    private $user;

    private $product;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $product)
    {
        $this->user = $user;

        $this->product = $product;
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
                ->subject('GREL New Sale From An Existing Customer')
                ->line('A new sale of ' . $this->product . ' just placed from an existing Customer with name:' . $this->user->name  . ' and email: ' . $this->user->email . '. For details please login.')
                ->action('Log In', url('https://me.grel.org/admin/mamango=1'))
                ->line('Thank you for using our application!');
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
