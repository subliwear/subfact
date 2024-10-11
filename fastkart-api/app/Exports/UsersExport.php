<?php

namespace App\Exports;

use App\Models\User;
use App\Enums\RoleEnum;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::role(RoleEnum::CONSUMER)->whereNull('deleted_at')->latest('created_at')->get();
    }

    public function columns(): array
    {
        return ["id","name", "email", "country_code", "phone", "status", "profile_image", "created_at"];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->country_code,
            $user->phone,
            $user->status,
            $user->profile_image?->original_url,
            $user->created_at
        ];
    }

    public function headings(): array
    {
        return $this->columns();
    }
}
