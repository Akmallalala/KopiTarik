<?php
// A line identifies both the product and its complete customisation.
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) $_SESSION['cart'] = [];
foreach ($_SESSION['cart'] as $key=>$line) {
    if (!is_array($line) || !isset($line['menu_id'],$line['quantity'],$line['customization'])) unset($_SESSION['cart'][$key]);
}
function is_hot_drink($name) { return stripos(trim($name), 'Hot ') === 0; }
function drink_customization($post, $name) {
    $options = ['size'=>['Small','Medium','Large'],'milk'=>['2% Milk','Whole Milk','No milk','Oat Milk','Almond Milk','Soy Milk'],'extra'=>['Ice','Extra ice','No ice'],'topping'=>['None','Caramel drizzle','Chocolate sauce','Whipped cream']];
    $custom = ['size'=>'Medium','milk'=>'2% Milk','extra'=>'Ice','topping'=>'None'];
    foreach ($options as $key=>$allowed) if (in_array($post[$key] ?? null,$allowed,true)) $custom[$key]=$post[$key];
    if (is_hot_drink($name)) $custom['extra']='None';
    $custom['shots']=max(0,min(6,(int)($post['shots'] ?? 2)));
    $custom['pumps']=max(0,min(8,(int)($post['pumps'] ?? 2)));
    $custom['note']=mb_substr(trim((string)($post['note'] ?? '')),0,80,'UTF-8');
    return $custom;
}
function drink_unit_cents($price,$custom) {
    $cents=(int)round((float)$price*100);
    $cents += ['Small'=>-50,'Medium'=>0,'Large'=>50][$custom['size']] ?? 0;
    if (in_array($custom['milk'],['Oat Milk','Almond Milk','Soy Milk'],true)) $cents+=80;
    // Toppings/shots/syrup retain the friend's included-in-price policy.
    return max(0,$cents);
}
function cart_rows($lock = false) {
    global $pdo;
    $counts=[]; $products=[];
    foreach ($_SESSION['cart'] as $line) {
        $id=(int)$line['menu_id'];$qty=(int)$line['quantity'];
        if ($id < 1 || $qty < 1 || $qty > 99) throw new RuntimeException('Please update the quantities in your cart.');
        $counts[$id]=($counts[$id] ?? 0)+$qty;
    }
    // Lock products in consistent order, including multiple customisations of one drink.
    ksort($counts);
    foreach ($counts as $id=>$qty) {
        $sql='SELECT * FROM menu_item WHERE menu_id=?';
        if ($lock) $sql.=' FOR UPDATE';
        $st=$pdo->prepare($sql);$st->execute([$id]);$m=$st->fetch();
        if (!$m || !$m['availability'] || $m['stock_quantity'] < $qty) throw new RuntimeException('A drink is unavailable or has insufficient stock. Update or remove it from your cart.');
        $products[$id]=$m;
    }
    $rows=[];
    foreach ($_SESSION['cart'] as $key=>$line) {
        $m=$products[(int)$line['menu_id']];
        $m['customization']=drink_customization($line['customization'],$m['menu_name']);
        $m['quantity']=(int)$line['quantity'];$m['line_key']=$key;
        $m['unit_cents']=drink_unit_cents($m['price'],$m['customization']);
        $m['unit_price']=$m['unit_cents']/100;
        $m['subtotal']=$m['unit_cents']*$m['quantity']/100;
        $rows[]=$m;
    }
    return $rows;
}
