<!DOCTYPE html>
<html>

<head>
    <title>Midtrans Test</title>
</head>

<body>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-lshZJixbbQ5g42KE">
    </script>

    <button onclick="pay()">Bayar</button>

    <script>
        function pay() {
snap.pay("{{$id}}", {
    // Optional
    onSuccess: function(result) {
    /* You may add your own implementation here */
    alert("payment success!"); console.log(result);
    },
    // Optional
    onPending: function(result) {
    /* You may add your own implementation here */
    alert("wating for your payment!"); console.log(result);
    },
    // Optional
    onError: function(result) {
    /* You may add your own implementation here */
    alert("payment failed!"); console.log(result);
    }
    });
}
    </script>

</body>

</html>
