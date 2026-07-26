// SaranIndex.com Cascading Location Selector (Block -> Panchayat -> Village)

document.addEventListener('DOMContentLoaded', function () {
    const blockSelect = document.getElementById('block_select');
    const panchayatSelect = document.getElementById('panchayat_select');
    const villageSelect = document.getElementById('village_select');

    if (blockSelect && panchayatSelect) {
        blockSelect.addEventListener('change', function () {
            const blockId = this.value;
            panchayatSelect.innerHTML = '<option value="">Loading Panchayats...</option>';
            if (villageSelect) {
                villageSelect.innerHTML = '<option value="">Select Village</option>';
            }

            if (!blockId) {
                panchayatSelect.innerHTML = '<option value="">Select Panchayat</option>';
                return;
            }

            fetch(`api/blocks_api.php?block_id=${blockId}&type=panchayats`)
                .then(res => res.json())
                .then(data => {
                    let html = '<option value="">Select Panchayat</option>';
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            html += `<option value="${item.id}">${item.panchayat_name} (${item.hindi_name})</option>`;
                        });
                    }
                    panchayatSelect.innerHTML = html;
                })
                .catch(err => {
                    console.error('Error fetching panchayats:', err);
                    panchayatSelect.innerHTML = '<option value="">Select Panchayat</option>';
                });
        });
    }

    if (panchayatSelect && villageSelect) {
        panchayatSelect.addEventListener('change', function () {
            const panchayatId = this.value;
            villageSelect.innerHTML = '<option value="">Loading Villages...</option>';

            if (!panchayatId) {
                villageSelect.innerHTML = '<option value="">Select Village</option>';
                return;
            }

            fetch(`api/blocks_api.php?panchayat_id=${panchayatId}&type=villages`)
                .then(res => res.json())
                .then(data => {
                    let html = '<option value="">Select Village</option>';
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            html += `<option value="${item.id}">${item.village_name} (${item.hindi_name})</option>`;
                        });
                    }
                    villageSelect.innerHTML = html;
                })
                .catch(err => {
                    console.error('Error fetching villages:', err);
                    villageSelect.innerHTML = '<option value="">Select Village</option>';
                });
        });
    }
});
