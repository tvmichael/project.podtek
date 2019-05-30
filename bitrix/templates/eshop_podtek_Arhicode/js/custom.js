console.log('psPlatonForm...');

if(!funcDefined('psPlatonForm')){
    function psPlatonForm(element) {
        var form = $("form.platon-ps-form"),
            order = $("input[name='order']", form),
            orderNumber = '';
        $(order).focusout(function () {
            if(orderNumber != order.val())
                $.ajax({
                    method: "GET",
                    url: "/order/platon/",
                    data: {ps_action:"order", order:order.val()}
                })
                    .done(function(data) {
                        var data = JSON.parse(data);
                        orderNumber = order.val();
                        if(data.status == 'OK')
                        {
                            $("input[name='key']", form).val(data.key);
                            $("input[name='sign']", form).val(data.sign);
                            $("input[name='data']", form).val(data.data);

                            if($("input[name='last_name']", form).val() == '')
                                $("input[name='last_name']", form).val(data.last_name);

                            if($("input[name='first_name']", form).val() == '')
                                $("input[name='first_name']", form).val(data.first_name);

                            if($("input[name='email']", form).val() == '')
                                $("input[name='email']", form).val(data.email);

                            if($("input[name='phone']", form).val() == '')
                                $("input[name='phone']", form).val(data.phone);

                            $("[type='submit']", form).removeClass('ps-submit-disabled');
                            $("[type='submit']", form).prop( "disabled", false );
                        }
                        else
                        {
                            $("[type='submit']", form).addClass('ps-submit-disabled');
                            $("[type='submit']", form).prop( "disabled", true );
                        }
                    })
                    .fail(function() {
                        alert( "ERROR" );
                    });
        });
    }
}