<?php

use App\Models\ProductView;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('user:make-admin {email} {--revoke : Remove admin access instead}', function (string $email) {
    $user = User::query()->where('email', $email)->first();

    if ($user === null) {
        $this->error("ไม่พบผู้ใช้อีเมล {$email} ให้สมัครสมาชิกก่อน");

        return 1;
    }

    $user->forceFill(['is_admin' => ! $this->option('revoke')])->save();

    $this->info($user->is_admin ? "{$email} เป็นแอดมินแล้ว" : "ยกเลิกสิทธิ์แอดมินของ {$email} แล้ว");

    return 0;
})->purpose('Grant or revoke admin access for a registered user');

Artisan::command('views:clear-samples', function () {
    $deleted = ProductView::query()->where('is_sample', true)->delete();

    $this->info("ลบคลิกตัวอย่างแล้ว {$deleted} รายการ คลิกจริงยังอยู่ครบ");

    return 0;
})->purpose('Delete sample product clicks created by ProductViewSampleSeeder');
