$(function() {
    ("use strict");

    let selectedGrid = "product";
    let selected = $(`#tableSelector button[value='${selectedGrid}']`).addClass(
        "selected"
    );

    $("#tableSelector button").on("click", function() {
        let elem = $(this);
        if (selected == null) {
            elem.addClass("selected");
            selected = elem;
        } else {
            selected.removeClass("selected");
            elem.addClass("selected");
            selected = elem;
        }
        selectedGrid = elem.attr("value");
        $("#gridContainer").trigger("newdata", []);
    });

    const gridTable = {
        product: [
            { label: "ID", name: "id", key: true, editable: false },
            { label: "Name", name: "name", editable: true },
            { label: "Price", name: "price", editable: true },
            { label: "Avaliable", name: "avaliable", editable: true },
            { label: "Type", name: "type", editable: true },
            { label: "Size", name: "size", editable: false },
            { label: "Image", name: "img", editable: true },
            { label: "Manufactor", name: "manufactor", editable: false },
            { label: "Gender", name: "gender", editable: false },
        ],
        types: [
            {
                label: "ID",
                name: "id",
                key: true,
                editable: false,
                editoptions: { readonly: true },
            },
            { label: "Type", name: "type", editable: true },
        ],
        manufactors: [
            {
                label: "ID",
                name: "id",
                key: true,
                editable: false,
                editoptions: { readonly: true },
            },
            { label: "Manufactor", name: "manufactor", editable: true },
        ],
        genders: [
            {
                label: "ID",
                name: "id",
                key: true,
                editable: false,
                editoptions: { readonly: true },
            },
            { label: "Gender", name: "gender", editable: true },
        ],
        sizes: [
            {
                label: "ID",
                name: "id",
                key: true,
                editable: false,
                editoptions: { readonly: true },
            },
            { label: "Size", name: "size", editable: true },
        ],
        users: [
            {
                label: "ID",
                name: "id",
                key: true,
                editable: false,
                editoptions: { readonly: true },
            },
            { label: "Login", name: "login", editable: true },
            { label: "Hash", name: "pass_hash", editable: false },
            { label: "Role", name: "role", editable: true },
        ],
    };

    $("#editRow").on("click", function() {
        var gr = jQuery("#grid").jqGrid("getGridParam", "selrow");
        if (gr != null)
            jQuery("#grid").jqGrid("editGridRow", gr, {
                height: 280,
                reloadAfterSubmit: false,
            });
        else alert("Please Select Row");
    });

    $("#addRow").on("click", function() {
        jQuery("#grid").jqGrid("editGridRow", "new", {
            height: 280,
            reloadAfterSubmit: false,
        });
    });

    $("#deleteRow").on("click", function() {
        var gr = jQuery("#grid").jqGrid("getGridParam", "selrow");
        if (gr != null)
            jQuery("#grid").jqGrid("delGridRow", gr, { reloadAfterSubmit: false });
        else alert("Please Select Row to delete!");
    });

    $("#gridContainer").trigger("newdata", []);
    $("#gridContainer").on("newdata", function() {
        $(this).html("").append("<table id='grid'></table>");
        $("#grid")
            .jqGrid({
                url: "/jqGrid/" + selectedGrid,
                method: "GET",
                datatype: "json",
                colModel: gridTable[selectedGrid],
                rowNum: 30,
                viewrecords: true,
                gridview: true,
                autowidth: true,
                shrinkToFit: true,
                pager: true,
                height: "65vh",
                editurl: "/jqGrid/edit/" + selectedGrid,
            })
            .jqGrid("filterToolbar");
    });
});
