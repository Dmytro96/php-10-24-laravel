import './bootstrap';


$(document).ready(function() {
    $('#per_page').on('change', function() {
        $(this).parents('form')?.submit();
    });

    $('.product-qty').on('change', function() {
        $(this).parent('form')?.submit();
    });

    $('.product-card-buy').on('click', function(e) {
        e.preventDefault();

        const url = $(this).data('action');

        axios.post(url, {})
            .then(response => {
                const {data} = response;
                iziToast.success({
                    message: data.message,
                    position: 'topRight'
                });

                $('#cartCountBadge').html(data.cart_count);
            })
            .catch(error => {
                const { message } = error.response.data;
                iziToast.error({
                    message,
                    position: 'topRight'
                });
            });
    });

});
