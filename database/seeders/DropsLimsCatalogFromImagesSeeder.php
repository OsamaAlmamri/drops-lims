<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DropsLimsCatalogFromImagesSeeder extends Seeder
{
    /**
     * يضيف كتالوج تحاليل كما في الصور (Urine / CBC / Biochemistry / Coagulation / Serology)
     * متوافق مع MySQL. حقول الجدول حسب لقطة الشاشة:
     * id, nomenclator_id (BIGINT), code (BIGINT), name (VARCHAR),
     * position (INT), biochemical_unit (DOUBLE), javascript (TEXT),
     * template (TEXT), template_variables (JSON), worksheet_template (VARCHAR),
     * created_at, updated_at
     */
    public function run(): void
    {
        $NOMENCLATOR_ID = (int) env('LIMS_NOMENCLATOR_ID', 1);
        $now = now();

        /**
         * ملاحظة عن الأكواد:
         * اخترت نطاقات مرقمة لكل مجموعة لتفادي التعارض:
         * 1100.. للـ Urine Macro، 1300.. للـ Urine Micro
         * 2100.. للـ CBC، 3100.. للـ Biochemistry
         * 4100.. للـ Coagulation، 5100.. للـ Serology
         */
        $catalog = [

            // Urine Analysis – Macroscopic
            ['code'=>1110,'name'=>'Ketone',            'group'=>'URINE_MAC', 'pos'=>10],
            ['code'=>1120,'name'=>'Nitrite',           'group'=>'URINE_MAC', 'pos'=>20],
            ['code'=>1130,'name'=>'Urobilinogen',      'group'=>'URINE_MAC', 'pos'=>30],
            ['code'=>1140,'name'=>'Blood',             'group'=>'URINE_MAC', 'pos'=>40],
            ['code'=>1150,'name'=>'Haemoglobin',       'group'=>'URINE_MAC', 'pos'=>50],
            ['code'=>1160,'name'=>'Appearance',        'group'=>'URINE_MAC', 'pos'=>60],
            ['code'=>1170,'name'=>'Protein',           'group'=>'URINE_MAC', 'pos'=>70],
            ['code'=>1180,'name'=>'Bilirubin',         'group'=>'URINE_MAC', 'pos'=>80],
            ['code'=>1190,'name'=>'Sugar (Glucose)',   'group'=>'URINE_MAC', 'pos'=>90],
            ['code'=>1200,'name'=>'pH',                'group'=>'URINE_MAC', 'pos'=>100],
            ['code'=>1210,'name'=>'Specific Gravity',  'group'=>'URINE_MAC', 'pos'=>110],

            // Urine Analysis – Microscopic
            ['code'=>1310,'name'=>'WBCs (/HPF)',       'group'=>'URINE_MIC', 'pos'=>10],
            ['code'=>1320,'name'=>'Epithelial Cells',  'group'=>'URINE_MIC', 'pos'=>20],
            ['code'=>1330,'name'=>'RBCs (/HPF)',       'group'=>'URINE_MIC', 'pos'=>30],

            // Hematology – CBC
            ['code'=>2110,'name'=>'Hb',                           'group'=>'CBC', 'pos'=>10],
            ['code'=>2120,'name'=>'PCV (Packed Cell Volume)',     'group'=>'CBC', 'pos'=>20],
            ['code'=>2130,'name'=>'WBC Count & Diff',             'group'=>'CBC', 'pos'=>30],
            ['code'=>2140,'name'=>'Neutrophil',                   'group'=>'CBC', 'pos'=>40],
            ['code'=>2150,'name'=>'Lymphocyte',                   'group'=>'CBC', 'pos'=>50],
            ['code'=>2160,'name'=>'Monocyte',                     'group'=>'CBC', 'pos'=>60],
            ['code'=>2170,'name'=>'Eosinophile',                  'group'=>'CBC', 'pos'=>70],
            ['code'=>2180,'name'=>'Basophile',                    'group'=>'CBC', 'pos'=>80],
            ['code'=>2190,'name'=>'Platelets count',              'group'=>'CBC', 'pos'=>90],
            ['code'=>2200,'name'=>'RBC (Red Blood Cells)',        'group'=>'CBC', 'pos'=>100],
            ['code'=>2210,'name'=>'MCV',                          'group'=>'CBC', 'pos'=>110],
            ['code'=>2220,'name'=>'MCH',                          'group'=>'CBC', 'pos'=>120],
            ['code'=>2230,'name'=>'MCHC',                         'group'=>'CBC', 'pos'=>130],
            ['code'=>2240,'name'=>'RDW',                          'group'=>'CBC', 'pos'=>140],

            // Biochemistry – Basic
            ['code'=>3110,'name'=>'Sodium',        'group'=>'BIOCHEM', 'pos'=>10],
            ['code'=>3120,'name'=>'Potassium',     'group'=>'BIOCHEM', 'pos'=>20],
            ['code'=>3130,'name'=>'Albumin',       'group'=>'BIOCHEM', 'pos'=>30],
            ['code'=>3140,'name'=>'Creatinine',    'group'=>'BIOCHEM', 'pos'=>40],
            // عناصر ظهرت في إحدى الصور (اختياري لكنها مفيدة):
            ['code'=>3150,'name'=>'Calcium Serum',     'group'=>'BIOCHEM', 'pos'=>50],
            ['code'=>3160,'name'=>'Bilirubin Total',   'group'=>'BIOCHEM', 'pos'=>60],
            ['code'=>3170,'name'=>'Bilirubin Direct',  'group'=>'BIOCHEM', 'pos'=>70],
            ['code'=>3180,'name'=>'ALT (GPT)',         'group'=>'BIOCHEM', 'pos'=>80],
            ['code'=>3190,'name'=>'AST (GOT)',         'group'=>'BIOCHEM', 'pos'=>90],

            // Coagulation – PT/PTT
            ['code'=>4110,'name'=>'Control PT',    'group'=>'COAG', 'pos'=>10],
            ['code'=>4120,'name'=>'INR',           'group'=>'COAG', 'pos'=>20],
            ['code'=>4130,'name'=>'Patient PT',    'group'=>'COAG', 'pos'=>30],
            ['code'=>4140,'name'=>'Control PTT',   'group'=>'COAG', 'pos'=>40],
            ['code'=>4150,'name'=>'Patient PTT',   'group'=>'COAG', 'pos'=>50],

            // Serology
            ['code'=>5110,'name'=>'Troponin I',    'group'=>'SERO', 'pos'=>10],
        ];

        // قواعد ترتيب المجموعات داخل الـ PDF (اختياري)
        $groupBase = [
            'URINE_MAC' => 100,
            'URINE_MIC' => 200,
            'CBC'       => 300,
            'BIOCHEM'   => 400,
            'COAG'      => 500,
            'SERO'      => 600,
        ];

        DB::beginTransaction();
        try {
            foreach ($catalog as $row) {
                $position = ($groupBase[$row['group']] ?? 100) + $row['pos'];

                DB::table('determinations')->updateOrInsert(
                    [
                        'nomenclator_id' => $NOMENCLATOR_ID,
                        'code'           => (int) $row['code'],   // BIGINT
                    ],
                    [
                        'name'             => $row['name'],
                        'position'         => (int) $position,
                        'biochemical_unit' => 1.00,               // تسعيرة/معامل افتراضي (عدّلها لاحقًا لو تحتاج)
                        'javascript'       => null,
                        'template'         => null,
                        // اترك template_variables و worksheet_template فارغة الآن (تضبطها من واجهة القوالب)
                        'updated_at'       => $now,
                        'created_at'       => $now,
                    ]
                );
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
