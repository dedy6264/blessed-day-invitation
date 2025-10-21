<?php

namespace App\Imports;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class GuestsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $coupleId;
    protected $weddingEventId;
    protected $importedCount = 0;

    public function __construct($coupleId, $weddingEventId)
    {
        $this->coupleId = $coupleId;
        $this->weddingEventId = $weddingEventId;
    }

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Return the guest model - Laravel Excel will save it
        return new Guest([
            'couple_id' => $this->coupleId,
            'name' => $row['name'] ?? null,
            'email' => $row['email'] ?? null,
            'phone' => $row['phone'] ?? null,
            'guest_index' => $this->generateGuestIndex($row['phone'] ?? null),
        ]);
    }

    /**
     * Generate guest index based on couple ID and phone number
     */
    private function generateGuestIndex($phone)
    {
        $guestIndex = null;
        if ($phone) {
            $guestIndex = $this->coupleId . '_' . preg_replace('/[^0-9]/', '', $phone);
        }
        return $guestIndex;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
        ];
    }

    /**
     * Get the count of imported guests
     */
    public function getImportedCount()
    {
        return $this->importedCount;
    }
}
