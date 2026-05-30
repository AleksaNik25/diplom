$(() => {
  // Для каталога внутри pjax
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

  // Для страницы товара вне pjax
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
          // удалили — теперь показываем как неактивную, меняем url на добавление
          icon.removeClass("text-danger").addClass("text-secondary")
          icon.data("url", `/account/account-favorits/add?product_id=${data.product_id}`)
        } else {
          // добавили — показываем как активную, меняем url на удаление
          icon.removeClass("text-secondary").addClass("text-danger")
          icon.data("url", `/account/account-favorits/remove?id=${data.favorit_id}`)
        }
      },
    })
  })
})