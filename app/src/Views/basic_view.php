<!DOCTYPE html>
<html>

<head>
    <title>Shop</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="/css/basic.css">
    <script src="/js/jquery-3.6.4.min.js"></script>
</head>

<body>
    <header>
        <div id="types">
            <a href='/boys'>Хлопцям</a>
            <a href="/girls">Дівчатам</a>
        </div>
        <div id="icons">
            <a><img id='search' src="/media/search1.svg"></a>
            <a><img id='login' src="/media/entrance.svg"></a>
            <a><img id='cart' src="/media/cart1.svg"></a>
        </div>
    </header>

    <div id="main">
        <?php require "src/Views/".$content_view; ?>
    </div>
</body>

</html>
