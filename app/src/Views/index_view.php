<link rel="stylesheet" href="css/index.css">
<div class="shop_menu">
    <?php
    $dom = new DOMDocument();

    foreach($data as $k=>$v){
        $menu = $dom->createElement("div");
        $menu->setAttribute("class", $k."_menu");
        $menu->appendChild($dom->createElement("span", $v[1]));
        $list = $dom->createElement("ul");
        foreach($v[0] as $val){
            $li = $dom->createElement("li");
            $check = $dom->createElement("input");
            $check->setAttribute("type", "checkbox");
            $check->setAttribute("name", $k."_".$val[0]);
            $li->appendChild($check);
            $li->appendChild($dom->createElement("span", $val[1]));
            $list->appendChild($li);
        }
        $menu->appendChild($list);
        $dom->appendChild($menu);
    }

    echo $dom->saveHTML();
    ?>
</div>
<div class="shop_list">
    <div class='shop_item' data-id='1'>
        <img class='item_img' src="media/cart.svg"> 
        <span class="item_name">Cart</span>
        <span class="item_price">Price: 12.34$</span>
        <button class="item_add">Add to cart</button>
    </div>
    <div class='shop_item' data-id='1'>
        <img class='item_img' src="media/cart.svg"> 
        <span class="item_name">Cart</span>
        <span class="item_price">Price: 12.34$</span>
        <button class="item_add">Add to cart</button>
    </div>
    <div class='shop_item' data-id='1'>
        <img class='item_img' src="media/cart.svg"> 
        <span class="item_name">Cart</span>
        <span class="item_price">Price: 12.34$</span>
        <button class="item_add">Add to cart</button>
    </div>
    <div class='shop_item' data-id='1'>
        <img class='item_img' src="media/cart.svg"> 
        <span class="item_name">Cart</span>
        <span class="item_price">Price: 12.34$</span>
        <button class="item_add">Add to cart</button>
    </div>
    <div class='shop_item' data-id='1'>
        <img class='item_img' src="media/cart.svg"> 
        <span class="item_name">Cart</span>
        <span class="item_price">Price: 12.34$</span>
        <button class="item_add">Add to cart</button>
    </div>
    <div class='shop_item' data-id='1'>
        <img class='item_img' src="media/cart.svg"> 
        <span class="item_name">Cart</span>
        <span class="item_price">Price: 12.34$</span>
        <button class="item_add">Add to cart</button>
    </div>
    <div class='shop_item' data-id='1'>
        <img class='item_img' src="media/cart.svg"> 
        <span class="item_name">Cart</span>
        <span class="item_price">Price: 12.34$</span>
        <button class="item_add">Add to cart</button>
    </div>
    <div class='shop_item' data-id='1'>
        <img class='item_img' src="media/cart.svg"> 
        <span class="item_name">Cart</span>
        <span class="item_price">Price: 12.34$</span>
        <button class="item_add">Add to cart</button>
    </div>
    <div class='shop_item' data-id='1'>
        <img class='item_img' src="media/cart.svg"> 
        <span class="item_name">Cart</span>
        <span class="item_price">Price: 12.34$</span>
        <button class="item_add">Add to cart</button>
    </div>
</div>
<div class="pages">
    123
</div>
