<link rel="stylesheet" href="/css/index.css">
<script type='module' src="/js/index/pagination.js" defer></script>
<script type="module" src="/js/index/modal.js"></script>
<script src="/js/index/dynSearch.js"></script>
<script type="module" src="/js/index/login.js"></script>
<div class="shop_menu">
    <div class='shop_params'>
        <?php
        $dom = new DOMDocument();

        foreach($data['menu'] as $k=>$v){
            $menu = $dom->createElement("div");
            $menu->appendChild($dom->createElement("span", $v[1]));
            $list = $dom->createElement("ul");
            foreach($v[0] as $val){
                $li = $dom->createElement("li");
                $check = $dom->createElement("input");
                $check->setAttribute("type", "checkbox");
                $check->setAttribute("value", $val[0]);
                $check->setAttribute("class", $k);
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
    <button id='filter'>Фільтрувати</button>
</div>
<div id="searchContainer">
    <input id="prodSearch" class="search" type="text">
</div>
<div class="shop_list">
</div>
<div class="pages">
</div>
<div id="loginModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal-close">&times;</span>
        </div>
        <div class="modal-body" id="loginPanel">
            <div class="login-row" style="">
                <label for="loginparam">Login</label>
                <input id="loginparam" type="text">
            </div>
            <div class="login-row" style="">
                <label for="passwordparam">Password</label>
                <div class='password-container'>
                    <input id="passwordparam" type="password">
                    <img id="passEye" src="/media/eye_open.svg">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <span id="loginError" style="display:none;"></span>
            <button class="loginButton" value='login'>Login</button>
            <button class="loginButton" value='register'>Register</button>
        </div>
    </div>
</div>
<div id='cartModal' class='modal'>
    <div class='modal-content'>
        <div class="modal-header">
            <span class="modal-close">&times;</span>
        </div>
        <div class='modal-body' id="cartPanel">
        </div>
        <div class='modal-footer'>
            <button id="orderButton">Оформити</button>
            <button id="deleteButton">Видалити все</button>
        </div>
    </div>
</div>
