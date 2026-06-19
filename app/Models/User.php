<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLES = [
        'owner'    => 'Owner',
        'mechanic' => 'Mekanik',
        'cashier'  => 'Kasir',
        'customer' => 'Pelanggan',
    ];

    /** Role-role yang memerlukan kode verifikasi saat registrasi */
    public const INTERNAL_ROLES = ['owner', 'mechanic', 'cashier'];

    public const MAX_BALANCE = 500000000;

    public const DEFAULT_AVATAR = 'profil/Profil 1 ( L ).webp';

    public const LEGACY_AVATAR_MAP = [
        'avatar_1.png' => 'profil/Profil 1 ( L ).webp',
        'avatar_2.png' => 'profil/Profil 1 ( P ).webp',
        'avatar_3.png' => 'profil/Profil 2 ( L ).webp',
        'avatar_4.png' => 'profil/Profil 2 ( P ).webp',
    ];

    public const PROFILE_AVATARS = [
        ['value' => 'profil/Profil 1 ( P ).webp', 'label' => 'Profil 1 P', 'side' => 'P'],
        ['value' => 'profil/Profil 2 ( P ).webp', 'label' => 'Profil 2 P', 'side' => 'P'],
        ['value' => 'profil/Profil 3 ( P ).webp', 'label' => 'Profil 3 P', 'side' => 'P'],
        ['value' => 'profil/Profil 4 ( P ).webp', 'label' => 'Profil 4 P', 'side' => 'P'],
        ['value' => 'profil/Hinata (P).webp', 'label' => 'Hinata P', 'side' => 'P'],
        ['value' => 'profil/Profil 1 ( L ).webp', 'label' => 'Profil 1 L', 'side' => 'L'],
        ['value' => 'profil/Profil 2 ( L ).webp', 'label' => 'Profil 2 L', 'side' => 'L'],
        ['value' => 'profil/Profil 3 ( L ).webp', 'label' => 'Profil 3 L', 'side' => 'L'],
        ['value' => 'profil/Profil 4 ( L ).webp', 'label' => 'Profil 4 L', 'side' => 'L'],
        ['value' => 'profil/Profil 5 ( L ).webp', 'label' => 'Profil 5 L', 'side' => 'L'],
    ];

    protected $fillable = [
        'name',
        'username',
        'avatar',
        'email',
        'phone',
        'role',
        'balance',
        'password',
        'withdraw_pin',
        'failed_login_attempts',
        'locked_until',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'customer_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function isRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function roleLabel(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    public static function avatarOptions(): array
    {
        return self::PROFILE_AVATARS;
    }

    public static function allowedAvatarValues(): array
    {
        return array_column(self::PROFILE_AVATARS, 'value');
    }

    public static function normalizeAvatar(?string $avatar): string
    {
        if ($avatar && isset(self::LEGACY_AVATAR_MAP[$avatar])) {
            return self::LEGACY_AVATAR_MAP[$avatar];
        }

        if ($avatar && in_array($avatar, self::allowedAvatarValues(), true)) {
            return $avatar;
        }

        return self::DEFAULT_AVATAR;
    }

    public function avatarPath(): string
    {
        return self::normalizeAvatar($this->avatar);
    }

    /**
     * Cek apakah role masih bisa mendaftar (belum melebihi batas).
     */
    public static function canRegisterRole(string $role): bool
    {
        $limit = config("role_verification.limits.{$role}");

        if ($limit === null) {
            return true; // tidak ada batas
        }

        return self::where('role', $role)->count() < $limit;
    }

    public function addBalance(float $amount): void
    {
        $this->balance = min(self::MAX_BALANCE, (float) $this->balance + $amount);
        $this->save();
    }

    public function remainingBalanceCapacity(): float
    {
        return max(0, self::MAX_BALANCE - (float) $this->balance);
    }

    public function canReceiveBalance(float $amount): bool
    {
        return $amount <= $this->remainingBalanceCapacity();
    }

    /**
     * Mengembalikan jumlah fee servis kasir yang dapat ditarik langsung ke ZeroPay.
     * Mengikuti prinsip OOP dengan menyembunyikan perhitungan internal (encapsulation).
     */
    public function withdrawableFees(): int
    {
        if ($this->role !== 'cashier') {
            return 0;
        }
        // Gaji harian Rp 50.000 hanya didapat saat kasir logout, tidak bisa ditarik
        return max(0, $this->unclaimed_salary - 50000);
    }

    /**
     * Memproses penarikan fee servis langsung ke saldo ZeroPay.
     * Melakukan validasi internal dan state transition secara aman.
     */
    public function withdrawFeesToZeroPay(int $amount): void
    {
        if ($amount < 1000) {
            throw new \InvalidArgumentException('Minimal penarikan fee adalah Rp 1.000.');
        }

        if ($amount > $this->withdrawableFees()) {
            throw new \InvalidArgumentException('Jumlah penarikan melebihi fee yang tersedia.');
        }

        $this->decrement('unclaimed_salary', $amount);
        $this->balance = min(self::MAX_BALANCE, (float) $this->balance + $amount);
        $this->save();
    }

    /**
     * Ambil kode verifikasi yang diperlukan untuk suatu role.
     * Return null jika tidak diperlukan.
     */
    public static function verificationCodeFor(string $role): ?string
    {
        if (! in_array($role, self::INTERNAL_ROLES, true)) {
            return null;
        }

        return config("role_verification.codes.{$role}");
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'locked_until'      => 'datetime',
            'password'          => 'hashed',
            'withdraw_pin'      => 'hashed',
        ];
    }
}

