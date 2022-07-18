
function getTransactions() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "Request.php?type=GetTransactions",
        success: function (res, status, xhr) {
            if (res != null && $.isArray(res.data)) {
                $("#transactions_table").empty();
                $.each(res.data, function (i, e) {
                    $("#transactions_table").append(`<tr id='tran-` + e.ID + `'>
                    <td>` + e.ID + `</td>
                    <td>` + e.WALLET_ID + `</td>
                    <td>` + e.TYPE + `</td>
                    <td>` + e.AMOUNT + `</td>
                    <td>` + e.REFERENCE + `</td>
                    <td>` + e.TIMESTAMP + `</td>
                    </tr>`);
                });
            }
        },
        error: function(xhr, error){
            alert(xhr.status);
        }
    });
}

$(document).ready(function () {
    $("#CreateTransaction").click(function (e) {
        e.preventDefault();
        var url = $("#CreateTransactionForm").attr("action");
        
        $.ajax({
            type: $("#CreateTransactionForm").attr("method"),
            dataType: "json",
            url: url,
            data: $("#CreateTransactionForm").serialize(),
            success: function (res, status, xhr) {
                alert(xhr.status);
                if (res != null && res.data != null) {
                    $("#transactions_table").append(`<tr id='tran-` + e.ID + `'>
                        <td>` + res.data.ID + `</td>
                        <td>` + res.data.WALLET_ID + `</td>
                        <td>` + res.data.TYPE + `</td>
                        <td>` + res.data.AMOUNT + `</td>
                        <td>` + res.data.REFERENCE + `</td>
                        <td>` + res.data.TIMESTAMP + `</td>
                        </tr>`);
                }
            },
            error: function(xhr, error){
                alert(xhr.status);
            }
        });
    });
})
