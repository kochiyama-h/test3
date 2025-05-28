<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Item;

class TransactionCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $buyer;
    public $item;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $seller, User $buyer, Item $item)
    {
        $this->seller = $seller;
        $this->buyer = $buyer;
        $this->item = $item;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('取引が完了しました - ' . $this->item->name)
                    ->view('emails.transaction_completed')
                    ->with([
                        'sellerName' => $this->seller->name,
                        'buyerName' => $this->buyer->name,
                        'itemName' => $this->item->name,
                        'itemPrice' => $this->item->price,
                    ]);
    }
}