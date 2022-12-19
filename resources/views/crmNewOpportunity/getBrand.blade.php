<?php
$v4 = $request->product_key;
?>

{!! Form::select('product['.$v4.'][brand_id]', $brandList, null, ['id'=> 'productBrandId_'.$v4, 'data-key' => $v4, 'class' => 'form-control brand-select js-source-states brand-item']) !!}

