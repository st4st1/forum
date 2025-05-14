<?php
// app/Services/ModerationService.php
namespace App\Services;

use Illuminate\Support\Facades\Config;

class ModerationService
{
    protected $badWords;

    public function __construct()
    {
        $this->badWords = Config::get('profanity.bad_words');
    }

    public function moderateText($text)
    {
        $words = explode(' ', $text);
        foreach ($words as $word) {
            if (in_array(strtolower($word), $this->badWords)) {
                return false;
            }
        }
        return true;
    }
}