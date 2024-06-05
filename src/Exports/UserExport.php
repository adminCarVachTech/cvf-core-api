<?php

namespace Fleetbase\Exports;

use Fleetbase\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
<<<<<<< HEAD
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UserExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    protected array $selections = [];

    public function __construct(array $selections = [])
    {
        $this->selections = $selections;
    }

=======
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UserExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
>>>>>>> origin/main
    public function map($user): array
    {
        return [
            $user->name,
            $user->company_name,
<<<<<<< HEAD
            $user->email,
            $user->phone,
            $user->country,
            $user->timezone,
            $user->ip_address,
            $user->last_login,
            $user->email_verified_at ? $user->email_verified_at : 'Never',
            $user->created_at,
=======
            $user->last_login,
            $user->email_verified_at ? Date::dateTimeToExcel($user->email_verified_at) : 'Never',
            Date::dateTimeToExcel($user->created_at),
>>>>>>> origin/main
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Company',
<<<<<<< HEAD
            'Email',
            'Phone',
            'Country',
            'Timezone',
            'IP Address',
            'Last Login',
            'Email Verified At',
            'Date Created',
=======
            'Last Login',
            'Email Verified At',
            'Created',
>>>>>>> origin/main
        ];
    }

    public function columnFormats(): array
    {
        return [
<<<<<<< HEAD
            'D' => '+#',
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
=======
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
>>>>>>> origin/main
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
<<<<<<< HEAD
        if (!empty($this->selections)) {
            return User::where('company_uuid', session('company'))->whereIn('uuid', $this->selections)->get();
        }

=======
>>>>>>> origin/main
        return User::where('company_uuid', session('company'))->get();
    }
}
