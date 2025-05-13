<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden" id="modal">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-blue-400 font-kanit">เพิ่มข้อมูลใหม่</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">ชื่อรายการ</label>
                <input type="text" name="name" class="w-full px-3 py-2 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">รายละเอียด</label>
                <textarea name="description" class="w-full px-3 py-2 border rounded-lg" rows="3" required></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">รูปภาพ</label>
                <input type="file" name="image" class="w-full px-3 py-2 border rounded-lg" accept="image/*"
                    required>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 bg-gray-400 hover:bg-red-500 rounded-lg mr-2 text-white">ยกเลิก</button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-400 hover:bg-blue-600 text-white rounded-lg">บันทึก</button>
            </div>
        </form>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function openModal() {
        document.getElementById('modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal').classList.add('hidden');

        document.getElementById('uploadForm').reset();
    }

    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');


        submitButton.disabled = true;
        submitButton.innerHTML = 'กำลังบันทึก...';

        fetch('/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: 'บันทึกข้อมูลสำเร็จ',
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'bg-gray-400 hover:bg-blue-500 text-white font-kanit rounded-lg px-6 py-2'
                        },
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            closeModal();
                        }
                    });


                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: data.message || 'ไม่สามารถบันทึกข้อมูลได้',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'ตกลง'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'เกิดข้อผิดพลาดในการส่งข้อมูล: ' + error.message,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'ตกลง'
                });
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = 'บันทึก';
            });
    });
</script>
