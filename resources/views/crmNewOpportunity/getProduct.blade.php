<?php
$v4 = $request->product_key;
?>

{!! Form::select('product['.$v4.'][product_id]', $productList, null, ['id'=> 'productId_'.$v4, 'data-key' => $v4, 'class' => 'form-control product-select js-source-states product-item']) !!}

