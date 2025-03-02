<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>PayPal JS SDK Standard Integration</title>
    </head>
    <body>
        <div id="paypal-button-container"></div>
        <p id="result-message"></p>

        <?php

    $total=$_POST['total'];





?>
        <!-- Initialize the JS-SDK -->
        <script
            src="https://www.paypal.com/sdk/js?client-id=ASjPMHzEimB-tqmjc6IX3EfeLiJw-cawYjqfR-qf_TExms1CPGd9YY7pU6DiGizEvefcQfqIU34p0ZMN&buyer-country=US&currency=EUR&components=buttons&enable-funding=venmo,paylater,card"
            data-sdk-integration-source="developer-studio"
        ></script>
        <script>
            window.paypal
    .Buttons({
        style: {
            shape: "pill",
            color: "blue",
            label: "pay",
        },
        createOrder:function(data,actions){
            return actions.order.create({
                purchase_units:[{
                    amount:{
                        value:<?php echo  $total ?>
                    }
                }]
            });

        },
        onApprove:function(data,actions){
            //let URL='clases/captura.php';
            actions.order.capture().then(function(detalles){
              console.log(detalles);
                window.location.href="completado.php";
                let url ='carrito.php';
                return fetch(url,{
                    method: 'post',
                    headers:{
                        'content-type':'aplicacion/json'
                    },
                    body: JSON.stringify({
                        detalles:detalles
                    })
                })
            })
        },
        onCancel:function(data){
            window.location.href="cancelado.php";
        }
       

    }).render("#paypal-button-container"); 

        </script><?php

           ?>
    </body>
</html>
