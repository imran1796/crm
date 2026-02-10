<?php

namespace App\Imports;

use App\Models\{Asset, AssetCategory, AssetSubCategory, Branch, AssetLocation, User};
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AssetImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            try {
                $indexx = 0;
                foreach ($rows as $index => $row) {
                    if(!isset($row['cat'])){continue;}

                    // 1. Category
                    $category = AssetCategory::firstOrCreate(
                        ['name' => trim($row['cat'] )]
                    );

                    // 2. SubCategory
                    $subCategory = AssetSubCategory::firstOrCreate(
                        [
                            'name' => trim($row['sub_cat'] ),
                            'category_id' => $category->id,
                        ]
                    );

                    // 3. Branch
                    $branch = null;
                    if (!empty($row['br'])) {
                        $branch = Branch::firstOrCreate(
                            ['short_name' => trim($row['br'])],
                        );
                    }

                    // 4. Location
                    $location = null;
                    if (!empty($row['location'])) {
                        $location = AssetLocation::firstOrCreate(
                            ['code' => trim($row['location'])],
                        );
                    }

                    // 6. Asset
                    Asset::updateOrCreate(
                        ['code' => trim($row['item'])],
                        [
                            'sub_category_id' => $subCategory->id,
                            'branch_id'       => $branch?->id,
                            'location_id'     => $location?->id,
                            'acquired_date' => !empty($row['acquired_date'])
                                ? (is_numeric($row['acquired_date'])
                                    ? Carbon::instance(Date::excelToDateTimeObject($row['acquired_date']))
                                    : Carbon::parse($row['acquired_date']))
                                : null,
                            'purchase_price'  => $row['purchase_price'] ?? null,
                            'manufacturer'    => $row['manufacturer'] ?? null,
                            'model'           => $row['model'] ?? null,
                            'status'          => 'in_use',
                        ]
                    );
                }
                $indexx++;
            } catch (\Throwable $th) {
                dd($th->getMessage());
            }
        });
    }
}
