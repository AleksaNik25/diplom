$(() => {
  $("#basket-pjax").on("click", ".basket-btn", function (e) {
    e.preventDefault();
    $.ajax({
      url: $(this).attr("href"),
      method: "POST",
      success: (data) => {
        if (data) {
          $.pjax.reload("#basket-pjax");
        }
      },
    });
  });


  const reloadBasketCount = () => {
    $.ajax({
      url: "/account/account-basket/get-count",      
      success: (data) => {        
           $("#basket-items-count").html(data);        
      },
    });

  }

  $("#basket-pjax").on('pjax:end', () => {
    reloadBasketCount()
  })


  $('#btn-clear-cart').on('click', function (e) {
    e.preventDefault()
    const url = $(this).attr('href')

    $.ajax({
      url: url,
      method: 'GET',
      success: function () {
        location.reload()
      },
      error: function () {
        alert('Ошибка при очистке корзины')
      }
    })
  })
});