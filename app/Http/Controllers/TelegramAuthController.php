<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TelegramAuthController extends Controller
{
    public function handle(Request $request)
{
    

    try {
        // Проверяем наличие всех обязательных параметров
        if (!$request->has(['id', 'first_name', 'hash', 'auth_date'])) {
            throw new \Exception("Недостаточно данных для авторизации через Telegram");
        }

        // Валидация данных Telegram
        $authData = $request->all();
        $checkHash = $authData['hash'];
        unset($authData['hash']);

        $dataCheckArr = [];
        foreach ($authData as $key => $value) {
            $dataCheckArr[] = $key . '=' . $value;
        }

        sort($dataCheckArr);
        $dataCheckString = implode("\n", $dataCheckArr);
        $secretKey = hash('sha256', config('services.telegram.bot_token'), true);
        $hash = hash_hmac('sha256', $dataCheckString, $secretKey);

        if (!hash_equals($hash, $checkHash)) {
            throw new \Exception('Неверная подпись данных Telegram');
        }

        // Проверяем срок действия данных (не старше 1 дня)
        if (time() - $authData['auth_date'] > 86400) {
            throw new \Exception('Данные авторизации устарели');
        }

        // Ищем пользователя по telegram_id или email
        $user = User::where('telegram_id', $authData['id'])
                  ->orWhere('email', $authData['id'].'@telegram')
                  ->first();

        if (!$user) {
            // Создаем нового пользователя
            $user = User::create([
                'telegram_id' => $authData['id'],
                'name' => $authData['first_name'],
                'email' => $authData['id'].'@telegram',
                'password' => Hash::make(Str::random(32))
            ]);
        } else {
            // Обновляем существующего пользователя
            $user->update([
                'telegram_id' => $authData['id'],
                'name' => $authData['first_name']
            ]);
        }

        Auth::login($user, true);
        
        return redirect()->intended(route('dashboard'))
            ->with('status', 'Вы успешно вошли через Telegram');

    } catch (\Exception $e) {
        Log::error('Telegram auth error: ' . $e->getMessage());
        return redirect()->route('login')->withErrors([
            'telegram' => $e->getMessage()
        ]);
    }
}

    
}