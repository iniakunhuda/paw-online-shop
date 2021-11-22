function showAlert(response) {
    if(response.status == 200) {
        let data = response.data;
        if(data.status == 200) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                html: data.message
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: data.message
            });
        }
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            html: "Ada yang salah, hubungi administrator!"
        });
    }
}

function countCart() {
    let url = API_URL + "cart/count";
    axios.get(url)
    .then(function (response) {
        let count = response.data;
        if(count == 0) {
            $('.total__cart').addClass('hidden');
        } else {
            $('.total__cart').removeClass('hidden');
            $('.total__cart').text(count);
        }
    })
    .catch(function (error) {
        console.log(error);
        showAlert(error);
    });  
}

function addToCart(id, qty, note) {
    let url = API_URL + "cart/add/" + id;
    axios.post(url,{
        qty: qty,
        note: note
    })
    .then(function (response) {
        showAlert(response);
        countCart();
    })
    .catch(function (error) {
        console.log(error);
        showAlert(error);
    });  
}

function removeFromCart(id) {
    let url = API_URL + "cart/remove/" + id;
    axios.post(url)
    .then(function (response) {
        showAlert(response);
        window.location.reload();
    })
    .catch(function (error) {
        showAlert(error);
    });  
}

function changeQtyCart(id, qty) {
    let url = API_URL + "cart/change_qty/" + id;

    if((qty < 1) || (qty == undefined)) {
        // swal(
        //     "Gagal!",
        //     "Jumlah produk yang dibeli minimal 1",
        //     "error"
        // );
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            html: "Jumlah produk yang dibeli minimal 1"
        });
        return;
    }

    axios.post(url,{
        qty: qty
    })
    .then(function (response) {
        showAlert(response);
        window.location.reload();
    })
    .catch(function (error) {
        console.log(error);
        showAlert(error);
    });
}

function destroyCart() {
    let url = API_URL + "cart/destroy";
    axios.post(url)
    .then(function (response) {
        showAlert(response);
        window.location.reload();
    })
    .catch(function (error) {
        showAlert(error);
    });  
}

countCart();