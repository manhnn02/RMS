
function getWallets() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "Request.php?type=GetWallet",
        success: function (res, status, xhr) {
            if (res != null && $.isArray(res.data)) {
                $("#wallets_table").empty();
                $.each(res.data, function (i, e) {
                    $("#wallets_table").append("<tr id='wall-" + e.ID + "'><td>" + e.ID + "</td><td>" + e.NAME + "</td><td>" + e.HASH_KEY + "</td><td><a href='#' onclick='DeleteWalletByID(" + e.ID + ")'>Delete by id</a></td></tr>");
                });
            }
        },
        error: function(xhr, error){
            alert(xhr.status);
        }
    });
}

function DeleteWalletByID(id) {
    $.ajax({
        type: "DELETE",
        dataType: "json",
        url: "Request.php?type=DeleteWalletByID",
        data: {"id": id},
        success: function (res, status, xhr) {
            alert(xhr.status);
            var deletedID = res.data.id;
            $("#wall-" + deletedID).remove();
        },
        error: function(xhr, error){
            alert(xhr.status);
        }
    });
}   

$(document).ready(function () {
    $("#createWallet").click(function (e) {
        e.preventDefault();
        var url = $("#createWalletForm").attr("action");
        
        $.ajax({
            type: $("#createWalletForm").attr("method"),
            dataType: "json",
            url: url,
            data: $("#createWalletForm").serialize(),
            success: function (res, status, xhr) {
                alert(xhr.status);
                if (res != null && res.data != null) {
                    $("#wallets_table").append("<tr id='wall-" + res.data.ID + "'><td>" + res.data.ID + "</td><td>" + res.data.NAME + "</td><td>" + res.data.HASH_KEY + "</td><td><a href='#' onclick='DeleteWalletByID(" + res.data.ID + ")'>Delete by id</a></td></tr>");
                }
            },
            error: function(xhr, error){
                alert(xhr.status);
            }
        });
    });  

    $("#DeleteWallet").click(function (e) {
        e.preventDefault();
        var url = $("#DeleteWalletForm").attr("action");
        
        $.ajax({
            type: $("#DeleteWalletForm").attr("method"),
            dataType: "json",
            url: url,
            data: $("#DeleteWalletForm").serialize(),
            success: function (res, status, xhr) {
                alert(xhr.status);
                var deletedID = res.data.id;
                $("#wall-" + deletedID).remove();                    
            },
            error: function(xhr, error){
                alert(xhr.status);
            }
        });
    });
})   