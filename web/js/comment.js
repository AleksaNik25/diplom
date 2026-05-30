$(() => {
	$(".btn-comment").on("click", function (e) {
		e.preventDefault()
		$("#comment-text").val("")
		$("#comment-text").removeClass("is-valid")
		$("#comment-text").removeClass("is-invalid")
		$("#modal-comment").modal("show")
	})

	$(".btn-comment-edit").on("click", function (e) {
		e.preventDefault()
		$("#comment-pjax").data("close", 0)
		$.pjax.reload("#comment-pjax", {
			url: $(this).attr("href"),
			push: false,
			replace: false,
			timeout: 5000,
		})
		$("#modal-comment").modal("show")
	})

	$("#comment-pjax").on("pjax:end", function () {
		if ($(this).data("close")) {
			$("#modal-comment").modal("hide")
			$.pjax.reload("#product-comments-pjax")
			$(".btn-comment").addClass("d-none")
		} else {
			$(this).data("close", 1)
		}
	})

	$("#modal-comment").on("click", ".btn-cancel", function (e) {
		e.preventDefault()
		$("#modal-comment").modal("hide")
	})

	// Открытие modal-comment
	document.getElementById("modal-comment").addEventListener("show.bs.modal", function (event) {
		var button = event.relatedTarget
		if (!button) return

		var parentId = button.getAttribute("data-parent-id")
		var productId = button.getAttribute("data-product-id")
		var editId = button.getAttribute("data-edit-id")
		var form = this.querySelector("#form-comment")
		if (!form) return

		var starBlock = form.querySelector(".mb-4")
		var textarea = form.querySelector("textarea[name='Comment[text]']")
		var parentInput = form.querySelector("input[name='Comment[parent_id]']")

		if (editId) {
			// Редактирование ответа
			form.setAttribute("action",
				"/account/account-comment/write?product_id=" + productId +
				"&parent_id=" + parentId +
				"&id=" + editId
			)
			if (parentInput) parentInput.value = parentId || ""
			if (starBlock) starBlock.style.display = "none"
			if (textarea) textarea.setAttribute("placeholder", window.commentReplyPlaceholder || "")

			// Запрашиваем текущий текст ответа
			$.getJSON("/account/account-comment/get-reply?id=" + editId, function (data) {
				if (data && data.text && textarea) {
					textarea.value = data.text
				}
			})

		} else if (parentId) {
			// Новый ответ
			form.setAttribute("action",
				"/account/account-comment/write?product_id=" + productId +
				"&parent_id=" + parentId
			)
			if (parentInput) parentInput.value = parentId
			if (starBlock) starBlock.style.display = "none"
			if (textarea) {
				textarea.value = ""
				textarea.setAttribute("placeholder", window.commentReplyPlaceholder || "")
			}
		}
	})

	// сбрасываем форму
	document.getElementById("modal-comment").addEventListener("hide.bs.modal", function () {
		var form = this.querySelector("#form-comment")
		if (!form) return

		var parentInput = form.querySelector("input[name='Comment[parent_id]']")
		if (parentInput) parentInput.value = ""

		var productId = (form.getAttribute("action").match(/product_id=(\d+)/) || [])[1] || ""
		form.setAttribute("action", "/account/account-comment/write?product_id=" + productId)

		var starBlock = form.querySelector(".mb-4")
		if (starBlock) starBlock.style.display = ""

		var textarea = form.querySelector("textarea[name='Comment[text]']")
		if (textarea) {
			textarea.value = ""
			textarea.setAttribute("placeholder", "Напишите ваш отзыв о товаре...")
		}
	})
})