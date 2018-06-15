$(document).ready(function () {
    /* Array de estado */
    let parSimple = {
        "categoria_id": "number",
        "tag": "text",
        "categorya": "text"
    };
    let parArray = {
        "id": "number",
        "idpublicacion": "number",
        "publicacion_id": "number",
        "user_id": "number",
        "id_posts": "number",
        "_page": "number",
        "_start": "number",
        "_end": "number",
        "_limit": "number",
        "id_gte": "number",
        "id_lte": "number",
        "state": "number",
        "url": "text",
        "name": "text",
        "title": "text",
        "introduction": "text",
        "full": "text",
        "creation": "text",
        "author": "text",
        "avatar": "text",
        "nick": "text",
        "_sort": "text",
        "title_gte": "text",
        "tag_gte": "text",
        "introduction_gte": "text",
        "full_gte": "text",
        "creation_gte": "text",
        "author_gte": "text",
        "title_lte": "text",
        "tag_lte": "text",
        "introduction_lte": "text",
        "full_lte": "text",
        "creation_lte": "text",
        "author_lte": "text",
        "title_like": "text",
        "tag_like": "text",
        "introduction_like": "text",
        "full_like": "text",
        "creation_like": "text",
        "author_like": "text",
        "q": "text",
        "View": "text"};
    let parborrados = {};
    let varJson = {};

    /* Seteo de entorno */
    let opt;
    for (let value in parSimple) {
        opt = $("<option></option>").val(value);
        $("#SujerenciaParametros").append(opt)
    }
    for (let value in parArray) {
        opt = $("<option></option>").val(value);
        $("#SujerenciaParametros").append(opt)
    }


    $("#Host").text("http://" + window.location.host + "/");


    $('body').on('keydown', '.NewRow', function (event) {
        if (event.keyCode == 13) {
            let padre = this.parentElement;
            let value = padre.parentElement.getElementsByClassName('InputValue')[0];
            let name = padre.parentElement.getElementsByClassName('InputNombre')[0];
            let ok = false;

            for (var obj in parSimple) {
                if (obj == name.value) {
                    ok = true;
                    if (typeof varJson[name.value] === 'undefined') {
                        varJson[name.value] = [];
                    }
                    varJson[name.value].push(value.value);
                }
            }

            for (var obj in parArray) {
                if (obj == name.value) {
                    ok = true;
                    parborrados[obj] = parArray[obj];
                    delete parArray[obj];
                    varJson[name.value] = value.value;
                }
            }

            if (name.value !== "" && ok && value.value !== "") {
                let variable = "<tr><td><input class='InputNombre' value='" + name.value + "' placeholder='Nombre' /></td><td><input class='InputValue'  value='" + value.value + "' placeholder='Value'/></td></tr>";
                $("#TableParametro").html($("#TableParametro").html() + variable);
                value.placeholder = "Value";
                value.value = "";
                name.value = "";
                document.getElementsByClassName('InputNombre')[0].focus();
            } else if (name.value === "" || !ok) {
                document.getElementsByClassName('InputNombre')[0].focus();
            }
        }
    });
    $('body').on('keydown', '.InputNombre', function (event) {
        if (event.keyCode == 13) {
            let padre = this.parentElement;
            let name = padre.parentElement.getElementsByClassName('InputNombre')[0];
            let value = padre.parentElement.getElementsByClassName('InputValue')[0];
            value.focus();
            for (var obj in parSimple) {
                if (obj == name.value) {
                    value.placeholder = parSimple[obj];
                    value.type = parArray[obj];
                    return;
                }
            }
            for (var obj in parArray) {
                if (obj == name.value) {
                    value.placeholder = parArray[obj];
                    value.type = parArray[obj];
                    return;
                }
            }
            value.placeholder = "Parametro no reconocido";
            value.type = "text";

        }
    });


    $(".JsonTabs").click(function () {
        $("#TextJson").val((JSON.stringify(varJson, null, 4)));
    });
    $("#Help").click(function () {
        $("#Main").toggleClass("active");
        $(".paginasHelp").fadeToggle();
        $(".page").toggleClass("active");
        if ($(this).val() !== "Probar API")
            $(this).val("Probar API");
        else
            $(this).val("Ayuda");
    });
    $("#dropdown-metodo a").click(function () {
        $("#searchLabel").text($(this).text());
    });

    $("#send").click(function () {
        $(".off").removeClass("off");

        if ($("#searchLabel").text() == "Metodo") {
            $("#searchLabel").addClass("off");
        } else if ($("#InputURL").val() == "") {
            $("#InputURL").addClass("off");
        } else if ($("#InputURL").val() == "") {
            $("#InputURL").addClass("off");
        } else {
            console.log(varJson);
            PeticionApi($("#searchLabel").text(), $("#InputURL").val(), varJson);
        }
    });


});

function PeticionApi(type, url, data) {
    $.ajax({
        type: type,
        url: "http://" + window.location.host + "/" + url,
        data: data,
        beforeSend: function (xhr) {
            $("#loader").removeClass("hidden");
        },
        success: function (data) {
            $("#loader").addClass("hidden");
            $(".tab-content .active").removeClass("active show");
            $("#returntabs").addClass("active show");
            $("#res").text(JSON.stringify(data, null, 4) + "\n");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $("#loader").addClass("hidden");
            $(".tab-content .active").removeClass("active show");
            $("#returntabs").addClass("active show");
            $("#res").text(JSON.stringify(jqXHR, null, 4) + "\n" + JSON.stringify(textStatus, null, 4) + "\n" + errorThrown + "\n");
        }
    });
}