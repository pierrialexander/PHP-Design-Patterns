$(document).ready(function () {
    
    
    $('#form1').submit(function (e) {
        
        e.preventDefault();
        console.log('COMEÇO DO AJAX')

        var u_estado = $('#estado').val();
        var u_cidade = $('#cidade').val();

        $.post('/transporte/backend.php', { estado: u_estado, cidade: u_cidade }, function (result) {
            console.log(result);


            var conteudoHtml = result.map(function (item) {
                return '<div class="b_comm"><h4>' +
                    item.name + '</h4><p>' +
                    item.label + '</p><p>' +
                    item.address + '</p><p>' +
                    item.district + '</p><p>' +
                    item.phone + '</p></div>';
            });

            $('#estado').val('');
            $('#cidade').val('');


            $('.box_comment').prepend(conteudoHtml);
        }, 'json');
    });
});