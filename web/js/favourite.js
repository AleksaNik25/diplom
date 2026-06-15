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
          $.ajax({
            url: "/account/account-basket/get-count",
            method: "POST",
            success(value) {
              $("#basket-items-count").html(value)
            },
          })
          $.pjax.reload("#favourite-pjax")
        }
      },
    })
  })

  // 2. Обработчик иконки избранного внутри PJAX (страница избранного)
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

  // 3. Обработчик иконки избранного вне PJAX (страница товара, главная страница, каталог)
  $(document).on("click", "i.icon-favourite", function (e) {
    // Не обрабатываем клики внутри PJAX-контейнера избранного (там свой обработчик)
    if ($(this).closest("#favourite-pjax").length) return

    e.preventDefault()
    const icon = $(this)
    const isActive = icon.hasClass("text-danger")

    $.ajax({
      url: icon.data("url"),
      method: "POST",
      success(data) {
        if (!data.success) return

        if (isActive) {
          // Убираем из избранного
          icon.removeClass("text-danger").addClass("text-white")
          icon.data("url", "/account/account-favorits/add?product_id=" + data.product_id)
          icon.attr("data-url", "/account/account-favorits/add?product_id=" + data.product_id)
        } else {
          // Добавляем в избранное
          icon.removeClass("text-white text-secondary").addClass("text-danger")
          icon.data("url", "/account/account-favorits/remove?id=" + data.favorit_id)
          icon.attr("data-url", "/account/account-favorits/remove?id=" + data.favorit_id)
        }
      },
    })
  })
})