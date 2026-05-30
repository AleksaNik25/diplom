$(() => {
  $("#product-user_stars").on("rating:change", function (event, value) {
    const parent = $(this).parents(".field-product-user_stars");

    $.ajax({
      url: parent.data("url"),
      method: "POST",
      data: {
        estimation: value,
        _csrf: yii.getCsrfToken() 
      },
      dataType: "json",
      success(data) {
        if (data) {
          $(".alert-stars").removeClass("d-none")
          $("#product-user_stars").rating("update", value).rating("refresh", {
            readonly: true,
            showClear: false,
            hoverEnabled: false,
          })
          parent.children(".rating-container").addClass("rating-disabled")
          $.pjax.reload({
            container: "#stars-block-pjax",
            url: window.location.href,  
            method: "GET",              
            push: false,
            timeout: 5000,
          })
        }
      },
    });
  });

  $("#productTop8").on("click", ".product-card", function (e) {
    e.preventDefault();
    location.assign($(this).data("url"));
  });


  $('#btn-add-to-cart').on('click', function (e) {
    e.preventDefault()
    const url = $(this).attr('href')

    $.ajax({
      url: url,
      method: 'GET', 
      success: function () {
        location.reload() 
      },
      error: function () {
        alert('Ошибка при добавлении товара в корзину')
      }
    })
  });


  $('#root-category-select').on('change', function () {
    var rootId = $(this).val()
    var grid = $('#subcategory-grid')
    var block = $('#subcategory-block')

    grid.empty()

    if (!rootId || !subcatsByRoot[rootId]) {
      block.hide()
      return
    }

    var subs = subcatsByRoot[rootId]
    subs.forEach(function (sub) {
      grid.append(
        '<div class="col">' +
        '<div class="form-check">' +
        '<input class="form-check-input" type="checkbox" name="category_ids[]" ' +
        'id="subcat-' + sub.id + '" value="' + sub.id + '">' +
        '<label class="form-check-label" for="subcat-' + sub.id + '">' +
        $('<span>').text(sub.title).html() +
        '</label>' +
        '</div></div>'
      )
    })

    block.show()
  })


  $('#form-product').on('beforeSubmit', function () {
    var checked = $('input[name="category_ids[]"]:checked')
    if (checked.length === 0) {
      alert('Необходимо выбрать хотя бы одну подкатегорию.')
      return false
    }
    var hasNonExtend = false
    checked.each(function () {
      var id = parseInt($(this).val())
      Object.values(subcatsByRoot).forEach(function (subs) {
        subs.forEach(function (sub) {
          if (sub.id === id && !sub.extend) {
            hasNonExtend = true
          }
        })
      })
    })
    if (!hasNonExtend) {
      alert('Необходимо выбрать хотя бы одну обычную подкатегорию (не расширяющую).')
      return false
    }
    return true
  })
});