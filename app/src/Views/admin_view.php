<link rel="stylesheet" href="/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/themes/redmond/jquery-ui.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/free-jqgrid/4.15.5/css/ui.jqgrid.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/free-jqgrid/4.15.5/jquery.jqgrid.min.js"></script>
<script src="/js/admin/jqgrid.js"></script>
<div id="tableSelector" class="gridSelector">
    <button value="product">Product</button>
    <button value="types">Type</button>
    <button value="manufactors">Manufactors</button>
    <button value="genders">Genders</button>
    <button value="manufactors">Sizes</button>
    <button value="users">Users</button>
</div>
<div id="gridContainer">
    <table id="grid"></table>
</div>
<div class="gridSelector">
    <button id="addRow">Add</button>
    <button id="editRow">Edit</button>
    <button id="deleteRow">Delete</button>
</div>
