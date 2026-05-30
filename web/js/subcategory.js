$(() => {
	const block = (index) => `
    <div class="border p-3 my-3 mx-3 item-subcategory col-8" data-index="${index}">
        <div class="d-flex justify-content-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success btn-remove">-</button>
                <button type="button" class="btn btn-success btn-add">+</button>
            </div>
        </div>
        <div class="d-flex gap-3 align-items-start flex-wrap">
            <div class="mb-3">
                <label class="form-label">Название</label>
                <input type="text"
                       name="Subcategory[${index}][title]"
                       class="form-control"
                       maxlength="255"
                       value="">
            </div>
            <div class="mb-3">
                <label class="form-label d-block">Расширяющая</label>
                <input type="hidden" name="Subcategory[${index}][extend]" value="0">
                <input type="checkbox"
                       name="Subcategory[${index}][extend]"
                       class="form-check-input fs-5 extend-checkbox"
                       value="1">
            </div>
            <input type="hidden" name="Subcategory[${index}][id]" value="">
        </div>
    </div>`

	$('#block-subcategory').on('click', '.btn-add', () => {
		subcategoryCount++
		$('#block-subcategory .item-subcategory:last').after(block(subcategoryCount))
	})

	$('#block-subcategory').on('click', '.btn-remove', function () {
		if ($('#block-subcategory .item-subcategory').length > 1) {
			$(this).closest('.item-subcategory').remove()
		}
	})

	// Валидация дропдауна корневой категории перед сабмитом
	$('#form-category').on('submit', function (e) {
		const rootSelect = $('#root-select')
		if (!rootSelect.val()) {
			rootSelect.addClass('is-invalid')
			e.preventDefault()
			return false
		}
		rootSelect.removeClass('is-invalid')
	})

})