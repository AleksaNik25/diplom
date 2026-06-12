$(() => {
  // 1. Обработчик кнопки "В корзину" внутри PJAX контейнера избранного
  $("#favourite-pjax").on("click", ".btn-basket-add", function (e) {
    e.preventDefault()
    const btn = $(this)

    $.ajax({
      url: btn.attr("href"),
      method: "POST",
      success(data) {
        if (data) {
          // Обновляем счетчик корзины в шапке сайта
          $.ajax({
            url: "/account/account-basket/get-count",
            method: "POST",
            success(value) {
              $("#basket-items-count").html(value)
            },
          })
          // Автообновление (перезагрузка) PJAX контейнера избранного
          $.pjax.reload("#favourite-pjax")
        }
      },
    })
  })

  // 2. Обработчик иконки избранного внутри PJAX
  $("#favourite-pjax").on("click", "i.icon-favourite", function () {
    $.ajax({
      url: $(this).data("url"),
      method: "POST",
      success(data) {
        if (data) {
          $.pjax.reload("#favourite-pjax")
        }
      },
    })
  })

  // 3. Обработчик иконки избранного вне PJAX (на странице товара)
  $(document).on("click", "i.icon-favourite", function () {
    if ($(this).closest("#favourite-pjax").length) return
    const icon = $(this)
    const isActive = icon.hasClass("text-danger")

    $.ajax({
      url: icon.data("url"),
      method: "POST",
      success(data) {
        if (!data.success) return

        if (isActive) {
          icon.removeClass("text-danger").addClass("text-secondary")
          icon.data("url", `/account/account-favorits/add?product_id=${data.product_id}`)
        } else {
          icon.removeClass("text-secondary").addClass("text-danger")
          icon.data("url", `/account/account-favorits/remove?id=${data.favorit_id}`)
        }
      },
    })
  })
})