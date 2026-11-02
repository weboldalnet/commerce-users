<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A webshop vásárlói fiókjai, a címeik és a jelszó-visszaállító tokenek.
 *
 * Saját táblák, NEM a Laravel `users` táblája: a core-ban az a `web` guardé, és egy
 * projekt használhatja másra — a vásárlói fiók külön guardon (`commerce_user`) fut.
 *
 * Idempotens (hasTable-őr) és PostgreSQL 9.4-kompatibilis: nincs `IF NOT EXISTS`.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('public.commerce_users')) {
            Schema::create('public.commerce_users', function (Blueprint $table) {
                $table->id();
                $table->string('last_name');
                $table->string('first_name');
                // kisbetűsítve tárolva, így az egyediség kis- és nagybetűtől független
                $table->string('email')->unique();
                $table->string('phone', 30)->nullable();
                $table->string('password');
                $table->timestamp('email_verified_at')->nullable();
                // az admin letilthatja a fiókot; letiltva nem lehet belépni
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_login_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('public.commerce_user_addresses')) {
            Schema::create('public.commerce_user_addresses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('commerce_user_id')->index();
                $table->string('type', 20); // shipping | billing
                $table->string('label', 100)->nullable(); // pl. Otthon, Iroda
                $table->string('name');
                $table->string('company')->nullable();
                $table->string('tax_number', 30)->nullable();
                $table->string('phone', 30)->nullable();
                $table->string('country', 2)->default('HU');
                $table->string('zip', 10);
                $table->string('city', 100);
                $table->string('address');
                $table->boolean('is_default')->default(false);
                $table->timestamps();

                $table->foreign('commerce_user_id')->references('id')->on('public.commerce_users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('public.commerce_user_password_resets')) {
            Schema::create('public.commerce_user_password_resets', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('public.commerce_user_password_resets');
        Schema::dropIfExists('public.commerce_user_addresses');
        Schema::dropIfExists('public.commerce_users');
    }
};
