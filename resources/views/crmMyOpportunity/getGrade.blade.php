<?php
$v4 = $request->product_key;
?>

{!! Form::select('product['.$v4.'][grade_id]', $gradeList, null, ['id'=> 'productGradeId_'.$v4, 'data-key' => $v4, 'class' => 'form-control grade-select js-source-states grade-item']) !!}

