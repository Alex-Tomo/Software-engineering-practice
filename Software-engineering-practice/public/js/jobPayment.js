$(document).ready(() => {
    let showPaymentForm = document.getElementById('showPaymentBtn');
    let closePaymentForm = document.getElementById('closePaymentBtn');

    //Hides PayPal button
    document.getElementById('payPalBtn').style.display = 'none';

    //Show payment form
    showPaymentForm.addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-5').style.display = 'block';
    });

    //Close payment form
    closePaymentForm.addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-5').style.display = 'none';
    });

    //Close payment form
    document.getElementById('overlay').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-5').style.display = 'none';
    })

    let calcTotal = document.getElementById('calcTotal');

    //Calculate total and display PayPal payment option
    calcTotal.addEventListener('click', () => {
        let hours = document.getElementById('hours').value;
        let price = document.getElementById('jobPrice').getAttribute('name');
        let total = hours * price;
        if (total === 0 || ''){
            alert('Hours must be above 0');
        } else {
            document.getElementById('payPalBtn').style.display = 'block';
            document.getElementById('total').innerHTML = 'Total: £'+total.toString();
            document.getElementById('amount').value = total;
        }
    });
});