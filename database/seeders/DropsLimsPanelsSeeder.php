<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DropsLimsPanelsSeeder extends Seeder
{
    public function run(): void
    {
        $NOMENCLATOR_ID = (int) env('LIMS_NOMENCLATOR_ID', 1);
        $now = now();

        /* =====  المعرّفات والمتغيرات لكل لوحة  ===== */

        // Urine Analysis
        $urineMacro = [
            ['id'=>'ketone',           'label'=>'Ketone',            'type'=>'select', 'options'=>['Negative','Trace','+','++','+++'], 'unit'=>'',   'range'=>'Negative'],
            ['id'=>'nitrite',          'label'=>'Nitrite',           'type'=>'select', 'options'=>['Negative','Positive'],              'unit'=>'',   'range'=>'Negative'],
            ['id'=>'urobilinogen',     'label'=>'Urobilinogen',      'type'=>'select', 'options'=>['Normal','Increased'],               'unit'=>'',   'range'=>'Normal'],
            ['id'=>'blood',            'label'=>'Blood',             'type'=>'select', 'options'=>['Nil','Trace','+','++','+++'],       'unit'=>'',   'range'=>'Nil'],
            ['id'=>'haemoglobin',      'label'=>'Haemoglobin',       'type'=>'select', 'options'=>['Nil','+','++','+++'],               'unit'=>'',   'range'=>'Nil'],
            ['id'=>'appearance',       'label'=>'Appearance',        'type'=>'select', 'options'=>['Clear','Turbid'],                   'unit'=>'',   'range'=>'Clear'],
            ['id'=>'protein',          'label'=>'Protein',           'type'=>'select', 'options'=>['Negative','Trace','+','++','+++'],  'unit'=>'',   'range'=>'Negative / Trace'],
            ['id'=>'bilirubin',        'label'=>'Bilirubin',         'type'=>'select', 'options'=>['Negative','(+)','(++)','(+++)'],    'unit'=>'',   'range'=>'Negative'],
            ['id'=>'sugar',            'label'=>'Sugar (Glucose)',   'type'=>'select', 'options'=>['Negative','+','++','+++'],          'unit'=>'',   'range'=>'Negative'],
            ['id'=>'ur_ph',            'label'=>'pH',                'type'=>'number', 'step'=>'0.1',                                   'unit'=>'',   'range'=>'5.0 - 8.0'],
            ['id'=>'specific_gravity', 'label'=>'Specific Gravity',  'type'=>'number', 'step'=>'0.001',                                 'unit'=>'',   'range'=>'1.005 - 1.030'],
        ];
        $urineMicro = [
            ['id'=>'wbc_hpf',          'label'=>'WBCs (/HPF)',       'type'=>'number', 'step'=>'1',                                     'unit'=>'/HPF', 'range'=>'0-5'],
            ['id'=>'epithelial_cells', 'label'=>'Epithelial Cells',  'type'=>'select', 'options'=>['Few','Moderate','Many'],            'unit'=>'',     'range'=>'Few'],
            ['id'=>'rbc_hpf',          'label'=>'RBCs (/HPF)',       'type'=>'number', 'step'=>'1',                                     'unit'=>'/HPF', 'range'=>'0-2'],
        ];

        // CBC
        $cbc = [
            ['id'=>'hb',         'label'=>'Hb',                          'unit'=>'g/dL',     'range'=>'12.2 - 18.1', 'type'=>'number','step'=>'0.1'],
            ['id'=>'pcv',        'label'=>'PCV (Packed Cell Volume)',    'unit'=>'%',        'range'=>'37.7 - 53.7',  'type'=>'number','step'=>'0.1'],
            ['id'=>'wbc',        'label'=>'WBC Count & Diff',            'unit'=>'x10^9/L',  'range'=>'4.6 - 10.2',   'type'=>'number','step'=>'0.1'],
            ['id'=>'neutrophil', 'label'=>'Neutrophil',                  'unit'=>'%',        'range'=>'50 - 70',      'type'=>'number','step'=>'1'],
            ['id'=>'lymphocyte', 'label'=>'Lymphocyte',                  'unit'=>'%',        'range'=>'25 - 40',      'type'=>'number','step'=>'1'],
            ['id'=>'monocyte',   'label'=>'Monocyte',                    'unit'=>'%',        'range'=>'2 - 8',        'type'=>'number','step'=>'1'],
            ['id'=>'eosinophile','label'=>'Eosinophile',                 'unit'=>'%',        'range'=>'0 - 6',        'type'=>'number','step'=>'1'],
            ['id'=>'basophile',  'label'=>'Basophile',                   'unit'=>'%',        'range'=>'0 - 2',        'type'=>'number','step'=>'1'],
            ['id'=>'platelets',  'label'=>'Platelets count',             'unit'=>'x10^9/L',  'range'=>'150 - 450',    'type'=>'number','step'=>'1'],
            ['id'=>'rbc',        'label'=>'RBC (Red Blood Cells count)', 'unit'=>'x10^12/L', 'range'=>'4.0 - 6.1',    'type'=>'number','step'=>'0.01'],
            ['id'=>'mcv',        'label'=>'MCV (Mean cell volume)',      'unit'=>'fL',       'range'=>'80 - 97',      'type'=>'number','step'=>'0.1'],
            ['id'=>'mch',        'label'=>'MCH (Mean Cell Haemoglobin)', 'unit'=>'pg',       'range'=>'27 - 31.2',    'type'=>'number','step'=>'0.1'],
            ['id'=>'mchc',       'label'=>'MCHC (Mean Cell Hb Conc.)',   'unit'=>'g/dL',     'range'=>'30 - 35',      'type'=>'number','step'=>'0.1'],
            ['id'=>'rdw',        'label'=>'RDW (Red Cell Distribution Widt)', 'unit'=>'%',   'range'=>'11.6 - 14.8',  'type'=>'number','step'=>'0.1'],
        ];

        // Biochemistry
        $bio = [
            ['id'=>'na',       'label'=>'Sodium',            'unit'=>'mmol/L', 'range'=>'135 - 155', 'type'=>'number','step'=>'1'],
            ['id'=>'k',        'label'=>'Potassium',         'unit'=>'mmol/L', 'range'=>'3.5 - 5.5', 'type'=>'number','step'=>'0.1'],
            ['id'=>'albumin',  'label'=>'ALB(Albumin)',      'unit'=>'g/dL',   'range'=>'3.8 - 5.1', 'type'=>'number','step'=>'0.1'],
            ['id'=>'creatinine','label'=>'Creatinine',       'unit'=>'mg/dL',  'range'=>'0.4 - 1.2', 'type'=>'number','step'=>'0.1'],
            // الإضافات من الصورة الرابعة
            ['id'=>'calcium',  'label'=>'Calcium Serum',     'unit'=>'mg/dL',  'range'=>'8.4 - 10.2', 'type'=>'number','step'=>'0.1'],
            ['id'=>'tbil',     'label'=>'BIL T (Bilirubin Total)',  'unit'=>'mg/dL', 'range'=>'0.2 - 1.2', 'type'=>'number','step'=>'0.1'],
            ['id'=>'dbil',     'label'=>'BIL-D (Bilirubin Direct)', 'unit'=>'mg/dL', 'range'=>'0.0 - 0.3', 'type'=>'number','step'=>'0.1'],
            ['id'=>'alt',      'label'=>'GPT(ALT)',           'unit'=>'U/L',    'range'=>'up to 55',   'type'=>'number','step'=>'1'],
            ['id'=>'ast',      'label'=>'GOT(AST)',           'unit'=>'U/L',    'range'=>'up to 34',   'type'=>'number','step'=>'1'],
        ];

        // Coagulation
        $coag = [
            ['id'=>'control_pt', 'label'=>'Control PT', 'unit'=>'sec', 'range'=>'10 - 15', 'type'=>'number','step'=>'0.1'],
            ['id'=>'inr',        'label'=>'INR',        'unit'=>'',    'range'=>'0.8 - 1.5','type'=>'number','step'=>'0.01'],
            ['id'=>'patient_pt', 'label'=>'Patient PT', 'unit'=>'sec', 'range'=>'10 - 15', 'type'=>'number','step'=>'0.1'],
            ['id'=>'control_ptt','label'=>'Control PTT','unit'=>'sec', 'range'=>'25 - 43', 'type'=>'number','step'=>'0.1'],
            ['id'=>'patient_ptt','label'=>'Patient PTT','unit'=>'sec', 'range'=>'25 - 43', 'type'=>'number','step'=>'0.1'],
        ];

        // Serology
        $sero = [
            ['id'=>'troponin_i', 'label'=>'Troponin I', 'unit'=>'ng/mL', 'range'=>'UP TO 0.3 NG/ML (≤ 0.3)', 'type'=>'number','step'=>'0.01'],
        ];

        /* =====  دوال مساعدة لبناء الـ HTML  ===== */

        $inputEl = function ($f) {
            if (($f['type'] ?? 'number') === 'select') {
                $opts = '';
                foreach (($f['options'] ?? []) as $o) {
                    $oEsc = htmlspecialchars($o, ENT_QUOTES, 'UTF-8');
                    $opts .= "<option value=\"{$oEsc}\">{$oEsc}</option>";
                }
                return "<select name=\"{$f['id']}\" id=\"{$f['id']}\" class=\"form-select\" style=\"min-width:140px;\">{$opts}</select>";
            }
            $step = isset($f['step']) ? " step=\"{$f['step']}\"" : '';
            return "<input type=\"number\" name=\"{$f['id']}\" id=\"{$f['id']}\" class=\"form-control\"{$step} style=\"width:120px;\" />";
        };

        $rowsToWorksheet = function (array $rows) use ($inputEl) {
            $trs = '';
            foreach ($rows as $r) {
                $label = htmlspecialchars($r['label'], ENT_QUOTES, 'UTF-8');
                $unit  = htmlspecialchars($r['unit']  ?? '', ENT_QUOTES, 'UTF-8');
                $range = htmlspecialchars($r['range'] ?? '', ENT_QUOTES, 'UTF-8');
                $el    = $inputEl($r);
                $trs  .= "<tr><td>{$label}</td><td>{$el}</td><td>{$range} {$unit}</td></tr>";
            }
            return $trs;
        };

        $rowsToTemplate = function (array $rows) {
            $trs = '';
            foreach ($rows as $r) {
                $label = htmlspecialchars($r['label'], ENT_QUOTES, 'UTF-8');
                $unit  = htmlspecialchars($r['unit']  ?? '', ENT_QUOTES, 'UTF-8');
                $range = htmlspecialchars($r['range'] ?? '', ENT_QUOTES, 'UTF-8');
                $var   = '${'.$r['id'].'}';
                $unitShow = ($unit === '') ? '&mdash;' : $unit;
                $trs  .= "<tr><td>{$label}</td><td>{$var}</td><td>{$range} {$unitShow}</td></tr>";
            }
            return $trs;
        };

        $varsMap = function (array $rows) {
            $m = [];
            foreach ($rows as $r) { $m[$r['id']] = ''; }
            return $m;
        };

        // JS تنبيه للأرقام خارج المدى (لو وجد مدى a-b) — بدون <script> وبإغلاق صحيح
        $buildJS = function (array $rows) {
            $rules = [];
            foreach ($rows as $r) {
                if (($r['type'] ?? 'number') !== 'number') continue;
                if (empty($r['range'])) continue;
                if (preg_match('/([0-9]+\\.?[0-9]*)\\s*[-–]\\s*([0-9]+\\.?[0-9]*)/u', $r['range'], $m)) {
                    $id = $r['id']; $min = $m[1]; $max = $m[2];
                    $rules[] = "  bindChk('{$id}', {$min}, {$max});";
                }
            }
            $rulesJs = implode("\n", $rules);
            return <<<JS
$(function () {
  function bindChk(id, min, max) {
    var \$el = \$("[name='" + id + "']");
    function chk() {
      var v = parseFloat(\$el.val());
      if (!isNaN(v) && (v < min || v > max)) { \$el.css('background', '#ffe6e6'); }
      else { \$el.css('background', ''); }
    }
    \$el.on('input change', chk);
    chk();
  }
{$rulesJs}
});
JS;
        };

        /* =====  لوحات مجمعة مطابقة للصور  ===== */

        // 1) Urine Analysis (Panel)
        $urineWorksheet = <<<HTML
<div style="direction:ltr; font-family:'DejaVu Sans',sans-serif;">
  <h4>Urine Analysis</h4>
  <strong>Macroscopic Examination</strong>
  <table class="table" style="width:100%; border-collapse:collapse;">
    <thead><tr>
      <th style="text-align:left;border-bottom:1px solid #ccc;"> Test Name</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Result</th>

      <th style="text-align:left;border-bottom:1px solid #ccc;">Normal Range</th>
    </tr></thead>
    <tbody>
      {$rowsToWorksheet($urineMacro)}
    </tbody>
  </table>

  <strong>Microscopic Examination</strong>
  <table class="table" style="width:100%; border-collapse:collapse;">
    <thead><tr>
      <th style="text-align:left;border-bottom:1px solid #ccc;"> Test Name</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Result</th>

      <th style="text-align:left;border-bottom:1px solid #ccc;">Normal Range</th>
    </tr></thead>
    <tbody>
      {$rowsToWorksheet($urineMicro)}
    </tbody>
  </table>
</div>
HTML;

        $urineTemplate = <<<HTML
<div style="direction:ltr; font-family:'DejaVu Sans',sans-serif;">
  <h4>Urine Analysis</h4>
  <strong>Macroscopic Examination</strong>
  <table style="width:100%; border-collapse:collapse;">
    <thead><tr>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Test Name</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Result</th>

      <th style="text-align:left;border-bottom:1px solid #ccc;">Normal Range</th>
    </tr></thead>
    <tbody>
      {$rowsToTemplate($urineMacro)}
    </tbody>
  </table>

  <strong>Microscopic Examination</strong>
  <table style="width:100%; border-collapse:collapse;">
    <thead><tr>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Test Name</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Result</th>

      <th style="text-align:left;border-bottom:1px solid #ccc;">Normal Range</th>
    </tr></thead>
    <tbody>
      {$rowsToTemplate($urineMicro)}
    </tbody>
  </table>
</div>
HTML;

        // 2) CBC
        $cbcWorksheet = <<<HTML
<div style="direction:ltr; font-family:'DejaVu Sans',sans-serif;">
  <h4>Haematology</h4>
  <table class="table" style="width:100%; border-collapse:collapse;">
    <thead><tr>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Test Name</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Result</th>

      <th style="text-align:left;border-bottom:1px solid #ccc;">Normal Range</th>
    </tr></thead>
    <tbody>
      {$rowsToWorksheet($cbc)}
    </tbody>
  </table>
</div>
HTML;

        $cbcTemplate = <<<HTML
<div style="direction:ltr; font-family:'DejaVu Sans',sans-serif;">
  <h4>Haematology</h4>
  <table style="width:100%; border-collapse:collapse;">
    <thead><tr>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Test Name</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Result</th>

      <th style="text-align:left;border-bottom:1px solid #ccc;">Normal Range</th>
    </tr></thead>
    <tbody>
      {$rowsToTemplate($cbc)}
    </tbody>
  </table>
</div>
HTML;

        // 3) Biochemistry
        $bioWorksheet = <<<HTML
<div style="direction:ltr; font-family:'DejaVu Sans',sans-serif;">
  <h4>Biochemistry</h4>
  <table class="table" style="width:100%; border-collapse:collapse;">
    <thead><tr>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Test Name</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Result</th>

      <th style="text-align:left;border-bottom:1px solid #ccc;">Normal Range</th>
    </tr></thead>
    <tbody>
      {$rowsToWorksheet($bio)}
    </tbody>
  </table>
</div>
HTML;

        $bioTemplate = <<<HTML
<div style="direction:ltr; font-family:'DejaVu Sans',sans-serif;">
  <h4>Biochemistry</h4>
  <table style="width:100%; border-collapse:collapse;">
    <thead><tr>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Test Name</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Result</th>

      <th style="text-align:left;border-bottom:1px solid #ccc;">Normal Range</th>
    </tr></thead>
    <tbody>
      {$rowsToTemplate($bio)}
    </tbody>
  </table>
</div>
HTML;

        // 4) Coagulation
        $coagWorksheet = <<<HTML
<div style="direction:ltr; font-family:'DejaVu Sans',sans-serif;">
  <h4>Coagulation</h4>
  <table class="table" style="width:100%; border-collapse:collapse;">
    <thead><tr>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Test Name</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Result</th>

      <th style="text-align:left;border-bottom:1px solid #ccc;">Normal Range</th>
    </tr></thead>
    <tbody>
      {$rowsToWorksheet($coag)}
    </tbody>
  </table>
</div>
HTML;

        $coagTemplate = <<<HTML
<div style="direction:ltr; font-family:'DejaVu Sans',sans-serif;">
  <h4>Coagulation</h4>
  <table style="width:100%; border-collapse:collapse;">
    <thead><tr>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Test Name</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Result</th>

      <th style="text-align:left;border-bottom:1px solid #ccc;">Normal Range</th>
    </tr></thead>
    <tbody>
      {$rowsToTemplate($coag)}
    </tbody>
  </table>
</div>
HTML;

        // 5) Serology – Troponin I
        $seroWorksheet = <<<HTML
<div style="direction:ltr; font-family:'DejaVu Sans',sans-serif;">
  <h4>Serology</h4>
  <table class="table" style="width:100%; border-collapse:collapse;">
    <thead><tr>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Test Name</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Result</th>

      <th style="text-align:left;border-bottom:1px solid #ccc;">Normal Range</th>
    </tr></thead>
    <tbody>
      {$rowsToWorksheet($sero)}
    </tbody>
  </table>
</div>
HTML;

        $seroTemplate = <<<HTML
<div style="direction:ltr; font-family:'DejaVu Sans',sans-serif;">
  <h4>Serology</h4>
  <table style="width:100%; border-collapse:collapse;">
    <thead><tr>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Test Name</th>
      <th style="text-align:left;border-bottom:1px solid #ccc;">Result</th>

      <th style="text-align:left;border-bottom:1px solid #ccc;">Normal Range</th>
    </tr></thead>
    <tbody>
      {$rowsToTemplate($sero)}
    </tbody>
  </table>
</div>
HTML;

        /* =====  كتابة السجلات  ===== */

        $panels = [
            [
                'code'=>10100, 'name'=>'Urine Analysis',
                'template'=>$urineTemplate,
                'worksheet'=>$urineWorksheet,
                'vars'=> array_merge($varsMap($urineMacro), $varsMap($urineMicro)),
                'js'  => $buildJS(array_merge($urineMacro, $urineMicro)),
                'position'=>100,
            ],
            [
                'code'=>20100, 'name'=>'Haematology – CBC',
                'template'=>$cbcTemplate,
                'worksheet'=>$cbcWorksheet,
                'vars'=> $varsMap($cbc),
                'js'  => $buildJS($cbc),
                'position'=>200,
            ],
            [
                'code'=>30100, 'name'=>'Biochemistry',
                'template'=>$bioTemplate,
                'worksheet'=>$bioWorksheet,
                'vars'=> $varsMap($bio),
                'js'  => $buildJS($bio),
                'position'=>300,
            ],
            [
                'code'=>40100, 'name'=>'Coagulation – PT/PTT/INR',
                'template'=>$coagTemplate,
                'worksheet'=>$coagWorksheet,
                'vars'=> $varsMap($coag),
                'js'  => $buildJS($coag),
                'position'=>400,
            ],
            [
                'code'=>50100, 'name'=>'Serology – Troponin I',
                'template'=>$seroTemplate,
                'worksheet'=>$seroWorksheet,
                'vars'=> $varsMap($sero),
                'js'  => $buildJS($sero),
                'position'=>500,
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($panels as $p) {

                // تنظيف أي وسوم <script> لو وُجدت بالخطأ
                $jsClean = preg_replace('/<\\/?script[^>]*>/i', '', $p['js']);

                DB::table('determinations')->updateOrInsert(
                    ['nomenclator_id'=>$NOMENCLATOR_ID, 'code'=>(int)$p['code']],
                    [
                        'name'               => $p['name'],
                        'position'           => (int)$p['position'],
                        'biochemical_unit'   => 1.00,
                        'template'           => $p['template'],
                        'worksheet_template' => $p['worksheet'],
                        'template_variables' => json_encode($p['vars'], JSON_UNESCAPED_UNICODE),
                        'javascript'         => $jsClean, // ← سكربت نظيف بدون <script>
                        'created_at'         => $now,
                        'updated_at'         => $now,
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
