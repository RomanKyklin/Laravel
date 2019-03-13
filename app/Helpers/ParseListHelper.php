<?php


namespace App\Helpers;


class ParseListHelper
{
    public $adv_id;
    public $title;
    public $href;
    public $price;
    public $description;
    public $create_date;

    public function toArray()
    {
        return [
          'adv_id' => $this->adv_id,
          'title' => $this->title,
          'href' => $this->href,
          'price' => $this->price,
          'description' => $this->description,
          'create_date' => $this->create_date
        ];
    }
}