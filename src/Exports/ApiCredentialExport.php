<?php

namespace Fleetbase\Exports;

use Fleetbase\Models\ApiCredential;
use Maatwebsite\Excel\Concerns\FromCollection;
<<<<<<< HEAD
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ApiCredentialExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
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

class ApiCredentialExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
>>>>>>> origin/main
    public function map($apiCredential): array
    {
        return [
            $apiCredential->name,
            $apiCredential->key,
            $apiCredential->secret,
            $apiCredential->test_mode ? 'Test' : 'Live',
<<<<<<< HEAD
            $apiCredential->expires_at ? $apiCredential->expires_at : 'Never',
            $apiCredential->last_used_at ? $apiCredential->last_used_at : 'Never',
            $apiCredential->created_at,
=======
            $apiCredential->expires_at ? Date::dateTimeToExcel($apiCredential->expires_at) : 'Never',
            $apiCredential->last_used_at ? Date::dateTimeToExcel($apiCredential->last_used_at) : 'Never',
            Date::dateTimeToExcel($apiCredential->created_at),
>>>>>>> origin/main
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Public Key',
            'Secret Key',
            'Environment',
<<<<<<< HEAD
            'Expiry Date',
            'Last Used',
            'Date Created',
=======
            'Expiry',
            'Last Used',
            'Created',
>>>>>>> origin/main
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
<<<<<<< HEAD
        if ($this->selections) {
            return ApiCredential::whereIn('uuid', $this->selections)->get();
        }

=======
>>>>>>> origin/main
        return ApiCredential::where('company_uuid', session('company'))->get();
    }
}
