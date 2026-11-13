import './bootstrap';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Swal = Swal;
window.Alpine = Alpine;

const GREEN = '#047857';
const RED = '#dc2626';

// ---------- Pesan flash dari server (session) ----------
function showFlash() {
    const el = document.getElementById('flash-data');
    if (!el) return;
    const flash = JSON.parse(el.textContent || '{}');

    if (flash.toast) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: flash.toast,
            showConfirmButton: false,
            timer: 2200,
            timerProgressBar: true,
        });
    }
    const asObject = (value) => (typeof value === 'string' ? { title: value } : value);
    if (flash.success) {
        Swal.fire({ icon: 'success', confirmButtonColor: GREEN, ...asObject(flash.success) });
    } else if (flash.error) {
        Swal.fire({ icon: 'error', title: 'Gagal', text: flash.error, confirmButtonColor: GREEN });
    } else if (flash.warning) {
        Swal.fire({ icon: 'warning', text: flash.warning, confirmButtonColor: GREEN });
    }
}

// ---------- Konfirmasi sebelum submit: <form data-confirm="Teks" ...> ----------
document.addEventListener('submit', async (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.dataset.confirm || form.dataset.confirmed) return;
    event.preventDefault();

    const danger = form.dataset.confirmDanger !== undefined;
    const result = await Swal.fire({
        title: form.dataset.confirmTitle || 'Apakah kamu yakin?',
        text: form.dataset.confirm,
        icon: danger ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonText: form.dataset.confirmButton || 'Ya',
        cancelButtonText: form.dataset.cancelButton || 'Batal',
        confirmButtonColor: danger ? RED : GREEN,
        reverseButtons: true,
    });
    if (result.isConfirmed) {
        form.dataset.confirmed = '1';
        form.requestSubmit(event.submitter || undefined);
    }
});

// ---------- Halaman keranjang ----------
Alpine.data('cartPage', (config) => ({
    items: config.items, // [{ id, price, quantity }]
    selected: config.selected,
    startDate: config.startDate || '',
    endDate: config.endDate || '',
    today: config.today,

    get allSelected() {
        return this.items.length > 0 && this.selected.length === this.items.length;
    },
    toggleAll(checked) {
        this.selected = checked ? this.items.map((item) => item.id) : [];
    },
    get selectedItems() {
        return this.items.filter((item) => this.selected.includes(item.id));
    },
    get rentalDays() {
        if (!this.startDate || !this.endDate || this.endDate < this.startDate) return 0;
        const ms = Date.parse(`${this.endDate}T00:00:00Z`) - Date.parse(`${this.startDate}T00:00:00Z`);
        return Math.floor(ms / 86400000) + 1;
    },
    get totalUnits() {
        return this.selectedItems.reduce((sum, item) => sum + (Number(item.quantity) || 1), 0);
    },
    get total() {
        return this.selectedItems.reduce((sum, item) => sum + item.price * (Number(item.quantity) || 1), 0) * this.rentalDays;
    },
    onStartChange() {
        if (this.endDate && this.startDate > this.endDate) this.endDate = '';
    },
    rupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    },
    async saveQuantity(item) {
        item.quantity = Math.min(99, Math.max(1, parseInt(item.quantity, 10) || 1));
        try {
            const { data } = await window.axios.patch(`/cart/${item.id}`, { quantity: item.quantity });
            document.querySelectorAll('[data-cart-count]').forEach((el) => {
                el.textContent = data.count;
            });
        } catch {
            // Jumlah tetap dikirim lagi bersama form checkout.
        }
    },
}));

// ---------- Form hubungi kami: siapkan draft email ----------
Alpine.data('contactForm', () => ({
    nama: '',
    email: '',
    pesan: '',
    prepared: false,
    submit() {
        const subject = encodeURIComponent(`Pertanyaan RentCampy dari ${this.nama.trim()}`);
        const body = encodeURIComponent(`Nama: ${this.nama.trim()}\nEmail: ${this.email.trim()}\n\nPesan:\n${this.pesan.trim()}`);
        window.location.href = `mailto:?subject=${subject}&body=${body}`;
        this.prepared = true;
    },
}));

Alpine.start();
document.addEventListener('DOMContentLoaded', showFlash);
