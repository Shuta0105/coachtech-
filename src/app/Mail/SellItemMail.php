<?php

namespace App\Mail;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SellItemMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public Item $new_item
    ) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('出品が完了しました')
            ->view('mail.sell-item');
    }
}
