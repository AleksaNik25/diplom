$(() => {

  // ── Корзина ────────────────────────────────────────────────────
  $("#catalog-pjax").on("click", ".btn-basket-add", function (e) {
    e.preventDefault()
    $.ajax({
      url: $(this).attr("href"),
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
        }
      },
    })
  })

  // ── Избранное ──────────────────────────────────────────────────
  $("#catalog-pjax").on("click", "i.icon-favourite", function (e) {
    $.ajax({
      url: $(this).data("url"),
      method: "POST",
      success(data) {
        if (data) {
          $.pjax.reload("#catalog-pjax")
        }
      },
    })
  })

  // ── Категорийный попап ────────────────────────────────────────

  // Открыть / закрыть попап
  $(document).on('click', '#category-dropdown-btn', function (e) {
    e.stopPropagation()
    const popup = $('#category-popup')
    const chevron = $('#category-chevron')
    const isOpen = popup.is(':visible')
    popup.toggle(!isOpen)
    chevron.toggleClass('fa-chevron-down', isOpen).toggleClass('fa-chevron-up', !isOpen)
  })

  // Закрыть попап при клике вне
  $(document).on('click', function (e) {
    if (!$(e.target).closest('#category-dropdown-wrap').length) {
      $('#category-popup').hide()
      $('#category-chevron').removeClass('fa-chevron-up').addClass('fa-chevron-down')
    }
  })

  // Клик по категории или подкатегории
  $(document).on('click', '.category-option', function (e) {
    e.stopPropagation()
    const id = $(this).data('id')
    const label = $(this).data('label')

    $('#search-category-id').val(id)
    $('#category-dropdown-label').text(label)
    $('#category-popup').hide()
    $('#category-chevron').removeClass('fa-chevron-up').addClass('fa-chevron-down')

    // Сабмит формы
    $('#form-search').submit()
  })

  // Подсветка при наведении
  $(document).on('mouseenter', '.category-option', function () {
    $(this).addClass('bg-white').css('border-radius', '4px')
  }).on('mouseleave', '.category-option', function () {
    $(this).removeClass('bg-white')
  })

});
