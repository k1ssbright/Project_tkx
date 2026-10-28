<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const pageNotice = {
        status: @json(session('status'), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
        errors: @json($errors->all(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
    };

    const swalBase = {
        confirmButtonColor: '#fe7b07',
        cancelButtonColor: '#808080',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
    };

    if (pageNotice.status) {
        Swal.fire({
            ...swalBase,
            icon: 'success',
            title: pageNotice.status,
        });
    }

    if (pageNotice.errors.length > 0) {
        const list = document.createElement('ul');
        list.className = 'swal-error-list';
        pageNotice.errors.forEach((message) => {
            const item = document.createElement('li');
            item.textContent = message;
            list.append(item);
        });

        Swal.fire({
            ...swalBase,
            icon: 'error',
            title: 'กรอกข้อมูลไม่ครบ',
            html: list,
        });
    }

    document.querySelectorAll('[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            Swal.fire({
                ...swalBase,
                icon: 'warning',
                title: form.dataset.confirm,
                showCancelButton: true,
                confirmButtonText: form.dataset.confirmButton || 'ยืนยัน',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
