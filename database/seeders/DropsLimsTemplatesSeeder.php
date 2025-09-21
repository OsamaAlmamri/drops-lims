<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DropsLimsTemplatesSeeder extends Seeder
{
    /**
     * يحدِّث/ينشئ التحاليل (Urine/CBC/Biochem/Coag/Serology)
     * ويملأ حقول template, worksheet_template, template_variables, javascript
     * الجدول حسب سكيمتك:
     * id, nomenclator_id, code(BIGINT), name, position, biochemical_unit(DOUBLE),
     * javascript(TEXT), template(TEXT), template_variables(JSON), worksheet_template(VARCHAR),
     * created_at, updated_at
     */
    public function run(): void
    {
        $NOMENCLATOR_ID = (int) env('LIMS_NOMENCLATOR_ID', 1);
        $now = now();

        // تعريف عناصر الكتالوج: الكود الرقمي، الاسم المعروض، اسم المتغير، النوع، الخيارات/الخطوة، الوحدة، النطاق، وتموضعه
        $items = [

            // ===== Urine - Macroscopic =====
            ['code'=>1110,'group'=>'URINE_MAC','pos'=>110,'name'=>'Ketone',           'var'=>'ketone',           'type'=>'select','options'=>['Negative','Trace','+','++','+++'],'unit'=>'','range'=>'Negative','step'=>null],
            ['code'=>1120,'group'=>'URINE_MAC','pos'=>120,'name'=>'Nitrite',          'var'=>'nitrite',          'type'=>'select','options'=>['Negative','Positive'],'unit'=>'','range'=>'Negative','step'=>null],
            ['code'=>1130,'group'=>'URINE_MAC','pos'=>130,'name'=>'Urobilinogen',     'var'=>'urobilinogen',     'type'=>'select','options'=>['Normal','Increased'],'unit'=>'','range'=>'Normal','step'=>null],
            ['code'=>1140,'group'=>'URINE_MAC','pos'=>140,'name'=>'Blood',            'var'=>'blood',            'type'=>'select','options'=>['Nil','Trace','+','++','+++'],'unit'=>'','range'=>'Nil','step'=>null],
            ['code'=>1150,'group'=>'URINE_MAC','pos'=>150,'name'=>'Haemoglobin',      'var'=>'haemoglobin',      'type'=>'select','options'=>['Nil','+','++','+++'],'unit'=>'','range'=>'Nil','step'=>null],
            ['code'=>1160,'group'=>'URINE_MAC','pos'=>160,'name'=>'Appearance',       'var'=>'appearance',       'type'=>'select','options'=>['Clear','Turbid'],'unit'=>'','range'=>'Clear','step'=>null],
            ['code'=>1170,'group'=>'URINE_MAC','pos'=>170,'name'=>'Protein',          'var'=>'protein',          'type'=>'select','options'=>['Negative','Trace','+','++','+++'],'unit'=>'','range'=>'Negative / Trace','step'=>null],
            ['code'=>1180,'group'=>'URINE_MAC','pos'=>180,'name'=>'Bilirubin',        'var'=>'bilirubin',        'type'=>'select','options'=>['Negative','(+)','(++)','(+++)'],'unit'=>'','range'=>'Negative','step'=>null],
            ['code'=>1190,'group'=>'URINE_MAC','pos'=>190,'name'=>'Sugar (Glucose)',  'var'=>'sugar',            'type'=>'select','options'=>['Negative','+','++','+++'],'unit'=>'','range'=>'Negative','step'=>null],
            ['code'=>1200,'group'=>'URINE_MAC','pos'=>200,'name'=>'pH',               'var'=>'ur_ph',            'type'=>'number','options'=>null,'unit'=>'','range'=>'5.0 – 8.0','step'=>'0.1'],
            ['code'=>1210,'group'=>'URINE_MAC','pos'=>210,'name'=>'Specific Gravity', 'var'=>'specific_gravity', 'type'=>'number','options'=>null,'unit'=>'','range'=>'1.005 – 1.030','step'=>'0.001'],

            // ===== Urine - Microscopic =====
            ['code'=>1310,'group'=>'URINE_MIC','pos'=>210,'name'=>'WBCs (/HPF)',      'var'=>'wbc_hpf',          'type'=>'number','options'=>null,'unit'=>'/HPF','range'=>'0–5','step'=>'1'],
            ['code'=>1320,'group'=>'URINE_MIC','pos'=>220,'name'=>'Epithelial Cells', 'var'=>'epithelial_cells', 'type'=>'select','options'=>['Few','Moderate','Many'],'unit'=>'','range'=>'Few','step'=>null],
            ['code'=>1330,'group'=>'URINE_MIC','pos'=>230,'name'=>'RBCs (/HPF)',      'var'=>'rbc_hpf',          'type'=>'number','options'=>null,'unit'=>'/HPF','range'=>'0–2','step'=>'1'],

            // ===== Hematology - CBC =====
            ['code'=>2110,'group'=>'CBC','pos'=>310,'name'=>'Hb',                      'var'=>'hb',         'type'=>'number','options'=>null,'unit'=>'g/dL','range'=>'12.2–18.1','step'=>'0.1'],
            ['code'=>2120,'group'=>'CBC','pos'=>320,'name'=>'PCV (Packed Cell Volume)','var'=>'pcv',        'type'=>'number','options'=>null,'unit'=>'%','range'=>'37.7–53.7','step'=>'0.1'],
            ['code'=>2130,'group'=>'CBC','pos'=>330,'name'=>'WBC Count & Diff',        'var'=>'wbc',        'type'=>'number','options'=>null,'unit'=>'x10^9/L','range'=>'4.6–10.2','step'=>'0.1'],
            ['code'=>2140,'group'=>'CBC','pos'=>340,'name'=>'Neutrophil',              'var'=>'neutrophil', 'type'=>'number','options'=>null,'unit'=>'%','range'=>'50–70','step'=>'1'],
            ['code'=>2150,'group'=>'CBC','pos'=>350,'name'=>'Lymphocyte',              'var'=>'lymphocyte', 'type'=>'number','options'=>null,'unit'=>'%','range'=>'25–40','step'=>'1'],
            ['code'=>2160,'group'=>'CBC','pos'=>360,'name'=>'Monocyte',                'var'=>'monocyte',   'type'=>'number','options'=>null,'unit'=>'%','range'=>'2–8','step'=>'1'],
            ['code'=>2170,'group'=>'CBC','pos'=>370,'name'=>'Eosinophile',             'var'=>'eosinophile','type'=>'number','options'=>null,'unit'=>'%','range'=>'0–6','step'=>'1'],
            ['code'=>2180,'group'=>'CBC','pos'=>380,'name'=>'Basophile',               'var'=>'basophile',  'type'=>'number','options'=>null,'unit'=>'%','range'=>'0–2','step'=>'1'],
            ['code'=>2190,'group'=>'CBC','pos'=>390,'name'=>'Platelets count',         'var'=>'platelets',  'type'=>'number','options'=>null,'unit'=>'x10^9/L','range'=>'150–450','step'=>'1'],
            ['code'=>2200,'group'=>'CBC','pos'=>400,'name'=>'RBC (Red Blood Cells)',   'var'=>'rbc',        'type'=>'number','options'=>null,'unit'=>'x10^12/L','range'=>'4.0–6.1','step'=>'0.01'],
            ['code'=>2210,'group'=>'CBC','pos'=>410,'name'=>'MCV',                     'var'=>'mcv',        'type'=>'number','options'=>null,'unit'=>'fL','range'=>'80–97','step'=>'0.1'],
            ['code'=>2220,'group'=>'CBC','pos'=>420,'name'=>'MCH',                     'var'=>'mch',        'type'=>'number','options'=>null,'unit'=>'pg','range'=>'27–31.2','step'=>'0.1'],
            ['code'=>2230,'group'=>'CBC','pos'=>430,'name'=>'MCHC',                    'var'=>'mchc',       'type'=>'number','options'=>null,'unit'=>'g/dL','range'=>'30–35','step'=>'0.1'],
            ['code'=>2240,'group'=>'CBC','pos'=>440,'name'=>'RDW',                     'var'=>'rdw',        'type'=>'number','options'=>null,'unit'=>'%','range'=>'11.6–14.8','step'=>'0.1'],

            // ===== Biochemistry =====
            ['code'=>3110,'group'=>'BIOCHEM','pos'=>410,'name'=>'Sodium','var'=>'na','type'=>'number','options'=>null,'unit'=>'mmol/L','range'=>'135–155','step'=>'1'],
            ['code'=>3120,'group'=>'BIOCHEM','pos'=>420,'name'=>'Potassium','var'=>'k','type'=>'number','options'=>null,'unit'=>'mmol/L','range'=>'3.5–5.5','step'=>'0.1'],
            ['code'=>3130,'group'=>'BIOCHEM','pos'=>430,'name'=>'Albumin','var'=>'albumin','type'=>'number','options'=>null,'unit'=>'g/dL','range'=>'3.8–5.1','step'=>'0.1'],
            ['code'=>3140,'group'=>'BIOCHEM','pos'=>440,'name'=>'Creatinine','var'=>'creatinine','type'=>'number','options'=>null,'unit'=>'mg/dL','range'=>'0.4–1.2','step'=>'0.1'],
            // (اختياري إضافي من صورك)
            ['code'=>3150,'group'=>'BIOCHEM','pos'=>450,'name'=>'Calcium Serum','var'=>'calcium','type'=>'number','options'=>null,'unit'=>'mg/dL','range'=>'8.4–10.2','step'=>'0.1'],
            ['code'=>3160,'group'=>'BIOCHEM','pos'=>460,'name'=>'Bilirubin Total','var'=>'tbil','type'=>'number','options'=>null,'unit'=>'mg/dL','range'=>'0.2–1.2','step'=>'0.1'],
            ['code'=>3170,'group'=>'BIOCHEM','pos'=>470,'name'=>'Bilirubin Direct','var'=>'dbil','type'=>'number','options'=>null,'unit'=>'mg/dL','range'=>'0.0–0.3','step'=>'0.1'],
            ['code'=>3180,'group'=>'BIOCHEM','pos'=>480,'name'=>'ALT (GPT)','var'=>'alt','type'=>'number','options'=>null,'unit'=>'U/L','range'=>'≤ 55','step'=>'1'],
            ['code'=>3190,'group'=>'BIOCHEM','pos'=>490,'name'=>'AST (GOT)','var'=>'ast','type'=>'number','options'=>null,'unit'=>'U/L','range'=>'≤ 34','step'=>'1'],

            // ===== Coagulation =====
            ['code'=>4110,'group'=>'COAG','pos'=>510,'name'=>'Control PT','var'=>'control_pt','type'=>'number','options'=>null,'unit'=>'sec','range'=>'10–15','step'=>'0.1'],
            ['code'=>4120,'group'=>'COAG','pos'=>520,'name'=>'INR','var'=>'inr','type'=>'number','options'=>null,'unit'=>'','range'=>'0.8–1.5','step'=>'0.01'],
            ['code'=>4130,'group'=>'COAG','pos'=>530,'name'=>'Patient PT','var'=>'patient_pt','type'=>'number','options'=>null,'unit'=>'sec','range'=>'10–15','step'=>'0.1'],
            ['code'=>4140,'group'=>'COAG','pos'=>540,'name'=>'Control PTT','var'=>'control_ptt','type'=>'number','options'=>null,'unit'=>'sec','range'=>'25–43','step'=>'0.1'],
            ['code'=>4150,'group'=>'COAG','pos'=>550,'name'=>'Patient PTT','var'=>'patient_ptt','type'=>'number','options'=>null,'unit'=>'sec','range'=>'25–43','step'=>'0.1'],

            // ===== Serology =====
            ['code'=>5110,'group'=>'SERO','pos'=>610,'name'=>'Troponin I','var'=>'troponin_i','type'=>'number','options'=>null,'unit'=>'ng/mL','range'=>'≤ 0.3','step'=>'0.01'],
        ];

        // دالة تبني قالب الإدخال (worksheet) حسب النوع
        $buildWorksheet = function ($var, $type, $step = null, $options = null) {
            if ($type === 'select') {
                $opts = '';
                foreach ($options as $o) { $opts .= '<option value="'.e($o).'">'.e($o).'</option>'; }
                return '<select name="'.$var.'" id="'.$var.'" style="min-width:140px;">'.$opts.'</select>';
            }
            $stepAttr = $step ? ' step="'.$step.'"' : '';
            return '<input type="number" name="'.$var.'" id="'.$var.'"'.$stepAttr.' style="width:120px;" />';
        };

        // قالب الطباعة: جدول صغير لكل تحليل
        $buildTemplate = function ($name, $var, $unit, $range) {
            $unitSafe  = $unit !== '' ? e($unit) : '&mdash;';
            $rangeSafe = e($range);
            $nameSafe  = e($name);
            $cellVar   = '${'.$var.'}';
            return <<<HTML
<table style="width:100%; border-collapse:collapse; margin-bottom:4px;">
  <thead>
    <tr>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Test Name</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Result</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Unit</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Normal Range</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>{$nameSafe}</td>
      <td>{$cellVar}</td>
      <td>{$unitSafe}</td>
      <td>{$rangeSafe}</td>
    </tr>
  </tbody>
</table>
HTML;
        };

        // JS بسيط يلوّن المدخلات الرقمية الخارجة عن النطاق (اختياري)
        $buildJS = function ($var, $range) {
            // نحاول استخراج min/max إن وُجِدت
            if (preg_match('/([0-9]+\\.?[0-9]*)\\s*[–-]\\s*([0-9]+\\.?[0-9]*)/u', $range, $m)) {
                $min = $m[1]; $max = $m[2];
                return <<<JS
$(function(){
  var \$el = \$("[name='{$var}']");
  function chk(){
    var v = parseFloat(\$el.val());
    if (!isNaN(v) && (v < {$min} || v > {$max})) { \$el.css('background','#ffe6e6'); }
    else { \$el.css('background',''); }
  }
  \$el.on('input change', chk); chk();
});
JS;
            }
            return null;
        };

        DB::beginTransaction();
        try {
            foreach ($items as $it) {
                // تأكد من وجود السطر (أو أنشئه) ثم حدّث القوالب
                DB::table('determinations')->updateOrInsert(
                    [
                        'nomenclator_id' => $NOMENCLATOR_ID,
                        'code'           => (int) $it['code'],
                    ],
                    [
                        'name'             => $it['name'],
                        'position'         => (int) $it['pos'],
                        'biochemical_unit' => 1.00,
                        'updated_at'       => $now,
                        'created_at'       => $now,
                    ]
                );

                $worksheet   = $buildWorksheet($it['var'], $it['type'], $it['step'], $it['options']);
                $template    = $buildTemplate($it['name'], $it['var'], $it['unit'], $it['range']);
                $js          = $buildJS($it['var'], $it['range']);
                $varsPayload = [
                    [
                        'id'      => $it['var'],
                        'type'    => $it['type'],
                        'unit'    => $it['unit'],
                        'range'   => $it['range'],
                        'step'    => $it['step'],
                        'options' => $it['options'],
                    ],
                ];

                DB::table('determinations')
                    ->where('nomenclator_id', $NOMENCLATOR_ID)
                    ->where('code', (int) $it['code'])
                    ->update([
                        'worksheet_template' => $worksheet,
                        'template'           => $template,
                        'template_variables' => json_encode($varsPayload, JSON_UNESCAPED_UNICODE),
                        'javascript'         => $js,
                        'updated_at'         => $now,
                    ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
